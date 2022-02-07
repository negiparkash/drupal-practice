<?php

namespace Drupal\rest_export\Form;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Utility\Token;
use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\rest\Plugin\Type\ResourcePluginManager;
use Drupal\rest_export\PhpSpreadsheetHelperTrait;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Provides rest_export_export_form form.
 */
class ExportForm extends FormBase implements ContainerInjectionInterface {

  use PhpSpreadsheetHelperTrait;


  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Resource plugin manager.
   *
   * @var \Drupal\rest\Plugin\Type\ResourcePluginManager
   */
  protected $resourcePluginManager;

  /**
   * Configuration entity to store enabled REST resources.
   *
   * @var \Drupal\rest\RestResourceConfigInterface
   */
  protected $resourceConfigStorage;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * The file system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs a new ExportForm.
   *
   * @param \Drupal\rest\Plugin\Type\ResourcePluginManager $resourcePluginManager
   *   The Rest resources.
   * @param \Drupal\Core\Entity\EntityStorageInterface $resource_config_storage
   *   The REST resource config storage.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Utility\Token $token
   *   The token service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system.
   */
  public function __construct(ResourcePluginManager $resourcePluginManager, EntityStorageInterface $resource_config_storage, ModuleHandlerInterface $module_handler, Token $token, FileSystemInterface $file_system) {
    $this->resourcePluginManager = $resourcePluginManager;
    $this->resourceConfigStorage = $resource_config_storage;
    $this->moduleHandler = $module_handler;
    $this->token = $token;
    $this->fileSystem = $file_system;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.rest'),
      $container->get('entity_type.manager')
        ->getStorage('rest_resource_config'),
      $container->get('module_handler'),
      $container->get('token'),
      $container->get('file_system')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rest_export_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('rest_export.settings');

    $form['format'] = [
      '#type' => 'select',
      '#title' => $this->t('Format'),
      '#options' => [
        'xlsx' => $this->t('Excel 2007 (.xlsx)'),
        'xls' => $this->t('Excel 2000/2002/2003 (.xls)'),
        'ods' => $this->t('OpenDocument spreadsheet (.ods)'),
        'csv' => $this->t('Comma separated (.csv)'),
        'tsv' => $this->t('Tab separated (.tsv)'),
      ],
      '#required' => TRUE,
    ];

    $form['settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Settings'),
      '#open' => TRUE,
    ];
    if ($this->moduleHandler->moduleExists('token')) {
      $form['settings']['tokens'] = [
        '#theme' => 'token_tree_link',
        '#global_types' => TRUE,
      ];
    }
    $form['settings']['filename'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Filename'),
      '#default_value' => $config->get('rest_export.filename'),
      '#description' => $this->t("Specify default filename (without extension) on download file. e.g., 'rest_export'. You can use tokens."),
      '#required' => TRUE,
    ];
    $form['settings']['sheet_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sheet title'),
      '#default_value' => $config->get('rest_export.sheet_title'),
      '#description' => $this->t("Specify default title for Excel sheet. e.g., 'Rest Export'. You can use tokens."),
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Download'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Output spreadsheet data.
    $format = $form_state->getValue('format');
    $temp_path = $this->fileSystem->realpath($this->fileSystem->tempnam('temporary://', 'rest_export'));
    $sheet_title = $this->token->replace($form_state->getValue('sheet_title'));

    try {
      $spreadsheet = $this->createSpreadsheet();
      $spreadsheet->getActiveSheet()->setTitle($sheet_title);
      $this->createWriter($format, $spreadsheet)->save($temp_path);
    }
    catch (\Exception $ex) {
      $this->messenger()->addError($this->t('An error has occurred while writing spreadsheet file. @error', ['@error' => $ex->getMessage()]));
      return;
    }

