/*
 * .upload-button がクリックされたら、ワードプレス標準のファイルアップローダを開く
 * 画像が選択されたら、画像のURLを直前のINPUTボックスに挿入する
 * 複数の入力欄に対応
 * 
 * ```
 * <input type="text" id="image-1" />
 * <input type="button" class="upload-button" />
 * ```
 *
 * http://stackoverflow.com/questions/17668899/how-to-add-the-media-uploader-in-wordpress-plugin
 * http://www.thecreatology.com/add-multiple-image-upload-button-using-wordpress-uploader-in-theme.html
 */ 
jQuery(document).ready(function($){
  $('.upload-button').click(function(e) {
    e.preventDefault();
    var form_field = $(this).prev().attr('id');
    var image = wp.media({
      title: '画像のアップロード',
      multiple: false,
    }).open()
      .on('select', function(e) {
        var uploaded_image = image.state().get('selection').first();
        var image_url = uploaded_image.toJSON().url;
        if (form_field) {
          $('#' + form_field).val(image_url);
        }
      });
  });
});

