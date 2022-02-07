<?php
namespace Drupal\form_module\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
    

class ADD_USER extends FormBase {
     
    public function getFormId() 
    {
        return 'Register User';
    }


      public function buildForm(array $form, FormStateInterface $form_state) {
        $form['user_name'] = array (
            '#type' => 'textfield',
            '#title' => t('Enter Name:'),
            '#placeholder'=> 'Enter The Username',
            '#class' => 'col-md-6',
            '#required' => TRUE,
          );
        $form['user_email'] = array(
            '#type' => 'email',
            '#title' => t('Enter Email'),
            '#placeholder'=> 'Enter email-id',
            '#required' => True,
        );
        $form['user_mobile'] = array(
            '#type' => 'tel',
            '#title' => t('Enter Contact Number'),
            '#placeholder'=> 'Enter mobile-num',
            '#required' => True,
        );
        $form['user_message'] = array(
            '#type' => 'textfield',
            '#title' => t('Type message'),
            '#placeholder'=> 'Enter your-text',
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
        if(strlen($form_state->getvalue('user_mobile')) < 9)
        {
            $form_state->set_error_Byname('user_mobile',$this->t('please enter valid mobile number'));
        }
        elseif(strlen($form_state->getvalue('user_mobile')) > 10)
        {
            $form_state->set_error_Byname('user_mobile',$this->t('please enter valid mobile number'));
        }
      }
      public function submitForm(array &$form, FormStateInterface $form_state)
      {
        \Drupal::messenger()->addMessage(t("User Registration Done!! Registered Values are:"));
        foreach ($form_state->getValues() as $key => $value) {
          \Drupal::messenger()->addMessage($key . ': ' . $value);
        }
      }
}
?>
