<?php
/*
 * Register 'エリア' custom taxonomy.
 *
 * This code is written based on the next article.
 *
 * http://sudarmuthu.com/blog/creating-single-select-wordpress-taxonomies/
 */

class ShopinfoTaxonomy {
  public function __construct() {
    register_activation_hook(__FILE__, array($this, 'activate'));
    add_action('init', array($this, 'register_taxonomy'));
    add_action('save_post_shopinfo', array($this, 'save_area_meta_box'));
  }

  public function activate() {
    static::register_taxonomy();
    static::register_terms();
  }

  static function register_taxonomy() {
    $area_args = array(
      'label' => 'エリア',
      'public' => true,
      'show_ui' => true,
      'hierarchical' => false,
      'meta_box_cb' => array('ShopinfoTaxonomy', 'shopinfo_area_meta_box')
    );
    register_taxonomy('shopinfo_area', 'shopinfo', $area_args);
  
    $item_args = array(
      'label' => '取扱品目',
      'public' => true,
      'show_ui' => true,
      'hierarchical' => true
    );
    register_taxonomy('shopinfo_items', 'shopinfo', $item_args);

    $station_args = array(
      'label' => '最寄り駅',
      'public' => true,
      'shouw_ui' => true,
      'hierarchical' => true,
    );
    register_taxonomy('shopinfo_stations', 'shopinfo', $station_args);
  }

  /*
   * Display select box for Area
   */
  static function shopinfo_area_meta_box( $post ) {
    $terms = get_terms('shopinfo_area', array('hide_empty' => false));
  
    $post = get_post();
    $area = wp_get_object_terms($post->ID, 'shopinfo_area', array('orderby' => 'term_id', 'order' => 'ASC'));
    $name = '';
  
    if (!is_wp_error($area)) {
      if (isset($area[0]) && isset($area[0]->name)) {
        $name = $area[0] -> name;
      }
    }
  
    echo '<select name="shopinfo_area" style="width: 100%;">';
    foreach ($terms as $term) {
      $selected = $term->name === $name ? 'selected' : '';
      echo '<option value="'.esc_attr($term->name).'" '.esc_attr($selected).'>'.esc_html($term->name).'</option>';
    }
    echo '</select>';
  }

  /*
   * Save selected terms
   */
  function save_area_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
    }
  
    if (!isset($_POST['shopinfo_area'])) {
      return;
    }
  
    $area = sanitize_text_field($_POST['shopinfo_area']);
  
    if (empty($area)) {
      remove('save_post_shopinfo', 'save_area_meta_box');
  
      $postdata = array(
        'ID' => $post_id,
        'post_status' => 'draft',
      );
      wp_update_post($postdata);
    } else {
      $term = get_term_by('name', $area, 'shopinfo_area');
  
      if (!empty($term) && !is_wp_error($term)) {
        wp_set_object_terms($post_id, $term->term_id, 'shopinfo_area', false);
      }
    }
  }

  static function register_terms() {
		include('area_terms.php');
    foreach ($area_terms as $slug => $name) {
      wp_insert_term(
        $name,
        'shopinfo_area',
        array(
          'description' => '',
          'slug' => $slug,
        )
      );
    }

    include('item_terms.php');
    foreach ($item_terms as $slug => $name) {
      wp_insert_term(
        $name,
        'shopinfo_items',
        array(
          'descriptions' => '',
          'slug' => $slug,
        )
      );
    }

    /* load station master data from the csv file */
    setlocale( LC_ALL, 'ja_JP' );

    $csv = file_get_contents(plugin_dir_path( __FILE__ ).'stations.csv');
    $csv = str_replace(array("\r\n","\r","\n"), "\n", $csv);
    $csv = explode("\n", $csv);
    array_shift($csv);

    $stations = array_map(str_getcsv, $csv);

    /* register all parent terms */
    foreach($stations as $station) {
      if ($station[2] == '') {
        wp_insert_term($station[0], 'shopinfo_stations', array( 'slug' => $station[1] ));
      }
    }

    /* register all child terms */
    foreach($stations as $station) {
      if ($station[2] != '') {
        $parent = get_term_by('name', $station[2], 'shopinfo_stations');
        wp_insert_term($station[0], 'shopinfo_stations', array( 'slug' => $parent->slug.'-'.$station[1], 'parent' => $parent->term_id ));
      }
    }
  }
    
  /*
   * Display an error message at the top of the post edit screen explaining that area is required.
   *
   * Doing this prevents users from getting confused when their new posts aren't published, as we 
   * require a valid area custom taxonomy.
   */
}

new ShopinfoTaxonomy();
?>
