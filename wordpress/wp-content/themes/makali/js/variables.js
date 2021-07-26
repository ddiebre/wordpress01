		"use strict";
		var makali_brandnumber = 6,
			makali_brandscrollnumber = 1,
			makali_brandpause = 3000,
			makali_brandanimate = 2000;
		var makali_brandscroll = false;
							makali_brandscroll = true;
					var makali_categoriesnumber = 6,
			makali_categoriesscrollnumber = 2,
			makali_categoriespause = 3000,
			makali_categoriesanimate = 700;
		var makali_categoriesscroll = 'false';
					var makali_blogpause = 3000,
			makali_bloganimate = 700;
		var makali_blogscroll = false;
					var makali_testipause = 2000,
			makali_testianimate = 300;
		var makali_testiscroll = false;
							makali_testiscroll = false;
					var makali_catenumber = 6,
			makali_catescrollnumber = 2,
			makali_catepause = 3000,
			makali_cateanimate = 700;
		var makali_catescroll = false;
					var makali_menu_number = 11;
		var makali_show_catmenu_home = 1;
		var makali_sticky_header = false;
							makali_sticky_header = true;
					jQuery(document).ready(function(){
			jQuery(".ws").on('focus', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".ws").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".wsearchsubmit").on('click', function(){
				if(jQuery("#ws").val()=="" || jQuery("#ws").val()==""){
					jQuery("#ws").focus();
					return false;
				}
			});
			jQuery(".search_input").on('focus', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".search_input").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".blogsearchsubmit").on('click', function(){
				if(jQuery("#search_input").val()=="" || jQuery("#search_input").val()==""){
					jQuery("#search_input").focus();
					return false;
				}
			});
		});
		