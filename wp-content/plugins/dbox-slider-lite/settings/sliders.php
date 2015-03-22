<?php // This function displays the page content for the DboxLite Slider Options submenu
function dboxlite_slider_create_multiple_sliders() {
global $dboxlite_slider;
?>

<div class="wrap" id="dboxlite_sliders_create" style="clear:both;">
<?php 
if (isset ($_POST['remove_posts_slider'])) {
   if ( isset($_POST['slider_posts'] ) ) {
	   global $wpdb, $table_prefix;
	   $table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	   $current_slider = $_POST['current_slider_id'];
	   foreach ( $_POST['slider_posts'] as $post_id=>$val ) {
		   $sql = "DELETE FROM $table_name WHERE post_id = '$post_id' AND slider_id = '$current_slider' LIMIT 1";
		   $wpdb->query($sql);
	   }
   }
   if (isset ($_POST['remove_all'])) {
	   if ($_POST['remove_all'] == __('Remove All at Once','dboxlite-slider')) {
		   global $wpdb, $table_prefix;
		   $table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
		   $current_slider = $_POST['current_slider_id'];
		   if(is_dboxlite_slider_on_slider_table($current_slider)) {
			   $sql = "DELETE FROM $table_name WHERE slider_id = '$current_slider';";
			   $wpdb->query($sql);
		   }
	   }
   }
   if (isset ($_POST['remove_all'])) {
	   if ($_POST['remove_all'] == __('Delete Slider','dboxlite-slider')) {
		   $slider_id = $_POST['current_slider_id'];
		   global $wpdb, $table_prefix;
		   $slider_table = $table_prefix.DBOXLITE_SLIDER_TABLE;
		   $slider_meta = $table_prefix.DBOXLITE_SLIDER_META;
		   $slider_postmeta = $table_prefix.DBOXLITE_SLIDER_POST_META;
		   if(is_dboxlite_slider_on_slider_table($slider_id)) {
			   $sql = "DELETE FROM $slider_table WHERE slider_id = '$slider_id';";
			   $wpdb->query($sql);
		   }
		   if(is_dboxlite_slider_on_meta_table($slider_id)) {
			   $sql = "DELETE FROM $slider_meta WHERE slider_id = '$slider_id';";
			   $wpdb->query($sql);
		   }
		   if(is_dboxlite_slider_on_postmeta_table($slider_id)) {
			   $sql = "DELETE FROM $slider_postmeta WHERE slider_id = '$slider_id';";
			   $wpdb->query($sql);
		   }
	   }
   }
}
if (isset ($_POST['reorder_posts_slider'])) {
   $i=1;
   global $wpdb, $table_prefix;
   $table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
   $slider_id=$_POST['current_slider_id'];
   foreach ($_POST['order'] as $slide_order) {
    $slide_order = intval($slide_order);
    $sql = 'UPDATE '.$table_name.' SET slide_order='.$i.' WHERE post_id='.$slide_order.' and slider_id='.$slider_id;
    $wpdb->query($sql);
    $i++;
  }
}

if ((isset ($_POST['rename_slider'])) and ($_POST['rename_slider'] == __('Rename','dboxlite-slider'))) {
	$slider_name = $_POST['rename_slider_to'];
	$slider_id=$_POST['current_slider_id'];
	if( !empty($slider_name) ) {
		global $wpdb,$table_prefix;
		$slider_meta = $table_prefix.DBOXLITE_SLIDER_META;
		$sql = 'UPDATE '.$slider_meta.' SET slider_name="'.$slider_name.'" WHERE slider_id='.$slider_id;
		$wpdb->query($sql);
	}
}
if ( isset($_POST['addSave']) and ($_POST['addSave']=='Save') ) {
	$images=(isset($_POST['imgID']))?$_POST['imgID']:array();
	$slider_id=$_POST['current_slider_id'];
	$ids=array_reverse($images);
	global $wpdb,$table_prefix;
	foreach($ids as $id){
		$title=(isset($_POST['title'][$id]))?$_POST['title'][$id]:'';
		$desc=(isset($_POST['desc'][$id]))?$_POST['desc'][$id]:'';
		$link=(isset($_POST['link'][$id]))?$_POST['link'][$id]:'';
		$nolink=(isset($_POST['nolink'][$id]))?$_POST['nolink'][$id]:'';
		$attachment = array(
			'ID'           => $id,
			'post_title'   => $title,
			'post_content' => $desc
		);
		wp_update_post( $attachment );
		update_post_meta($id, 'dboxlite_slide_redirect_url', $link);
		update_post_meta($id, 'dboxlite_sslider_nolink', $nolink);
		if(!dboxlite_slider($id,$slider_id)) {
				$dt = date('Y-m-d H:i:s');
				$sql = "INSERT INTO ".$table_prefix.DBOXLITE_SLIDER_TABLE." (post_id, date, slider_id) VALUES ('$id', '$dt', '$slider_id')";
				$wpdb->query($sql);
		}
	}
}
?>
<h2 class="top_heading"><span><?php _e('Sliders Created','dboxlite-slider'); ?></span></h2>
<div style="clear:left"></div>
<?php $url = dboxlite_sslider_admin_url( array( 'page' => 'dboxlite-slider-settings' ) );?>
<a class="svorangebutton" href="<?php echo $url; ?>" title="<?php _e('Settings Page for DboxLite Slider where you can change the color, font etc. for the sliders','dboxlite-slider'); ?>"><?php _e('Go to DboxLite Slider Settings page','dboxlite-slider'); ?></a>
<div style="clear:right"></div>
<?php $sliders = dboxlite_ss_get_sliders(); ?>

<div id="slider_tabs">
        <ul class="ui-tabs">
        <?php foreach($sliders as $slider){?>
            <li class="yellow"><a href="#tabs-<?php echo $slider['slider_id'];?>"><?php echo $slider['slider_name'];?></a></li>
        <?php } ?>
	
        </ul>

<?php foreach($sliders as $slider){?>
<div id="tabs-<?php echo $slider['slider_id'];?>">
<strong>Quick Embed Shortcode:</strong>
<div class="admin_shortcode">
<pre style="padding: 10px 0;">[dboxslider]</pre>
</div>

<!-- Add bulk images start v1.1-->
<?php 
if ( ! did_action( 'wp_enqueue_media' ) ) wp_enqueue_media();
wp_enqueue_script( 'media-uploader', dboxlite_slider_plugin_url( 'js/media-uploader.js' ),array( 'jquery', 'iris' ), DBOXLITE_SLIDER_VER, false);
?>
	<h3 class="sub-heading" style="margin-left:0px;"><?php _e('Add Images to','dboxlite-slider'); ?> <?php echo $slider['slider_name'];?> (Slider ID = <?php echo $slider['slider_id'];?>)</h3>

	<div class="uploaded-images">
		<form method="post" class="addImgForm">
			<div style="clear:left;margin-top:10px;" class="image-uploader">
				<input type="submit" class="upload-button slider_images_upload" name="slider_images_upload" value="Upload" />
			</div>
			<input type="hidden" name="current_slider_id" value="<?php echo $slider['slider_id'];?>" />
			<input type="hidden" name="active_tab" class="dboxlite_activetab" value="0" />
		</form>
	</div>
<!-- Add bulk images end v1.1-->

<form action="" method="post">
<?php settings_fields('dboxlite-slider-group'); ?>

<input type="hidden" name="remove_posts_slider" value="1" />
<div id="tabs-<?php echo $slider['slider_id'];?>">
<h3><?php _e('Posts/Pages Added To','dboxlite-slider'); ?> <?php echo $slider['slider_name'];?></h3>
<p><em><?php _e('Check the Post/Page and Press "Remove Selected" to remove them From','dboxlite-slider'); ?> <?php echo $slider['slider_name'];?>. <?php _e('Press "Remove All at Once" to remove all the posts from the','dboxlite-slider'); ?> <?php echo $slider['slider_name'];?>.</em></p>

    <table class="widefat">
    <thead class="blue"><tr><th><?php _e('Post/Page Title','dboxlite-slider'); ?></th><th><?php _e('Author','dboxlite-slider'); ?></th><th><?php _e('Post Date','dboxlite-slider'); ?></th><th><?php _e('Remove Post','dboxlite-slider'); ?></th></tr></thead><tbody>

<?php  
	/*global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;*/
	$slider_id = $slider['slider_id'];
	//$slider_posts = $wpdb->get_results("SELECT post_id FROM $table_name WHERE slider_id = '$slider_id'", OBJECT); 
    $slider_posts=dboxlite_get_slider_posts_in_order($slider_id); ?>
	
    <input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
    
<?php    $count = 0;	
	foreach($slider_posts as $slider_post) {
	  $slider_arr[] = $slider_post->post_id;
	  $post = get_post($slider_post->post_id);	  
		if(isset($post) and isset($slider_arr)){
			if ( in_array($post->ID, $slider_arr) ) {
			  $count++;
			  $sslider_author = get_userdata($post->post_author);
			  $sslider_author_dname = $sslider_author->display_name;
			  echo '<tr' . ($count % 2 ? ' class="alternate"' : '') . '><td><strong>' . $post->post_title . '</strong><a href="'.get_edit_post_link( $post->ID, $context = 'display' ).'" target="_blank"> '.__( '(Edit)', 'dboxlite-slider' ).'</a> <a href="'.get_permalink( $post->ID ).'" target="_blank"> '.__( '(View)', 'dboxlite-slider' ).' </a></td><td>By ' . $sslider_author_dname . '</td><td>' . date('l, F j. Y',strtotime($post->post_date)) . '</td><td><input type="checkbox" name="slider_posts[' . $post->ID . ']" value="1" /></td></tr>'; 
			}
		}
	}
		
	if ($count == 0) {
		echo '<tr><td colspan="4">'.__( 'No posts/pages have been added to the Slider - You can add respective post/page to slider on the Edit screen for that Post/Page', 'dboxlite-slider' ).'</td></tr>';
	}
	echo '</tbody><tfoot class="blue"><tr><th>'.__( 'Post/Page Title', 'dboxlite-slider' ).'</th><th>'.__( 'Author', 'dboxlite-slider' ).'</th><th>'.__( 'Post Date', 'dboxlite-slider' ).'</th><th>'.__( 'Remove Post', 'dboxlite-slider' ).'</th></tr></tfoot></table>'; 
    
	echo '<div class="submit">';
	
	if ($count) {echo '<input type="submit" value="'.__( 'Remove Selected', 'dboxlite-slider' ).'" onclick="return confirmRemove()" /><input type="submit" name="remove_all" value="'.__( 'Remove All at Once', 'dboxlite-slider' ).'" onclick="return confirmRemoveAll()" />';}
	
	if($slider_id != '1') {
	   echo '<input type="submit" value="'.__( 'Delete Slider', 'dboxlite-slider' ).'" name="remove_all" onclick="return confirmSliderDelete()" />';
	}
	
	echo '</div>';
?>    
    </tbody></table>
	
	<input type="hidden" name="active_tab" class="dboxlite_activetab" value="0" />
	
 </form>
 
 
 <form action="" method="post">
    <input type="hidden" name="reorder_posts_slider" value="1" />
    <h3 class="sub-heading" style="margin-left:0px;"><?php _e('Reorder the Posts/Pages Added To','dboxlite-slider'); ?> <?php echo $slider['slider_name'];?></h3>
    <p><em><?php _e('Click on and drag the post/page title to a new spot within the list, and the other items will adjust to fit.','dboxlite-slider'); ?> </em></p>
    <ul id="sslider_sortable" style="color:#326078;overflow: auto;">    
    <?php  
    /*global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;*/
	$slider_id = $slider['slider_id'];
	//$slider_posts = $wpdb->get_results("SELECT post_id FROM $table_name WHERE slider_id = '$slider_id'", OBJECT); 
    $slider_posts=dboxlite_get_slider_posts_in_order($slider_id);?>
        
        <input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
        
    <?php    $count = 0;	
        foreach($slider_posts as $slider_post) {
			$slider_arr[] = $slider_post->post_id;
			$post = get_post($slider_post->post_id);	  
			if(isset($post) and isset($slider_arr)){
				if ( in_array($post->ID, $slider_arr) ) {
					$count++;
					$sslider_author = get_userdata($post->post_author);
					$sslider_author_dname = $sslider_author->display_name;
					echo '<li id="'.$post->ID.'" class="reorder"><input type="hidden" name="order[]" value="'.$post->ID.'" /><strong> &raquo; &nbsp; ' . $post->post_title . '</strong></li>'; 
				}
			}
        }
            
        if ($count == 0) {
            echo '<li>'.__( 'No posts/pages have been added to the Slider - You can add respective post/page to slider on the Edit screen for that Post/Page', 'dboxlite-slider' ).'</li>';
        }
		        
        echo '</ul><div class="submit">';
        
        if ($count) {echo '<input type="submit" value="Save the order"  />';}
                
        echo '</div>';
    ?>    
       </div>     

		<input type="hidden" name="active_tab" class="dboxlite_activetab" value="0" />
		
  </form>
  
<form action="" method="post"> 
	<table class="form-table">
		<tr valign="top">
		<th scope="row"><h3><?php _e('Rename Slider to','dboxlite-slider'); ?></h3></th>
		<td><h3><input type="text" name="rename_slider_to" class="regular-text" value="<?php echo $slider['slider_name'];?>" /></h3></td>
		</tr>
	</table>
	<input type="hidden" name="current_slider_id" value="<?php echo $slider_id;?>" />
	<input type="submit" value="<?php _e('Rename','dboxlite-slider'); ?>"  name="<?php _e('rename_slider','dboxlite-slider'); ?>" />
	
	<input type="hidden" name="active_tab" class="dboxlite_activetab" value="0" />
	
</form>
  
</div> 
 
<?php } ?>

<div style="clear:left;"></div>
</div>

</div> <!--end of float wrap -->
<?php	
}
?>
