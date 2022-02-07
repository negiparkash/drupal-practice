<?php
namespace Drupal\filter_name\Plugin\Filter;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 *Replaces "[name:FIRSTNAME:LASTNAME]" to "Name: LASTNAME FIRSTNAME".

 * @Filter(
 *   id = "filter_name",
 *   title = @Translation("Name Rearrange Filter"),
 *   description=@Translation("Replaces [name:FIRSTNAME:LASTNAME] with Name: LASTNAME FIRSTNAME"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_REVERSIBLE,
 * )
 */

class Filtername extends FilterBase
{
    public function process($text, $langcode) {
        $regex = '/\[name\:(\w+)\:(\w+)\]/';
        $replace = "Name: " .'$2 $1';
        $new_text = preg_replace($regex, $replace, $text);
        return  new FilterProcessResult($new_text);
    }
    
}   
?>