<?php
namespace Drupal\custom_filter\Plugin\Filter;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * @Filter(
 *   id = "filter_celebrate",
 *   title = @Translation("Celebrate Filter"),
 *   description = @Translation("Help this text format celebrate good times!"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class FilterCelebrate extends FilterBase {
    public function process($text, $langcode) {
        $replace = '<span class="celebrate-filter">’ . $this->t(‘Good Times!’) . ‘</span>';
        $new_text = str_replace('[celebrate]', $replace, $text);
        return new FilterProcessResult($text);
    }
}
?>