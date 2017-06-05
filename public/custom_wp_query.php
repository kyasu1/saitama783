<?php
if ( !class_exists('Custom_WP_Query') ) {
  /*
   * @link http://qiita.com/chiyoyo/items/b10bd3864f3ce5c56291
   * @link https://bradt.ca/blog/extending-wp_query/
   *  
   **/
  class Custom_WP_Query extends WP_Query {
    function __construct( $args = array() ) {
      if (!empty($args['lat']) && !empty($args['lng'])) {
        $this->lat = $args['lat'];
        $this->lng = $args['lng'];
      } else {
        $this->lat = 35.8577210;
        $this->lng = 139.647804;
      }

      add_filter( 'posts_fields', array( $this, 'posts_fields' ) );
      add_filter( 'posts_join', array( $this, 'posts_join' ) );
      add_filter( 'posts_where', array( $this, 'posts_where' ) );
      add_filter( 'posts_orderby', array( $this, 'posts_orderby' ) );

      parent::__construct( $args );

      remove_filter( 'posts_fields', array( $this, 'posts_fields' ) );
      remove_filter( 'posts_join', array( $this, 'posts_join' ) );
      remove_filter( 'posts_where', array( $this, 'posts_where' ) );
      remove_filter( 'posts_orderby', array( $this, 'posts_orderby' ) );
    }

    function posts_fields( $sql ) {
      global $wpdb;
      $field = sprintf(", Glength(GeomFromText(Concat('LineString(', %s, ' ', %s, ', ', lat.meta_value, ' ', lng.meta_value, ')'))) * 112.12 AS distance ",
        $this->lat, $this->lng);
      return $sql . $field;
    }

    function posts_join( $sql ) {
      global $wpdb;
      $sql .= " LEFT JOIN {$wpdb->postmeta} AS lat ON {$wpdb->posts}.ID = lat.post_id ";
      $sql .= " LEFT JOIN {$wpdb->postmeta} AS lng ON {$wpdb->posts}.ID = lng.post_id ";
      return $sql;
    }

    function posts_where( $sql ) {
      global $wpdb;
      $sql .= " AND lat.meta_key = 'shop_field_lat' ";
      $sql .= " AND Lng.meta_key = 'shop_field_lng' ";
      return $sql;
    }

    function posts_orderby( $sql ) {
      return " distance ASC, " . $sql;
    }
  }
}
?>
