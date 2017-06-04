# About
埼玉県質屋組合連合会向けの店舗情報カスタム投稿タイプとその検索を行うためのプラグインになります。

## 初期データの投入
初期データのcsvファイルを用意しますので、プラグイン[Really Simple CSV Importer](https://ja.wordpress.org/plugins/really-simple-csv-importer/)を使用してインポートして下さい。現状で取扱品目と画像以外は一度に読み込めます。

## テンプレートのオーバーライド
テスト用に作った下記ののテンプレートが`templates`以下に用意してあります。
- `archive-shopinfo.php`
  エリアごとにまとめて店舗のリストを表示するテンプレート
- `single-shopinfo.php`
  各店舗の詳細情報を表示するテンプレート
- `partials/_shopinfo-brief.php`
  検索結果内の店舗情報に対するテンプレート

これらを、テーマフォルダの中へコピーするか、新たに同名で作成することにより、自動的にカスタマイズ可能となります。

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
  - goldandplatinum

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

固定ページの`slug`とショートコードの属性として与える`slug`は必ず同一にしてください。

### 基準点からの距離を計算してソートする
基準点はクエリパラメータの`lat`および`lng`にて与えられる。もしこれらのパラメータが存在しない場合は、浦和県庁の位置情報をデフォルトとする。
```
[shopinfo-location-search slug='location' lat='35.33333' lng='135.00000']
```
- `slug`には固定ページのスラッグを指定する
- `lag`
- `lng`

### 市区町村と取扱品目でフィルターされた結果を表示する
```
[shopinfo-complex-search slug='area-items']
```
- `slug`には固定ページのスラッグを指定する

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
