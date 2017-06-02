<?php
/*
 * short code for custom search form
 */

function shopinfo_search_form($form) {
?>
  <form class="shopinfo-search" role="search" action="<?php echo home_url('/'); ?>">
    <input type="search" name="s" placeholder="店名で検索">
    <input type="submit" value="検索">
    <input type="hidden" name="post_type" value="shopinfo">
  </form>
<?php
}
add_shortcode('shopinfo-search', 'shopinfo_search_form');

function draw_area_select_box() {
  $areas = get_terms('shopinfo_area');
  echo "<div class='shopinfo-area'>";
  echo "<label>エリアを選択してください</label>";
  echo "<select name='shopinfo-area'>";
  foreach($areas as $area) {
    echo "<option value=$area->name>$area->name</option>";
  }
  echo "</select>";
  echo "</div>";
}

function draw_items_check_boxes() {
  $items = get_terms('shopinfo_items');
  echo "<div class='shopinfo-items'>";
  echo "<label>お探しの取扱品目をチェックして下さい</label>";
  echo "<ul>";
  foreach ($items as $item) {
    $input_id = 'shopinfo-items-checkbox-' . $item->term_id;
    echo "<input id='$input_id' type='checkbox' value='$item->term_id'><label for='$input_id'>$item->name</label>";
  }
  echo "</ul>";
  echo "</div>";
}

function shopinfo_complex_search_form($attr) {
  global $wpdb;

  draw_area_select_box();
  draw_items_check_boxes();
}
add_shortcode('shopinfo-complex-search', 'shopinfo_complex_search_form');


?>