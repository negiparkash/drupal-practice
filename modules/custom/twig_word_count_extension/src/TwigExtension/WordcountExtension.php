<?php

namespace Drupal\twig_word_count_extension\TwigExtension;

use Twig_Extension;
use Twig_SimpleFilter;

class WordcountExtension extends \Twig_Extension  {
  /**
   * This is the same name we used on the services.yml file
   */
  public function getName() {
    return 'twig_word_count_extension.twig_extension';
  }

  // Basic definition of the filter. You can have multiple filters of course.
  public function getFilters() {
    return [
      new Twig_SimpleFilter('word_count', [$this, 'wordCountFilter']),
      new Twig_SimpleFilter('word_count2', [$this, 'arraycount']),
      new Twig_SimpleFilter('arraycon', [$this, 'arrayconversion']),
      new Twig_SimpleFilter('printAuthor', [$this, 'printAuthor']),
    ];
  }
  // The actual implementation of the filter.
  public function wordCountFilter($context) {
    if(is_string($context)) {
      $context = str_word_count($context);
    }
    return $context;
  }
  public function arraycount($data)
  {
    $efo='';
    if(is_array($data))
    {
      print_r($data);
    }
    // return $efo;
  }
  public function arrayconversion($value)
  {
    $efo='';
    if(is_array($value))
    {
      foreach($value as $x => $va)
      {
      $name[$x] = $va;
      }
      echo "<pre>";  
      var_dump($name);
    }
    // return $efo;
  }
  public function printAuthor($node) {
    if (!($node instanceof \Drupal\node\Entity\Node)) {
       return;
     }
     $user = $node->getOwner();
     return $user->getDisplayName();
   }
}
?>