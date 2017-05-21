<?php
const NUM_IMAGES = 6;

function add_shopinfo_field() {
  add_meta_box('shopinfo-name', '店舗名', 'create_form_shopinfo_name', 'shopinfo', 'normal');
  add_meta_box('shopinfo-zip', '郵便番号', 'create_form_shopinfo_zip', 'shopinfo', 'normal');
  add_meta_box('shopinfo-address', '住所', 'create_form_shopinfo_address', 'shopinfo', 'normal');
  add_meta_box('shopinfo-tel', '電話番号', 'create_form_shopinfo_tel', 'shopinfo', 'normal');
  add_meta_box('shopinfo-closed', '定休日', 'create_form_shopinfo_closed', 'shopinfo', 'normal');
  add_meta_box('shopinfo-opening', '営業時間', 'create_form_shopinfo_opening','shopinfo', 'normal');
  add_meta_box('shopinfo-url', 'ホームページ', 'create_form_shopinfo_url', 'shopinfo', 'normal');
  add_meta_box('shopinfo-parking', '駐車場', 'create_form_shopinfo_parking', 'shopinfo', 'normal');
  add_meta_box('shopinfo-lng', '経度', 'create_form_shopinfo_lng', 'shopinfo', 'normal');
  add_meta_box('shopinfo-lat', '緯度', 'create_form_shopinfo_lat', 'shopinfo', 'normal');

  add_meta_box('shopinfo-images', '画像', 'create_form_shopinfo_images', 'shopinfo', 'normal');
}

function create_form_shopinfo_name($post) {
  echo '<input name="shop_field_name" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_name', true).'"/>';
}

function create_form_shopinfo_zip($post) {
  echo '<input name="shop_field_zip" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_zip', true).'"/>';
}

function create_form_shopinfo_address($post) {
  echo '<input name="shop_field_address" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_address', true).'"/>';
}

function create_form_shopinfo_tel($post) {
  echo '<input name="shop_field_tel" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_tel', true).'"/>';
}

function create_form_shopinfo_closed($post) {
  echo '<input name="shop_field_closed" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_closed', true).'"/>';
}

function create_form_shopinfo_opening($post) {
  echo '<input name="shop_field_opening" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_opening', true).'"/>';
}

function create_form_shopinfo_url($post) {
  echo '<input name="shop_field_url" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_url', true).'"/>';
}

function create_form_shopinfo_parking($post) {
  echo '<input name="shop_field_parking" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_parking', true).'"/>';
}

function create_form_shopinfo_lng($post) {
  echo '<input name="shop_field_lng" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_lng', true).'"/>';
}

function create_form_shopinfo_lat($post) {
  echo '<input name="shop_field_lat" style="width: 100%;" value="'.get_post_meta($post->ID, 'shop_field_lat', true).'"/>';
}

function create_form_shopinfo_images($post) {
  $images = get_post_meta($post->ID, 'shop_field_images', true);

  for ($i = 0; $i < NUM_IMAGES; $i++) {
    $value = '';
    if ($images[$i] != '' && isset($images[$i]['image'])) {
      $value = $images[$i]['image'];
    }

    echo '<div style="display: flex; width: 100%;">';
    echo '<label style="width: 2.5rem;">'.($i + 1).'枚目</label>';
    echo '<input type="text" id="shop-field-images-'.$i.'" name="shop_field_images[]" style="flex: 1;" value="'.$value.'"/>';
    echo '<input type="button" class="upload-button" value="画像を登録" />';
    echo '</div>';
  }
}

add_action('admin_menu', 'add_shopinfo_field');

function save_shopinfo_field($post_id) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  $my_fields = [
    'shop_field_name',
    'shop_field_zip',
    'shop_field_address',
    'shop_field_tel',
    'shop_field_closed',
    'shop_field_opening',
    'shop_field_url',
    'shop_field_parking',
    'shop_field_lng',
    'shop_field_lat'
  ];

  foreach($my_fields as $my_field) {
    if (isset($_POST[$my_field])) {
      $value = sanitize_text_field($_POST[$my_field]);
    } else {
      $value = '';
    }

    if (strcmp($value, get_post_meta($post_id, $my_field, true)) != 0) {
      update_post_meta($post_id, $my_field, $value);
    } elseif ($value == '') {
      delete_post_meta($post_id, $my_field, get_post_meta($post_id, $my_field, true));
    }
  }

  $images = $_POST['shop_field_images'];
  $updated_images = array();

  if (isset($images)) {
    for ($i = 0; $i < NUM_IMAGES; $i++) {
      if ($images[$i] != '') {
        $updated_images[$i]['image'] = stripslashes(strip_tags($images[$i]));
      }
    }

    if (!empty($updated_images) && $updated_images != $images) {
      update_post_meta($post_id, 'shop_field_images', $updated_images);
    } elseif (empty($updated_images) && $images) {
      delete_post_meta($post_id, 'shop_field_images', $images);
    }
  }
}

add_action('save_post', 'save_shopinfo_field');

/* file upload engine */
function load_wp_media_files() {
  wp_enqueue_media();
  wp_enqueue_script('custom_uploader', plugin_dir_url(__FILE__) . '/js/custom_uploader.js', '20170521-01', true);
}
add_action('admin_enqueue_scripts', 'load_wp_media_files');
?>
