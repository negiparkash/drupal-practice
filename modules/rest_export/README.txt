CONTENTS OF THIS FILE
---------------------
   
 * Introduction
 * Requirements
 * Installation
 * Configuration

INTRODUCTION
------------

The Rest Export module provides features to export restful
APIs list via:
 * Excel (*.xlsx, *.xls)
 * OpenDocument Spreadsheet (*.ods)
 * Comma separated values (*.csv)
 * Tab separated values (*.tsv)

This module is useful in the following case:
 * Site has many restful APIs, so helpful to prepare a spreadsheet
 of exported APIs with status.

REQUIREMENTS
------------

This module requires the following:
 * PHP 7.3 or greater
 * Drupal core 8.8.0 or greater
 * PhpSpreadsheet 1.3.0 or greater

INSTALLATION
------------

 * Strongly recommend installing this module using composer:
   composer require drupal/rest_export

 * Also you can install PhpSpreadsheet separately using composer:
   composer require phpoffice/phpspreadsheet:^1.3

CONFIGURATION
-------------

Visit /admin/config/services/restexport
(Administration > Configuration > Web Services > Rest Export)

ABOUT SPREADSHEET FORMAT
-------------

 * Column A (1st)
   This column should the Resource Name.
   
 * Column 2 (2nd)
   This column shows the resource path.
   
 * Column C (3rd)
   This column shows the resource description.

 * Column D (4th)
   This column shows the resource status.
  
 * Rows
   First row is header row, do not remove.
