<?php 
function dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr,$out_echo,$set,$data=array() ){
	//If no Skin specified, consider Default
	$skin='default';
	if(isset($dboxlite_slider_curr['stylesheet'])) $skin=$dboxlite_slider_curr['stylesheet'];
	if(empty($skin))$skin='default';
	
	//Always include Default Skin
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/default/functions.php');
	//Include Skin function file
	if($skin!='default' and file_exists(dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php')) require_once ( dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php');
	
	//Skin specific post processor and html generation
	$post_processor_fn='dboxlite_post_processor_'.$skin;
	if(!function_exists($post_processor_fn))$post_processor_fn='dboxlite_post_processor_default';
	$r_array=$post_processor_fn($posts, $dboxlite_slider_curr,$out_echo,$set,$data);
	return $r_array;	
}
function get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data=array()){
	//If no Skin specified, consider Default
	$skin='default';
	if(isset($dboxlite_slider_curr['stylesheet'])) $skin=$dboxlite_slider_curr['stylesheet'];
	if(empty($skin))$skin='default';
	
	//Include CSS
	wp_enqueue_style( 'dboxlite_'.$skin, dboxlite_slider_plugin_url( 'css/skins/'.$skin.'/style.css' ),	false, DBOXLITE_SLIDER_VER, 'all');
	//Always include Default Skin
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/default/functions.php');
	//Include Skin function file
	if($skin!='default' and file_exists(dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php'))
	require_once ( dirname( dirname(__FILE__) ) . '/css/skins/'.$skin.'/functions.php');
	
	//Skin specific post processor and html generation
	$get_processor_fn='dboxlite_slider_get_'.$skin;
	
	if(!function_exists($get_processor_fn))$get_processor_fn='dboxlite_slider_get_default';
	$r_array=$get_processor_fn($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo,$data);
	return $r_array;	
}
function dboxlite_carousel_posts_on_slider($max_posts, $offset=0, $slider_id = '1',$out_echo = '1',$set='',$data=array()) {
 global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	$post_table = $table_prefix."posts";
	$rand = $dboxlite_slider_curr['rand'];
	if(isset($rand) and $rand=='1'){
	  $orderby = 'RAND()';
	}
	else {
	  $orderby = 'a.slide_order ASC, a.date DESC';
	}
	
	$posts = $wpdb->get_results("SELECT * FROM 
	                             $table_name a LEFT OUTER JOIN $post_table b 
								 ON a.post_id = b.ID 
								 WHERE (b.post_status = 'publish' OR (b.post_type='attachment' AND b.post_status = 'inherit')) AND a.slider_id = '$slider_id'  
	                             ORDER BY ".$orderby." LIMIT $offset, $max_posts", OBJECT);
	
	$r_array=dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr, $out_echo, $set, $data );
	return $r_array;
}

if(!function_exists('get_dbox_slider')){
	function get_dbox_slider($slider_id='', $set='', $offset=0,$data=array()) {
		global $dboxlite_slider,$default_dboxlite_slider_settings;  
		$set='';
	 	$dboxlite_slider_options='dbox_slider_options'.$set;
	    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
		if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
		foreach($default_dboxlite_slider_settings as $key=>$value){
			if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
		}	
	
		
		if(empty($slider_id) or !isset($slider_id))  $slider_id = '1';
		if( !$offset or empty($offset) or !is_numeric($offset)  ) $offset=0;
		if(!empty($slider_id)){
			$data['slider_id']=$slider_id;
			$slider_handle='dboxlite_slider_'.$slider_id;
			$data['slider_handle']=$slider_handle;
			$r_array = dboxlite_carousel_posts_on_slider($dboxlite_slider_curr['no_posts'], $offset, $slider_id, '0', $set, $data); 
			get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data);
		} //end of not empty slider_id condition  		
	}
}

function dboxlite_carousel_posts_on_slider_category($max_posts='5', $catg_slug='', $offset=0, $out_echo = '1', $set='', $data=array() ) {
    global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	
	
	global $wpdb, $table_prefix;
	
	if (!empty($catg_slug)) {
		$category = get_category_by_slug($catg_slug);
		$slider_cat = $category->term_id;
		}
	else {
		$category = get_the_category();
		$slider_cat = $category[0]->cat_ID;
	}
	
	$rand = $dboxlite_slider_curr['rand'];
	if(isset($rand) and $rand=='1') $orderby = '&orderby=rand';
	else $orderby = '';
	
	//extract the posts
	$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset.'&category='.$slider_cat.$orderby);
	
	$r_array=dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr, $out_echo,$set,$data );
	return $r_array;
}
if(!function_exists('get_dbox_slider_category')){
function get_dbox_slider_category($catg_slug='', $set='', $offset=0, $data=array()) {
    global $dboxlite_slider,$default_dboxlite_slider_settings;  
	$set='';
 	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	

	if( !$offset or empty($offset) or !is_numeric($offset)  ) $offset=0;
   	$slider_handle='dboxlite_slider_'.$catg_slug;
    $data['slider_handle']=$slider_handle;
	$r_array = dboxlite_carousel_posts_on_slider_category($dboxlite_slider_curr['no_posts'], $catg_slug, $offset, '0', $set, $data); 
	get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data);
	} 
}

