<?php
 namespace Drupal\basic_page\Controller;
 use Drupal\Core\Controller\Controllerbase;

 class BasicPageController extends Controllerbase
 {
     public function basicpage() {
     $array = array(
         '#markup' => 'Your module is succesfully created!!',
     );
     return $array;
    }
    public function information() {
       return [
           '#title'=>'Information page',
           '#theme'=>'information',
           '#items'=>'This is my first hook!!'
        ];
       }
 } 
?>