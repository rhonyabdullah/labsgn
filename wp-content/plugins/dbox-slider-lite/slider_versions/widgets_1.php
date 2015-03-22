<?php
if(!class_exists('Dbox_Slider_Simple_Widget')){
	class Dbox_Slider_Simple_Widget extends WP_Widget {
		function Dbox_Slider_Simple_Widget() {
			$widget_options = array('classname' => 'dbox_slider_wclass', 'description' => 'Insert Dbox Slider' );
			$this->WP_Widget('dbox_sslider_wid', 'Dbox Slider - Simple', $widget_options);
		}

		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
		    global $dbox_slider;
		
			$title = apply_filters( 'widget_title', $instance['title'] );
		
			echo $before_widget;
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title; 
			get_dbox_slider('','');
			echo $after_widget;
		}

		function update($new_instance, $old_instance) {
		    global $dbox_slider;
			$instance = $old_instance;
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;
		}

		function form($instance) {
		    global $dbox_slider;
		
			$instance = wp_parse_args( (array) $instance, array( 'title'=>'') );
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = '';
			}
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>	
				 		 
	<?php }
	}
	add_action( 'widgets_init', create_function('', 'return register_widget("Dbox_Slider_Simple_Widget");') );
}
//Category Widget
if(!class_exists('Dbox_Slider_Category_Widget')){
	class Dbox_Slider_Category_Widget extends WP_Widget {
		function Dbox_Slider_Category_Widget() {
			$widget_options = array('classname' => 'dbox_sliderc_wclass', 'description' => 'Dbox Category Slider' );
			$this->WP_Widget('dbox_ssliderc_wid', 'Dbox Slider - Category', $widget_options);
		}

		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
		    global $dbox_slider;
		
			$title = apply_filters( 'widget_title', $instance['title'] );
		
			echo $before_widget;
		
			$cat = empty($instance['cat']) ? '' : apply_filters('widget_cat', $instance['cat']);
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
			 get_dbox_slider_category($cat,'',$offset='0');
			echo $after_widget;
		}

		function update($new_instance, $old_instance) {
		    global $dbox_slider;
			$instance = $old_instance;
		
			$instance['cat'] = strip_tags($new_instance['cat']);
					
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

			return $instance;
		}

		function form($instance) {
		    global $dbox_slider;
		
			$instance = wp_parse_args( (array) $instance, array( 'cat' => '','title' => '' ) );
			$cat = strip_tags($instance['cat']);
			
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = '';
			}
			?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>	
			<?php
			
			$categories = get_categories();
			$scat_html='<option value="" selected >Select the Category</option>';
	 
			foreach ($categories as $category) { 
			 if($category->slug==$cat){$selected = 'selected';} else{$selected='';}
			 $scat_html =$scat_html.'<option value="'.$category->slug.'" '.$selected.'>'. $category->name .'</option>';
			} 
		?>
			  <p><label for="<?php echo $this->get_field_id('cat'); ?>">Select Category for Slider: <select class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>"><?php echo $scat_html;?></select></label></p>
	  
			 
	<?php }
	}
	add_action( 'widgets_init', create_function('', 'return register_widget("Dbox_Slider_Category_Widget");') );
}
//Recent Posts Widget
if(!class_exists('Dbox_Slider_Recent_Widget')){
	class Dbox_Slider_Recent_Widget extends WP_Widget {
		function Dbox_Slider_Recent_Widget() {
			$widget_options = array('classname' => 'dbox_sliderr_wclass', 'description' => 'Dbox Recent Posts Slider' );
			$this->WP_Widget('dbox_ssliderr_wid', 'Dbox Slider - Recent Posts', $widget_options);
		}

		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
		    global $dbox_slider;
		
			$title = apply_filters( 'widget_title', $instance['title'] );
		
			echo $before_widget;
			if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
			 get_dbox_slider_recent('',$offset='0');
			echo $after_widget;
		}

		function update($new_instance, $old_instance) {
		    global $dbox_slider;
			$instance = $old_instance;
		
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

			return $instance;
		}

		function form($instance) {
		    global $dbox_slider;
		
			$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
				
			if ( isset( $instance[ 'title' ] ) ) {
				$title = $instance[ 'title' ];
			}
			else {
				$title = '';
			}
			?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title;?>" /></label></p>
	  
	 <?php }
	}

	add_action( 'widgets_init', create_function('', 'return register_widget("Dbox_Slider_Recent_Widget");') );
}
?>
