# About
埼玉県質屋組合連合会向けの店舗情報カスタム投稿タイプとその検索を行うためのプラグインになります。

## 初期データの投入
初期データのcsvファイルを用意しますので、プラグイン[Really Simple CSV Importer](https://ja.wordpress.org/plugins/really-simple-csv-importer/)を使用してインポートして下さい。現状で取扱品目と画像以外は一度に読み込めます。

## カスタム投稿タイプ
`shopinfo`

## カスタムフィールド
- 店舗名
  `shop_field_name`
- 郵便番号
  `shop_field_zip`
- 住所
  `shop_field_address`
- 電話番号
  `shop_field_tel`
- 定休日
  `shop_field_closed`
- 営業時間
  `shop_field_open`
- ホームページ
  `shop_field_url`
- 駐車場
  `shop_field_parking`
  - あり
  - なし
- 店頭販売
  `shop_field_shopping`
  - あり
  - なし
- 通信販売
  `shop_field_mailorder`
  - あり
  - なし
- ひとこと
  `shop_field_notice`
- 緯度
  `shop_field_lat`
- 経度
  `shop_field_lng`

## カスタムタクソノミー
### エリア `shopinfo_area`
一つの投稿に対して必ず一つだけ存在する
- さいたま市大宮区
  - oomiya
- さいたま市浦和区
  - urawa

### 取扱品目 `shopinfo_items`
一つの投稿に対して複数存在する
- 時計
  - watch
- 金プラチナ
  - goldandplatinum

### 路線 `shopinfo_train_routes`
- JR京浜東北線
  - 大宮駅
  - 浦和駅
  - 北浦和駅
- 東武スカイツリーライン
  - 新越谷駅
  - 蒲生駅

## ループ
### 基準点からの距離を計算してソートする
基準点はクエリパラメータの`lat`および`lng`にて与えられる。もしこれらのパラメータが存在しない場合は、浦和県庁の位置情報をデフォルトとする。
```php
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
```
### 市区町村と取扱品目でフィルターされた結果を表示する
```php
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
```
### 路線と駅を基準に近い順に10件を表示する

### TODO
- [ ] 複数のURLに対応する
- [ ] 画像も一緒にアップロードできるようにする
- [x] カスタムタクソノミーでの検索
- [ ] 沿線と最寄り駅での検索
