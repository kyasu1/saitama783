<?php
/*
 * エリア一覧をセレクトボックスで表示
 */
function draw_area_select_box() {
  $shopinfo_area = $_REQUEST['shopinfo_area'];
  $areas = get_terms('shopinfo_area', array( 'orderby' => 'term_id', 'hide_empty' => false) );
  echo "<div class='shopinfo-area'>";
  echo "<label>エリアを選択してください</label>";
  echo "<select name='shopinfo_area'>";

  $selected = isset($shopinfo_area) ? '' : 'selected';
  echo "<option value=''>全エリア</option>";
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
 * エリアと取扱品目を選択肢とする検索フォーム、その検索結果を表示するショートコードを定義
 * `[shopinfo-complex-search]`
 */
function shopinfo_complex_search_form($attr) {
  $a = shortcode_atts( array(
    'slug' => 'area-items',
    'noresult' => false,
  ), $attr );
?>
<form class="shopinfo-complex-search" role="search" action="<?php echo esc_url(home_url('/')); ?>" action="/area-items/">
  <?php draw_area_select_box(); ?>
  <?php draw_items_check_boxes(); ?>
  <input type="hidden" name="s" value="">
  <input type="hidden" name="post_type" value="shopinfo">
  <input type="hidden" name="page" value="<?php echo esc_attr($a['slug']); ?>">
  <input type="submit" value="検索">
</form>
<?php if ($a['noresult']) { return; } /* `noresult`がtrueの場合は結果を表示しない*/ ?>
<?php
  $lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] : 35.8577210;
  $lng = isset($_REQUEST['lng']) ? $_REQUEST['lng'] : 139.647804;
  $s = $_REQUEST['s'];

  global $wpdb;

  if (isset($_REQUEST['shopinfo_area']) && $_REQUEST['shopinfo_area'] != '') {
    $area = $_REQUEST['shopinfo_area'];
  } else {
    $area = wp_list_pluck( get_terms( 'shopinfo_area' ), 'name' );
  }

  if (isset($_REQUEST['shopinfo_items'])) {
    $query_items = implode(',', $_REQUEST['shopinfo_items']);
  } else {
    $query_items = array();
  }

  include( 'custom_wp_query.php');

  $the_query = new Custom_WP_Query( array(
    'post_type' => 'shopinfo',
    'orderby' => 'ID',
    'nopaging' => true,
    'lat' => $lat,
    'lng' => $lng,
    'tax_query' => array(
      array(
        'taxonomy' => 'shopinfo_area',
        'field' => 'name',
        'terms' => $area,
      ),
      array(
        'taxonomy' => 'shopinfo_items',
        'field' => 'term_id',
        'terms' => $query_items,
      )
    )
  ) );
?>
<div>
  <?php if ( $the_query->have_posts() ) : ?>
    <ul>
    <?php while ( $the_query->have_posts() ) : $the_query->the_post() ?>
      <?php shopinfo_get_template('partials/_shopinfo-brief.php'); ?>
    <? endwhile; ?>
    </ul>
    <?php wp_reset_postdata(); ?>
  <?php else: ?>
    <p>見つかりませんでした。</p>
  <?php endif; ?>
</div>
<?php } ?>
<?php add_shortcode('shopinfo-complex-search', 'shopinfo_complex_search_form'); ?>
