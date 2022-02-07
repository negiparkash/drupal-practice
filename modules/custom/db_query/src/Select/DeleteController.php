<?php
namespace Drupal\db_query\Select;
use Drupal\Core\Controller\Controllerbase;
use Symfony\Component\HttpFoundation\RedirectResponse;

// https://drupal.stackexchange.com/questions/138697/what-function-method-can-i-use-to-redirect-users-to-a-different-page

class DeleteController extends Controllerbase
{
    public function delete($nid)
    {       
    $database=\Drupal::database();
    $num_deleted = $database->delete('tb_products')
    ->condition('nid', $nid)
    ->execute();
    $response = new RedirectResponse('../product-data');
    $response->send();
    }
}

?>