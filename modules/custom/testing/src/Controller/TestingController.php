<?php
namespace Drupal\testing\Controller;

use Drupal\Core\Controller\Controllerbase;

class TestingController extends Controllerbase
{
    public function testing(){

        $array = array(
            '#markup' => 'Your module is succesfully created!!',
        );
        return $array;
    } 
}

?>