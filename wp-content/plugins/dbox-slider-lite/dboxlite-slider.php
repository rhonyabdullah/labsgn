<?php /*
Plugin Name: Dbox Slider Lite
Plugin URI: http://slidervilla.com/dbox-lite/
Description: DBOXLITE slider adds a very beautiful 3D responsive Slider to your site with a graceful fallback on IE and Old browsers.
Version: 1.2
Author: SliderVilla
Author URI: http://slidervilla.com/
Wordpress version supported: 3.5 and above
License: GPL2
*/
/*  Copyright 2010-2015  Slider Villa  (email : support@slidervilla.com)
	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//defined global variables and constants here
global $dboxlite_slider,$default_dboxlite_slider_settings,$dboxlite_db_version;
$dboxlite_slider = get_option('dbox_slider_options');
$dboxlite_db_version='1.2';
$default_dboxlite_slider_settings = array('speed'=>'6', 
						   'no_posts'=>'8',
						   'width'=>'640',
						   'height'=>'460',
						   'bg_color'=>'#ffffff',
						   'bg'=>'0',	
						   'img_pick'=>array('1','dboxliteslider_thumbnail','1','1','1','0'), //use custom field/key, name of the key, use post featured image, pick the image attachment, attachment order,scan images
						   'image_only'=>'0',
						   'ptitle_font'=>'Trebuchet MS,sans-serif',
						   'ptitle_fontg'=>'',
						   'ptitle_fsize'=>'14',
						   'ptitle_fstyle'=>'bold',
						   'ptitle_fcolor'=>'#222222',
						   'content_font'=>'Arial,Helvetica,sans-serif',
						   'content_fontg'=>'',
						   'content_fsize'=>'12',
						   'content_fstyle'=>'normal',
						   'content_fcolor'=>'#000000',
						   'content_from'=>'content',
						   'content_chars'=>'',
						   'content_limit'=>'22',
						   'show_content'=>'1',
						   'show_content_hover'=>'0',
						   'bg_opacity'=>'0.8',
						   'frame_gap'=>'5',
                           			   'nav_img_width' => '64',
						   'nav_img_height' => '64',
						   'allowable_tags'=>'',
						   'more'=>'',
						   'user_level'=>'edit_others_posts',
						   'crop'=>'0',
						   'slide_nav_limit'=>'5',
						   'stylesheet'=>'default',
						   'rand'=>'0',
						   'ver'=>'1',
						   'fouc'=>'0',
						   'navpos'=>'bottom',
						   'custom_post'=>'0',
						   'preview'=>'2',
						   'slider_id'=>'1',
						   'catg_slug'=>'',
						   'css'=>'',
						   'setname'=>'Set',
						   'a_attr'=>'',
						   'pn_width'=>'42',
						   'disable_preview'=>'0',
						   'remove_metabox'=>array(),
						   'css_js'=>'',
						   'image_title_text'=>'0',
						   'active_tab'=>'0',
						   'default_image'=>dboxlite_slider_plugin_url( 'images/default_image.png' ),
						   'navarr_bgcolor'=>'#666666',
						   'navarr_bgcolor_hover'=>'#000000',
						   'navdot_color'=>'#666666',
						   'currnavdot_color'=>'#ffffff',
						   'dotsize'=>'16',
						   'playbt_color'=>'#666666',
						   'playbt_color_hover'=>'#000000',
						   'playbt_size'=>'30',
						   'direction_rotation'=>'v',
						   'disable_autoplay'=>'0',
						   'interval'=>'3',
						   'cc'=>'5',
						   'new'=>'1',
						   'popup'=>'1',
						   'timthumb'=>'0',
						   'noscript'=>''
			              );
define('DBOXLITE_SLIDER_TABLE','dbox_slider'); //Slider TABLE NAME
define('DBOXLITE_SLIDER_META','dbox_slider_meta'); //Meta TABLE NAME
define('DBOXLITE_SLIDER_POST_META','dbox_slider_postmeta'); //Meta TABLE NAME
define("DBOXLITE_SLIDER_VER","1.2",false);//Current Version of DboxLite Slider
if ( ! defined( 'DBOXLITE_SLIDER_PLUGIN_BASENAME' ) )
	define( 'DBOXLITE_SLIDER_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'DBOXLITE_SLIDER_CSS_DIR' ) ){
	define( 'DBOXLITE_SLIDER_CSS_DIR', WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'/css/skins/' );
}
// Create Text Domain For Translations
load_plugin_textdomain('dboxlite-slider', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

function install_dboxlite_slider() {
	global $wpdb, $table_prefix, $dboxlite_db_version;
	$installed_ver = get_option( "dboxlite_db_version");
	if( $installed_ver != $dboxlite_db_version ) {
		$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
		$sql = "CREATE TABLE $table_name (
						id int(5) NOT NULL AUTO_INCREMENT,
						post_id int(11) NOT NULL,
						date datetime NOT NULL,
						slider_id int(5) NOT NULL DEFAULT '1',
						slide_order int(5) NOT NULL DEFAULT '0',
						UNIQUE KEY id(id)
					);";
			$rs = $wpdb->query($sql);
	}

	$meta_table_name = $table_prefix.DBOXLITE_SLIDER_META;
	if($wpdb->get_var("show tables like '$meta_table_name'") != $meta_table_name) {
		$sql = "CREATE TABLE $meta_table_name (
					slider_id int(5) NOT NULL AUTO_INCREMENT,
					slider_name varchar(100) NOT NULL default '',
					UNIQUE KEY slider_id(slider_id)
				);";
		$rs2 = $wpdb->query($sql);
	
		$sql = "INSERT INTO $meta_table_name (slider_id,slider_name) VALUES('1','DboxLite Slider');";
		$rs3 = $wpdb->query($sql);
	}

	$slider_postmeta = $table_prefix.DBOXLITE_SLIDER_POST_META;
	if($wpdb->get_var("show tables like '$slider_postmeta'") != $slider_postmeta) {
		$sql = "CREATE TABLE $slider_postmeta (
					post_id int(11) NOT NULL,
					slider_id int(5) NOT NULL default '1',
					UNIQUE KEY post_id(post_id)
				);";
		$rs4 = $wpdb->query($sql);
	}
	// Need to delete the previously created options in old versions and create only one option field for DboxLite Slider
	$default_slider = array();
	global $default_dboxlite_slider_settings;
	$default_slider = $default_dboxlite_slider_settings;
	$dboxlite_slider_options='dbox_slider_options';
	$dboxlite_slider_curr=get_option($dboxlite_slider_options);
	   				 
	if(!isset($dboxlite_slider_curr)) {
	 $dboxlite_slider_curr = array();
	}

  	 foreach($default_slider as $key=>$value) {
		  if(!isset($dboxlite_slider_curr[$key])) {
			 $dboxlite_slider_curr[$key] = $value;
		  }
	}
	delete_option($dboxlite_slider_options);	  
	update_option($dboxlite_slider_options,$dboxlite_slider_curr);  
	update_option( "dboxlite_db_version", $dboxlite_db_version );

	}//end of if db version chnage
}
register_activation_hook( __FILE__, 'install_dboxlite_slider' );
/* Added for auto update - start */
function dboxlite_update_db_check() {
    global $dboxlite_db_version;
    if (get_option('dboxlite_db_version') != $dboxlite_db_version) {
        install_dboxlite_slider();
    }
}
add_action('plugins_loaded', 'dboxlite_update_db_check');
/* Added for auto update - end */
require_once (dirname (__FILE__) . '/includes/dboxlite-slider-functions.php');
require_once (dirname (__FILE__) . '/includes/sslider-get-the-image-functions.php');

