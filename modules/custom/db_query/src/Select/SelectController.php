<?php
namespace Drupal\db_query\Select;
use Drupal\Core\Controller\ControllerBase;

class SelectController extends ControllerBase
{
    public function data($nid = '')
    {
    $database=\Drupal::Database();
    $query =$database->query("SELECT * FROM tb_products");
    $result=$query->fetchAll(2);

    if($nid != ''){
        $query = $database->query("SELECT * FROM {tb_products} WHERE nid = $nid ");
        $result1 = $query->fetchAll();
        if(count($result1) > 0){
            $num_deleted = $database->delete('tb_products')
            ->condition('nid', $nid)
            ->execute();
            $this->messenger()->addStatus($this->t("deleted successfully !!".' status'));
          }
          else{
            $this->messenger()->addStatus($this->t("Product not Available!!".' error'));
          }

    }
    return 
    [
        '#theme'=>'information2',
        '#items2'=>$result,
    ];
    }
    
}

?>