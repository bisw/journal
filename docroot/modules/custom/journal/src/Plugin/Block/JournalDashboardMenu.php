<?php

namespace Drupal\journal\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a generic Menu block.
 *
 * @Block(
 *   id = "journal_dashboard_menu_block",
 *   admin_label = @Translation("Dashboard Menu"),
 *   category = @Translation("Journal"),
 * )
 */
class JournalDashboardMenu extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    global $base_url;
    $current_user = \Drupal::currentUser();
    $user = \Drupal\user\Entity\User::load($current_user->id());

    $list = [
      'dashboard' => [
        'url' => $base_url . '/dashboard',
        'title' => t('Dashboard')
      ],
      'user' => [
        'url' => $base_url . '/user/' . $current_user->id(),
        'title' => t('Welcome - ' . $user->getUsername())
      ],
      'logout' => [
        'url' => $base_url . '/user/logout',
        'title' => t('Logout')
      ],
    ];

    $output = array(
      '#theme' => 'journal_dashboard_menu',
      '#items' => $list,
    );
  
    return $output;
  }

  private function generateSubMenuTree($input) {
    $output = [];
    foreach($input as $item) {
      //If menu element disabled skip this branch
      if ($item->link->isEnabled()) {
        $title = $item->link->getTitle();
        //kint($title);
        $url = $item->link->getUrlObject();
        $list[] = Link::fromTextAndUrl($title, $url);
      }
    }

    return array(
      '#theme' => 'item_list',
      '#items' => $list,
    );
  }

}
