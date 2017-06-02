<?php

register_uninstall_hook(__FILE__, 'shopinfo_uninstall_hook');

function shopinfo_uninstall_hook() {
  foreach ( array( 'shopinfo_items', 'shopinfo_area', ) as $taxonomy ) {
    // Prepare & excecute SQL, Delete Terms
    $wpdb->get_results( $wpdb->prepare( "DELETE t.*, tt.* FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id WHERE tt.taxonomy IN ('%s')", $taxonomy ) );

    // Delete Taxonomy
    $wpdb->delete( $wpdb->term_taxonomy, array( 'taxonomy' => $taxonomy ), array( '%s' ) );
  }
}
?>
