<?php
/*
Template Name: Shop Info
Template Post Type: shopinfo
 */

get_header('shopinfo');

$fields = array(
  '郵便番号' => 'shop_field_zip',
  '住所' => 'shop_field_address',
  '電話番号' => 'shop_field_tel',
  '定休日' => 'shop_field_closed',
  '営業時間' => 'shop_field_opening',
  'ホームページ' => 'shop_field_url',
  '駐車場' => 'shop_field_parking',
)

?>
<div class="wrap">
  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
      <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header">
            <?php the_title('<h1>', '</h1>'); ?>
          </header>
            <section
              id="map"
              style="width: 600px; height: 600px;"
              data-lat="<?php echo get_post_meta(get_the_ID(), 'shop_field_lat', true); ?>"
              data-lng="<?php echo get_post_meta(get_the_ID(), 'shop_field_lng', true); ?>">
          </section>
          <section>
            <dl>
              <dt>住所</dt>
              <dd>〒<?php preg_match('/(.{3})(.{4})/', get_post_meta(get_the_ID(), 'shop_field_zip', true), $match); echo $match[1].'-'.$match[2]; ?></dd>
              <dd><?php echo esc_html(get_post_meta(get_the_ID(), 'shop_field_address', true)); ?></dd>
              <dd><?php echo esc_html(get_post_meta(get_the_ID(), 'shop_field_tel', true)); ?></dd>
            </dl>
            <dl>
              <dt>定休日</dt>
              <dd><?php echo esc_html(get_post_meta(get_the_ID(), 'shop_field_closed', true)); ?></dd>
            </dl>
            <dl>
              <dt>営業時間</dt>
              <dd><?php echo esc_html(get_post_meta(get_the_ID(), 'shop_field_opening', true)); ?></dd>
            </dl>
            <dl>
              <dt>駐車場</dt>
              <dd><?php echo esc_html(get_post_meta(get_the_ID(), 'shop_field_parking', true)); ?></dd>
            </dl>
            <?php
            $url = get_post_meta(get_the_ID(), 'shop_field_url', true); 
            if ($url) {
              echo '<dl><dt>ホームページ</dt><dd><a href="http://'.$url.'">'.$url.'</a></dd></dl>';
            }
            ?>
          </section>
          <section>
            <ul>
              <?php
                $images = get_post_meta(get_the_ID(), 'shop_field_images', true);
                if (!empty($images)) {
                  for ($i = 0; $i < count($images); $i++) {
                    $url = $images[$i]['image'];
                    if ($url) {
                      echo '<li><img src='.$url.'></li>';
                    }
                  }
                }
              ?>
            </ul>
          </section>
          <div class="entry-content"><?php the_content(); ?></div>
        </article>
      <?php endwhile; ?>
    </main>
  <div>
</div>

<?php get_footer(); ?>
    
