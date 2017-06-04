<?php
/*
 * 沿線と駅から検索するフォームを表示するショートコードを定義
 */
function shopinfo_route_station_search_form($attr) {
  $route_id = $_REQUEST['route_id'];
  $a = shortcode_atts( array(
    'slug' => 'shoplist',
  ), $attr );

  $terms = get_terms( 'shopinfo_stations', array( 'orderby' => 'term_id', 'hide_empty' => false ) );
  $routes = array_filter( $terms, function($term) { return $term->parent == 0; } );
  $stations = array_filter( $terms, function($term) use($route_id) { return $term->parent == $route_id; } );
?>
  <div class='shopinfo-routes'>
    <label>路線を選んください</label>
    <ul name='shopinfo-route'>
      <?php foreach ($routes as $route): if ($route->term_id == $route_id): ?>
      <li>
        <?php echo esc_html($route->name); ?>
      </li>
      <?php else: ?>
      <li>
        <a href="/<?php echo esc_attr($a['slug']); ?>/?route_id=<?php echo esc_attr($route->term_id); ?>"><?php echo esc_html($route->name); ?></a>
      </li>
      <?php endif; endforeach; ?>
    </ul>
  </div>
  <div class="shopinfo-stations">
    <label>駅を選んで下さい</label>
    <ul>
      <?php foreach ($stations as $station): ?>
      <li>
        <a href="#shopinfo_station_<?php echo esc_attr($station->term_id); ?>">
          <?php echo esc_html($station->name); ?>
          <span>(<?php echo esc_html($station->count); ?>)</span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <ul>  
    <?php foreach ($stations as $station): ?>
    <li id="shopinfo_station_<?php echo esc_attr($station->term_id); ?>">
      <label><?php echo esc_html($station->name); ?>駅</label>
        <?php $the_query = new WP_Query( array(
          'post_type' => 'shopinfo',
          'orderby' => 'ID',
          'tax_query' => array(
            array(
              'taxonomy' => 'shopinfo_stations',
              'field' => 'term_id',
              'terms' => $station->term_id,
            )
          )
        ));
        ?>
        <?php if ( $the_query->have_posts() ) : ?>
          <ul>
          <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <?php shopinfo_get_template('partials/_shopinfo-brief.php'); ?>
          <?php endwhile; ?>
          </ul>
          <?php wp_reset_postdata(); ?>
        <?php else: ?>
          <p>見つかりませんでした。</p>
        <?php endif; ?>
    </li>
    <?php endforeach; ?>
  </ul>
<?php
}
add_shortcode('shopinfo-route-station-search', 'shopinfo_route_station_search_form');
?>
