<?php
/*
 * Plugin Name: Site Plugin for saitama783.com
 * Description: Site specific code changes for saitama783.com
 * */
/* Start Adding Functions Below this Line */

include('admin/shopinfo_custom_taxonomy.php');
register_activation_hook(__FILE__, array('ShopinfoTaxonomy', 'activate'));

include('admin/shopinfo_custom_post_type.php');
include('admin/shopinfo_custom_field.php');
include('admin/shopinfo_template_loader.php');

include('public/shopinfo_google_maps.php');
include('public/shopinfo_search_form.php');
/* Stop Adding Functions Below this Line */
?>
