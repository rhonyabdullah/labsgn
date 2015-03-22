jQuery(function () {
  jQuery('.moreInfo').each(function () {
    // options
    var distance = 10;
    var time = 250;
    var hideDelay = 200;

    var hideDelayTimer = null;

    // tracker
    var beingShown = false;
    var shown = false;
    
    var trigger = jQuery('.trigger', this);
    var tooltip = jQuery('.tooltip', this).css('opacity', 0);
	
    // set the mouseover and mouseout on both element
    jQuery([trigger.get(0), tooltip.get(0)]).mouseover(function () {
      // stops the hide event if we move from the trigger to the tooltip element
      if (hideDelayTimer) clearTimeout(hideDelayTimer);

      // don't trigger the animation again if we're being shown, or already visible
      if (beingShown || shown) {
        return;
      } else {
        beingShown = true;

        // reset position of tooltip box
        tooltip.css({
          display: 'block' // brings the tooltip back in to view
        })

        // (we're using chaining on the tooltip) now animate it's opacity and position
        .animate({
          /*top: '-=' + distance + 'px',*/
          opacity: 1
        }, time, 'swing', function() {
          // once the animation is complete, set the tracker variables
          beingShown = false;
          shown = true;
        });
      }
    }).mouseout(function () {
      // reset the timer if we get fired again - avoids double animations
      if (hideDelayTimer) clearTimeout(hideDelayTimer);
      
      // store the timer so that it can be cleared in the mouseover if required
      hideDelayTimer = setTimeout(function () {
        hideDelayTimer = null;
        tooltip.animate({
          /*top: '-=' + distance + 'px',*/
          opacity: 0
        }, time, 'swing', function () {
          // once the animate is complete, set the tracker variables
          shown = false;
          // hide the tooltip entirely after the effect (opacity alone doesn't do the job)
          tooltip.css('display', 'none');
        });
      }, hideDelay);
    });
  });
	/* Added for validations of settings form - start v1.1*/
	jQuery('#dboxlite_slider_form').submit(function(event) { 
			//event.preventDefault();
			/* Added for validations - Start */			
			var slider_speed=jQuery("#dboxlite_slider_speed").val();
			if(slider_speed=='' || slider_speed <= 0 || isNaN(slider_speed)) {
				alert("Speed should be a number greater than 0!"); 
				jQuery("#dboxlite_slider_speed").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_speed').offset().top-50}, 600);
				return false;
			}	
			var slider_time=jQuery("#dboxlite_slider_time").val();
			if(slider_time=='' || slider_time <= 0 || isNaN(slider_time)) {
				alert("Transition interval should be a number greater than 0!"); 
				jQuery("#dboxlite_slider_time").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_time').offset().top-50}, 600);
				return false;
			}	
			var slider_no_posts=jQuery("#dboxlite_slider_no_posts").val();
			if(slider_no_posts=='' || slider_no_posts <= 0 || isNaN(slider_no_posts)) {
				alert("Max. Number of Posts in the DboxLite Slider should be greater than 0!"); 
				jQuery("#dboxlite_slider_no_posts").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_no_posts').offset().top-50}, 600);
				return false;
			}
			
			var slider_width=jQuery("#dboxlite_slider_width").val();
			if(slider_width=='' || slider_width <= 0 || isNaN(slider_width)) {
				alert("Max. Slider Width should be greater than 0"); 
				jQuery("#dboxlite_slider_width").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_width').offset().top-50}, 600);
				return false;
			}
			var slider_height=jQuery("#dboxlite_slider_height").val();
			if(slider_height=='' || slider_height <= 0 || isNaN(slider_height)) {
				alert("Max. Slider Height should be greater than 0"); 
				jQuery("#dboxlite_slider_height").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_height').offset().top-50}, 600);
				return false;
			}
			
			/* Added for validations - End */
			var slider_preview = jQuery("#dboxlite_slider_preview").val(),
			    slider_catslug=jQuery("#dboxlite_slider_catslug").val(),
			    set=jQuery("#set").val();
			  
			if(slider_preview == "1" && slider_catslug == ''){
				alert("Category slug should be mentioned whose posts you want to display in slider");
				jQuery("#dboxlite_slider_catslug").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_catslug').offset().top-50}, 600);
				return false;
			}
	
			var prev=jQuery("#dboxlite_slider_preview").val(),
			    hiddenpreview=jQuery("#hidden_preview").val(),
			    new_save=jQuery("#oldnew").val(),
			    hiddencatslug=jQuery("#hidden_category").val();
			    if(hiddenpreview != prev || new_save=='1' || slider_catslug != hiddencatslug ) jQuery('#dboxlitepopup').val("1");					
			else jQuery('#dboxlitepopup').val("0");	
//
			var slider_id = jQuery("#dboxlite_slider_id").val(),	
			    hiddensliderid=jQuery("#hidden_sliderid").val(),		
			    slider_catslug=jQuery("#dboxlite_slider_catslug").val(),
			    hiddencatslug=jQuery("#hidden_category").val(),
			    prev=jQuery("#dboxlite_slider_preview").val(),
			    hiddenpreview=jQuery("#hidden_preview").val(),
			    new_save=jQuery("#oldnew").val();
			if(prev=='1' && slider_catslug=='') {
				alert("Select the category whose posts you want to show!"); 
				jQuery("#dboxlite_slider_catslug").addClass('error');
				jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_catslug').offset().top-50}, 600);
				return false;
			}
			if(prev=='0') {
				if(slider_id=='' || isNaN(slider_id) || slider_id<=0){
					alert("Select the slider name!"); 
					jQuery("#dboxlite_slider_id").addClass('error');
					jQuery("html,body").animate({scrollTop:jQuery('#dboxlite_slider_id').offset().top-50}, 600);
					return false;
				}
			}
//

		});
/* Added for validations of settings form - end v1.1*/
});



/* Added for preview v1.1 */
function checkpreview(curr_preview){		
	if(curr_preview=='2')
		jQuery("#dboxlite_slider_form .form-table tr.dboxlite_slider_params").css("display","none");
	else if(curr_preview=='1'){
		jQuery("#dboxlite_slider_form .dboxlite_sid").css("display","none");
		jQuery("#dboxlite_slider_form .form-table tr.dboxlite_slider_params").css("display","table-row");
		jQuery("#dboxlite_slider_form .dboxlite_catslug").css("display","block");
	}
	else if(curr_preview=='0'){
		jQuery("#dboxlite_slider_form .dboxlite_catslug").css("display","none");
		jQuery("#dboxlite_slider_form .form-table tr.dboxlite_slider_params").css("display","table-row");
		jQuery("#dboxlite_slider_form .dboxlite_sid").css("display","block");
	}
}
/* Added for preview v1.1 */
	
