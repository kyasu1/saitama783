<?php
/*
 * Register 'エリア' custom taxonomy.
 *
 * This code is written based on the next article.
 *
 * http://sudarmuthu.com/blog/creating-single-select-wordpress-taxonomies/
 */

class AreaTerms {
  public function __construct() {
    register_activation_hook(__FILE__, array($this, 'activate'));
    add_action('init', array($this, 'register_area_taxonomy'));
    add_action('save_post_shopinfo', array($this, 'save_area_meta_box'));
  }

  public function activate() {
    static::register_area_taxonomy();
    static::register_area_terms();
  }

  static function register_area_taxonomy() {
    $args = array(
      'label' => 'エリア',
      'public' => true,
      'show_ui' => true,
      'hierarchical' => false,
      'meta_box_cb' => array('AreaTerms', 'shopinfo_area_meta_box')
    );
    
    register_taxonomy('shopinfo_area', 'shopinfo', $args);
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
   * Save the area select box result.
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

  static function register_area_terms() {
    $taxonomy = 'shopinfo_area';
    
		include('area_code.php');

    foreach ($area_code as $slug => $name) {
      wp_insert_term(
        $name,
        $taxonomy,
        array(
          'description' => '',
          'slug' => $slug,
        )
      );
    }
  }
    
  /*
   * Display an error message at the top of the post edit screen explaining that area is required.
   *
   * Doing this prevents users from getting confused when their new posts aren't published, as we 
   * require a valid area custom taxonomy.
   */
}

new AreaTerms();
?>
