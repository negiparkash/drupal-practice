<?php

namespace Drupal\enquery_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "enquery-block",
 *   admin_label = @Translation("For Enquery"),
 *   category = @Translation("enquery-block"),
 * )
 */
class FormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    
      return \Drupal::formBuilder()->getForm('Drupal\enquery_module\Form\Enquery_user');
    
  }

}