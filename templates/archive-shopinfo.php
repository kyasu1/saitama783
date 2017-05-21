<?php
/*
Template Name: Shop Info Archive
Template Post Type: shopinfo
 */

get_header('shopinfo');

$areas = get_terms('shopinfo_area');
if (!empty($areas)) {
  foreach ($areas as $area) {
?>
<div class="wrap">
  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

      <h2><?php echo $area->name ?></h2>
      <?php
        $shops_by_area = get_posts(array(
          'nopaging' => true,
          'post_type' => 'shopinfo',
          'taxonomy' => 'shopinfo_area',
          'term' => $area -> slug,
        ));
      ?>
      <div id="archive-content">
        <ul>
        <?php foreach ($shops_by_area as $shop) { ?>
          <li class="archive-shopinfo-shop">
            <a href="/shopinfo/<?php echo $shop->post_name; ?>">
              <div class="archive-shopinfo-shop-thumbnail"><?php echo get_the_post_thumbnail($shop->ID, array(100, 100)); ?></div>
              <div class="archive-shopinfo-shop-name"><?php echo $shop->post_title; ?></div>
              <div class="archive-shopinfo-shop-address"><?php echo get_post_meta($shop->ID, 'shop_field_address', true); ?></div>
              <div class="archive-shopinfo-shop-tel"><?php echo get_post_meta($shop->ID, 'shop_field_tel', true); ?></div>
            </a>
          </li>
        <?php } ?>
        </ul>
      </div><!-- #archive-content -->
    </main>
  </div>
</div>
<?php
  }
}

get_footer();
?>
