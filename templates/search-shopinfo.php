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
AND tax_area.taxonomy = 'shopinfo_area' AND term_area.name = '%s'
AND tax_items.taxonomy = 'shopinfo_items' AND term_items.term_id IN ($query_items)
ORDER BY distance
 ", $lat, $lng, $area
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
