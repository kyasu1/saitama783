@extends('layouts.app')

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

@section('content')
  <header><strong>{{$_REQUEST['s']}}</strong>の検索結果：{{$wpdb->num_rows}}</header>

  <main class="flex flex-column-reverse flex-row-l ph2">
    <nav class="w-100 w5-l flex-shrink-0 pa2">
      @include('partials.shopinfo-search-form')
    </nav>
  
    <ul class="list pl0 w-100">
      @if (!$results)
        <div class="alert alert-warning">
          {{  __('Sorry, no results were found.', 'sage') }}
        </div>
      @endif
    
      @php
        global $post;
        foreach ($results as $post):
           setup_postdata($post);
      @endphp
    
      @include('partials.content-shopinfo')
    
      @php
        endforeach;
      @endphp
    </ul>
  </main>
@endsection
