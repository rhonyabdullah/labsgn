<?php 
function dboxlite_post_processor_default($posts, $dboxlite_slider_curr,$out_echo,$set,$data=array()){
	$skin='default';
	global $dboxlite_slider,$default_dboxlite_slider_settings;
	$dboxlite_slider_css = dboxlite_get_inline_css($set);
	$html = '';
	$dboxlite_sldr_j = $i = 0;
	
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
	//Timthumb
	$timthumb='0';
	if($dboxlite_slider_curr['timthumb']=='1'){
		$timthumb='1';
	}
	
	$slider_handle='';
	if ( is_array($data) and isset($data['slider_handle']) ) {
		$slider_handle=$data['slider_handle'];
	}
	
	foreach($posts as $post) {
		$id = $post_id = $post->ID;
		$post_title = get_post_meta($id, 'SlideTitle', true);
		if(empty($post_title)) {
			$post_title = stripslashes($post->post_title);
			$post_title = str_replace('"', '', $post_title);
		}
		//filter hook
		if (isset($post_id)) $post_title=apply_filters('dboxlite_post_title',$post_title,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
		$slider_content = $post->post_content;
		
		$dbox_sslider_nolink = get_post_meta($post_id,'dbox_sslider_nolink',true);
		
		$permalink = get_permalink($post_id);

		if($dbox_sslider_nolink=='1'){
		  $permalink='';
		}
		$dboxlite_sldr_j++;	
		
		
		
		$html .= '<li class="dboxlite_slideri">';
			
		if($dboxlite_slider_curr['show_content']=='1'){
			if ($dboxlite_slider_curr['content_from'] == "slider_content") {
				$slider_content = get_post_meta($post_id, 'slider_content', true);
			}
			if ($dboxlite_slider_curr['content_from'] == "excerpt") {
				$slider_content = $post->post_excerpt;
			}

			$slider_content = strip_shortcodes( $slider_content );

			$slider_content = stripslashes($slider_content);
			$slider_content = str_replace(']]>', ']]&gt;', $slider_content);
	
			$slider_content = str_replace("\n","<br />",$slider_content);
			$slider_content = strip_tags($slider_content, $dboxlite_slider_curr['allowable_tags']);
				
			$content_limit=$dboxlite_slider_curr['content_limit'];
			$content_chars=$dboxlite_slider_curr['content_chars'];
			if(empty($content_limit) && !empty($content_chars)){ 
				$slider_excerpt = substr($slider_content,0,$content_chars);
			}
			else{ 
				$slider_excerpt = dboxlite_slider_word_limiter( $slider_content, $limit = $content_limit);
			}
			if(!isset($slider_excerpt))$slider_excerpt='';
			//filter hook
			$slider_excerpt=apply_filters('dboxlite_slide_excerpt',$slider_excerpt,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
			$slider_excerpt='<span '.$dboxlite_slider_css['dboxlite_slider_span'].' class="dboxlite-content-span"> '.$slider_excerpt.'</span>';
		}
		else{
		    $slider_excerpt='';
		}
		//filter hook
		$slider_excerpt=apply_filters('dboxlite_slide_excerpt_html',$slider_excerpt,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
			
		//All images
		$dbox_media = get_post_meta($post_id,'dbox_media',true);
		if(!isset($dboxlite_slider_curr['img_pick'][0])) $dboxlite_slider_curr['img_pick'][0]='';
		if(!isset($dboxlite_slider_curr['img_pick'][2])) $dboxlite_slider_curr['img_pick'][2]='';
		if(!isset($dboxlite_slider_curr['img_pick'][3])) $dboxlite_slider_curr['img_pick'][3]='';
		if(!isset($dboxlite_slider_curr['img_pick'][5])) $dboxlite_slider_curr['img_pick'][5]='';
		
		if($dboxlite_slider_curr['img_pick'][0] == '1'){
		 $custom_key = array($dboxlite_slider_curr['img_pick'][1]);
		}
		else {
		 $custom_key = '';
		}
		
		if($dboxlite_slider_curr['img_pick'][2] == '1'){
		 $the_post_thumbnail = true;
		}
		else {
		 $the_post_thumbnail = false;
		}
		
		if($dboxlite_slider_curr['img_pick'][3] == '1'){
		 $attachment = true;
		 $order_of_image = $dboxlite_slider_curr['img_pick'][4];
		}
		else{
		 $attachment = false;
		 $order_of_image = '1';
		}
		
		if($dboxlite_slider_curr['img_pick'][5] == '1'){
			 $image_scan = true;
		}
		else {
			 $image_scan = false;
		}
		
		if($dboxlite_slider_curr['crop'] == '0'){
		 $extract_size = 'full';
		}
		elseif($dboxlite_slider_curr['crop'] == '1'){
		 $extract_size = 'large';
		}
		elseif($dboxlite_slider_curr['crop'] == '2'){
		 $extract_size = 'medium';
		}
		else{
		 $extract_size = 'thumbnail';
		}
		
		//Slide link anchor attributes
		$a_attr='';$imglink='';$a_attr_img='';
		$a_attr=get_post_meta($post_id,'dbox_link_attr',true);	
		if( empty($a_attr) and isset( $dboxlite_slider_curr['a_attr'] ) ) $a_attr=$dboxlite_slider_curr['a_attr'];
		$a_attr_img=$a_attr;
		$default_image=(isset($dboxlite_slider_curr['default_image']))?($dboxlite_slider_curr['default_image']):('false');
		$image_title_text=(isset($dboxlite_slider_curr['image_title_text']))?($dboxlite_slider_curr['image_title_text']):('0');
		if($dboxlite_slider_curr['timthumb']==1)$ht=$dboxlite_slider_curr['height'];
		else $ht=false;
		$img_args = array(
			'custom_key' => $custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => $extract_size,
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => $default_image,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'dboxlite_slider_thumbnail',
			'image_scan' => $image_scan,
			'height' => $ht,  // Change in height parameter for timthumb
			'echo' => false,
			'permalink' => $permalink,
			'timthumb'=>$timthumb,
			'style'=> $dboxlite_slider_css['dboxlite_slider_thumbnail'],
			'a_attr'=> $a_attr_img,
			'imglink'=>$imglink,
			'width'=>$dboxlite_slider_curr['width'],
			'image_title_text'=>$image_title_text
		);
			
//Added for video -Start
		$dboxlite_ytburl=get_post_meta($post_id, '_dbox_youtubeurl', true);
                $dboxlite_mpurl=get_post_meta($post_id, '_dbox_mp4url', true);
                $dboxlite_weburl=get_post_meta($post_id, '_dbox_webmurl', true);
                $dboxlite_ogurl=get_post_meta($post_id, '_dbox_oggurl', true);
		$dboxlite_vshortcode=get_post_meta($post_id, '_dbox_video_shortcode', true);
//added for video -End
		
		if( empty($dbox_media) or $dbox_media=='' or !($dbox_media) ) {  
			$dboxlite_large_image=dboxlite_sslider_get_the_image($img_args);
		}
		else{
			$dboxlite_large_image=$dbox_media;
		}
		//filter hook
	
		$dboxlite_large_image=apply_filters('dboxlite_large_image',$dboxlite_large_image,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css);
//Added for getting src of image	
		$html .= $dboxlite_large_image;	
		
		//Added for video -Start
		if(!empty($dboxlite_vshortcode)){
			$shortcode_html=do_shortcode($dboxlite_vshortcode);
			//die($vhtml);
			$html.='<div class="dboxlite_video">'.$shortcode_html.'</div>';
		}
		$vdohtml=dboxlite_slide_video($slider_handle.$dboxlite_sldr_j, $dboxlite_ytburl, $dboxlite_mpurl, $dboxlite_weburl, $dboxlite_ogurl, $dboxlite_slider_curr['width'], $dboxlite_slider_curr['height']);

			if(!empty($vdohtml))
				$html.=$vdohtml;		
			
		//added for video -End
		
		$cleardiv='<div class="sldr_clearlt"></div><div class="sldr_clearrt"></div>';
		$more='';
		if($dboxlite_slider_curr['show_content']=='1' && !empty($permalink)){
			$more_name=$dboxlite_slider_curr['more'];
			if($more_name and !empty($more_name) ){
				$more= '<span class="more"><a href="'.$permalink.'" '.$a_attr.'>'.$dboxlite_slider_curr['more'].'</a></span>';
			}
		}
		$dbox_meta='';
		if(!empty($more)) $dbox_meta='<span class="dboxlite-meta">'.$more.'</span>';
		
		if ($dboxlite_slider_curr['image_only'] == '1') { 
			$html .= $cleardiv.'</li>';
		}
		else {
			if($permalink!='') {
				$slide_title = '<h2 '.$dboxlite_slider_css['dboxlite_slider_h2'].'><a href="'.$permalink.'" '.$dboxlite_slider_css['dboxlite_slider_h2_a'].' '.$a_attr.'>'.$post_title.'</a></h2>';
				//filter hook
				$slide_title=apply_filters('dboxlite_slide_title_html',$slide_title,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css,$post_title);
				
				$html .= '<div class="textshow"></div><div class="dboxlite_text" '.$dboxlite_slider_css['dboxlite_text'].'> '.$slide_title.'<div class="dboxlite-content">'.$slider_excerpt.$dbox_meta.'</div><div class="texthide"></div>'.'
					</div>'.$cleardiv.'</li>'; 
			}
			else{
				$slide_title = '<h2 '.$dboxlite_slider_css['dboxlite_slider_h2'].'>'.$post_title.'</h2>';
				//filter hook
				$slide_title=apply_filters('dboxlite_slide_title_html',$slide_title,$post_id,$dboxlite_slider_curr,$dboxlite_slider_css,$post_title);
				$html .='<div class="textshow"></div><div class="dboxlite_text" '.$dboxlite_slider_css['dboxlite_text'].'> '.$slide_title.'<div class="dboxlite-content">'.$slider_excerpt.$dbox_meta.'</div>
					<div class="texthide"></div></div>'.$cleardiv.'</li>';    
			}
		}
	  $i++;
	}
	//filter hook
	$html=apply_filters('dboxlite_extract_html',$html,$dboxlite_sldr_j,$posts,$dboxlite_slider_curr);
	if($out_echo == '1') {
	   echo $html;
	}
	$r_array = array( $dboxlite_sldr_j, $html);
	$r_array=apply_filters('dboxlite_r_array',$r_array,$posts, $dboxlite_slider_curr,$set);
	return $r_array;	
}
function dboxlite_slider_get_default($slider_handle,$r_array,$dboxlite_slider_curr,$set,$echo='1',$data=array()){
	$skin='default';
	global $dboxlite_slider,$default_dboxlite_slider_settings;
	//die("Test: ".$r_array[0]);	
	$dboxlite_sldr_j = $r_array[0];
	
	$dboxlite_slider_css = dboxlite_get_inline_css($set);
	$script_before_dom=$slider_script= $html='';	
	
	foreach($default_dboxlite_slider_settings as $key=>$value){
		if(!isset($dboxlite_slider_curr[$key])) $dboxlite_slider_curr[$key]='';
	}
		
	if ($dboxlite_slider_curr['bg'] == '1') { $dboxlite_slideri_bg = "transparent";} else { $dboxlite_slideri_bg = $dboxlite_slider_curr['bg_color']; }
	wp_enqueue_script( 'modernizr', dboxlite_slider_plugin_url( 'js/modernizr.js' ),'', DBOXLITE_SLIDER_VER, false);
	wp_enqueue_script( 'dboxlite', dboxlite_slider_plugin_url( 'js/dboxlite.js' ),array('jquery'), DBOXLITE_SLIDER_VER, false); 
	wp_enqueue_script( 'jquery.touchwipe', dboxlite_slider_plugin_url( 'js/jquery.touchwipe.js' ),array('jquery'), DBOXLITE_SLIDER_VER, false);

// FOUC Code
	if(!isset($dboxlite_slider_curr['fouc']) or $dboxlite_slider_curr['fouc']=='' or $dboxlite_slider_curr['fouc']=='0' ){
			$fouc_dom='jQuery(".dboxlite_slider_fouc #'.$slider_handle.'").hide();';

			$fouc_ready='jQuery("html").addClass("dboxlite_slider_fouc");jQuery(document).ready(function() {
		   		jQuery(".dboxlite_slider_fouc #'.$slider_handle.'").show();

			});';
		}	
		else{
			$fouc_dom=$fouc_ready='';
		}
//FOUC code ends
		
	//for dboxlite navigation dots span
	$spn='';	
	for($s=0;$s<$dboxlite_sldr_j;$s++)
	{
	if($s==0) $spn.="<span class='dboxlite_nav_dot_current' ></span>";
	else $spn.="<span ></span>";
	}
	//for autoplay	
	if($dboxlite_slider_curr['disable_autoplay']=='1') $autoplay="false";
	else $autoplay="true";
	//Added for replacing hyphen by underscore in slide handle
	$script_before_dom='<script type="text/javascript">'.$fouc_ready.'</script>';	
	$slider_script='<script type="text/javascript">';
	
			$slider_script.='jQuery(document).ready(function($) {
				var dbox_slider = dboxlite_slider_init({"handle":"#dbox_slider","options":{"direction" : "'.$dboxlite_slider_curr['direction_rotation'].'","width":"'.$dboxlite_slider_curr['width'].'","height":"'.$dboxlite_slider_curr['height'].'","perspective" : "1200","cubes" : "'.$dboxlite_slider_curr['cc'].'","randomize" : false,"disperseFactor" : "0","timeFactor" : "150","speed" : "'.($dboxlite_slider_curr['speed']*100).'","autoplay" : '.$autoplay.',"interval": "'.($dboxlite_slider_curr['interval']*1000).'","fallbackeffect":"slideup"},"navbg":"'.$dboxlite_slider_curr['navarr_bgcolor'].'","navbghover":"'.$dboxlite_slider_curr['navarr_bgcolor_hover'].'","playbtcolor":"'.$dboxlite_slider_curr['playbt_color'].'","playbtcolorhover":"'.$dboxlite_slider_curr['playbt_color_hover'].'","dot_bg":"'.$dboxlite_slider_curr['navdot_color'].'","dot_fill":"'.$dboxlite_slider_curr['currnavdot_color'].'","dotsz":"'.$dboxlite_slider_curr['dotsize'].'","show_text_onhover":"'.$dboxlite_slider_curr['show_content_hover'].'","slider_width":"'.$dboxlite_slider_curr['width'].'","plugin_path":"'.dboxlite_slider_plugin_url().'","hide_content":"0"});
				dbox_slider.init();
			});';

	do_action('dboxlite_global_script',$slider_handle,$dboxlite_slider_curr);

	$slider_script.=$fouc_dom.'</script> <noscript><p><strong>'. $dboxlite_slider['noscript'] .'</strong></p></noscript>';
	$html.=$script_before_dom.'<div id="dbox_slider_wrap" class="dboxlite_slider dboxlite_slider_set '.$slider_handle.'_wrap" '.$dboxlite_slider_css['dboxlite_slider'].' tabindex="100">
		<ul id="dbox_slider" class="dboxlite_slider_handle">
			'.$r_array[1].'
		</ul>
		<div class="dboxlite_shadow"></div>

				<div class="dboxlite_nav_arrows">
					<a href="#" class="dboxlite_next" '.$dboxlite_slider_css['nav_next'].'>Next</a>
					<a href="#" class="dboxlite_prev" '.$dboxlite_slider_css['nav_prev'].'>Previous</a>
				</div>

				<div class="dboxlite_nav_dots" '.$dboxlite_slider_css['nav_dots'].'>'.$spn.'</div>
				<div class="dboxlite_nav_options" '.$dboxlite_slider_css['nav_options'].'>
					<span class="dboxlite_play" '.$dboxlite_slider_css['nav_play'].'>Play</span>
					<span class="dboxlite_pause" '.$dboxlite_slider_css['nav_pause'].'>Pause</span>
				</div>
		</div>'.$slider_script;
	$html=apply_filters('dboxlite_slider_html',$html,$r_array,$dboxlite_slider_curr,$set);
	if($echo == '1')  {echo $html;}
	else { return $html; }
}