//For displaying recent posts in chronologically reverse order
function dboxlite_carousel_posts_on_slider_recent($max_posts='5', $offset=0, $out_echo = '1', $set='', $data=array()) {
    global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	
	
	$rand = $dboxlite_slider_curr['rand'];
	if(isset($rand) and $rand=='1')	  $orderby = '&orderby=rand';
	else  $orderby = '';
	//extract posts data
	$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset.$orderby);
	$r_array=dboxlite_global_posts_processor( $posts, $dboxlite_slider_curr, $out_echo,$set,$data );
	return $r_array;
}
if(!function_exists('get_dbox_slider_recent')){
function get_dbox_slider_recent($set='', $offset=0, $data=array()) {
	global $dboxlite_slider,$default_dboxlite_slider_settings; 
	$set='';
 	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}	

	if( !$offset or empty($offset) or !is_numeric($offset)  ) $offset=0;
	$slider_handle='dboxlite_slider_recent';
	$data['slider_handle']=$slider_handle;
	$r_array = dboxlite_carousel_posts_on_slider_recent($dboxlite_slider_curr['no_posts'], $offset, '0', $set, $data);
	get_global_dboxlite_slider($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data);
	}
}
require_once (dirname (__FILE__) . '/shortcodes_1.php');
require_once (dirname (__FILE__) . '/widgets_1.php');

function dboxlite_slider_enqueue_scripts() {
	
	wp_enqueue_script( 'jquery.bpopup.min', dboxlite_slider_plugin_url( 'js/jquery.bpopup.min.js' ),'', DBOXLITE_SLIDER_VER, false);
	wp_enqueue_script( 'jquery');
}

add_action( 'wp_enqueue_scripts', 'dboxlite_slider_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'dboxlite_slider_enqueue_scripts' );

//admin settings
function dboxlite_slider_admin_scripts() {
global $dboxlite_slider;
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( isset($_GET['page']) && ('dboxlite-slider-admin' == $_GET['page'] or 'dboxlite-slider-settings' == $_GET['page'] )  ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'dboxlite_slider_admin_js', dboxlite_slider_plugin_url( 'js/admin.js' ),
			array('jquery'), DBOXLITE_SLIDER_VER, false);
		wp_enqueue_style( 'dboxlite_slider_admin_css', dboxlite_slider_plugin_url( 'css/admin.css' ),
			false, DBOXLITE_SLIDER_VER, 'all');
		
	}
  }
}

