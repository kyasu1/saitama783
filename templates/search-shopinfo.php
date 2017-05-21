<?php
/*
Template Name: Shop Info Archive
Template Post Type: shopinfo
 */

get_header('shopinfo');

?>
<div class="wrap">
  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

      <h2>検索結果</h2>
      <div id="archive-content">
        <?php if (have_posts()) : ?>
          <ul>
          <?php  while(have_posts()) : the_post(); ?>
          <li class="archive-shopinfo-shop">
            <a href="<?php the_permalink($post); ?>">
              <div class="archive-shopinfo-shop-thumbnail"><?php the_post_thumbnail(array(100, 100)); ?></div>
              <div class="archive-shopinfo-shop-name"><?php the_title(); ?></div>
              <div class="archive-shopinfo-shop-address"><?php echo get_post_meta($post->ID, 'shop_field_address', true); ?></div>
              <div class="archive-shopinfo-shop-tel"><?php echo get_post_meta($post->ID, 'shop_field_tel', true); ?></div>
            </a>
          </li>
          <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <h3>見つかりませんでした...</h3>
        <?php endif; ?>
      </div><!-- #archive-content -->
    </main>
  </div>
</div>
<?php
get_footer();
?>
