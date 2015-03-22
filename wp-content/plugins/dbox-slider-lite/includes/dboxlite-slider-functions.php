<?php 
function dboxlite_ss_get_sliders(){
	global $wpdb,$table_prefix;
	$slider_meta = $table_prefix.DBOXLITE_SLIDER_META; 
	$sql = "SELECT * FROM $slider_meta";
 	$sliders = $wpdb->get_results($sql, ARRAY_A);
	return $sliders;
}
function dboxlite_get_slider_posts_in_order($slider_id) {
    global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	$slider_posts = $wpdb->get_results("SELECT * FROM $table_name WHERE slider_id = '$slider_id' ORDER BY slide_order ASC, date DESC", OBJECT);
	return $slider_posts;
}
function get_dboxlite_slider_name($slider_id) {
    global $wpdb, $table_prefix;
	$slider_name = '';
	$table_name = $table_prefix.DBOXLITE_SLIDER_META;
	$slider_obj = $wpdb->get_results("SELECT * FROM $table_name WHERE slider_id = '$slider_id'", OBJECT);
	if (isset ($slider_obj[0]))$slider_name = $slider_obj[0]->slider_name;
	return $slider_name;
}
function dboxlite_ss_get_post_sliders($post_id){
    global $wpdb,$table_prefix;
	$slider_table = $table_prefix.DBOXLITE_SLIDER_TABLE; 
	$sql = "SELECT * FROM $slider_table 
	        WHERE post_id = '$post_id';";
	$post_sliders = $wpdb->get_results($sql, ARRAY_A);
	return $post_sliders;
}
function dboxlite_ss_post_on_slider($post_id,$slider_id){
    global $wpdb,$table_prefix;
	$slider_postmeta = $table_prefix.DBOXLITE_SLIDER_POST_META;
    $sql = "SELECT * FROM $slider_postmeta  
	        WHERE post_id = '$post_id' 
			AND slider_id = '$slider_id';";
	$result = $wpdb->query($sql);
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function dboxlite_ss_slider_on_this_post($post_id){
    global $wpdb,$table_prefix;
	$slider_postmeta = $table_prefix.DBOXLITE_SLIDER_POST_META;
    $sql = "SELECT * FROM $slider_postmeta  
	        WHERE post_id = '$post_id';";
	$result = $wpdb->query($sql);
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
//Checks if the post is already added to slider
function dboxlite_slider($post_id,$slider_id = '1') {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	$check = "SELECT id FROM $table_name WHERE post_id = '$post_id' AND slider_id = '$slider_id';";
	$result = $wpdb->query($check);
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_post_on_any_dboxlite_slider($post_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	$check = "SELECT post_id FROM $table_name WHERE post_id = '$post_id' LIMIT 1;";
	$result = $wpdb->query($check);
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_dboxlite_slider_on_slider_table($slider_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_TABLE;
	$check = "SELECT * FROM $table_name WHERE slider_id = '$slider_id' LIMIT 1;";
	$result = $wpdb->query($check);
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_dboxlite_slider_on_meta_table($slider_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_META;
	$check = "SELECT * FROM $table_name WHERE slider_id = '$slider_id' LIMIT 1;";
	$result = $wpdb->query($check);
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function is_dboxlite_slider_on_postmeta_table($slider_id) {
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_POST_META;
	$check = "SELECT * FROM $table_name WHERE slider_id = '$slider_id' LIMIT 1;";
	$result = $wpdb->query($check);
	if($result == 1) { return TRUE; }
	else { return FALSE; }
}
function get_dboxlite_slider_for_the_post($post_id) {
    global $wpdb, $table_prefix;
	$table_name = $table_prefix.DBOXLITE_SLIDER_POST_META;
	$sql = "SELECT slider_id FROM $table_name WHERE post_id = '$post_id' LIMIT 1;";
	$slider_postmeta = $wpdb->get_row($sql, ARRAY_A);
	$slider_id = $slider_postmeta['slider_id'];
	return $slider_id;
}
function dboxlite_slider_word_limiter( $text, $limit = 50 ) {
    $text = str_replace(']]>', ']]&gt;', $text);
	//Not using strip_tags as to accomodate the 'retain html tags' feature
	//$text = strip_tags($text);
	
    $explode = explode(' ',$text);
    $string  = '';

    $dots = '...';
    if(count($explode) <= $limit){
        $dots = '';
    }
    for($i=0;$i<$limit;$i++){
        if (isset ($explode[$i]))  $string .= $explode[$i]." ";
    }
    if ($dots) {
        $string = substr($string, 0, strlen($string));
    }
    return $string.$dots;
}
function dboxlite_sslider_admin_url( $query = array() ) {
	global $plugin_page;

	if ( ! isset( $query['page'] ) )
		$query['page'] = $plugin_page;

	$path = 'admin.php';

	if ( $query = build_query( $query ) )
		$path .= '?' . $query;

	$url = admin_url( $path );

	return esc_url_raw( $url );
}
function dboxlite_slider_table_exists($table, $db) { 
	$tables = mysql_list_tables ($db); 
	while (list ($temp) = mysql_fetch_array ($tables)) {
		if ($temp == $table) {
			return TRUE;
		}
	}
	return FALSE;
}
function dboxlite_get_google_font($font=''){
	$font_a=explode(',',$font,2);
	$font_b=explode(':',$font_a[0],2);
	$font=str_replace("+", " ", $font_b[0]);
	return $font;
}
function dboxlite_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}
//Added for video
function dboxlite_slide_video($id='0', $ytb='', $mp4='', $webm='', $ogg='', $wd='', $ht=''){
	$html_video='';
	if(!empty($ytb) || !empty($mp4) || !empty($webm) || !empty($ogg)){
		wp_enqueue_script('wp-mediaelement');
		wp_enqueue_style('wp-mediaelement');
		
		if(empty($ytb)){
                       $randint=rand(0,99);
                       $mp4=add_query_arg('i',$randint,$mp4);
                       $webm=add_query_arg('i',$randint,$webm);
                       $ogg=add_query_arg('i',$randint,$ogg);
               }

		if(is_admin()) $flashswf=includes_url( 'js/mediaelement/flashmediaelement.swf' );
		else $flashswf='flashmediaelement.swf';
		$html_video.='<video id="'.$id.'" width="'.$wd.'" height="'.$ht.'" controls="controls" preload="metadata" class="dboxlite_video">';
		            
		if( !empty($mp4))$html_video.='<source type="video/mp4" src="'.$mp4.'" />';
		if( !empty($webm))$html_video.='<source type="video/webm" src="'.$webm.'" />';
		if( !empty($ogg))$html_video.='<source type="video/ogg" src="'.$ogg.'" />';
		if(!empty($ytb)) $html_video.='<source type="video/youtube" src="'.$ytb.'" />';
		if(!empty($mp4)) $html_video.='<object width="'.$wd.'" height="'.$ht.'" type="application/x-shockwave-flash" data="'.includes_url( 'js/mediaelement/flashmediaelement.swf' ).'"><param name="movie" value="'.includes_url( 'js/mediaelement/flashmediaelement.swf' ).'" /><param name="flashvars" value="controls=true&file='.$mp4.'" /></object>';		
		$html_video.='</video><script>
		jQuery(document).ready(function($) {
			new MediaElementPlayer("#'.$id.'",{pauseOtherPlayers: true,flashName: "'.$flashswf.'", features: ["playpause"]});
		});
		</script>';
	}
	return $html_video;
}
?>
