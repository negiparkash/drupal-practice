<?php

/**
 * @file
 * Contains \Drupal\rest_api_authentication\Form\miniorangeAPIAuth.
 */

namespace Drupal\rest_api_authentication\Form;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\rest_api_authentication\MiniorangeRestAPICustomer;
use Drupal\rest_api_authentication\MiniorangeApiAuthConstants;
use Drupal\rest_api_authentication\Utilities;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class miniorangeAPIAuth extends FormBase {

    public function getFormId() {
        return 'rest_api_authentication_config_client';
    }

    public function buildForm(array $form, FormStateInterface $form_state){
        global $base_url;
        $auth_method = \Drupal::config('rest_api_authentication.settings')->get('authentication_method');
        if(empty($auth_method) || $auth_method=null){
          \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('authentication_method',0)->save();
        }
        $module_path = drupal_get_path('module', 'rest_api_authentication');
        $finalpath = $base_url;
        $form['markup_library'] = array(
          '#attached' => array(
              'library' => array(
                  "rest_api_authentication/rest_api_authentication.style_settings",
              )
          ),
      );
      $form['markup_reg_msg'] = array(
        '#markup' => '<br><div class="mo_oauth_server_highlight_background_note_1">If you want to test the Premium verison of the module, please reach out to us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a> and we can provide you up with a <b><u>14 Day trial version</u></b> of the module </div><br>',
      );
      $prefixvalue = '<div class="mo_rest_token_div"><table><tr><td style="vertical-align: middle;">';
      $suffixvalue = '</td>';
      $prefixvalue1 = '<div class="mo_rest_token_div">';
      $suffixvalue1 = '</div>';
      $current_status = \Drupal::config('rest_api_authentication.settings')->get('rest_api_authentication_status');
      $form['header_top_style_1'] = array('#markup' => '<div class="mo_oauth_table_layout_1"><table><tr class="hidden-table"><td>');
      
        $tab = (isset($_GET['tab'])) ? ($_GET['tab']) : ('edit-api-auth');
        
      
        $form['information'] = array(
            '#type' => 'vertical_tabs',
            '#default_tab' => $tab,
          );

          /**
           * api method settings tabs
           */
          $form['api_auth'] = array(
            '#type' => 'details',
            '#title' => $this
              ->t('<br>Configure API Authentication'),
            '#group' => 'information',
          );
          $form['api_auth']['mo_rest_api_authentication_authenticator'] = array(
            '#type' => 'fieldset',
            '#attributes' => array( 'style' => 'padding:2% 2% 8% 5%; margin-bottom:4%;' ),
            );
            $form['api_auth']['mo_rest_api_authentication_authenticator']['head_text'] = array(
              '#markup' => '<h3><b>SELECT AN AUTHENTICATION METHOD OF YOUR CHOICE:</b> <a class="btn btn-know-more" target="_blank" href="https://plugins.miniorange.com/drupal-api-authentication">Setup Guide</a><br></h3><br><hr><div class="mo_oauth_server_highlight_background_note_1">Need any help? We can help you with configuring your API authentication method. Just send us a query and we will get back to you soon.<br/></div><br>',
            );
          $form['api_auth']['mo_rest_api_authentication_authenticator']['settings']['active'] = array(
            '#type' => 'radios',
            '#prefix' => '<br>',
            '#title' => '',
            '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('authentication_method'),
            '#options' => array(
              0 => $this
                ->t('Basic Authentication'),
              1 => $this
                ->t('API Key'),
              2 => $this
                ->t('OAuth <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans"><b>[Premium]</b></a>'),
              3 => $this
                ->t('JWT <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans"><b>[Premium]</b></a>'),
              4 => $this
                ->t('3rd Party <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans"><b>[Premium]</b></a>'),
            ),
            '#attributes' => array('class' => array('container-inline')),
          );
          
          $form['api_auth']['rest_api_authentication_method_submit'] = array(
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 0 ), ),),
            '#value' => t('Select Method'),
            '#submit' => array('::rest_api_authentication_save_basic_auth_conf'),
          );




          $form['api_auth']['mo_rest_api_authentication_authenticator']['rest_api_authentication_ext_oauth'] = array(
            '#type' => 'textfield',
            '#id'  => 'rest_api_authentication_token_key',
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 4 ), ),),
            '#title' => $this->t('User Info Endpoint: '),
            '#disabled' => TRUE,
            '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('user_info_endpoint'),
            '#attributes' => array('style' => 'width:100%'),
            '#prefix' => $prefixvalue1,
          );
          $form['api_auth']['mo_rest_api_authentication_authenticator']['rest_api_authentication_ext_oauth_username'] = array(
            '#type' => 'textfield',
            '#disabled' => TRUE,
            '#id'  => 'rest_api_authentication_token_key',
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 4 ), ),),
            '#title' => $this->t('Username Attribute: '),
            '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('username_attribute'),
            '#attributes' => array('style' => 'width:100%'),
            '#suffix' => $suffixvalue1,
          );
          $form['api_auth']['rest_api_authentication_save_ext_oauth'] = array(
            '#type' => 'submit',
            '#disabled' => TRUE,
            '#button_type' => 'primary',
            '#value' => t('Save Configurations'),
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 4 ), ),),
            '#submit' => array('::rest_api_authentication_save_ext_oauth'),
            );

          $form['api_auth']['mo_rest_api_authentication_authenticator']['rest_api_authentication_key'] = array(
            '#type' => 'textfield',
            '#id'  => 'rest_api_authentication_token_key',
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 1 ), ),),
            '#title' => $this->t('API Key required for Authentication: '),
            '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('api_token'),
            '#attributes' => array('style' => 'width:100%'),
            '#prefix' => $prefixvalue,
            '#suffix' => $suffixvalue,
          );
          $form['api_auth']['rest_api_authentication_generate_key'] = array(
            '#type' => 'submit',
            '#button_type' => 'primary',
            '#value' => t('Generate API Key'),
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 1 ), ),),
            '#submit' => array('::rest_api_authentication_generate_api_token'),
            '#prefix' => '<td class="btn">',
            '#suffix' => '</td></tr></table></div>',
          
            );

          $form['api_auth']['rest_api_authentication_key_generate_all_keys'] = array(
            '#type' => 'checkbox',
            '#disabled' => TRUE,
            '#title' => t('Generate separate API Keys for all Drupal users <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans">[Premium]</a>'),
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 1 ), ),),
          );
          $form['api_auth']['rest_api_authentication_key_generate_akey'] = array(
            '#type' => 'checkbox',
            '#disabled' => TRUE,
            '#title' => t('Reset API Key for a specific Drupal user <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans">[Premium]</a>'),
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 1 ), ),),
          );

          $form['api_auth']['mo_rest_api_authentication_authenticator']['rest_api_authentication_oauth_client_id'] = array(
            '#type' => 'textfield',
            '#id'  => 'rest_api_authentication_token_key',
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 2 ), ),),
            '#title' => $this->t('Client ID: '),
            '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('oauth_client_id'),
            '#attributes' => array('style' => 'width:100%'),
            '#disabled' => 'true',
          );
          $form['api_auth']['mo_rest_api_authentication_authenticator']['rest_api_authentication_oauth_client_secret'] = array(
            '#type' => 'textfield',
            '#id'  => 'rest_api_authentication_token_key',
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 2 ), ),),
            '#title' => $this->t('Client Secret: '),
            '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('oauth_client_secret'),
            '#attributes' => array('style' => 'width:100%'),
            '#disabled' => 'true',
          );
          $form['api_auth']['rest_api_authentication_generate_and_secret'] = array(
            '#type' => 'submit',
            '#disabled' => TRUE,
            '#value' => t('Generate a new Client ID and Secret'),
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 2 ), ),),
            '#submit' => array('::rest_api_authentication_generate_oauth_keys'),
          );
          $form['api_auth']['rest_api_authentication_save_oauth_config'] = array(
            '#type' => 'submit',
            '#disabled' => TRUE,
            '#button_type' => 'primary',
            '#value' => t('Save Settings'),
            '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 2 ), ),),
            '#submit' => array('::rest_api_authentication_save_oauth_token'),
          );
      $form['api_auth']['rest_api_authentication_generate_id_token'] = array(
        '#type' => 'submit',
        '#button_type' => 'primary',
        '#disabled' => TRUE,
        '#value' => t('Save JWT Configuration'),
        '#states' => array('visible' => array(':input[name = "active"]' => array('value' => 3 ), ),),
        '#submit' => array('::rest_api_authentication_save_id_token'),
      );
      $disabled = TRUE;
      $form['advanced_settings'] = array(
        '#type' => 'details',
        '#title' => $this
          ->t('Advance Settings'),
        '#group' => 'information',
      );
      $form['advanced_settings']['support_container_outline'] = array(
        '#type' => 'fieldset',
        '#attributes' => array( 'style' => 'padding:2% 2% 8% 5%; margin-bottom:4%;' ),
      );
      $form['advanced_settings']['support_container_outline']['publisher'] = array(
        '#markup' => '<b>ADVANCED SETTINGS:</b><hr><br><div class="mo_oauth_server_highlight_background_note_1">Need any help? Just send us a query and we will help you setup the module in no time.<br /></div><br>',
      );
      $form['advanced_settings']['support_container_outline']['custom_headers'] = array(
        '#type' => 'details',
        '#title' => $this
          ->t(' Custom Header <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans" style="color:red">Premium</a>'),
        '#description' => 'This feature allows you to set custom headers for authentication. If you want to authenticate the Drupal REST APIs in a more secure way, you can set a custom Header.',
      );
      $form['advanced_settings']['support_container_outline']['custom_headers']['div_key'] = array(
        '#type' => 'textfield',
        '#disabled' => $disabled,
        '#id'  => 'rest_api_authentication_token_key',
        '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('custom_header'),
        '#title' => $this->t('Custom header for authentication: '),
        '#attributes' => array('style' => 'width:50%'),
      );
      $form['advanced_settings']['support_container_outline']['custom_headers']['div_key3'] = array(
        '#type' => 'submit',
        '#button_type' => 'primary',
        '#disabled' => $disabled,
        '#value' => t('Save Configuration'),
        '#submit' => array('::custom_header_submit'),
      );

    $form['advanced_settings']['support_container_outline']['token_expiry'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t(' Token Expiry Configurations <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans" style="color:red">Premium</a>'),
      '#description' => 'Eligible for OAuth 2.0 and JWT Authentication. JWT Token and the OAuth Access Token will be expired on the given time.',
    );

    $form['advanced_settings']['support_container_outline']['token_expiry']['access_token_expiry_time'] = array(
      '#type' => 'textfield',
      '#disabled' => $disabled,
      '#title' => $this->t('Access Token Expiry Time (In minutes): '),
      '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('token_expiry'),
      '#attributes' => array('style' => 'width:50%'),
    );
    $form['advanced_settings']['support_container_outline']['token_expiry']['support_container_outline'] = array(
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#disabled' => $disabled,
      '#value' => t('Save Token Configuration'),
      '#submit' => array('::token_expiry_submit'),
    );

    $form['advanced_settings']['support_container_outline']['list_ip'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t(' White list & Blacklist Ip Addresses <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans" style="color:red">Premium</a>'),
      '#description' => 'Given APIs will be publicly accessible from the all users.',
    );
    $form['advanced_settings']['support_container_outline']['list_ip']['settings'] = array(
      '#type' => 'radios',
      '#disabled' => $disabled,
      '#prefix' => '<hr><br>',
      '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('ip_access_type'),
      '#title' => '',
      '#options' => array(
        0 => $this
          ->t('Allowed IP Addresses'),
        1 => $this
          ->t('Blocked IP Addresses'),
      ),
      '#attributes' => array('class' => array('container-inline')),
    );
  $form['advanced_settings']['support_container_outline']['list_ip']['ip_textarea'] = array(
    '#type' => 'textarea',
    '#disabled' => $disabled,
    '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('list_of_ips'),
    '#title' => $this->t('You can add the IP Addresses here: '),
    '#attributes' => array('style' => 'width:100%','placeholder' => 'You can also add multiple APIs using a ; as a seperator'),
  );
  $form['advanced_settings']['support_container_outline']['list_ip']['token_submit_key3'] = array(
    '#type' => 'submit',
    '#disabled' => $disabled,
    '#button_type' => 'primary',
    '#name'=>'submit',
    '#value' => t('Save IP Configuration'),
    '#submit' => array('::ip_restriction_submit'),
  );

  $form['advanced_settings']['support_container_outline']['list_apis'] = array(
    '#type' => 'details',
    '#title' => $this
      ->t(' Restrict custom APIs <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans" style="color:red">Premium</a>'),
    '#description' => 'Given APIs will be restricted. You can and multipel APIs using ";" as a separator',
  );
  $form['advanced_settings']['support_container_outline']['list_apis']['custom_api_textarea'] = array(
    '#type' => 'textarea',
    '#disabled' => $disabled,
    '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('list_of_apis'),
    '#title' => $this->t('You can add the APIs here: '),
    '#attributes' => array('style' => 'width:100%','placeholder' => 'You can also add multiple APIs using a ; as a seperator'),
  );
  $form['advanced_settings']['support_container_outline']['list_apis']['token_submit_key3'] = array(
    '#type' => 'submit',
    '#button_type' => 'primary',
    '#disabled' => $disabled,
    '#name'=>'submit',
    '#value' => t('Save Configuration'),
  );

  $form['advanced_settings']['support_container_outline']['restrict_roles'] = array(
    '#type' => 'details',
    '#title' => $this
      ->t(' Role Based Restriction <a href = "'.$base_url.'/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-upgrade-plans" style="color:red">Premium</a>'),
    '#description' => 'Restriction based on User Roles.',
  );
  $form['advanced_settings']['support_container_outline']['restrict_roles']['api_textarea'] = array(
    '#type' => 'textarea',
    '#disabled' => $disabled,
    '#default_value' => \Drupal::config('rest_api_authentication.settings')->get('list_of_role_restrictions'),
    '#title' => $this->t('You can add the APIs and roles here: '),
    '#attributes' => array('style' => 'width:100%','placeholder' => 'You can also add multiple APIs->roles using a ; as a seperator'),
  );
  $form['advanced_settings']['support_container_outline']['restrict_roles']['token_submit_key3'] = array(
    '#type' => 'submit',
    '#button_type' => 'primary',
    '#disabled' => $disabled,
    '#value' => t('Save Role Based Restrictions'),
    '#submit' => array('::role_restriction_submit'),
  );


  $form['demo'] = array(
    '#type' => 'details',
    '#title' => $this
      ->t('Support/Request For a Demo'),
    '#group' => 'information',
  );
  $form['demo']['container_outline'] = array(
    '#type' => 'fieldset',
    '#attributes' => array( 'style' => 'padding:2% 2% 8% 5%; margin-bottom:4%;' ),
  );
  $form['demo']['container_outline']['markup_support_1'] = array(
    '#markup' => '<h3><b>Support/Request for a demo/Custom Feature Request:</b></h3><hr><br><div class="mo_oauth_server_highlight_background_note_1">Want to test our premium module before purchasing? Just send us a request from here and we will get back to you in no time.<br>In case your requirements include any custom APIs, we can integrate those APIs for you as well.</div><br>',
  );
  $form['demo']['container_outline']['rest_api_authentication_email_address'] = array(
      '#type' => 'textfield',
      '#id' => 'general_text_field',
      '#prefix' => '<br>',
      '#attributes' => array('placeholder' => 'Enter your Email','style' => 'width: 70%'),
  );

      $form['demo']['container_outline']['rest_api_authentication_phone_number'] = array(
          '#type' => 'textfield',
          '#id' => 'general_text_field',
          '#prefix' => '<br>',
          '#attributes' => array('placeholder' => 'Enter your Phone Number','style' => 'width: 70%'),
      );

      $form['demo']['container_outline']['rest_api_authentication_demo_query'] = array(
          '#type' => 'textarea',
          '#id' => 'general_text_field',
          '#prefix' => '<br>',
          '#attributes' => array('placeholder' => 'Please write your use case requirements here','style' => 'width: 70%'),
      );
      $form['demo']['container_outline']['rest_api_authentication_demo_query_submit'] = array(
        '#type' => 'submit',
        '#prefix' => '<br>',
        '#button_type' => 'primary',
        '#value' => t('Submit'),
        '#submit' => array('::saved_demo_request'),
      );
      $form['upgrade_plans'] = array(
        '#type' => 'details',
        '#title' => $this
          ->t('<br>Upgrade Plans'),
        '#group' => 'information',
      );
      $form['upgrade_plans']['upgradecontainer_outline'] = array(
        '#type' => 'fieldset',
        '#attributes' => array( 'style' => 'padding:2% 2% 8% 5%; margin-bottom:4%;' ),
      );
      $form['upgrade_plans']['upgradecontainer_outline']['licensing'] = array(
        '#markup' =>'<html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- Main Style -->
        </head>
        <body>
        <!-- Pricing Table Section -->
        <section id="pricing-table">
            <div class="mo_oauth_container_1">
                <div class="mo_oauth_row">
                    <div class="pricing">
                        <div>


                        <div class="pricing-table class_inline_1">
                        <div class="pricing-header">
                            <p class="pricing-title">Free</p>
                            <p class="pricing-rate"><sup>$</sup> 0</p>
                             <a href="#" class="btn btn-primary">Current Plan</a><br><br>
                        </div>

                        <div class="pricing-list">
                            <ul>
                              <li>Single App Support</li>
                              <li>Supports JSON API module</li>
                              <li>Supports default REST APIs</li>
                              <li>API Key Authentication</li>
                              <li>Basic Authentication</li>
                              <li>-</li>
                              <li>-</li>
                              <li>-</li>
                              <li>-</li>
                              <li>-</li>
                              <li>-</li>
                              <li>-</li>
                              <li>-</li>
                              <li>-</li>
                              <li>Basic Email Support</li>
                            </ul>
                        </div>
                    </div>

                            <div class="pricing-table class_inline_1">
                                <div class="pricing-header">
                                    <p class="pricing-title">Premium</p>
                            <p class="pricing-rate"><sup>$</sup> 349<sup>*</sup></p>
                            <a href="https://www.miniorange.com/contact" target="_blank" class="btn btn-primary">Contact Us</a><br><br>
                                </div>
                                <div class="pricing-list">
                                    <ul>
                                        <li>Single App Support</li>
                                        <li>Supports JSON API module</li>
                                        <li>Supports default REST APIs</li>
                                        <li>API Key Authentication</li>
                                        <li>Basic Authentication</li>
                                        <li>Access Token Authentication</li>
                                        <li>JWT Authentication</li>
                                        <li>OAuth 2 Authentication</li>
                                        <li>Generate separate API Keys for every user</li>
                                        <li>Custom Header</li>
                                        <li>Custom Token Expiry</li>
                                        <li>Custom API Restrictions</li>
                                        <li>IP Address Based Restriction</li>
                                        <li>Role Based Restriction</li>
                                        <li>Premium GoTo Meeting Support</li>
                                    </ul>
                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pricing Table Section End -->
    </body>
    </html>',
      );
      $form['upgrade_plans']['upgradecontainer_outline']['instr'] = array(
        '#markup' => '
        <br>
        <b>*</b>The above mentioned cost is applicable for one instance only. Licenses are perpetual and include free version updates for the first 12 months. You can renew maintenance(version updates) after the first 12 months at 50% of the current license cost. However, even if you do not want to renew your maintenance, the module will still work.
        <br><br><br><b>10 Days Return Policy -</b><br><br> 
        At miniOrange, we want to ensure you are 100% happy with your purchase. If the premium module you purchased is not working as advertised and you have attempted to resolve any issues with our support team, which could not get resolved. we will refund the whole amount given that you have a raised a refund request within the first 10 days of the purchase.<br><br>Please email us at <a href="mailto:drupalsupport@xecurify.com">drupalsupport@xecurify.com</a> for any queries.'
    );
          /**
           * customer setup
           */
          $form['customer_setup'] = array(
            '#type' => 'details',
            '#title' => $this
              ->t('Register/Login'),
            '#group' => 'information',
          );
          $form['customer_setup']['customer_setup_container_outline'] = array(
            '#type' => 'fieldset',
            '#attributes' => array( 'style' => 'padding:2% 2% 8% 5%; margin-bottom:4%;' ),
          );

          if ($current_status == 'PLUGIN_CONFIGURATION') {

            $form['customer_setup']['customer_setup_container_outline']['markup_support_1'] = array(
              '#markup' => '<div class="mo_rest_welcome_message">Thank you for registering with miniOrange</div><br><br><h4>Your Profile: </h4>'
          );

          $header = array(
              'email' => array(
                  'data' => t('Customer Email')
              ),
              'customerid' => array(
                  'data' => t('Customer ID')
              ),
              'token' => array(
                  'data' => t('Token Key')
              ),
              'apikey' => array(
                  'data' => t('API Key')
              ),
          );



          $options = [];

          $options[0] = array(
              'email' => \Drupal::config('rest_api_authentication.settings')->get('rest_api_authentication_customer_admin_email'),
              'customerid' => \Drupal::config('rest_api_authentication.settings')->get('rest_api_authentication_customer_id'),
              'token' => \Drupal::config('rest_api_authentication.settings')->get('rest_api_authentication_customer_admin_token'),
              'apikey' => \Drupal::config('rest_api_authentication.settings')->get('rest_api_authentication_customer_api_key'),
          );

          $form['customer_setup']['customer_setup_container_outline']['customerinfo'] = array(
              '#theme' => 'table',
              '#header' => $header,
              '#rows' => $options,
              '#suffix' => '<br>'
          );

        \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_status', $current_status)->save();
      }
          else{
          $form['customer_setup']['customer_setup_container_outline']['markup_support_1'] = array(
            '#markup' => '<h3><b>Login to your account using your miniOrange credentials</b></h3><hr><br><div class="mo_oauth_server_highlight_background_note_1">If you do not have an account with us yet, please click on the link <a href="https://www.miniorange.com/businessfreetrial" target="_blank">here</a><br>In case you are facing any issues while trying to configure or use our module, just send us a request and we will get back to you soon.<br /></div><br>',
        );
  
        $form['customer_setup']['customer_setup_container_outline']['rest_api_authentication_user_email'] = array(
            '#type' => 'textfield',
            '#id' => 'general_text_field',
            '#title' => $this->t('Email: '),
            '#prefix' => '<br>',
            '#attributes' => array('placeholder' => 'Enter your Email here','style' => 'width: 70%'),
        );
  
        $form['customer_setup']['customer_setup_container_outline']['rest_api_authentication_user_password'] = array(
            '#type' => 'password',
            '#id' => 'general_text_field',
            '#title' => $this->t('Password: '),
            '#prefix' => '<br>',
            '#attributes' => array('placeholder' => 'Enter your Password here','style' => 'width: 70%'),
        );

        $form['customer_setup']['customer_setup_container_outline']['rest_api_authentication_activate_module_submit'] = array(
          '#type' => 'submit',
          '#prefix' => '<br><table><tr><td>',
          '#suffix' => '',
          '#button_type' => 'primary',
          '#value' => t('Activate Module'),
          '#submit' => array('::activate_module_request'),
        );
        $form['header_bottom_create_account'] = array('#markup' => '<a href="https://www.miniorange.com/businessfreetrial" target="_blank">  Create a new account</a></td></tr></table>');
      }

    $form['header_bottom1_style_1'] = array('#markup' => '</td><td style = "vertical-align: baseline;">');
    $form['markup_idp_attr_header_top_support'] = array(
        '#markup' => '<div id="Support_Section" class="mo_saml_table_layout_support_1">',
    );

    $form['markup_support_1'] = array(
        '#markup' => '<h3><b>Feature Request/Contact Us:</b></h3><div>Need any help? We can help you with configuring the module according to your requirements. Just send us a query and we will get back to you soon.<br /></div><br>',
    );

    $form['rest_api_authentication_support_email_address'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => 'Enter your Email'),
    );
    $form['rest_api_authentication_support_phone_number'] = array(
        '#type' => 'textfield',
        '#attributes' => array('placeholder' => 'Enter your Phone Number'),
    );

    $form['rest_api_authentication_support_query'] = array(
        '#type' => 'textarea',
        '#clos' => '10',
        '#rows' => '5',
        '#attributes' => array('placeholder' => 'Write your query here'),
    );
    $form['rest_api_authentication_support_query_submit'] = array(
      '#type' => 'submit',
      '#prefix' => '<table><tr><td>',
      '#suffix' => '</td>',
      '#button_type' => 'primary',
      '#value' => t('Send Query'),
      '#submit' => array('::saved_support'),
    );

    $form['rest_api_authentication_support_queryss_submit'] = array(
      '#prefix' => '<td>',
      '#suffix' => '</td></tr></table>',
      '#markup' => '<a class="btn btn-primary-color" href= "https://plugins.miniorange.com/drupal-api-authentication" target="_blank">Setup Guide</a>',

    );

      $form['header_bottom2_style_1'] = array('#markup' => '</td></tr></table>');
      return $form;
    }

    function rest_api_authentication_generate_api_token(array &$form, FormStateInterface $form_state){
      global $base_url;
      $api_key = Utilities::generateRandom(64);
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('api_token', $api_key)->save();
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('authentication_method',1)->save();
      \Drupal::messenger()->addMessage(t('New API Key generated successfully.'));
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-api-auth");
      $response->send();
      return;
    }
    function rest_api_authentication_save_ext_oauth(array &$form, FormStateInterface $form_state){
      global $base_url;
      $user_info = $form['api_auth']['mo_rest_api_authentication_authenticator']['rest_api_authentication_ext_oauth']['#value'];
      $username_attribute = $form['api_auth']['mo_rest_api_authentication_authenticator']['rest_api_authentication_ext_oauth_username']['#value'];
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('user_info_endpoint',$user_info)->save();
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('username_attribute',$username_attribute)->save();
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('authentication_method',4)->save();
      \Drupal::messenger()->addMessage(t('Configurations for 3rd party provider saved successfully.'));
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-api-auth");
      $response->send();
      return;
    }
    function rest_api_authentication_generate_oauth_keys(array &$form, FormStateInterface $form_state){
      $client_id = Utilities::generateRandom(30);
      $client_secret = Utilities::generateRandom(30);
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('oauth_client_id',$client_id)->save();
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('oauth_client_secret',$client_secret)->save();
      self::rest_api_authentication_save_oauth_token($form, $form_state);
    }
    function rest_api_authentication_save_oauth_token(array &$form, FormStateInterface $form_state){
      global $base_url;
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('authentication_method',2)->save();
      \Drupal::messenger()->addMessage(t('OAuth method configurations Saved successfully.'));
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-api-auth");
      $response->send();
      return;
    }
    function rest_api_authentication_save_id_token(array &$form, FormStateInterface $form_state){
      global $base_url;
      if(isset($form['api_auth']['mo_rest_api_authentication_authenticator']['settings']['active']))
        $authentication_method =  $form['api_auth']['mo_rest_api_authentication_authenticator']['settings']['active']['#value'];     
      if($authentication_method == 0){
        \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('authentication_method',0)->save();
        \Drupal::messenger()->addMessage(t('Basic Authentication Configurations Saved successfully.'));
        $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-api-auth");
        $response->send();
        return;
      }
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('authentication_method',3)->save();
      \Drupal::messenger()->addMessage(t('Selected method saved Successfully.'));
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-api-auth");
      $response->send();
      return;
    }

    function submitForm(array &$form, FormStateInterface $form_state){
      global $base_url;
      $list_of_apis = $form['advanced_settings']['support_container_outline']['list_apis']['api_textarea']['#value'];
      $api_access =   $form['advanced_settings']['support_container_outline']['list_apis']['settings']['#value'];
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('api_access_type',$api_access)->save();
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('list_of_apis',$list_of_apis)->save();
      \Drupal::messenger()->addMessage(t('Configurations for API Based Restriction saved successfully.'));
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-advanced-settings");
      $response->send();
      return;
      return;
    }

    function rest_api_authentication_save_basic_auth_conf(array &$form, FormStateInterface $form_state){
      global $base_url;
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('authentication_method',0)->save();
      \Drupal::messenger()->addMessage(t('Configurations saved successfully.'));
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-api-auth");
      $response->send();
      return;
    }
    
    /**
     * Send support query.
     */
    public function saved_support(array &$form, FormStateInterface $form_state) {
      $email = trim($form['rest_api_authentication_support_email_address']['#value']);
      $phone = $form['rest_api_authentication_support_phone_number']['#value'];
      $query = trim($form['rest_api_authentication_support_query']['#value']);
      Utilities::send_support_query($email, $phone, $query, null);
    }

    /**
     * Custom Header Save Button
     */
    public function custom_header_submit(array &$form, FormStateInterface $form_state) {
      global $base_url;
      $custom_header = trim($form['advanced_settings']['support_container_outline']['custom_headers']['div_key']['#value']);
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('custom_header',$custom_header)->save();
      \Drupal::messenger()->addMessage(t('Custom Header Configurations saved successfully.'));
      
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-advanced-settings");
      $response->send();
      return;
    }

    /**
     * Save token Expiry
     */
    public function token_expiry_submit(array &$form, FormStateInterface $form_state) {
      global $base_url;
      $custom_header = trim($form['advanced_settings']['support_container_outline']['custom_headers']['div_key']['#value']);
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('custom_header',$custom_header)->save();
      $token_expiry = trim($form['advanced_settings']['support_container_outline']['token_expiry']['access_token_expiry_time']['#value']);
      \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('token_expiry',$token_expiry)->save();
      \Drupal::messenger()->addMessage(t('Configurations saved successfully.'));
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-advanced-settings");
      $response->send();
      return;
    }

    /**
     * Send a request for Demo.
     */
    public function saved_demo_request(array &$form, FormStateInterface $form_state) {      
      global $base_url;
      $email = trim($form['demo']['container_outline']['rest_api_authentication_email_address']['#value']);
      $phone = $form['demo']['container_outline']['rest_api_authentication_phone_number']['#value'];
      $query = trim($form['demo']['container_outline']['rest_api_authentication_demo_query']['#value']);
      Utilities::send_support_query($email, $phone, $query, 'demo');
      $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-demo");
      $response->send();
    }