//This adds the post to the slider
function dboxlite_add_to_slider($post_id) {
global $dboxlite_slider;
 if(isset($_POST['dboxlite-sldr-verify']) and current_user_can( $dboxlite_slider['user_level'] ) ) {
	global $wpdb, $table_prefix, $post;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	
	if( !isset($_POST['dboxlite-slider']) and  is_post_on_any_dboxlite_slider($post_id) ){
		$sql = "DELETE FROM $table_name where post_id = '$post_id'";
		 $wpdb->query($sql);
	}
	
	if(isset($_POST['dboxlite-slider']) and !isset($_POST['dboxlite_slider_name'])) {
	  $slider_id = '1';
	  if(is_post_on_any_dboxlite_slider($post_id)){
	     $sql = "DELETE FROM $table_name where post_id = '$post_id'";
		 $wpdb->query($sql);
	  }
	  
	  if(isset($_POST['dboxlite-slider']) and $_POST['dboxlite-slider'] == "dboxlite-slider" and !dboxlite_slider($post_id,$slider_id)) {
		$dt = date('Y-m-d H:i:s');
		$sql = "INSERT INTO $table_name (post_id, date, slider_id) VALUES ('$post_id', '$dt', '$slider_id')";
		$wpdb->query($sql);
	  }
	}
	if(isset($_POST['dboxlite-slider']) and $_POST['dboxlite-slider'] == "dboxlite-slider" and isset($_POST['dboxlite_slider_name'])){
	  $slider_id_arr = $_POST['dboxlite_slider_name'];
	  $post_sliders_data = dboxlite_ss_get_post_sliders($post_id);
	  
		foreach($post_sliders_data as $post_slider_data){
			if(!in_array($post_slider_data['slider_id'],$slider_id_arr)) {
			  $sql = "DELETE FROM $table_name where post_id = '$post_id'";
			  $wpdb->query($sql);
			}
		}

		foreach($slider_id_arr as $slider_id) {
			if(!dboxlite_slider($post_id,$slider_id)) {
				$dt = date('Y-m-d H:i:s');
				$sql = "INSERT INTO $table_name (post_id, date, slider_id) VALUES ('$post_id', '$dt', '$slider_id')";
				$wpdb->query($sql);
			}
		}
	}
	
	$table_name = $table_prefix.DBOXLITE_SLIDER_POST_META;
	if(isset($_POST['dboxlite_display_slider']) and !isset($_POST['dboxlite_display_slider_name'])) {
	  $slider_id = '1';
	}
	if(isset($_POST['dboxlite_display_slider']) and isset($_POST['dboxlite_display_slider_name'])){
	  $slider_id = $_POST['dboxlite_display_slider_name'];
	}
  	if(isset($_POST['dboxlite_display_slider'])){	
		  if(!dboxlite_ss_post_on_slider($post_id,$slider_id)) {
		    $sql = "DELETE FROM $table_name where post_id = '$post_id'";
		    $wpdb->query($sql);
			$sql = "INSERT INTO $table_name (post_id, slider_id) VALUES ('$post_id', '$slider_id')";
			$wpdb->query($sql);
		  }
	}
	$dboxlite_slider_style = get_post_meta($post_id,'_dbox_slider_style',true);
	$post_dboxlite_slider_style=$_POST['_dbox_slider_style'];
	if($dboxlite_slider_style != $post_dboxlite_slider_style and isset($post_dboxlite_slider_style) and !empty($post_dboxlite_slider_style)) {
	  update_post_meta($post_id, '_dbox_slider_style', $_POST['_dbox_slider_style']);	
	}
	
	$thumbnail_key = (isset($dboxlite_slider['img_pick'][1]) ? $dboxlite_slider['img_pick'][1] : 'dboxliteslider_thumbnail');
	$dboxlite_sslider_thumbnail = get_post_meta($post_id,$thumbnail_key,true);
	$post_slider_thumbnail=$_POST['dboxlite_sslider_thumbnail'];
	if($dboxlite_sslider_thumbnail != $post_slider_thumbnail) {
	  update_post_meta($post_id, $thumbnail_key, $post_slider_thumbnail);	
	}
	
	$dbox_link_attr = get_post_meta($post_id,'dbox_link_attr',true);
	$link_attr=htmlentities($_POST['dbox_link_attr'],ENT_QUOTES);
	if($dbox_link_attr != $link_attr) {
	  update_post_meta($post_id, 'dbox_link_attr', $link_attr);	
	}
	
	$dbox_sslider_nolink = get_post_meta($post_id,'dbox_sslider_nolink',true);
	$post_dbox_sslider_nolink = $_POST['dbox_sslider_nolink'];
	if($dbox_sslider_nolink != $post_dbox_sslider_nolink) {
	  update_post_meta($post_id, 'dbox_sslider_nolink', $_POST['dbox_sslider_nolink']);	
	}

	$dboxlite_sslider_youtubeurl = get_post_meta($post_id,'_dbox_youtubeurl',true);
	$post_dboxlite_sslider_youtubeurl = $_POST['dboxlite_sslider_youtubeurl'];
	if($dboxlite_sslider_youtubeurl != $post_dboxlite_sslider_youtubeurl) {
	  update_post_meta($post_id, '_dbox_youtubeurl', $post_dboxlite_sslider_youtubeurl);	
	}

	$dboxlite_sslider_webmurl = get_post_meta($post_id,'_dbox_webmurl',true);
	$post_dboxlite_sslider_webmurl = $_POST['dboxlite_sslider_webmurl'];
	if($dboxlite_sslider_webmurl != $post_dboxlite_sslider_webmurl) {
	  update_post_meta($post_id, '_dbox_webmurl', $post_dboxlite_sslider_webmurl);	
	}

	$dboxlite_sslider_mp4url = get_post_meta($post_id,'_dbox_mp4url',true);
	$post_dboxlite_sslider_mp4url = $_POST['dboxlite_sslider_mp4url'];
	if($dboxlite_sslider_mp4url != $post_dboxlite_sslider_mp4url) {
	  update_post_meta($post_id, '_dbox_mp4url', $post_dboxlite_sslider_mp4url);	
	}

	$dboxlite_sslider_oggurl = get_post_meta($post_id,'_dbox_oggurl',true);
	$post_dboxlite_sslider_oggurl = $_POST['dboxlite_sslider_oggurl'];
	if($dboxlite_sslider_oggurl != $post_dboxlite_sslider_oggurl) {
	  update_post_meta($post_id, '_dbox_oggurl', $post_dboxlite_sslider_oggurl);	
	}

	$dboxlite_sslider_vshortcode = get_post_meta($post_id,'_dbox_video_shortcode',true);
	$post_dboxlite_sslider_vshortcode = $_POST['dboxlite_sslider_vshortcode'];
	if($dboxlite_sslider_vshortcode != $post_dboxlite_sslider_vshortcode) {
	  update_post_meta($post_id, '_dbox_video_shortcode', $post_dboxlite_sslider_vshortcode);	
	}
	
  } //dboxlite-sldr-verify
}

