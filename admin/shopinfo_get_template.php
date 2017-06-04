<?php
/*
 * $template_nameで指定されたテンプレートを次の順番でさがし、最初に見つかったものを読み込む
 * 1. テーマディレクトリ
 * 2. このプラグインの`templates/`ディレクトリ以下
 * 
 * もし見つからない場合は、エラーを表示する。
 * 
 * @param $template_name
 */
function shopinfo_get_template($template_name) {
  $plugin_path = plugin_dir_path( __DIR__ ) . 'templates/';

  $template = locate_template( array(
    $template_name,
  ), false, false );

  if ( ! $template ) {
    $template = $plugin_path . $template_name;
  }

  if (!file_exists($template)) {
    _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template ), '1.0.0' );
  }

  include($template);
}
?>
