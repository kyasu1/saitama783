# About
埼玉県質屋組合連合会向けの店舗情報カスタム投稿タイプとその検索を行うためのプラグインになります。

## カスタム投稿タイプ
`shopinfo`

## カスタムフィールド
- 店舗名
- 郵便番号
- 住所
- 電話番号
- 定休日
- 営業時間
- ホームページ
- 駐車場
  - あり
  - なし
- 店頭販売
  - あり
  - なし
- 通信販売
  - あり
  - なし
- ひとこと
- 緯度
- 経度

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

### 路線と駅を基準に近い順に10件を表示する
