<?php
/*
 * エリアごとに店舗の一覧を表示する
 */
$areas = get_terms('shopinfo_area');
if (!empty($areas)): foreach ($areas as $area):
?>
<div class="wrap">
  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <h2><?php echo esc_html($area->name); ?></h2>
      <?php $shops_by_area = new WP_Query( array(
              'nopaging' => true,
              'post_type' => 'shopinfo',
              'taxonomy' => 'shopinfo_area',
              'term' => $area -> slug,
            ));
      ?>
      <div id="archive-content">
      <?php if ( $shops_by_area->have_posts() ) : ?>
        <ul>
        <?php while ( $shops_by_area->have_posts() ) : $shops_by_area->the_post(); ?>
          <?php shopinfo_get_template ( 'partials/_shopinfo-brief.php' ); ?>
        <?php endwhile; ?>
        </ul>
        <?php wp_reset_postdata(); ?>
      <?php else: ?>
        <p>見つかりませんでした。</p>
      <?php endif; ?>
      </div><!-- #archive-content -->
    </main>
  </div>
</div>
<?php
endforeach; endif;
?>