/**
 * Remove account
 */
    function rest_api_authentication_remove_account(&$form, $form_state){
        global $base_url;
        if (isset($_POST['value_check']) && $_POST['value_check'] == 'True'){ 
            if(\Drupal::config('rest_api_authentication.settings')->get('rest_api_authentication_license_key') != NULL) {
              $current_status = 'CUSTOMER_SETUP';
                $username = \Drupal::config('rest_api_authentication.settings')->get('rest_api_authentication_customer_admin_email');
                $customer = new MiniorangeRestAPICustomer($username, NULL);
                $response = json_decode($customer->updateStatus());

                if ($response->status == 'SUCCESS'){
                    $clear_value = '';
                    \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_admin_email', $clear_value)->save();
                    \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_id', $clear_value)->save();
                    \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_api_key', $clear_value)->save();
                    \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_license_key', $clear_value)->save();
                    \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_password', $clear_value)->save();

                    \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_status', $current_status)->save();

                    \Drupal::messenger()->addMessage(t('Your account has been removed successfully!'), 'status');
                    $_POST['value_check'] = 'False';
                }
            }
            $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-customer-setup");
            $response->send();
            return;
        }
        else{
            $myArray = array();
            $myArray = $_POST;
            $form_id = $_POST['form_id'];
            $form_token = $_POST['form_token'];
            $op = $_POST['op'];
            $build_id = $_POST['form_build_id'];
            global $base_url;
            ?>

            <html>
            <head>
                <title>Confirmation</title>
                <link href="https://fonts.googleapis.com/css?family=PT+Serif" rel="stylesheet">
            </head>

            <body style="font-family: 'PT Serif', serif;">
            <div style="margin: 15% auto; height:35%; width: 40%; background-color: #eaebed; text-align: center; box-shadow: 10px 5px 5px darkgray; border-radius: 2%;">
                <div style="color: #a94442; background-color:#f2dede; padding: 15px; margin-bottom: 20px; text-align:center; border:1px solid #E6B3B2; font-size:16pt; border-radius: 2%;">
                    <strong>Are you sure you want to remove account..!!</strong>
                </div>
                <p style="font-size:14px; margin-left: 8%; margin-right: 8%"><strong>Warning </strong>: If you remove your account, you will have to enter licence key again after login/sign in with the new account.</p>
                <br/>
                <form name="f" method="post" action="" id="mo_remove_account">
                    <div>
                        <input type="hidden" name="op" value=<?php echo $op;?>>
                        <input type="hidden" name="form_build_id" value= <?php echo $build_id;?>>
                        <input type="hidden" name="form_token" value=<?php echo $form_token;?>>
                        <input type="hidden" name="form_id" value= <?php echo $form_id;?>>
                        <input type="hidden" name="value_check" value= 'True'>
                    </div>
                    <div  style="margin: auto; text-align: center;"   class="mo2f_modal-footer">
                        <input type="submit" style=" padding:1%; width:100px; background: #0091CD none repeat scroll 0% 0%; cursor: pointer; font-size:15px; border-width: 1px; border-style: solid; border-radius: 3px; white-space: nowrap; box-sizing: border-box;border-color: #0073AA; box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset; color: #FFF;" name="miniorange_confirm_submit" class="button button-danger button-large" value="Confirm"/>&nbsp;&nbsp;&nbsp;&nbsp;

                        <a type="submit" style=" padding:1%; width:100px; background: #0091CD none repeat scroll 0% 0%; cursor: pointer; font-size:15px; border-width: 1px; border-style: solid; border-radius: 3px; white-space: nowrap; box-sizing: border-box;border-color: #0073AA; box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset; color: #FFF; text-decoration: none;" class="button button-danger button-large" href=<?php echo $base_url.'/admin/config/people/ldap_auth/customer_setup'; ?> >Cancel</a>

                    </div>
                </form>
            </div>
            </body>
            </html>
            <?php
            exit;
        }
    }

    /**
     * Activating the module
     */
    public function activate_module_request(array &$form, FormStateInterface $form_state) {
      global $base_url;
      $username = $form['customer_setup']['customer_setup_container_outline']['rest_api_authentication_user_email']['#value'];
      $password = $form['customer_setup']['customer_setup_container_outline']['rest_api_authentication_user_password']['#value'];
      if(empty($username)||empty($password)){
          \Drupal::messenger()->addMessage(t('The <b><u>Email Address</u></b> and the <b><u>Password</u></b> fields are mandatory.'), 'error');
          return;
      }
      if (!\Drupal::service('email.validator')->isValid( $username )) {
          \Drupal::messenger()->addMessage(t('The email address <i>' . $username . '</i> does not seems to be valid.'), 'error');
          return;
      }
      $customer_config = new MiniorangeRestAPICustomer($username,$password);
      $check_customer_response = json_decode($customer_config->checkCustomer());
      if ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') {
        \Drupal::messenger()->addMessage(t('Invalid credentials'), 'error');
        return;
      }
      elseif ($check_customer_response->status == 'CURL_ERROR') {
        \Drupal::messenger()->addMessage(t('cURL is not enabled. Please enable cURL'), 'error');
      }
      else {
        $customer_keys_response = json_decode($customer_config->getCustomerKeys());
        if (json_last_error() == JSON_ERROR_NONE) {
            \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_id', $customer_keys_response->id)->save();
            \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_admin_token', $customer_keys_response->token)->save();
            \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_admin_email', $username)->save();
            \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_api_key', $customer_keys_response->apiKey)->save();
            \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_customer_password', $password)->save();
          $current_status = 'PLUGIN_CONFIGURATION';
            \Drupal::configFactory()->getEditable('rest_api_authentication.settings')->set('rest_api_authentication_status', $current_status)->save();
            \Drupal::messenger()->addMessage(t('Successfully retrieved your account.'));
            $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-customer-setup");
            $response->send();
            return;
      }
      else {
        \Drupal::messenger()->addMessage(t('Invalid credentials'), 'error');
        $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-customer-setup");
        $response->send();
        return;
      }
    }
    $response = new RedirectResponse($base_url."/admin/config/people/rest_api_authentication/auth_settings/?tab=edit-customer-setup");
    $response->send();
      return;
    }
}