<?php
namespace Drupal\enquery_module\Form;
use  Drupal\Core\Form\FormBase;
use \Drupal\user\Entity\User;
use  Drupal\Core\Form\FormStateInterface;

class Enquery_user extends FormBase {

public function getFormId()
{
    return 'Enquery_user';    
}
public function buildForm(array $form, FormStateinterface $form_state)
{
   
   $form['email'] = array(
    '#type' => 'email',
    '#title' => 'Enter Email',
    '#placeholder' => 'enter email',
    '#required' => True,
    );
    $form['message'] = array(
        '#type' => 'textarea',
        '#title' => 'Message',
        '#placeholder' => 'enter your enquery',
        '#required' => True,
    );
    $form['submit'] = 
        [
            '#type' => 'submit',
            '#value' => $this->t('Submit')
        ];
        return $form;
    }
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
     
    }
    public function submitForm(array &$form, FormStateinterface $form_state)
    {
     $email=$form_state->getvalue('email');
     $message=$form_state->getvalue('message');
     
     $connection = \Drupal::service('database');
     
     $result = $connection->insert('tb_enquery')
    ->fields([
    'email' => $email,
    'message' =>$message, 
    ])->execute();

     }
}

?>
