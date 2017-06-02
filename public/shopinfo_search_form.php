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
  $areas = get_terms('shopinfo_area');
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

  $items = get_terms('shopinfo_items');
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
?>
  <form id="shopinfo-search" role="search" action="<?php echo esc_url(home_url('/')); ?>">
    <input type="hidden" name="s" value="">
    <input type="hidden" name="post_type" value="shopinfo">
    <input type="submit" value="検索">
  </form>
<?php
}
add_shortcode('shopinfo-route-station-search', 'shopinfo_route_station_search_form');

?>
