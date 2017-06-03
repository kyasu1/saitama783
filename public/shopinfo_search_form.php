<?php
/*
 * short code for custom search form
 */
function shopinfo_search_form($attr) {
?>
  <form class="shopinfo-search" role="search" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="search" name="s" placeholder="店名で検索">
    <input type="submit" value="検索">
    <input type="hidden" name="post_type" value="shopinfo">
  </form>
<?php
}
add_shortcode('shopinfo-search', 'shopinfo_search_form');

/*
 * エリア一覧をセレクトボックスで表示
 */
function draw_area_select_box() {
  $shopinfo_area = $_REQUEST['shopinfo_area'];
  $areas = get_terms('shopinfo_area', array( 'orderby' => 'term_id', 'hide_empty' => false) );
  echo "<div class='shopinfo-area'>";
  echo "<label>エリアを選択してください</label>";
  echo "<select name='shopinfo_area'>";
  foreach($areas as $area) {
    $selected = $shopinfo_area === $area->name ? 'selected' : '';
    echo "<option value=$area->name $selected>$area->name</option>";
  }
  echo "</select>";
  echo "</div>";
}

/*
 * 取扱品目一覧をチェックボックのリストとして並べる
 */
function draw_items_check_boxes() {
  $options = $_REQUEST['shopinfo_items'];

  $items = get_terms( 'shopinfo_items', array( 'orderby' => 'term_id', 'hide_empty' => false) );
  echo "<div class='shopinfo-items'>";
  echo "<label>お探しの取扱品目をチェックして下さい</label>";
  echo "<ul>";
  foreach ($items as $item) {
    $checked = '';
    if (isset($options)) {
      foreach($options as $option) {
        if ($option == $item->term_id) {
          $checked = 'checked';
          break;
        }
      }
    }
    $input_id = 'shopinfo-items-checkbox-' . $item->term_id;
    echo "<input type='checkbox' id='$input_id' name='shopinfo_items[]' value='$item->term_id' $checked>";
    echo "<label for='$input_id'>$item->name</label>";
  }
  echo "</ul>";
  echo "</div>";
}

/*
 * エリアと取扱品目を選択肢とする検索フォームを表示するショートコードを定義
 * `[shopinfo-complex-search]`
 */
function shopinfo_complex_search_form($attr) {
?>
  <form class="shopinfo-complex-search" role="search" action="<?php echo esc_url(home_url('/')); ?>">
    <?php draw_area_select_box(); ?>
    <?php draw_items_check_boxes(); ?>
    <input type="hidden" name="s" value="">
    <input type="hidden" name="post_type" value="shopinfo">
    <input type="submit" value="検索">
  </form>
<?php
}
add_shortcode('shopinfo-complex-search', 'shopinfo_complex_search_form');

/*
 * 沿線と駅から検索するフォームを表示するショートコードを定義
 */
function shopinfo_route_station_search_form($attr) {
  $route_id = $_REQUEST['route_id'];

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
        <a href="/shoplist/?route_id=<?php echo esc_attr($route->term_id); ?>"><?php echo esc_html($route->name); ?></a>
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
      <ul>
        <?php $shops = get_posts(
                array(
                  'post_type' => 'shopinfo',
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'shopinfo_stations',
                      'field' => 'term_id',
                      'terms' => $station->term_id
                    )
                  )
                )
              );
          var_dump($shops);
        ?>
      </ul>
    </li>
    <?php endforeach; ?>
  </ul>
<?php
}
add_shortcode('shopinfo-route-station-search', 'shopinfo_route_station_search_form');
?>