//Removes the post from the slider, if you uncheck the checkbox from the edit post screen
function dboxlite_remove_from_slider($post_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	
	// authorization
	if (!current_user_can('edit_post', $post_id))
		return $post_id;
	// origination and intention
	if (!wp_verify_nonce($_POST['dboxlite-sldr-verify'], 'DboxLiteSlider'))
		return $post_id;
	
    if(empty($_POST['dboxlite-slider']) and is_post_on_any_dboxlite_slider($post_id)) {
		$sql = "DELETE FROM $table_name where post_id = '$post_id'";
		$wpdb->query($sql);
	}
	
	$display_slider = $_POST['dboxlite_display_slider'];
	$table_name = $table_prefix.DBOXLITE_SLIDER_POST_META;
	if(empty($display_slider) and dboxlite_ss_slider_on_this_post($post_id)){
	  $sql = "DELETE FROM $table_name where post_id = '$post_id'";
		    $wpdb->query($sql);
	}
} 
  
function dboxlite_delete_from_slider_table($post_id){
    global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
    if(is_post_on_any_dboxlite_slider($post_id)) {
		$sql = "DELETE FROM $table_name where post_id = '$post_id'";
		$wpdb->query($sql);
	}
	$table_name = $table_prefix.DBOXLITE_SLIDER_POST_META;
    if(dboxlite_ss_slider_on_this_post($post_id)) {
		$sql = "DELETE FROM $table_name where post_id = '$post_id'";
		$wpdb->query($sql);
	}
}

