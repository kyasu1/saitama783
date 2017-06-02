<?php 
$lat = isset($_REQUEST['lat']) ? $_REQUEST['lat'] : 35.8577210;
$lng = isset($_REQUEST['lng']) ? $_REQUEST['lng'] : 139.647804;
$s = $_REQUEST['s'];

global $wpdb;

$keyword = '%' . $wpdb->esc_like($s) . '%';
$query = $wpdb->prepare("
SELECT p.*,
p1.meta_value AS shop_field_lat,
p2.meta_value AS shop_field_lng,
Glength(GeomFromText(Concat('LineString(', %f, ' ', %f, ', ', p1.meta_value, ' ', p2.meta_value, ')'))) * 112.12 AS distance
FROM $wpdb->posts p
LEFT JOIN $wpdb->postmeta AS p1 ON p1.post_id = p.ID
LEFT JOIN $wpdb->postmeta AS p2 ON p1.post_id = p2.post_id
WHERE p1.meta_key = 'shop_field_lat' AND p2.meta_key = 'shop_field_lng'
AND p.post_status = 'publish'
AND p.post_type = 'shopinfo'
AND p.post_title LIKE '%s'
ORDER BY distance
 ",
$lat,
  $lng,
  $keyword
);

$results = $wpdb->get_results($query);
?>

  <header><strong><?php echo $_REQUEST['s']; ?></strong>の検索結果：<?php echo $wpdb->num_rows; ?></header>

<main class="flex flex-column-reverse flex-row-l ph2">
  <header class="w-100 w5-l flex-shrink-0 pa2">
  <?php do_shortcode('[shopinfo-complex-search]'); ?>
  </header>

  <ul class="list pl0 w-100">
    <?php if (!$results) { ?>
      <div class="alert alert-warning"> 見つかりませんでした。 </div>
    <?php } ?>
<?php
global $post;
foreach ($results as $post) {
  setup_postdata($post);
  echo '<div><a href="'.get_permalink().'">'.get_the_title().'</a></div>';
  echo '<div>';
  echo '<div>'.round(($post->distance) , 1).'</div>';
  echo '<div>Km</div>';
  echo '</div>';
}
?>
  </ul>
  </main>
