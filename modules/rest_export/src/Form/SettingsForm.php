<?php

namespace Drupal\rest_export\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures rest_export settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rest_export_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'rest_export.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('rest_export.settings');

    $form['rest_export'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Export'),
      '#tree' => TRUE,
    ];
    $form['rest_export']['tokens'] = [
      '#theme' => 'token_tree_link',
      '#global_types' => TRUE,
    ];
    $form['rest_export']['filename'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Download filename'),
      '#description' => $this->t("Specify default filename (without extension) on download file. e.g., 'rest_export'. You can use tokens."),
      '#required' => TRUE,
      '#default_value' => $config->get('rest_export.filename') ?: 'rest_export',
    ];
    $form['rest_export']['sheet_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sheet title'),
      '#description' => $this->t("Specify default title for Excel sheet. e.g., 'Rest Export'. You can use tokens."),
      '#required' => TRUE,
      '#default_value' => $config->get('rest_export.sheet_title') ?: 'Rest Export',
    ];
    $form['rest_export']['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Export only enabled APIs.'),
      '#description' => $this->t("Select if you want the list of only enabled REST APIs"),
      '#default_value' => $config->get('rest_export.status') ?: NULL,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('rest_export.settings')
      ->set('rest_export', $values['rest_export'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
