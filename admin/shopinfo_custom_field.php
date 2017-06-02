<?php
/* helper functions for text input */
function create_form_input($post, $field) {
  $value = esc_html(get_post_meta($post->ID, $field, true));
  echo "<input name='$field' style='width: 100%;' value='$value' />";
}

/* helper function for radio button with true or false */
function create_form_radio_toggle($post, $field) {
  $value = get_post_meta($post->ID, $field, true);
  $id_for_true = $field . '-true';
  $id_for_false = $field . '-false';
  if ($value === 'あり') {
    $checked_true = 'checked';
    $checked_false = '';
  } else {
    $checked_true = '';
    $checked_false = 'checked';
  }
  echo "<input type='radio' id=$id_for_true  name=$field value='あり' $checked_true>";
  echo "<label for=$id_for_true>あり</label>";
  echo "<input type='radio' id=$id_for_false name=$field value='あり' $checked_false>";
  echo "<label for=$id_for_false>なし</label>";
}

/* custom field definitions start from here */

/* number of images to support */
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
  add_meta_box('shopinfo-shopping', '店頭販売', 'create_form_shopinfo_shopping', 'shopinfo', 'normal');
  add_meta_box('shopinfo-mailorder', '通信販売', 'create_form_shopinfo_mailorder', 'shopinfo', 'normal');
  add_meta_box('shopinfo-notice', 'ひとこと', 'create_form_shopinfo_notice', 'shopinfo', 'normal');
  add_meta_box('shopinfo-lng', '経度', 'create_form_shopinfo_lng', 'shopinfo', 'normal');
  add_meta_box('shopinfo-lat', '緯度', 'create_form_shopinfo_lat', 'shopinfo', 'normal');

  add_meta_box('shopinfo-images', '画像', 'create_form_shopinfo_images', 'shopinfo', 'normal');
}

function create_form_shopinfo_name($post) {
  create_form_input($post, 'shop_field_name');
}

function create_form_shopinfo_zip($post) {
  create_form_input($post, 'shop_field_zip');
}

function create_form_shopinfo_address($post) {
  create_form_input($post, 'shop_field_address');
}

function create_form_shopinfo_tel($post) {
  create_form_input($post, 'shop_field_tel');
}

function create_form_shopinfo_closed($post) {
  create_form_input($post, 'shop_field_closed');
}

function create_form_shopinfo_opening($post) {
  create_form_input($post, 'shop_field_opening');
}

function create_form_shopinfo_url($post) {
  create_form_input($post, 'shop_field_url');
}

function create_form_shopinfo_parking($post) {
  create_form_radio_toggle($post, 'shop_field_parking');
}

function create_form_shopinfo_shopping($post) {
  create_form_radio_toggle($post, 'shop_field_shopping');
}

function create_form_shopinfo_mailorder($post) {
  create_form_radio_toggle($post, 'shop_field_mailorder');
}

function create_form_shopinfo_notice($post) {
  echo '<textarea name="shop_field_notice" style="width: 100%;">';
  echo esc_html(get_post_meta($post->ID, 'shop_field_notice', true));
  echo '</textarea>';
}

function create_form_shopinfo_lng($post) {
  create_form_input($post, 'shop_field_lng');
}

function create_form_shopinfo_lat($post) {
  create_form_input($post, 'shop_field_lat');
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
    'shop_field_shopping',
    'shop_field_mailorder',
    'shop_field_notice',
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
