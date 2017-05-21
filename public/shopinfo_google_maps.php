<?php
const KEY = 'AIzaSyDcQVnY3uzjJsmooMvG-4E60v3Qoxu5auM';

function load_google_maps() {
  if (is_singular('shopinfo')) {
    wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js?key='.KEY, array(), null, true);
    wp_enqueue_script('google-maps-script', plugin_dir_url(__FILE__) . 'js/google-maps-script.js', array(), null, true);
  }
}

add_action('wp_enqueue_scripts', 'load_google_maps');
?>