add_action( 'admin_init', 'dboxlite_slider_admin_scripts' );
function dboxlite_slider_admin_head() {
global $dboxlite_slider;
if ( is_admin() ){ // admin actions
// Sliders & Settings page only
    if ( isset($_GET['page']) && ('dboxlite-slider-admin' == $_GET['page'] or 'dboxlite-slider-settings' == $_GET['page']) ) {
	  $sliders = dboxlite_ss_get_sliders(); 
		global $dboxlite_slider;
		$dboxlite_slider_options='dbox_slider_options';
		$dboxlite_slider_curr=get_option($dboxlite_slider_options);
		$active_tab=(isset($dboxlite_slider_curr['active_tab']))?$dboxlite_slider_curr['active_tab']:0;
		if ( isset($_GET['page']) && ('dboxlite-slider-admin' == $_GET['page']) ){ if(isset($_POST['active_tab']) ) $active_tab=$_POST['active_tab'];else $active_tab = 0;}
		if(empty($active_tab)){$active_tab=0;}
		
	?>
		<script type="text/javascript">
		
            // <![CDATA[
        jQuery(document).ready(function() {
                jQuery(function() {
					jQuery("#slider_tabs").tabs({fx: { opacity: "toggle", duration: 300}, active: <?php echo $active_tab;?> }).addClass( "ui-tabs-vertical-left ui-helper-clearfix" );jQuery( "#slider_tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
				<?php 	if ( isset($_GET['page']) && (( 'dboxlite-slider-settings' == $_GET['page']) or ('dboxlite-slider-admin' == $_GET['page']) ) ) { ?>
					jQuery( "#slider_tabs" ).on( "tabsactivate", function( event, ui ) { jQuery( "#dboxlite_activetab, .dboxlite_activetab" ).val( jQuery( "#slider_tabs" ).tabs( "option", "active" ) ); });
				<?php 	}
				foreach($sliders as $slider){ ?>
                    jQuery("#sslider_sortable").sortable();
                    jQuery("#sslider_sortable").disableSelection();
			    <?php } ?>
		    jQuery( ".uploaded-images" ).sortable({ items: ".addedImg" });
                });
        });
		
        function confirmRemove(){
            var agree=confirm("This will remove selected Posts/Pages from Slider.");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmRemoveAll(){
            var agree=confirm("Remove all Posts/Pages from DboxLite Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmSliderDelete(){
            var agree=confirm("Delete this Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function slider_checkform ( form ){
          if (form.new_slider_name.value == "") {
            alert( "Please enter the New Slider name." );
            form.new_slider_name.focus();
            return false ;
          }
          return true ;
        }
        </script>
<?php
   } //Sliders page only
   /* Added for new WP color picker v1.0.1 */
 if ( isset($_GET['page']) && 'dboxlite-slider-settings' == $_GET['page']  ) {
		wp_enqueue_style( 'wp-color-picker' );
   		wp_enqueue_script( 'wp-color-picker' );
		
?>
<script type="text/javascript">
	// <![CDATA[
jQuery(document).ready(function() {
		jQuery('.wp-color-picker-field').wpColorPicker();
		
});
function confirmSettingsCreate()
        {
            var agree=confirm("Create New Settings Set??");
            if (agree)
            return true ;
            else
            return false ;
}
function confirmSettingsDelete()
        {
            var agree=confirm("Delete this Settings Set??");
            if (agree)
            return true ;
            else
            return false ;
}
</script>
<style type="text/css">.color-picker-wrap {position: absolute;	display: none; background: #fff;border: 3px solid #ccc;	padding: 3px;z-index: 1000;}</style>
<?php
   } //for dboxlite slider option page
 }//only for admin
//Below css will add the menu icon for DboxLite Slider admin menu
?>
<style type="text/css">#adminmenu #toplevel_page_dboxlite-slider-admin div.wp-menu-image:before { content: "\f233"; }</style>
<?php
}
add_action('admin_head', 'dboxlite_slider_admin_head');

function dboxlite_get_inline_css($set='',$echo='0'){
    global $dboxlite_slider,$default_dboxlite_slider_settings;
	$dboxlite_slider_options='dbox_slider_options'.$set;
    $dboxlite_slider_curr=get_option($dboxlite_slider_options);
	if(!isset($dboxlite_slider_curr) or !is_array($dboxlite_slider_curr) or empty($dboxlite_slider_curr)){$dboxlite_slider_curr=$dboxlite_slider;$set='';}
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	
	global $post;
	if(is_singular()) { $dbox_slider_style = get_post_meta($post->ID,'_dbox_slider_style',true);}
	if((is_singular() and ($dbox_slider_style == 'default' or empty($dbox_slider_style) or !$dbox_slider_style)) or (!is_singular() and $dboxlite_slider['stylesheet'] == 'default')  )	{ $default=true;	}
	else{ $default=false;}
	
	$dboxlite_slider_css=array(
		'dboxlite_slider'=>'',
		'dboxlite_slider_h2'=>'',
		'dboxlite_slider_h2_a'=>'',
		'dboxlite_slider_span'=>'',
		'dboxlite_slider_thumbnail'=>'',
		'dboxlite_text'=>'',
		'nav_next'=>'',
		'nav_prev'=>'',
		'nav_pause'=>'',
		'nav_play'=>'',
		'nav_options'=>'',
		'nav_dots'=>''
	);
	if($default){
		$style_start= ($echo=='0') ? 'style="':'';
		$style_end= ($echo=='0') ? '"':'';
	//dboxlite_slider
		$dboxlite_slider_css['dboxlite_slider']=$style_start.'max-width:'.$dboxlite_slider_curr['width'].'px;max-height:'.$dboxlite_slider_curr['height'].'px;height:100%;'.$style_end;
		
	//dboxlite_slideri
		//$dboxlite_slider_css['dboxlite_slideri']=$style_start.'height:'.$dboxlite_slider_curr['height'].'px;'.$style_end;

	//dboxlite_slider_h2	
		$ptitle_fontg=isset($dboxlite_slider_curr['ptitle_fontg'])?trim($dboxlite_slider_curr['ptitle_fontg']):'';
		if(!empty($ptitle_fontg)) 	{
			wp_enqueue_style( 'dboxlite_ptitle', 'http://fonts.googleapis.com/css?family='.$ptitle_fontg,array(),DBOXLITE_SLIDER_VER);
			$ptitle_fontg=dboxlite_get_google_font($ptitle_fontg);
			$ptitle_fontg='\''.$ptitle_fontg.'\''.',';
		}	
		if ($dboxlite_slider_curr['ptitle_fstyle'] == "bold" or $dboxlite_slider_curr['ptitle_fstyle'] == "bold italic" ){$ptitle_fweight = "bold";} else {$ptitle_fweight = "normal";}
		if ($dboxlite_slider_curr['ptitle_fstyle'] == "italic" or $dboxlite_slider_curr['ptitle_fstyle'] == "bold italic"){$ptitle_fstyle = "italic";} else {$ptitle_fstyle = "normal";}
		$dboxlite_slider_css['dboxlite_slider_h2']=$style_start.'clear:none;line-height:'. ($dboxlite_slider_curr['ptitle_fsize'] + 5) .'px;font-family:'. $ptitle_fontg . ' ' . $dboxlite_slider_curr['ptitle_font'].', Arial, Helvetica, sans-serif;font-size:'.$dboxlite_slider_curr['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';color:'.$dboxlite_slider_curr['ptitle_fcolor'].';'.$style_end;
		
	//dboxlite_slider_h2 a
		$dboxlite_slider_css['dboxlite_slider_h2_a']=$style_start.'font-family:'. $ptitle_fontg . ' ' . $dboxlite_slider_curr['ptitle_font'].';font-size:'.$dboxlite_slider_curr['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';color:'.$dboxlite_slider_curr['ptitle_fcolor'].';'.$style_end;

	//dboxlite_slider_span		
		$content_fontg=isset($dboxlite_slider_curr['content_fontg'])?trim($dboxlite_slider_curr['content_fontg']):'';
		if(!empty($content_fontg)) 	{
			wp_enqueue_style( 'dboxlite_content', 'http://fonts.googleapis.com/css?family='.$content_fontg,array(),DBOXLITE_SLIDER_VER);
			$content_fontg=dboxlite_get_google_font($content_fontg);
			$content_fontg='\''.$content_fontg.'\''.',';
		}	
		if ($dboxlite_slider_curr['content_fstyle'] == "bold" or $dboxlite_slider_curr['content_fstyle'] == "bold italic" ){$content_fweight= "bold";} else {$content_fweight= "normal";}
		if ($dboxlite_slider_curr['content_fstyle']=="italic" or $dboxlite_slider_curr['content_fstyle'] == "bold italic"){$content_fstyle= "italic";} else {$content_fstyle= "normal";}
		$dboxlite_slider_css['dboxlite_slider_span']=$style_start.'font-family:'. $content_fontg . ' '.$dboxlite_slider_curr['content_font'].', Arial, Helvetica, sans-serif;font-size:'.$dboxlite_slider_curr['content_fsize'].'px;font-weight:'.$content_fweight.';font-style:'.$content_fstyle.';color:'. $dboxlite_slider_curr['content_fcolor'].';'.$style_end;
		
	//dboxlite_slider_thumbnail
		$dboxlite_slider_css['dboxlite_slider_thumbnail']=$style_start.'max-width:'.$dboxlite_slider_curr['width'].'px;position: relative;max-height:'.($dboxlite_slider_curr['height']-60).'px;'.$style_end;
	
	
	//dboxlite_text
	if ($dboxlite_slider_curr['bg'] == '1') { $dboxlite_text_bg = "transparent";} else { $dboxlite_text_bg = $dboxlite_slider_curr['bg_color']; }
		$dboxlite_slider_css['dboxlite_text']=$style_start.'background-color:'.$dboxlite_text_bg.';opacity:'.$dboxlite_slider_curr['bg_opacity'].';-ms-filter: \'progid:DXImageTransform.Microsoft.Alpha(Opacity='.($dboxlite_slider_curr['bg_opacity']*100).')\';filter: alpha(opacity='.($dboxlite_slider_curr['bg_opacity']*100).');background-position: initial initial; background-repeat: initial initial;'.$style_end;
	
	//nav_next
	$dboxlite_slider_css['nav_next']=$style_start.'background: '.$dboxlite_slider_curr['navarr_bgcolor'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/next.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['pn_width'].'px;height:'.$dboxlite_slider_curr['pn_width'].'px;background-size:'.($dboxlite_slider_curr['pn_width']-10).'px;'.$style_end;

	//nav_prev
	$dboxlite_slider_css['nav_prev']=$style_start.'background: '.$dboxlite_slider_curr['navarr_bgcolor'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/prev.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['pn_width'].'px;height:'.$dboxlite_slider_curr['pn_width'].'px;background-size:'.($dboxlite_slider_curr['pn_width']-10).'px;'.$style_end;
	
	//nav_pause
	$dboxlite_slider_css['nav_pause']=$style_start.'background: '.$dboxlite_slider_curr['playbt_color'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/pause.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['playbt_size'].'px;height:'.$dboxlite_slider_curr['playbt_size'].'px;background-size:'.($dboxlite_slider_curr['playbt_size']-14).'px;'.$style_end;
	
	//nav_play
	$dboxlite_slider_css['nav_play']=$style_start.'background: '.$dboxlite_slider_curr['playbt_color'].' url('.dboxlite_slider_plugin_url( 'css/skins/default/images/play.png').')no-repeat;background-position: center center;width:'.$dboxlite_slider_curr['playbt_size'].'px;height:'.$dboxlite_slider_curr['playbt_size'].'px;background-size:'.($dboxlite_slider_curr['playbt_size']-14).'px;'.$style_end;

	//nav_options
	$dboxlite_slider_css['nav_options']=$style_start.'width:'.($dboxlite_slider_curr['playbt_size']+2).'px;height:'.($dboxlite_slider_curr['playbt_size']+2).'px;'.$style_end;
	
	//nav_dots	
	$dboxlite_slider_css['nav_dots']=$style_start.'height:'.($dboxlite_slider_curr['dotsize']+14).'px;'.$style_end;
}
	return $dboxlite_slider_css;
}
function dboxlite_slider_css() {
	global $dboxlite_slider;
	$css=$dboxlite_slider['css'];
	if($css and !empty($css)){
	?>
		<style type="text/css"><?php echo $css;?></style>
	<?php
	} 
}
add_action('wp_head', 'dboxlite_slider_css');
add_action('admin_head', 'dboxlite_slider_css');
?>
