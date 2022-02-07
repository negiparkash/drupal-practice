<?php
namespace Drupal\form_module\User;
use Drupal\Core\Controller\ControllerBase;

class DetailController extends ControllerBase
{
    public function Detail()
    {
     $database= \Drupal::Database();     
     $query =$database->query("SELECT * FROM users_field_data"); 
     $result=$query->fetchAll();
        // $data=[];
        // foreach($result as $row)
        // {
        //     $data[]= 
        //     [
        //         'id' => $row->uid,
        //         'name' => $row->name,
        //         'email' =>$row->mail,
        //         'pass' =>$row->pass,
        //         'status'=>$row->status,
        //     ];
        // }
        // $header = array('Id','Name','Email','Password','status');
        // $build['table']=[
        //     '#type' => 'table',
        //     '#header'=>$header,
        //     '#rows'=>$data,
        // ];
        return [
            // '#title'=>'Information page',
            '#theme'=>'information',
            '#items'=>$result,
        ];
    }
}


?>