    $filename = $this->token->replace($form_state->getValue('filename'));
    $response = new BinaryFileResponse($temp_path);
    $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename . '.' . $format);
    $response->deleteFileAfterSend(TRUE);

    $form_state->setResponse($response);
  }

  /**
   * Creates permission spreadsheet.
   *
   * @return \PhpOffice\PhpSpreadsheet\Spreadsheet
   *   A spreadsheet object.
   */
  protected function createSpreadsheet() {
    $config = $this->config('rest_export.settings');

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header data.
    $system_column = 1;
    $set_header_cell = function ($column, $text) use ($sheet) {
      $sheet->getColumnDimensionByColumn($column)->setAutoSize(TRUE);
      $sheet->setCellValueByColumnAndRow($column, 1, $text);

      $style = $sheet->getStyleByColumnAndRow($column, 1);
      $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
      $style->getFont()->setBold(TRUE);
    };

    $set_header_cell($system_column++, $this->t('RESOURCE NAME'));
    $set_header_cell($system_column++, $this->t('PATH'));
    $set_header_cell($system_column++, $this->t('DESCRIPTION'));
    $set_header_cell($system_column++, $this->t('STATUS'));

    $column = $system_column;

    $sheet->getStyle(Coordinate::stringFromColumnIndex($system_column) . ':' . Coordinate::stringFromColumnIndex($column))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Get the list of enabled and disabled resources.
    $resource_config = $this->resourceConfigStorage->loadMultiple();
    // Strip out the nested method configuration.
    $enabled_resources = array_combine(array_keys($resource_config), array_keys($resource_config));
    $available_resources = ['enabled' => [], 'disabled' => []];
    $resources = $this->resourcePluginManager->getDefinitions();
    foreach ($resources as $id => $resource) {
      $key = $this->getResourceKey($id);
      $status = (in_array($key, $enabled_resources) && $resource_config[$key]->status()) ? 'enabled' : 'disabled';
      $available_resources[$status][$id] = $resource;
    }

    // Sort the list of resources by label.
    $sort_resources = function ($resource_a, $resource_b) {
      return strcmp($resource_a['label'], $resource_b['label']);
    };
    if (!empty($available_resources['enabled'])) {
      uasort($available_resources['enabled'], $sort_resources);
    }
    if (!empty($available_resources['disabled'])) {
      uasort($available_resources['disabled'], $sort_resources);
    }

    // List of resources.
    $row = 2;
    $api_status = $config->get('rest_export.status') ? ['enabled'] : ['enabled', 'disabled'];
    foreach ($api_status as $status) {
      foreach ($available_resources[$status] as $id => $resource) {
        $canonical_uri_path = !empty($resource['uri_paths']['canonical'])
            ? $resource['uri_paths']['canonical']
            : FALSE;
        $create_uri_path = !empty($resource['uri_paths']['create'])
        ? $resource['uri_paths']['create']
        : FALSE;
        $available_methods = array_intersect(array_map('strtoupper', get_class_methods($resource['class'])), [
          'HEAD',
          'GET',
          'POST',
          'PUT',
          'DELETE',
          'TRACE',
          'OPTIONS',
          'CONNECT',
          'PATCH',
        ]);
        // @todo Remove this when https://www.drupal.org/node/2300677 is fixed.
        $is_config_entity = isset($resource['serialization_class']) && is_subclass_of($resource['serialization_class'], ConfigEntityInterface::class, TRUE);

        if (isset($resource_config[$this->getResourceKey($id)])) {
          $enabled_methods = $resource_config[$this->getResourceKey($id)]->getMethods();
          $disabled_methods = array_diff($available_methods, $enabled_methods);
          $resource_configured_methods = array_merge(
            array_intersect($available_methods, $enabled_methods),
            array_map(function ($method) {
              return "$method";
            }, $disabled_methods)
          );
        }
        else {
          $resource_configured_methods = $available_methods;
        }

        if ($is_config_entity) {
          $available_methods = array_diff($available_methods,
          ['POST', 'PATCH', 'DELETE']);
          $create_uri_path = FALSE;
        }
        // All necessary information is collected, now generate some HTML.
        $canonical_methods = implode(', ', array_diff($resource_configured_methods, ['POST']));
        if ($canonical_uri_path && $create_uri_path) {
          $uri_paths = "$canonical_uri_path: $canonical_methods\n";
          $uri_paths .= "\n$create_uri_path: POST";
        }
        else {
          if ($canonical_uri_path) {
            $uri_paths = "$canonical_uri_path: $canonical_methods\n";
          }
          else {
            $uri_paths = "\n$create_uri_path: POST";
          }
        }

        $column = 1;
        $description = [];
        if ($status != 'disabled') {
          $granularity = $resource_config[$this->getResourceKey($id)]->get('granularity');
          $resource_configuration = $resource_config[$this->getResourceKey($id)]->get('configuration');
          $description = "granularity: " . $granularity . "\n";
          if ($granularity == 'resource') {
            $description .= "methods: {{" . implode(", ", $resource_configuration['methods']) .
            "}}\n";
            $description .= "formats: {{" . implode(", ", $resource_configuration['formats']) . "}}\n";
            $description .= "authentication: {{" . implode(", ", $resource_configuration['authentication']) . "}}";
          }
          else {
            $description .= "methods: { {" . implode(", ", array_keys($resource_configuration)) . "}}\n";

            $description .= "formats: {{" . implode(", ", reset(array_unique(array_column($resource_configuration, "supported_formats")))) . "}}\n";

            $description .= "authentication: {{" . implode(", ", reset(array_unique(array_column($resource_configuration, "supported_auth")))) . "}}";
          }
        }
        $sheet->setCellValueByColumnAndRow($column++, $row, !$is_config_entity ? $resource['label'] : $this->t('@label', ['@label' => $resource['label']]));
        $sheet->setCellValueByColumnAndRow($column++, $row, $uri_paths);
        $sheet->setCellValueByColumnAndRow($column++, $row, $description);
        $sheet->setCellValueByColumnAndRow($column++, $row, $status);

        $row++;
      }
    }
    // Add borders.
    $sheet->getStyle('D1:D' . ($row - 1))->getBorders()->getRight()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex($column - 1) . '1')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
    $sheet->getStyle('A2:' . Coordinate::stringFromColumnIndex($column - 1) . ($row - 1))->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

    return $spreadsheet;
  }

  /**
   * The key used in the form.
   *
   * @param string $resource_id
   *   The resource ID.
   *
   * @return string
   *   The resource key in the form.
   */
  protected function getResourceKey($resource_id) {
    return str_replace(':', '.', $resource_id);
  }

}
