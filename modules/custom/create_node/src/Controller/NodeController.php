<?php
namespace  Drupal\create_node\Controller;
use Drupal\Core\Controller\Controllerbase;
use Drupal\node\Entity\Node;

class NodeController extends Controllerbase{
    public function content()
    {
    //  $node= Node::create(['type' => 'artical']);
    //  $node= set('title','custom node');
    //  $node= set('uid',1);
    //  $node= set('status',1);
    // $node=save();
    $node = Node::create([
        'type'  => 'banner',
        'title' => 'banner',
      ]);
     $node->body->value = "this is our node body";
     $node->field_image->value = "";
      $node->save();
      return array(
        '#markup' => 'create node'
   );
    }
  //   public function values()
  //   {
  //     $nid = 123;
  //     $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
  //     $body = $node->get('body')->getValue();
  //     $title = $node->get('title')->getString();
  //     $title = $node->get('title')->value;
  //     dd($node); 
  // }
  public function values()
  {
    $result=[];
      $nids = \Drupal::entityQuery('node')->condition('type','services')
      ->sort('title','body');
      $nodes_ids = $nids->execute();
      //  dd($nodes_ids);
      if($nodes_ids)
      {
        foreach ($nodes_ids as $node_id) {
          $node = \Drupal\node\Entity\Node::load($node_id);
          echo "<pre>";
          var_dump($node);
        }      
      }
      return $result;
  }
 
}


      // $nids = \Drupal::entityQuery('node')->condition('type','packages')->execute();
      // $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
      // foreach($nodes as $key => $values)
      // {  
      // dd($key->title);
      // }
?>