// Slider checkbox on the admin page

function dboxlite_slider_edit_custom_box(){
   dboxlite_add_to_slider_checkbox();
}

function dboxlite_slider_add_custom_box() {
 global $dboxlite_slider;
 if (current_user_can( $dboxlite_slider['user_level'] )) {
	if( function_exists( 'add_meta_box' ) ) {
	    $post_types=get_post_types(); 
		$remove_post_type_arr=( isset($dboxlite_slider['remove_metabox']) ? $dboxlite_slider['remove_metabox'] : '' );
		if(!isset($remove_post_type_arr) or !is_array($remove_post_type_arr) ) $remove_post_type_arr=array();
		foreach($post_types as $post_type) {
			if(!in_array($post_type,$remove_post_type_arr)){
				add_meta_box( 'dboxlite_slider_box', __( 'DboxLite Slider' , 'dboxlite-slider'), 'dboxlite_slider_edit_custom_box', $post_type, 'advanced' );
			}
		}
		//add_meta_box( $id,   $title,     $callback,   $page, $context, $priority ); 
	} 
 }
}
/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'dboxlite_slider_add_custom_box');

function dboxlite_add_to_slider_checkbox() {
	global $post, $dboxlite_slider;
	if (current_user_can( $dboxlite_slider['user_level'] )) {
		$extra = "";
		
		$post_id = $post->ID;
		
		if(isset($post->ID)) {
			$post_id = $post->ID;
			if(is_post_on_any_dboxlite_slider($post_id)) { $extra = 'checked="checked"'; }
		} 
		
		$post_slider_arr = array();
		
		$post_sliders = dboxlite_ss_get_post_sliders($post_id);
		if($post_sliders) {
			foreach($post_sliders as $post_slider){
			   $post_slider_arr[] = $post_slider['slider_id'];
			}
		}
		
		$sliders = dboxlite_ss_get_sliders();
		
?>
				<script type="text/javascript">
		jQuery(document).ready(function($) {
			jQuery("#dboxlite_basic").css({"background":"#222222","color":"#ffffff"});
			jQuery("#dboxlite_basic").on("click", function(){ 
				jQuery("#dboxlite_basic_tab").fadeIn("fast");
				jQuery("#dboxlite_advaced_tab").fadeOut("fast");
				jQuery(this).css({"background":"#222222","color":"#ffffff"});
				jQuery("#dboxlite_advanced").css({"background":"buttonface","color":"#222222"});
			});
			jQuery("#dboxlite_advanced").on("click", function(){
				jQuery("#dboxlite_basic_tab").fadeOut("fast");
				jQuery("#dboxlite_advaced_tab").fadeIn("fast");
				jQuery(this).css({"background":"#222222","color":"#ffffff"});
				jQuery("#dboxlite_basic").css({"background":"buttonface","color":"#222222"});
				
			});
		}); 
		</script>
		
		<div style="border-bottom: 1px solid #ccc;padding-bottom: 0;padding-left: 10px;">
		<button type="button" id="dboxlite_basic" style="padding:5px 30px 5px 30px;margin: 0;cursor:pointer;border:0;outline:none;">Basic</button>
		<button type="button" id="dboxlite_advanced" style="padding:5px 30px 5px 30px;margin:0 0 0 10px;cursor:pointer;border:0;outline:none">Advanced</button>
		</div>
		
		<div id="dboxlite_basic_tab">	
			<div class="slider_checkbox">
			<table class="form-table">
				
				<tr valign="top">
				<th scope="row"><input type="checkbox" class="sldr_post" name="dboxlite-slider" value="dboxlite-slider" <?php echo $extra;?> />
				<label for="dboxlite-slider"><?php _e('Add this post/page to','dboxlite-slider'); ?> </label></th>
				<td><select name="dboxlite_slider_name[]" multiple="multiple" size="<?php echo count($sliders);?>" style="width:75%;">
                <?php foreach ($sliders as $slider) { ?>
                  <option value="<?php echo $slider['slider_id'];?>" <?php if(in_array($slider['slider_id'],$post_slider_arr)){echo 'selected';} ?>><?php echo $slider['slider_name'];?></option>
                <?php } ?>
                </select>
				<input type="hidden" name="dboxlite-sldr-verify" id="dboxlite-sldr-verify" value="<?php echo wp_create_nonce('DboxLiteSlider');?>" />
				</td>
				</tr>
		
         
	    
        <?php
        $dboxlite_slider_style = get_post_meta($post->ID,'_dbox_slider_style',true);
        ?>

	
	
        
  <?php         $thumbnail_key = (isset($dboxlite_slider['img_pick'][1]) ? $dboxlite_slider['img_pick'][1] : 'dboxliteslider_thumbnail');
                $dboxlite_sslider_thumbnail= get_post_meta($post_id, $thumbnail_key, true); 
		$dbox_sslider_nolink=get_post_meta($post_id, 'dbox_sslider_nolink', true);
		$dbox_link_attr=get_post_meta($post_id, 'dbox_link_attr', true);
		$dboxlite_youtubeurl=get_post_meta($post_id, '_dbox_youtubeurl', true);
                $dboxlite_mp4url=get_post_meta($post_id, '_dbox_mp4url', true);
                $dboxlite_webmurl=get_post_meta($post_id, '_dbox_webmurl', true);
                $dboxlite_oggurl=get_post_meta($post_id, '_dbox_oggurl', true);
		$dboxlite_video_shortcode=get_post_meta($post_id, '_dbox_video_shortcode', true);
  ?>
			
				<div class="slider_checkbox">
				<table class="form-table">
				<tr valign="top">
				<th scope="row"><label for="dboxlite_sslider_thumbnail"><?php _e('Custom Thumbnail Image(url)','dboxlite-slider'); ?></label></th>
                <td><input type="text" name="dboxlite_sslider_thumbnail" class="dboxlite_sslider_thumbnail" value="<?php echo $dboxlite_sslider_thumbnail;?>" size="50" style="width:90%;" /></td>
				</tr>

				<tr valign="top">
				<th scope="row"><label for="dboxlite_sslider_nolink"><?php _e('Do not link this slide to any page(url)','dboxlite-slider'); ?> </label></th>
				<td><input type="checkbox" name="dboxlite_sslider_nolink" class="dboxlite_sslider_nolink" value="1" <?php if($dbox_sslider_nolink=='1'){echo "checked";}?>  /></td>
				</tr>

				</table>
			</div>
		</div>
		<div id="dboxlite_advaced_tab" style="display:none;">
			<div class="slider_checkbox">
			<table class="form-table">
		         	<tr valign="top">
		 <th scope="row"><label for="_dbox_slider_style"><?php _e('Stylesheet to use if slider is displayed on this Post/Page','dboxlite-slider'); ?> </label></th>
		 	<td><select name="_dbox_slider_style" >
			<?php 
            $directory = DBOXLITE_SLIDER_CSS_DIR;
            if ($handle = opendir($directory)) {
                while (false !== ($file = readdir($handle))) { 
                 if($file != '.' and $file != '..') { ?>
                  <option value="<?php echo $file;?>" <?php if (($dboxlite_slider_style == $file) or (empty($dboxlite_slider_style) and $dboxlite_slider['stylesheet'] == $file)){ echo "selected";}?> ><?php echo $file;?></option>
             <?php  } }
                closedir($handle);
            }
            ?>
        </select></td>
		</tr>

		<tr valign="top">
                <th scope="row"><label for="dbox_link_attr"><?php _e('Slide Link (anchor) attributes ','dboxlite-slider'); ?></label></th>
                <td><input type="text" name="dbox_link_attr" class="dbox_link_attr" value="<?php echo htmlentities( $dbox_link_attr, ENT_QUOTES);?>" size="50" style="width:90%;" /><br /><small><?php _e('e.g. target="_blank" rel="external nofollow"','dboxlite-slider'); ?></small></td>
		</tr>
<!-- Added for video - Start -->
	<tr valign="top">
	<th scope="row"><label for="video"><?php _e('Video URL','dboxlite-slider'); ?> </label><br><br><div style="font-weight:normal;border:1px dashed #ccc;padding:5px;color:#666;line-height:20px;font-size:13px;">webm video, ogg video are the fallback URL used for specific browsers. You can mention your self hosted videos over here.</div></th>
	<td>

		        <fieldset>
		        <table>
		        
		         <tr>
		        <td><label for="mp4_video"><?php _e('MP4 Video','dboxlite-slider'); ?></label></td>

		        <td><input type="text" name="dboxlite_sslider_mp4url" value="<?php echo htmlentities( $dboxlite_mp4url, ENT_QUOTES);?>" size="50" style="width:90%;" /></td>
			</tr>

		        <tr>
		        <td><label for="webm_video"><?php _e('Webm Video','dboxlite-slider'); ?></label></td>
		        <td><input type="text" name="dboxlite_sslider_webmurl" value="<?php echo htmlentities( $dboxlite_webmurl, ENT_QUOTES);?>" size="50" style="width:90%;" /></td>
			</tr>

		       <tr>
		        <td><label for="ogg_video"><?php _e('Ogg Video','dboxlite-slider'); ?></label></td>
		        <td><input type="text" name="dboxlite_sslider_oggurl" value="<?php echo htmlentities( $dboxlite_oggurl, ENT_QUOTES);?>" size="50" style="width:90%;" /></td>
			</tr>

			<tr>
		        <td><label for="Youtube_video"><?php _e('Youtube Video','dboxlite-slider'); ?></label></td>
		        <td><input type="text" name="dboxlite_sslider_youtubeurl" value="<?php echo htmlentities( $dboxlite_youtubeurl, ENT_QUOTES);?>" size="50" style="width:90%;" /></td>
			</tr>
		        </table>
		        </fieldset>
	
	</td>
	</tr>

 	 <tr valign="top">
	 <th scope="row"><label for="video_shortcode"><?php _e('Embed Shortcode','dboxlite-slider'); ?> </label><br><br><div style="font-weight:normal;border:1px dashed #ccc;padding:5px;color:#666;line-height:20px;font-size:13px;">You can embed any type of shortcode e.g video shortcode or button shortcode which you want to be overlaid on the slide.</div></th>
         <td><textarea rows="4" cols="50" name="dboxlite_sslider_vshortcode"><?php echo htmlentities( $dboxlite_video_shortcode, ENT_QUOTES);?></textarea></td>
	 </tr>
<!-- Added for video - End -->
				</table>

				</div>     
			</div>
<?php }
}

