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
<?php
  $lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] : 35.8577210;
  $lng = isset($_REQUEST['lng']) ? $_REQUEST['lng'] : 139.647804;
  $s = $_REQUEST['s'];

  global $wpdb;

  $area = $_REQUEST['shopinfo_area'];

  if (isset($_REQUEST['shopinfo_items'])) {
    $query_items = implode(',', $_REQUEST['shopinfo_items']);
  } else {
    $query_items = '';
  }

  $query = $wpdb->prepare("
  SELECT DISTINCT p.*,
  p1.meta_value AS shop_field_lat,
  p2.meta_value AS shop_field_lng,
  Glength(GeomFromText(Concat('LineString(', %f, ' ', %f, ', ', p1.meta_value, ' ', p2.meta_value, ')'))) * 112.12 AS distance
  FROM $wpdb->posts p
  LEFT JOIN $wpdb->postmeta AS p1 ON p1.post_id = p.ID
  LEFT JOIN $wpdb->postmeta AS p2 ON p1.post_id = p2.post_id

  LEFT JOIN $wpdb->term_relationships AS rel_area ON p.ID = rel_area.object_id
  LEFT JOIN $wpdb->term_taxonomy AS tax_area ON rel_area.term_taxonomy_id = tax_area.term_taxonomy_id
  LEFT JOIN $wpdb->terms AS term_area ON term_area.term_id = tax_area.term_id

  LEFT JOIN $wpdb->term_relationships AS rel_items ON rel_area.object_id = rel_items.object_id
  LEFT JOIN $wpdb->term_taxonomy AS tax_items ON rel_items.term_taxonomy_id = tax_items.term_taxonomy_id
  LEFT JOIN $wpdb->terms AS term_items ON term_items.term_id = tax_items.term_id

  WHERE p1.meta_key = 'shop_field_lat' AND p2.meta_key = 'shop_field_lng'
  AND p.post_status = 'publish'
  AND p.post_type = 'shopinfo'
  AND tax_area.taxonomy = 'shopinfo_area' AND ('%s' = '' OR term_area.name = '%s')
  AND tax_items.taxonomy = 'shopinfo_items' AND term_items.term_id IN ($query_items)
  ORDER BY distance
   ", $lat, $lng, $area, $area
  );

  $results = $wpdb->get_results($query);
?>
  <?php if (!$results) : ?>
  <header> 
    <div class="alert alert-warning"> 見つかりませんでした。</div>
  </header>
  <?php else : ?> 
  <header> 
    <div><?php echo $wpdb->num_rows; ?>件見つかりました</div>
  </header>
  <main>
    <ul>
      <?php global $post;
        foreach ($results as $post) {
          setup_postdata($post);
          shopinfo_get_template('partials/_shopinfo-brief.php');
        }
      ?>
    </ul>
  </main>
  <?php endif; ?>
<?php } ?>
<?php add_shortcode('shopinfo-complex-search', 'shopinfo_complex_search_form'); ?>
