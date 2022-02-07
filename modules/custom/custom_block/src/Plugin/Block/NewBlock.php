<?php

namespace Drupal\custom_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Hello' Block.
 *
 * @Block(
 *   id = "hello_block",
 *   admin_label = @Translation("information"),
 *   category = @Translation("Hello World"),
 * )
 */
class NewBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    
      return [
        '#theme' => 'custom_block',
        '#title' => 'custom-block',
        '#data' => [
        'Developed by' => 'parkash singh',
        'since'=>'2 May 1998',
      ],
    ];
    
  }

}