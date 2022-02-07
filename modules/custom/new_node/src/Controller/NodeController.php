<?php
namespace Drupal\new_node\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class NodeController extends ControllerBase{
public function content(){
    $node = Node::create([
        'type'  => 'article',
        'title' => 'new_node',
      ]);
     $node->body->value = "this is our node body";
      $node->save();
      return array(
        '#markup' => 'create node'
   );
}
}
?>