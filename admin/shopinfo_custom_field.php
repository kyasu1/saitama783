<?php

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

add_action('admin_menu', 'add_shopinfo_field');

function save_shopinfo_field($post_id) {
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
}

add_action('save_post', 'save_shopinfo_field');
?>
