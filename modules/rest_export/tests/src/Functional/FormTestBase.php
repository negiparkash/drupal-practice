<?php

namespace Drupal\Tests\rest_export\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Provides base class for testing form.
 */
abstract class FormTestBase extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['rest', 'rest_export'];

  /**
   * The path of the form page.
   */
  const PAGE_PATH = '';

  /**
   * A user with permission to administer rest spreadsheet.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The configuration object for the module.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $moduleConfig;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->adminUser = $this->drupalCreateUser(['administer rest spreadsheet', 'access administration pages']);
    $this->moduleConfig = $this->config('rest_export.settings');
  }

  /**
   * Tests access restriction.
   */
  public function testAccess() {
    // Test access check.
    $regular_user = $this->drupalCreateUser();
    $this->drupalLogin($regular_user);
    $this->drupalGet(static::PAGE_PATH);
    $this->assertSession()->statusCodeEquals(403, 'Deny access from user who does not have the permission.');

    $this->drupalLogin($this->adminUser);
    $this->drupalGet(static::PAGE_PATH);
    $this->assertSession()->statusCodeEquals(200, 'Allow access from user who has the permission.');
  }

  /**
   * Tests form with Excel book format.
   */
  public function testProcessXlsx() {
    $this->doTestFileFormats('xlsx');
  }

  /**
   * Tests form with old Excel book format..
   */
  public function testProcessXls() {
    $this->doTestFileFormats('xls');
  }

  /**
   * Tests form with OpenDocument spreadsheet format.
   */
  public function testProcessOds() {
    $this->doTestFileFormats('ods');
  }

  /**
   * Tests form with comma separated value format.
   */
  public function testProcessCsv() {
    $this->doTestFileFormats('csv');
  }

  /**
   * Tests form with Tab separated value format.
   */
  public function testProcessTsv() {
    $this->doTestFileFormats('tsv');
  }

  /**
   * Tests form with specific format.
   *
   * @param string $format
   *   The format to test.
   */
  abstract public function doTestFileFormats($format);

}
