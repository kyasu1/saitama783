<?php
/*
 *
 * https://nskw-style.com/2015/wordpress/theme-templates-in-plugin.html
 */

class shopinfo_template_loader {
  public function __construct() {
    add_filter('template_include', array(__CLASS__, 'template_loader'));
  }

  public static function template_loader($template) {
    $template_dir = plugin_dir_path(__DIR__) . 'templates/';

    if (is_search() && 'shopinfo' == $_GET['s']) {
      $file_name = 'search-shopinfo.php';
    } elseif (is_singular('shopinfo')) {
      $file_name = 'single-shopinfo.php';
    }

    if (isset($file_name)) {
      $theme_file = locate_template($file_name);
    }

    if (isset($theme_file) && $theme_file) {
      $template = $theme_file;
    } elseif (isset($file_name) && $file_name) {
      $template = $template_dir . $file_name;
    }

    return $template;
  }
}

new shopinfo_template_loader();
?>
