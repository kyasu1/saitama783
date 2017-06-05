# About
埼玉県質屋組合連合会向けの店舗情報カスタム投稿タイプとその検索を行うためのプラグインになります。

## 初期データの投入
このプラグインを有効にすると、カスタムタクソノミーのデータが自動的にデータベースに読み込まれます。

別途用意されたcsvファイルを、プラグイン[Really Simple CSV Importer](https://ja.wordpress.org/plugins/really-simple-csv-importer/)を使用してインポートして下さい。現状で画像以外は一度に読み込めます。

## テンプレートのオーバーライド
ベースとなるテンプレートが`templates`以下に用意してあります。
- `archive-shopinfo.php`
  エリアごとにまとめて店舗のリストを表示するテンプレート
- `single-shopinfo.php`
  各店舗の詳細情報を表示するテンプレート
- `partials/_shopinfo-brief.php`
  検索結果内の店舗情報に対するテンプレート
  別途用意したカスタムテンプレートローダーを使用して読み込みます。テーマフォルダに同名のファイルがある場合はそちらが優先されます。
  ```
  <?php shopinfo_get_template('partials/_shopinfo-brief.php'); ?>
  ```
これらを、テーマフォルダの中へコピーするか、新たに同名で作成することにより、オーバーライド可能となります。
- 検索フォームおよび検索結果の本体は、ショートコードによって作成されますので、そちらに合わせてcssを当てて下さい。

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
  - omiya
- さいたま市浦和区
  - urawa

### 取扱品目 `shopinfo_items`
一つの投稿に対して複数存在する
- 時計
  - watch
- 金プラチナ
  - gold

### 路線 `shopinfo_train_routes`
- JR京浜東北線
  - 大宮駅
  - 浦和駅
  - 北浦和駅
- 東武スカイツリーライン
  - 新越谷駅
  - 蒲生駅

## ショートコード
店舗検索のために複数のショートコードが定義されています。固定ページを作成して、いずれかのショートコードを配置することにより、検索フォームと検索結果を表示する事が出来ます。

検索結果を表示する固定ページの`slug`とショートコードの属性として与える`slug`を同じにしてください。

### 基準点からの距離を計算してソートし、地図上にマーカーを表示する　※未実装
基準点はクエリパラメータの`lat`および`lng`にて与えられる。もしこれらのパラメータが存在しない場合は、浦和県庁の位置情報をデフォルトとする。
```
[shopinfo-location-search slug='location' noresult=false lat='35.33333' lng='135.00000']
```
- `slug`には固定ページのスラッグを指定する
- `lat`
- `lng`

### 市区町村と取扱品目でフィルターされた結果を表示する
```
[shopinfo-complex-search slug='area-items' noresult=false ]
```
- `slug`には固定ページのスラッグを指定する
- `noresult`を`true`とすると結果を表示しない。トップページなどにフォームのみを表示したい場合にセットする。その際には`slug`は結果を表示する固定ページのスラッグを指定する事。

### 路線と駅からお店の一覧を表示する
```
[shopinfo-route-station-search slug='route-station']
```
- `slug`には固定ページのスラッグを指定する。

## TODO
- [ ] 複数のURLに対応する
- [ ] 画像も一緒にアップロードできるようにする
- [x] カスタムタクソノミーでの検索
- [x] 沿線と最寄り駅での検索
