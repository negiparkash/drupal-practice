<?php

namespace Drupal\form_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "form-block",
 *   admin_label = @Translation("form-block"),
 *   category = @Translation("form-block"),
 * )
 */
class FormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    
      return \Drupal::formBuilder()->getForm('Drupal\form_module\Form\Register_User');
    
  }

}