//CSS for the checkbox on the admin page
function dboxlite_slider_checkbox_css() {
?><style type="text/css" media="screen">.slider_checkbox{margin: 5px 0 10px 0;padding:3px;font-weight:bold;}.slider_checkbox input,.slider_checkbox select{font-weight:bold;}.slider_checkbox label,.slider_checkbox input,.slider_checkbox select{vertical-align:top;}</style>
<?php
}

add_action('publish_post', 'dboxlite_add_to_slider');
add_action('publish_page', 'dboxlite_add_to_slider');
add_action('edit_post', 'dboxlite_add_to_slider');
add_action('publish_post', 'dboxlite_remove_from_slider');
add_action('edit_post', 'dboxlite_remove_from_slider');
add_action('deleted_post','dboxlite_delete_from_slider_table');

add_action('edit_attachment', 'dboxlite_add_to_slider');
add_action('delete_attachment','dboxlite_delete_from_slider_table');

function dboxlite_slider_plugin_url( $path = '' ) {
	global $wp_version;
	if ( version_compare( $wp_version, '2.8', '<' ) ) { // Using WordPress 2.7
		$folder = dirname( plugin_basename( __FILE__ ) );
		if ( '.' != $folder )
			$path = path_join( ltrim( $folder, '/' ), $path );

		return plugins_url( $path );
	}
	return plugins_url( $path, __FILE__ );
}

