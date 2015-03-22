<?php
function return_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='0',$data=array()){
	$slider_html='';
	$slider_html=get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo,$data);
	return $slider_html;
}

function return_dboxlite_slider($slider_id='',$offset=0,$data=array()) {
	global $dboxlite_slider,$default_dboxlite_slider_settings;
	$set='';
 	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
 	if(empty($slider_id) or !isset($slider_id)){
	  $slider_id = '1';
	}
	$slider_html='';
	if(!empty($slider_id)){
		$data['slider_id']=$slider_id;
		$slider_handle='dboxlite_slider_'.$slider_id;
		$data['slider_handle']=$slider_handle;
		$r_array = dboxlite_carousel_posts_on_slider($dboxlite_slider_curr['no_posts'], $offset, $slider_id, $echo = '0', $set,$data); 
		$slider_html=return_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='0',$data);
	} //end of not empty slider_id condition
	
	return $slider_html;
}

function dboxlite_slider_simple_shortcode($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'offset'=>'0',
	), $atts));

	return return_dboxlite_slider($id,$offset);
}
if (!shortcode_exists( 'dboxslider' ) ) add_shortcode('dboxslider', 'dboxlite_slider_simple_shortcode');

function return_dboxlite_slider_category($catg_slug='', $offset=0, $data=array()) {
	global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$set='';
 	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	
	$slider_handle='dboxlite_slider_'.$catg_slug;
	$data['slider_handle']=$slider_handle;
    	$r_array = dboxlite_carousel_posts_on_slider_category($dboxlite_slider_curr['no_posts'], $catg_slug, $offset, '0', $set, $data); 
	//get slider 


	$slider_html=return_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='0',$data);
	
	return $slider_html;
}

function dboxlite_slider_category_shortcode($atts) {
	extract(shortcode_atts(array(
		'catg_slug' => '',
		'offset'=>'0',
	), $atts));
	return return_dboxlite_slider_category($catg_slug,$offset);
}
if (!shortcode_exists( 'dboxcategory' ) ) add_shortcode('dboxcategory', 'dboxlite_slider_category_shortcode');

function return_dboxlite_slider_recent($offset=0, $data=array()) {
	global $dboxlite_slider,$default_dboxlite_slider_settings;
	$set='';
 	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	
	$slider_handle='dboxlite_slider_recent';
	$data['slider_handle']=$slider_handle;
	$r_array = dboxlite_carousel_posts_on_slider_recent($dboxlite_slider_curr['no_posts'], $offset, '0', $set,$data);  
	//get slider 
	$slider_html=return_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='0',$data);
	
	return $slider_html;
}

function dboxlite_slider_recent_shortcode($atts) {
	extract(shortcode_atts(array(
		'offset'=>'0',
	), $atts));
	return return_dboxlite_slider_recent($offset);
}
if (!shortcode_exists( 'dboxrecent' ) ) add_shortcode('dboxrecent', 'dboxlite_slider_recent_shortcode');
?>
