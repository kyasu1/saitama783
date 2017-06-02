<?php
function shopinfo_custom_post_type()
{
  $labels = array(
    'name' => _x('店舗情報', 'post type general name'),
    'singular_name' => _x('店舗情報', 'post type singular name'),
    'add_new' => _x('店舗情報を追加', 'shopinfo'),
    'add_new_item' => __('新しい店舗情報を追加'),
    'edit_item' => __('店舗情報を編集'),
    'new_item' => __('新しい店舗情報'),
    'view_item' => __('店舗情報を編集'),
    'search_items' => __('店舗情報を探す'),
    'not_found' => __('店舗情報はありません'),
    'not_found_in_trash' => __('ゴミ箱に店舗情報はありませｎ'),
    'parent_item_colon' => ''
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 5,
    'has_archive' => true,
    'supports' => array('title', 'author', 'thumbnail'),
    'taxonomies' => array('shopinfo_area', 'shopinfo_items', 'shopinfo_stations')
  );
  register_post_type('shopinfo', $args);
}
add_action('init', 'shopinfo_custom_post_type');

function hide_category_add() {
  global $pagenow;
  global $post_type;
  if (is_admin() && ($pagenow == 'post-new.php' || $pagenow == 'post.php') && $post_type == 'shopinfo') {
    echo '
<style type="text/css">
  #shopinfo_items-adder {
    display: none;
  }
  #shopinfo_items-tabs {
    display: none;
  }
</style>';
  }
}
add_action('admin_head', 'hide_category_add');
?>
