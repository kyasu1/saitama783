<?php 
/*
 * `page`パラメータがセットされている場合は、`page`へGETリクエストのパラメータを
 * 追加してリダイレクトする。
 *
 */
if (isset($_REQUEST['page'])) {
  unset($_GET['post_type']);
  wp_redirect( $_REQUEST['page'] . "?" . http_build_query( $_GET ) );
  exit();
}
?>
