<li>
  <?php $post = get_post($post); ?>
  <a href="<?php the_permalink(); ?>">
    <div class="archive-shopinfo-shop-thumbnail"><?php echo get_the_post_thumbnail(get_the_ID(), array(100, 100)); ?></div>
    <div class="archive-shopinfo-shop-name"><?php the_title(); ?></div>
    <div class="archive-shopinfo-shop-address"><?php echo get_post_meta(get_the_ID(), 'shop_field_address', true); ?></div>
    <div class="archive-shopinfo-shop-tel"><?php echo get_post_meta(get_the_ID(), 'shop_field_tel', true); ?></div>
    <?php if (isset($post->distance)) : ?>
      <div><?php echo round(esc_html($post->distance), 1); ?>Km</div>
    <?php endif; ?>
  </a>
</li>
 
