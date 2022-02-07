<?php
namespace Drupal\form_module\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Database\Database;
use \Drupal\user\Entity\User;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;

class Register_User extends FormBase {

public function getFormId()
{
    return 'Register_user';    
}

public function buildForm(array $form, FormStateinterface $form_state)
{   
   $form['username'] = array(
       '#type' => 'textfield',
       '#title' => 'User Name',
       '#placeholder' => 'Enter user name',
       '#required' => True,
   );
   $form['email'] = array(
    '#type' => 'email',
    '#title' => 'Enter Email',
    '#placeholder' => 'Enter email Address',
    '#required' => True,
    );
    $form['password'] = array(
        '#type' => 'password',
        '#title' => 'Enter password',
        '#placeholder' => 'Enter password',
        '#required' => True,
    );
    $form['message'] = array(
        '#type' => 'textfield',
        '#title' => 'Message',
        '#placeholder' => 'Enter your text',
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
     if(strlen($form_state->getvalue('username')) < 3)
     {
         $form_state->setErrorByname('username',$this->t('Your name length is short'));
     }
     if(strlen($form_state->getvalue('mobile')) > 10)
     {
         $form_state->setErrorByname('mobile',$this->t('Enter valid mobile number'));
     }
     elseif(strlen($form_state->getvalue('mobile')) > 10)
     {
         $form_state->setErrorByname('mobile',$this->t('It takes only 10 digit'));
     }
    }
    public function submitForm(array &$form, FormStateinterface $form_state)
    {
     $username =$form_state->getvalue('username');
     $email=$form_state->getvalue('email');
     $password=$form_state->getvalue('password');
     $message=$form_state->getvalue('message');
     
     $database= \Drupal::Database();
     
     $query = $database->query("SELECT * FROM users_field_data WHERE mail='$email'");
     $result=$query->fetchAll();

     if(count($result) < 1)
     {
         $data=array(
             'name' => $username,
             'mail' => $email,
             'pass' => $password,
             'message' => $message,
             'status' => 1,
             'rols'=> array("DRUPAL_AUTHENTICATED_RID" => 'authenticated user',
                            3 => 'administrator',
                            )                           
         );         
         
         // creating node progrmatically  with (use \Drupal\node\Entity\Node;)
        $node = Node::create([
            'type'  => 'services',
            'title' => $username,
          ]);
          $node->save();

         $user = User::create($data)->save();
         $uid = user_load_by_mail($email); // user load 
         $uid->addRole('administrator');
         $uid->save();
         
     }
    }
}

?>
