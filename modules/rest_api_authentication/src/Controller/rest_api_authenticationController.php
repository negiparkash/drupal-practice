<?php 
/**
 * @file
 * Contains \Drupal\rest_api_authentication\Controller\DefaultController.
 */

namespace Drupal\rest_api_authentication\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Utility\Html;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Session\AccountInterface;

use Drupal\rest_api_authentication\Utilities;

class rest_api_authenticationController extends ControllerBase {

}