function dboxlite_get_string_limit($output, $max_char)
{
    $output = str_replace(']]>', ']]&gt;', $output);
    $output = strip_tags($output);

  	if ((strlen($output)>$max_char) && ($espacio = strpos($output, " ", $max_char )))
	{
        $output = substr($output, 0, $espacio).'...';
		return $output;
   }
   else
   {
      return $output;
   }
}

function dboxlite_slider_get_first_image($post) {
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1] [0];
	return $first_img;
}
add_filter( 'plugin_action_links', 'dboxlite_sslider_plugin_action_links', 10, 2 );

function dboxlite_sslider_plugin_action_links( $links, $file ) {
	if ( $file != DBOXLITE_SLIDER_PLUGIN_BASENAME )
		return $links;

	$url = dboxlite_sslider_admin_url( array( 'page' => 'dboxlite-slider-settings' ) );

	$settings_link = '<a href="' . esc_attr( $url ) . '">'
		. esc_html( __( 'Settings') ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}

//New Custom Post Type
if( $dboxlite_slider['custom_post'] == '1' and !post_type_exists('slidervilla') ){
	add_action( 'init', 'dboxlite_post_type', 11 );
	function dboxlite_post_type() {
			$labels = array(
			'name' => _x('SliderVilla Slides', 'post type general name'),
			'singular_name' => _x('SliderVilla Slide', 'post type singular name'),
			'add_new' => _x('Add New', 'dboxlite'),
			'add_new_item' => __('Add New SliderVilla Slide'),
			'edit_item' => __('Edit SliderVilla Slide'),
			'new_item' => __('New SliderVilla Slide'),
			'all_items' => __('All SliderVilla Slides'),
			'view_item' => __('View SliderVilla Slide'),
			'search_items' => __('Search SliderVilla Slides'),
			'not_found' =>  __('No SliderVilla slides found'),
			'not_found_in_trash' => __('No SliderVilla slides found in Trash'), 
			'parent_item_colon' => '',
			'menu_name' => 'SliderVilla Slides'

			);
			$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor','thumbnail','excerpt','custom-fields')
			); 
			register_post_type('slidervilla',$args);
	}

	//add filter to ensure the text SliderVilla, or slidervilla, is displayed when user updates a slidervilla 
	add_filter('post_updated_messages', 'dboxlite_updated_messages');
	function dboxlite_updated_messages( $messages ) {
	  global $post, $post_ID;

	  $messages['dboxlite'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('SliderVilla Slide updated. <a href="%s">View SliderVilla slide</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('SliderVilla Slide updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('SliderVilla Slide restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('SliderVilla Slide published. <a href="%s">View SliderVilla slide</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('DboxLite saved.'),
		8 => sprintf( __('SliderVilla Slide submitted. <a target="_blank" href="%s">Preview SliderVilla slide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('SliderVilla Slides scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview SliderVilla slide</a>'),
		  // translators: Publish box date format, see http://php.net/date
		  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('SliderVilla Slide draft updated. <a target="_blank" href="%s">Preview SliderVilla slide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	  );

	  return $messages;
	}
} //if custom_post is true

require_once (dirname (__FILE__) . '/includes/media-images.php');
require_once (dirname (__FILE__) . '/slider_versions/dboxlite_1.php');
require_once (dirname (__FILE__) . '/settings/settings.php');

?>
