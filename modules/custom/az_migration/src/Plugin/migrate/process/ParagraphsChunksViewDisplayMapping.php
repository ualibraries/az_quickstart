<?php

namespace Drupal\az_migration\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Process plugin to map view and display for views paragraphs.
 *
 * @MigrateProcessPlugin(
 *   id = "paragraphs_chunks_view_display_mapping"
 * )
 */
class ParagraphsChunksViewDisplayMapping extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $transformed_value = [];
    $view_data = [];
    $view_mappings = self::getViewMappings();

    if (isset($view_mappings[$value['target_id']])) {
      $transformed_value['target_id'] = $view_mappings[$value['target_id']]['view'];
      $transformed_value['display_id'] = $view_mappings[$value['target_id']]['display'][$value['display_id']];

      if (isset($value['argument'])) {
        $transformed_value['argument'] = $value['argument'];
      }
    }
    else {
      $transformed_value['target_id'] = $value['target_id'];
      $transformed_value['display_id'] = $value['display_id'];
    }

    // Setting Items per page: 6 for 3 Column news block.
    if ($view === 'uaqs_news' && $display === 'three_col_news_block') {
      $view_data['limit'] = 6;
    }
    $transformed_value['data'] = serialize($view_data);

    return $transformed_value;
  }

  /**
   * Provides Quickstart 1 to Quickstart 2 view mapping data.
   */
  protected static function getViewMappings() {
    $uaqs_events = [
      'view' => 'az_events',
      'display' => [
        'default' => 'page_1',
        'page' => 'page_1',
        'list_block' => 'page_1',
        'card_group_block' => 'az_grid',
        'block_1' => 'az_sidebar',
      ],
    ];
    $uaqs_news = [
      'view' => 'az_news',
      'display' => [
        'default' => 'az_grid',
        'three_col_news_block_3' => 'az_grid',
        'three_col_news_block' => 'az_grid',
        'sidebar_promoted_news' => 'az_sidebar',
        'uaqs_teaser_list_page' => 'az_teaser_grid',
        'uaqs_media_list_page' => 'az_paged_row',
        'recent_news_marquee' => 'marquee',
        'recent_news_medium_media_list' => 'az_paged_row',
      ],
    ];
    $uaqs_person_directory = [
      'view' => 'az_person',
      'display' => [
        'default' => 'grid',
        'page' => 'grid',
        'page_1' => 'row',
      ],
    ];
    $uaqs_content_chunks_views_page_by_category = [
      'view' => 'az_page_by_category',
      'display' => [
        'default' => 'row',
        'page' => 'row',
        'page_1' => 'grid',
      ],
    ];

    return [
      'uaqs_events' => $uaqs_events,
      'uaqs_news' => $uaqs_news,
      'uaqs_person_directory' => $uaqs_person_directory,
      'uaqs_content_chunks_views_page_by_category' => $uaqs_content_chunks_views_page_by_category,
    ];
  }

}
