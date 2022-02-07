<?php

namespace Drupal\Tests\rest_export\Functional;

use Drupal\file\Entity\File;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Tests export form.
 *
 * @group rest_export
 */
class ExportFormTest extends FormTestBase {

  /**
   * The path of the form page.
   */
  const PAGE_PATH = 'admin/config/services/restexport/data';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->moduleConfig->set('rest_export.status', 1);
    $this->moduleConfig->save();
  }

  /**
   * {@inheritdoc}
   */
  public function doTestFileFormats($format) {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet(static::PAGE_PATH);
    $this->assertSession()->statusCodeEquals(200);

    // Submit export form.
    $edit = [];
    $edit['format'] = $format;
    $edit['filename'] = $this->moduleConfig->get('rest_export.filename');
    $edit['sheet_title'] = 'Rest Export';

    $this->drupalPostForm(static::PAGE_PATH, $edit, t('Download'));
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->responseHeaderContains('Content-Disposition', 'attachment; filename=' . $edit['filename'] . '.' . $format);

    // Save the exported file.
    $file_params = [
      'filename' => $edit['filename'] . '.' . $format,
      'uri' => 'temporary://rest_export.' . $format,
      'filemime' => $this->getSession()->getResponseHeader('Content-Type'),
    ];
    $file = File::create($file_params);
    file_put_contents($file->getFileUri(), $this->getSession()->getDriver()->getContent());

    $file->save();

    $this->assertTrue((boolean) $file->id());

    // Test the exported file.
    $sheet = NULL;
    try {
      $reader = IOFactory::load($this->container->get('file_system')->realPath($file->getFileUri()));
      $sheet = $reader->getActiveSheet();
    }
    catch (\Exception $ex) {
      $this->assertNotNull($sheet, 'Load exported file.');
    }
  }

}
