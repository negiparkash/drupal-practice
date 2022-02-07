<?php
namespace Drupal\test_module\Controller;

use Drupal\Core\Controller\Controllerbase;

class ExtController extends Controllerbase
{
    public function test()
    {
        return [
            '#title'=>'Information',
            '#theme'=>'information',
            '#items'=> [
                'name' => 'parkash',
                'role' => 'admin',
                'class' => 'b.s.c',
            ],
         ];
    } 
}

?>