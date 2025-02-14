<?php

namespace Drupal\az_migration\Plugin\migrate\source;

use Drupal\migrate\Row;
use Drupal\node\Plugin\migrate\source\d7\Node as D7Node;

/**
 * Provides a 'AZNode' migrate source plugin.
 *
 * @see Drupal\node\Plugin\migrate\source\d7\Node
 *
 * Additions to D7Node:
 *  - alias: string containing the content's relative path alias beginning
 *    with '/'
 *
 * @MigrateSource(
 *   id = "az_node"
 * )
 */
class AZNode extends D7Node {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return ['alias' => $this->t('Path alias')] + parent::fields();
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // Include path alias.
    $nid = $row->getSourceProperty('nid');
    $query = $this->select('url_alias', 'ua')
      ->fields('ua', ['alias']);
    $query->condition('ua.source', 'node/' . $nid);
    $alias = $query->execute()->fetchField();
    if (!empty($alias)) {
      $row->setSourceProperty('alias', '/' . $alias);
    }

    return parent::prepareRow($row);
  }

}
