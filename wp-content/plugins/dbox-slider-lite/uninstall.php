<?php 
delete_option('dbox_slider_options');
delete_option('dboxlite_db_version');
global $wpdb, $table_prefix;
$slider_table = $table_prefix.'dbox_slider';
$slider_meta = $table_prefix.'dbox_slider_meta';
$slider_postmeta = $table_prefix.'dbox_slider_postmeta';
$sql = "DROP TABLE $slider_table;";
$wpdb->query($sql);
$sql = "DROP TABLE $slider_meta;";
$wpdb->query($sql);
$sql = "DROP TABLE $slider_postmeta;";
$wpdb->query($sql);
?>
