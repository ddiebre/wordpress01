/* Theme JS */
(function($) {
	"use strict";
	jQuery(document).on('mouseup',function (e) {
		var container = jQuery('.header-search .product-categories');
		var container_cart;
		if (!container.is(e.target) && container.has(e.target).length === 0 && !jQuery('.cate-toggler').is(e.target) ) { /* if the target of the click isn't the container nor a descendant of the container */
			if(jQuery('.header-search .product-categories').hasClass('open')) {
				jQuery('.header-search .product-categories').removeClass('open');
			}
		}
		container = jQuery('.atc-notice-wrapper');
		if (!container.is(e.target) && container.has(e.target).length === 0 ) {
			jQuery('.atc-notice-wrapper').fadeOut();
		}
		//hide search input if need
		container = jQuery('.searchform');
		if (!container.is(e.target) && container.has(e.target).length === 0 ) {
			jQuery(".ws").removeClass("show");
		}
	});
	//height body
	var heightbody = jQuery('.page-wrapper').height();
	if( heightbody < jQuery(window).height() ) {
	   	jQuery('body').addClass('small-body');
	} else {
	   	jQuery('body').removeClass('small-body');
	}
	//For categories menu
	var oldCateMenuH, realMMH, realCateMenuH;
	jQuery(document).ready(function(){
		//Mobile Sidebar
		jQuery('.close-sidebar').on("click",function(){
			jQuery('.sidebar-mobile').toggleClass('show');
		});
		jQuery('.open-sidebar').on("click",function(){
			jQuery('.sidebar-mobile').toggleClass('show');
		});
		//count wishlist
		jQuery(document).on( 'added_to_wishlist removed_from_wishlist', function(){
			var counter = jQuery('.wishlist-count');
			$.ajax({
				url: yith_wcwl_l10n.ajax_url,
				data: {
					action: 'yith_wcwl_update_wishlist_count'
				},
				dataType: 'json',
				success: function( data ){
					counter.html( data.count );
				},
				beforeSend: function(){
					counter.block();
				},
				complete: function(){
					counter.unblock();
				}
			});
		});
		// custom quantity
		jQuery (".progressBar" ).each(function() {
			var product_id = jQuery (this).data('pid');
			var product_qty = jQuery (this).data('pqty');
			var product_sold = jQuery (this).data('psold');
			var total = product_sold + product_qty;
			jQuery('.progressBar-'+product_id).find('div').width(Math.floor(product_qty/total*100) + "%");
		});
		// show/hide menu category home demo 
		if(makali_show_catmenu_home){
			jQuery('.categories-menu-wrapper').addClass('show-catemenu');
		} else{
			jQuery('.categories-menu-wrapper').removeClass('show-catemenu');
		}
		//Hide more button on default wp menu
		if(jQuery('.categories-menu-container').length > 0 || jQuery('.categories-menu > ul').length > 0){
			jQuery('.morelesscate').css('display', 'none');
			jQuery('.morelesscate').addClass('alwayshide');
		}
		//Hide button if number of menu is not bigger then number menu in options
		if(jQuery('#categories ul.mega_main_menu_ul > li.menu-item').length < makali_menu_number ){
			jQuery('.morelesscate').css('display', 'none');
			jQuery('.morelesscate').addClass('alwayshide');
			makali_menu_number = jQuery('#mega_main_menu ul.mega_main_menu_ul > li.menu-item').length ;
		}
		//init height
		oldCateMenuH = makali_menu_number * jQuery('#categories ul.mega_main_menu_ul > li.menu-item').outerHeight() + jQuery('.morelesscate').outerHeight();
		realMMH = jQuery('#categories ul.mega_main_menu_ul > li.menu-item').length * jQuery('#categories ul.mega_main_menu_ul > li.menu-item').outerHeight();
		realCateMenuH = realMMH + jQuery('.morelesscate').outerHeight() ;
		if(jQuery('#categories').css('display')=='block'){
			jQuery('.categories-menu').addClass('show');
			jQuery('.catemenu-inner').css('height', oldCateMenuH);
		} else {
			jQuery('.categories-menu').removeClass('show');
			jQuery('.catemenu-inner').css('height', realCateMenuH);
		}
		//init height on default wp menu
		if(jQuery('.categories-menu-container').length > 0 || jQuery('.categories-menu > ul').length > 0){
			oldCateMenuH = jQuery('ul.categories-menu > li.menu-item').length * jQuery('ul.categories-menu > li.menu-item').outerHeight();
			jQuery('.catemenu-inner').css('height', oldCateMenuH);
		}
		//For closed menu, have to re-calculate height of elements
		jQuery('.catemenu-toggler').on('click', function(){
			//for default wordpress menu (not selected menu location)
			if(jQuery('.categories-menu > ul').css('display')=='none'){
				jQuery('.categories-menu > ul').css('display', 'block');
			} else {
				jQuery('.categories-menu > ul').css('display', 'none');
			}
			// for default wordpress menu (selected menu location)
			if(jQuery('.categories-menu-container').css('display')=='none'){
				jQuery('.categories-menu').addClass('show');
				jQuery('.catemenu-inner').css({'height': 'auto'});
				jQuery('.categories-menu-container').css('display', 'block');
			} else {
				jQuery('.categories-menu').removeClass('show');
				jQuery('.catemenu-inner').css({'height': 0});
				jQuery('.categories-menu-container').css('display', 'none');
			}
			// normal
			if(jQuery('#categories').css('display')=='none'){
				jQuery('.categories-menu').addClass('show');
				jQuery('#categories').css('display', 'block');
				jQuery('#categories').animate({'opacity': 1}, 100, function(){
					if(!jQuery('.morelesscate').hasClass('alwayshide')){
						jQuery('.morelesscate').fadeIn('fast');
					}
					//update height
					oldCateMenuH = makali_menu_number * jQuery('#categories ul.mega_main_menu_ul > li.menu-item').outerHeight() + jQuery('.morelesscate').outerHeight();
					realMMH = jQuery('#categories ul.mega_main_menu_ul > li.menu-item').length * jQuery('#categories ul.mega_main_menu_ul > li').outerHeight();
					realCateMenuH = realMMH + jQuery('.morelesscate').outerHeight();
					if(jQuery('.morecate').css('display')=='none'){
						jQuery('.catemenu-inner').css({'height': realCateMenuH});
					} else {
						jQuery('.catemenu-inner').css({'height': oldCateMenuH});
					}
				});
			} else {
				jQuery('#categories').animate({'opacity': 0}, 200, function(){
					jQuery('.categories-menu').removeClass('show');
					jQuery('#categories').css('display', 'none');
					jQuery('.morelesscate').css('display', 'none');
					//update height
					oldCateMenuH = makali_menu_number * jQuery('#categories ul.mega_main_menu_ul > li.menu-item').outerHeight();
					realMMH = jQuery('#categories ul.mega_main_menu_ul > li.menu-item').length * jQuery('#categories ul.mega_main_menu_ul > li').outerHeight();
					realCateMenuH = realMMH + jQuery('.morelesscate').outerHeight();
					jQuery('.catemenu-inner').css({'height': oldCateMenuH});
				});
			}
		});
		//hide items out of height
		var catemidx = 1;
		jQuery('#categories ul.mega_main_menu_ul > li').each(function(){
			if( catemidx > makali_menu_number && jQuery('.morecate').css('display')!='none'){
				jQuery(this).css('display', 'none');
				jQuery(this).addClass('mhide');
			}
			catemidx++;
		});
		//More categories click
		jQuery('.morelesscate').on('click', function() {
			oldCateMenuH = makali_menu_number * jQuery('#categories ul.mega_main_menu_ul > li.menu-item').outerHeight() + jQuery('.morelesscate').outerHeight();
			realMMH = jQuery('#categories ul.mega_main_menu_ul > li.menu-item').length * jQuery('#categories ul.mega_main_menu_ul > li').outerHeight();
			realCateMenuH = realMMH + jQuery('.morelesscate').outerHeight();
			if(jQuery('.morecate').css('display')=='none'){ /* opened menu */
				jQuery('.catemenu-inner').animate({'height': oldCateMenuH}, function(){
					jQuery('.morecate').css('display', 'block');
					jQuery('.lesscate').css('display', 'none');
					jQuery('.mhide').css('display', 'none');
				});
			} else { /* closed menu */
				jQuery('.mhide').css('display', 'block');
				jQuery('.catemenu-inner').animate({'height': realCateMenuH}, function(){
					jQuery('.morecate').css('display', 'none');
					jQuery('.lesscate').css('display', 'block');
				});
			}
		});
		// Show/hide search input
		jQuery(".wsearchsubmit").on('click',function(){
			if(jQuery(".ws").width()==0){
				if(jQuery(".ws").hasClass("show")){
					jQuery(".ws").removeClass("show");
				} else {
					jQuery(".ws").addClass("show");
					return false;
				}
			}
		});
		// Search Top
		jQuery('.search-dropdown').on('mouseover', function() {
			jQuery(this).find('.searchform').stop(true, false).slideDown();
		});
		jQuery('.search-dropdown').on('mouseleave', function() {
			jQuery(this).find('.searchform').stop(true, false).slideUp();
		});
		// Language: add class to ul if it has children (to style border and box shadow)
		jQuery('.wpml-ls .wpml-ls-sub-menu li').parent().addClass('hasChild');
		// show/hide sub product categories
		var item_procat = '.product-categories > li';
		jQuery('.product-categories .current-cat-parent').addClass('hidesub');
		jQuery(item_procat).on('click', function(){
			if (jQuery(this).hasClass('hidesub')) {
                jQuery(this).removeClass('hidesub').find('.children').slideUp();
            } else {
				jQuery(item_procat).removeClass('hidesub').find('.children').slideUp();
				jQuery(this).addClass('hidesub').find('.children').slideDown();
			}
		});
		//Vertical dropdown menu 
		jQuery('.vmenu-toggler').on('mouseover', function() {
			jQuery(this).find('.vmenu-content').stop(true, false).slideDown();
		});
		jQuery('.vmenu-toggler').on('mouseleave', function() {
			jQuery(this).find('.vmenu-content').stop(true, false).slideUp();
		});
		//Horizontal dropdown menu
			//default, not selected locations
		jQuery('.horizontal-menu .nav-menu > ul').superfish({
			delay: 100,
			speed: 'fast'
		});
			//default, selected locations
		jQuery('.primary-menu-container ul.nav-menu').superfish({
			delay: 100,
			speed: 'fast'
		});
		//Mobile Menu
		var mobileMenuWrapper = jQuery('.mobile-menu-container');
		mobileMenuWrapper.find('.menu-item-has-children').each(function(){
			var linkItem = jQuery(this).find('a').first();
			linkItem.after('<i class="fa fa-angle-right"></i>');
		});
		//calculate the init height of menu
		var totalMenuLevelFirst = jQuery('.mobile-menu-container .nav-menu > li').length;
		var mobileMenuH = totalMenuLevelFirst*40 + 10; //40 is height of one item, 10 is padding-top + padding-bottom;
		jQuery('.mbmenu-toggler').on('click', function(){
			var mobileMenuHi = jQuery('div.nav-menu > ul').outerHeight();
			if(jQuery('.mobile-menu div.nav-menu').outerHeight() == 0) {
				jQuery('.mobile-menu div.nav-menu').animate({'height': mobileMenuHi}, 'fast');
			} else {
				jQuery('.mobile-menu div.nav-menu').animate({'height': 0}, 'fast');
			}
			if(mobileMenuWrapper.hasClass('open')) {
				mobileMenuWrapper.removeClass('open');
				mobileMenuWrapper.animate({'height': 0}, 'fast');
			} else {
				mobileMenuWrapper.addClass('open');
				mobileMenuWrapper.animate({'height': mobileMenuH}, 'fast');
			}
		});
			//set the height of all li.menu-item-has-children items
		jQuery('.mobile-menu-container li.menu-item-has-children').each(function(){
			jQuery(this).css({'height': 40, 'overflow': 'hidden'});
		});
			//process the parent items
		jQuery('.mobile-menu-container li.menu-item-has-children').each(function(){
			var parentLi = jQuery(this);
			var dropdownUl = parentLi.find('ul.sub-menu').first();
			parentLi.find('.fa').first().on('click', function(){
				//set height is auto for all parents dropdown
				parentLi.parents('li.menu-item-has-children').css('height', 'auto');
				//set height is auto for menu wrapper
				mobileMenuWrapper.css({'height': 'auto'});
				var dropdownUlheight = dropdownUl.outerHeight() + 40;
				if(parentLi.hasClass('opensubmenu')) {
					parentLi.removeClass('opensubmenu');
					parentLi.animate({'height': 40}, 'fast', function(){
						//calculate new height of menu wrapper
						mobileMenuH = mobileMenuWrapper.outerHeight();
					});
					parentLi.find('.fa').first().removeClass('fa-angle-down');
					parentLi.find('.fa').first().addClass(' fa-angle-right');
				} else {
					parentLi.addClass('opensubmenu');
					parentLi.animate({'height': dropdownUlheight}, 'fast', function(){
						//calculate new height of menu wrapper
						mobileMenuH = mobileMenuWrapper.outerHeight();
					});
					parentLi.find('.fa').first().addClass('fa-angle-down');
					parentLi.find('.fa').first().removeClass(' fa-angle-right');
				}
			});
		});
		//Mini Cart
		if(jQuery(window).width() > 1024){
			jQuery('.widget_shopping_cart').each(function(){
				jQuery(this).on('mouseover', function(){
					var mCartHeight = jQuery(this).find('.mini_cart_inner').outerHeight();
					var cCartHeight = jQuery(this).find('.mini_cart_content').outerHeight();
					if(cCartHeight < mCartHeight) {
						jQuery(this).find('.mini_cart_content').stop(true, false).animate({'height': mCartHeight});
					}
				});
				jQuery(this).on('mouseleave', function(){
					jQuery(this).find('.mini_cart_content').animate({'height':'0'});
				});
			});
		}
			//For tablet & mobile
		jQuery('.widget_shopping_cart').each(function(){
			jQuery(this).on('click', function(event){
				if(jQuery(window).width() < 1025){
					var closed = false;
					var mCartHeight = jQuery(this).find('.mini_cart_inner').outerHeight();
					var mCartToggler = jQuery(this).find('.cart-toggler');
					if(jQuery(this).find('.mini_cart_content').height() == 0 ) {
						closed = true;
					}
					if (mCartToggler.is(event.target) || mCartToggler.has(event.target).length != 0 || mCartToggler.is(event.target) ) {
						event.preventDefault();
						if(closed) {
							jQuery(this).find('.mini_cart_content').animate({'height': mCartHeight});
							closed = false;
						} else {
							jQuery(this).find('.mini_cart_content').animate({'height':'0'}, function(){
								closed = true;
							});
						}
					}
				}
			});
		});
		//Header Search by category
		var cateToggler = jQuery('.cate-toggler');
		jQuery('.header-search .product-categories').prepend('<li><a href="'+jQuery('.searchform').attr('action')+'">'+cateToggler.html()+'</a></li>');
		cateToggler.on('click', function(){
			jQuery('.header-search .product-categories').toggleClass('open');
		});
		/* Init values */
		var searchCat = ''; //category to search, set when click on a category
		var currentCat = RoadgetParameterByName( 'product_cat', jQuery('.header-search .product-categories .current-cat a').attr('href') ); /* when SEO off */
		var currentCatName = jQuery('.current-cat a').html();
		if(currentCatName!=''){
			cateToggler.html(currentCatName);
			//change form action when click submit
			jQuery('.wsearchsubmit').on('click', function(){
				if( searchCat==''){
					jQuery('.searchform').attr( 'action', jQuery('.header-search .product-categories .current-cat a').attr('href') );
				}
			});
		}
		if(currentCat!='') {
			/* when SEO off, we need product_cat */
			if( !(jQuery('#product_cat').length > 0) ) {
				jQuery('.searchform').append('<input type="hidden" id="product_cat" name="product_cat" value="'+currentCat+'" />');
			}
			jQuery('#product_cat').val(currentCat);
		}
		jQuery('.header-search .product-categories a').each(function(){
			jQuery(this).on('click', function(event){
				event.preventDefault();
				jQuery('.header-search .product-categories a.active').removeClass('active');
				jQuery(this).addClass('active');
				jQuery('.header-search .product-categories').removeClass('open');
				jQuery('.searchform').attr( 'action', jQuery(this).attr('href') );
				cateToggler.html(jQuery(this).html());
				searchCat = jQuery(this).attr('href');
				/* when SEO off, we need product_cat */
				if( !( jQuery('#product_cat').length > 0) && ( RoadgetParameterByName( 'product_cat', jQuery(this).attr('href') ) != '' ) ) {
					jQuery('.searchform').append('<input type="hidden" id="product_cat" name="product_cat" value="" />');
				}
				jQuery('#product_cat').val( RoadgetParameterByName( 'product_cat', jQuery(this).attr('href') ) );
			});
		});
		//add to cart callback
		jQuery('body').append('<div class="atc-notice-wrapper"><div class="atc-notice"></div><div class="close"><i class="fa fa-times-circle"></i></div></div>');
		jQuery('.atc-notice-wrapper .close').on('click', function(){
			jQuery('.atc-notice-wrapper').fadeOut();
			jQuery('.atc-notice').html('');
		});
		var ajaxPId = 0;
		jQuery('body').on( 'adding_to_cart', function(event, button, data) {
			ajaxPId = button.attr('data-product_id');
		});
		jQuery('body').on( 'added_to_cart', function(event, fragments, cart_hash) {
			//get product info by ajax
			jQuery.post(
				ajaxurl, 
				{
					'action': 'get_productinfo',
					'data':   {'pid': ajaxPId}
				},
				function(response){
					jQuery('.atc-notice').html(response);
					//show product info after added
					jQuery('.atc-notice-wrapper').fadeIn();
				}
			);
		});
		//Thumbnails click
		jQuery('li.yith_magnifier_thumbnail.first').addClass('active');
		jQuery.each( jQuery('li.yith_magnifier_thumbnail') , function() { 
			jQuery(this).on('click', function(){   
				jQuery('li.yith_magnifier_thumbnail').removeClass('active'); 
				jQuery(this).addClass('active'); 
			});
		});
		// Shop toolbar sort
		jQuery('.toolbar .orderby').chosen({disable_search: true, width: "auto"});
		jQuery('#lang_choice_1').chosen({disable_search: true, width: "auto"});
		//Image slider carousel
		jQuery('.image-slider').each(function(){ 
			var items_1201up    = parseInt(jQuery(this).attr('data-1201up'));
			var items_993_1200  = parseInt(jQuery(this).attr('data-993-1200'));
			var items_769_992   = parseInt(jQuery(this).attr('data-769-992'));
			var items_641_768   = parseInt(jQuery(this).attr('data-641-768'));
			var items_481_640   = parseInt(jQuery(this).attr('data-481-640'));
			var items_0_480     = parseInt(jQuery(this).attr('data-0-480'));
			var navigation      = true; 
			if (parseInt(jQuery(this).attr('data-navigation'))!==1)  {navigation = false} ;
			var pagination      = false; 
			if (parseInt(jQuery(this).attr('data-pagination'))==1)  {pagination = true} ;
			var item_margin     = parseInt(jQuery(this).attr('data-margin'));
			var auto            = false; 
			if (parseInt(jQuery(this).attr('data-auto'))==1)  {auto = true} ;
			var loop            = false; 
			if (parseInt(jQuery(this).attr('data-loop'))==1)  {loop = true} ;
			var speed           = parseInt(jQuery(this).attr('data-speed'));
			jQuery(this).addClass('owl-carousel owl-theme').owlCarousel({ 
				nav            : navigation, 
				dots           : pagination,
				margin         : item_margin,
				loop           : loop,
				autoplay       : auto,
				smartSpeed     : speed,
				addClassActive : false,
				responsiveClass: true,
				responsive     : {
					0: {
						items: items_0_480,
					},
					481: {
						items: items_481_640,
					},
					641: {
						items: items_641_768,
					},
					769: { 
						items: items_769_992,
					},
					993: { 
						items: items_993_1200,
					}, 
					1201: {
						items: items_1201up,
					},
				}
			});
		});
		jQuery('.brands-carousel').each(function(){ 
			var items_1201up    = parseInt(jQuery(this).attr('data-1201up'));
			var items_993_1200  = parseInt(jQuery(this).attr('data-993-1200'));
			var items_769_992   = parseInt(jQuery(this).attr('data-769-992'));
			var items_641_768   = parseInt(jQuery(this).attr('data-641-768'));
			var items_481_640   = parseInt(jQuery(this).attr('data-481-640'));
			var items_0_480     = parseInt(jQuery(this).attr('data-0-480'));
			var navigation      = true; 
			if (parseInt(jQuery(this).attr('data-navigation'))!==1)  {navigation = false} ;
			var pagination      = false; 
			if (parseInt(jQuery(this).attr('data-pagination'))==1)  {pagination = true} ;
			var item_margin     = parseInt(jQuery(this).attr('data-margin'));
			var auto            = false; 
			if (parseInt(jQuery(this).attr('data-auto'))==1)  {auto = true} ;
			var loop            = false; 
			if (parseInt(jQuery(this).attr('data-loop'))==1)  {loop = true} ;
			var speed           = parseInt(jQuery(this).attr('data-speed'));
			jQuery(this).addClass('owl-carousel owl-theme').owlCarousel({ 
				nav            : navigation, 
				dots           : pagination,
				margin         : item_margin,
				loop           : loop,
				autoplay       : auto,
				smartSpeed     : speed,
				addClassActive : false,
				responsiveClass: true,
				responsive     : {
					0: {
						items: items_0_480,
					},
					481: {
						items: items_481_640,
					},
					641: {
						items: items_641_768,
					},
					769: { 
						items: items_769_992,
					},
					993: { 
						items: items_993_1200,
					}, 
					1201: {
						items: items_1201up,
					},
				}
			});
		});
		//Brand logos carousel inner
		jQuery('.inner-brands .brands-carousel').each(function(){
			var plantmore_brandcols = jQuery(this).attr('data-col');
			jQuery(this).slick({
				infinite: true,
				slidesToShow: 5,
				slidesToScroll: plantmore_brandscrollnumber,
				speed: plantmore_brandanimate,
				easing: 'linear',
				dots: true,
				arrows: false,
				autoplay: plantmore_brandscroll,
				autoplaySpeed: plantmore_brandpause,
				responsive: [
					{
					  breakpoint: 1199,
					  settings: {
						slidesToShow: 4,
					  }
					},
					{
					  breakpoint: 767,
					  settings: {
						slidesToShow: 3,
					  }
					},
					{
					  breakpoint: 479,
					  settings: {
						slidesToShow: 1,
					  }
					}
				]
			});
		});
		//Countdown
		jQuery('.countdown').each(function(){
			var countTime = jQuery(this).attr('data-time');
			jQuery(this).countdown(countTime, function(event) {
				jQuery(this).html(
					'<div class="timebox day"><div class="timebox-inner">'+'<strong>'+event.strftime('%D')+'</strong>'+'<span>Days</span></div></div>'+'<div class="timebox hour"><div class="timebox-inner">'+'<strong>'+event.strftime('%H')+'</strong>'+'<span>Hours</span></div></div>'+'<div class="timebox minute"><div class="timebox-inner">'+'<strong>'+event.strftime('%M')+'</strong>'+'<span>Mins</span></div></div>'+'<div class="timebox second"><div class="timebox-inner">'+'<strong>'+event.strftime('%S')+'</strong>'+'<span>Secs</span></div></div>'
				);
			});
			// jQuery(this).countdown('stop');
		});
		jQuery('.countbox.hastime').each(function(){
			var countTime = jQuery(this).attr('data-time');
			jQuery(this).countdown(countTime, function(event) {
				jQuery(this).html(
					'<div class="timebox day"><div class="timebox-inner">'+'<strong>'+event.strftime('%D')+'</strong>'+'<span>Days</span></div></div>'+'<div class="timebox hour"><div class="timebox-inner">'+'<strong>'+event.strftime('%H')+'</strong>'+'<span>Hours</span></div></div>'+'<div class="timebox minute"><div class="timebox-inner">'+'<strong>'+event.strftime('%M')+'</strong>'+'<span>Mins</span></div></div>'+'<div class="timebox second"><div class="timebox-inner">'+'<strong>'+event.strftime('%S')+'</strong>'+'<span>Secs</span></div></div>'
				);
			});
			// jQuery(this).countdown('stop');
		});
		//Latest posts carousel
		jQuery('.posts-carousel.roadthemes-slider').each(function(){ 
			var items_1201up    = parseInt(jQuery(this).attr('data-1201up'));
			var items_993_1200  = parseInt(jQuery(this).attr('data-993-1200'));
			var items_769_992   = parseInt(jQuery(this).attr('data-769-992'));
			var items_641_768   = parseInt(jQuery(this).attr('data-641-768'));
			var items_481_640   = parseInt(jQuery(this).attr('data-481-640'));
			var items_0_480     = parseInt(jQuery(this).attr('data-0-480'));
			var navigation      = true; 
			if (parseInt(jQuery(this).attr('data-navigation'))!==1)  {navigation = false} ;
			var pagination      = false; 
			if (parseInt(jQuery(this).attr('data-pagination'))==1)  {pagination = true} ;
			var item_margin     = parseInt(jQuery(this).attr('data-margin'));
			var auto            = false; 
			if (parseInt(jQuery(this).attr('data-auto'))==1)  {auto = true} ;
			var loop            = false; 
			if (parseInt(jQuery(this).attr('data-loop'))==1)  {loop = true} ;
			var speed           = parseInt(jQuery(this).attr('data-speed'));
			jQuery(this).addClass('owl-carousel owl-theme').owlCarousel({ 
				nav            : navigation, 
				dots           : pagination,
				margin         : item_margin,
				loop           : loop,
				autoplay       : auto,
				smartSpeed     : speed,
				addClassActive : false,
				responsiveClass: true,
				responsive     : {
					0: {
						items: items_0_480,
					},
					481: {
						items: items_481_640,
					},
					641: {
						items: items_641_768,
					},
					769: { 
						items: items_769_992,
					},
					993: { 
						items: items_993_1200,
					}, 
					1201: {
						items: items_1201up,
					},
				}
			});
		});
		//Cross-sells Products carousel
		jQuery('.cross-sells .shop-products').addClass('owl-carousel').owlCarousel({
			items      : 4, 
			margin     : 20,
			nav        : true,
			dots       : false,
			responsive : {
				0: {
					items: 1,
				},
				360: {
					items: 2,
				},
				641: {
					items: 3,
				},
				769: { 
					items: 3,
				},
				993: { 
					items: 4,
				}, 
				1201: {
					items: 4,
				}
		  } 
		});
		//Image zoom
		jQuery('.zoom_in_marker').on('click', function(){
			jQuery.fancybox({
				href: jQuery('.woocommerce-main-image').attr('href'),
				openEffect  : 'elastic',
				closeEffect : 'elastic'
			});
		});
		//Upsells Products carousel
		jQuery('.upsells .shop-products').addClass('owl-carousel').owlCarousel({
			items      : 4, 
			margin     : 20,
			nav        : true,
			dots       : false,
			responsive : {
				0: {
					items: 1,
				},
				360: {
					items: 2,
				},
				641: {
					items: 3,
				},
				769: { 
					items: 3,
				},
				993: { 
					items: 4,
				}, 
				1201: {
					items: 4,
				}
			} 
		});
		//Related Products carousel
		jQuery('.related .shop-products').addClass('owl-carousel').owlCarousel({
			items      : 4, 
			margin     : 20,
			nav        : true,
			dots       : false,
			responsive : {
				0: {
					items: 1,
				},
				360: {
					items: 2,
				},
				641: {
					items: 3,
				},
				769: { 
					items: 3,
				},
				993: { 
					items: 4,
				}, 
				1201: {
					items: 4,
				}
			} 
		});
		// add class firstActiveItem and lastActiveItem in product carousel
		jQuery('.products-carousel.shop-products').each(function(){
			var total = jQuery(this).find('.owl-stage .owl-item.active').length;
			jQuery(this).find('.owl-stage .owl-item.active').each(function(index){
	            if (index === 0) {
	                jQuery(this).addClass('firstActiveItem');
	            }
	            if (index === total - 1) {
	                jQuery(this).addClass('lastActiveItem');
	            }
	        });
	        jQuery(this).on('translated.owl.carousel', function(event) {
	        	jQuery(this).find('.owl-stage .owl-item').removeClass('firstActiveItem lastActiveItem');
    			jQuery(this).find('.owl-stage .owl-item.active').each(function(index){
    	            if (index === 0) {
    	                jQuery(this).addClass('firstActiveItem');
    	            }
    	            if (index === total - 1) {
    	                jQuery(this).addClass('lastActiveItem');
    	            }
    	        });
	        });
		});
		//Category view mode
		jQuery('.view-mode').each(function(){
			jQuery(this).find('.grid').on('click', function(event){
				event.preventDefault();
				jQuery('.view-mode').find('.grid').addClass('active');
				jQuery('.view-mode').find('.list').removeClass('active');
				jQuery('#archive-product .shop-products').removeClass('list-view');
				jQuery('#archive-product .shop-products').addClass('grid-view');
				jQuery('#archive-product .list-col4').removeClass('col-xs-12 col-sm-4');
				jQuery('#archive-product .list-col8').removeClass('col-xs-12 col-sm-8');
			});
			jQuery(this).find('.list').on('click', function(event){
				event.preventDefault();
				jQuery('.view-mode').find('.list').addClass('active');
				jQuery('.view-mode').find('.grid').removeClass('active');
				jQuery('#archive-product .shop-products').addClass('list-view');
				jQuery('#archive-product .shop-products').removeClass('grid-view');
				jQuery('#archive-product .list-col4').addClass('col-xs-12 col-sm-4');
				jQuery('#archive-product .list-col8').addClass('col-xs-12 col-sm-8');
			});
		});
		//Tooltip
		jQuery('.yith-wcwl-add-to-wishlist a').each(function(){
			makalitip(jQuery(this), 'html');
		});
		var compareText = jQuery('.single-product-info .compare').html();
		jQuery('.single-product-info .compare').html('<span class="comparetip">'+compareText+'</span>');
		jQuery('.comparetip').each(function(){
			makalitip(jQuery(this), 'html');
		});
		jQuery('.compare-button a').each(function(){
			makalitip(jQuery(this), 'html');
		});
		jQuery('.add_to_cart_inline a').each(function(){
			// makalitip(jQuery(this), 'html');
		});
		jQuery('.quickviewbtn .quickview').each(function(){
			makalitip(jQuery(this), 'html');
		});
		jQuery('.sharefriend a').each(function(){
			makalitip(jQuery(this), 'html');
		});
		jQuery('.social-icons a').each(function(){
			makalitip(jQuery(this), 'title');
		});
		//Quickview
		jQuery('.product-wrapper').each(function(){
			jQuery(this).on('mouseover click', function(){
				jQuery(this).addClass('hover');
			});
			jQuery(this).on('mouseleave', function(){
				jQuery(this).removeClass('hover');
			});
		});
			//Add quick view box
		jQuery('body').append('<div class="quickview-wrapper"><span class="qvbtn qvprev"><i class="fa fa-caret-left"></i></span><span class="qvbtn qvnext"><i class="fa fa-caret-right"></i></span><div class="quick-modal"><span class="qvloading"></span><span class="closeqv"><i class="fa fa-times"></i></span><div id="quickview-content"></div><div class="clearfix"></div></div></div>');
			//quick view id array
			var arrIdx       = 0;
			var quickviewArr = Array();
			var nextArrID    = 0;
			var prevArrID    = 0;
		//show quick view
		jQuery('.quickview').each(function(){
			var quickviewLink = jQuery(this);
			var productID = quickviewLink.attr('data-quick-id');
			if(quickviewArr.indexOf(productID) == -1){
				quickviewArr[arrIdx] = productID;
				arrIdx++;
			}
			quickviewLink.on('click', function(event){
				event.preventDefault();
				prevArrID = quickviewArr[quickviewArr.indexOf(productID) - 1];
				nextArrID = quickviewArr[quickviewArr.indexOf(productID) + 1];
				jQuery('.qvprev').attr('data-quick-id', prevArrID);
				jQuery('.qvnext').attr('data-quick-id', nextArrID);
				showQuickView(productID, quickviewArr);
			});
		});
		jQuery('.qvprev').on('click', function(){
			showQuickView(jQuery(this).attr('data-quick-id'), quickviewArr);
		});
		jQuery('.qvnext').on('click', function(){
			showQuickView(jQuery(this).attr('data-quick-id'), quickviewArr);
		});
		jQuery('.closeqv').on('click', function(){
			hideQuickView();
		});
		//Fancy box
		jQuery(".fancybox").fancybox({
			openEffect  : 'elastic',
			closeEffect : 'fade',
			beforeShow: function () {
				if (this.title) {
					// New line
					this.title += '<div class="fancybox-social">';
					// Add tweet button
					this.title += '<a href="https://twitter.com/share" class="twitter-share-button" data-count="none" data-url="' + this.href + '">Tweet</a> ';
					// Add FaceBook like button
					this.title += '<iframe src="//www.facebook.com/plugins/like.php?href=' + this.href + '&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:23px;" allowTransparency="true"></iframe></div>';
				}
			},
			afterShow: function() {
				// Render tweet button
				twttr.widgets.load();
			},
			helpers:  {
				title : {
					type : 'inside'
				},
				overlay : {
					showEarly : false
				}
			}
		});
		//Fancy box for single project
		jQuery(".prfancybox").fancybox({
			openEffect  : 'fade',
			closeEffect : 'elastic',
			nextEffect  : 'fade',
			prevEffect  : 'fade',
			helpers:  {
				title : {
					type : 'inside'
				},
				overlay : {
					showEarly : false
				},
				buttons	: {},
				thumbs	: {
					width	: 100,
					height	: 100
				}
			}
		});
		//Counter up
		jQuery('.counter-number span').counterUp({
			delay: 10,
			time: 1000
		});
		//Go to top
		jQuery('#back-top').on('click', function(){
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		});
		//Landing tabs
		jQuery('.landing-link a').each(function(){
			var menulinkID = jQuery(this).attr('href');
			if(jQuery(menulinkID).length > 0){
				var targetOffset = jQuery(menulinkID).offset().top;
				jQuery(this).on('click', function(event){
					event.preventDefault();
					jQuery("html, body").animate({ scrollTop: targetOffset }, "slow");
				});
			}
		});
		//Sticky header
			//add a space for header
		if(makali_sticky_header==true && jQuery('.header-sticky').length > 0){
			var headerSpaceH = jQuery('.header-sticky').height();
			jQuery('.header-sticky').after('<div class="headerSpace" style="height: '+headerSpaceH+'px;"></div>');
		}
	});
	// Scroll
	var currentP = 0;
	var stickyOffset = 0;
	if(makali_sticky_header==true && jQuery('.header-sticky').length > 0){
		stickyOffset = jQuery('.header-sticky').offset().top;
		stickyOffset += jQuery('.header-sticky').outerHeight();
	}
	jQuery(window).scroll(function(){
		var headerH = jQuery('.header-container').height();
		var scrollP = jQuery(window).scrollTop();
		var headerAdb = jQuery('#wpadminbar').height();
		if(jQuery(window).width() <= 600){
			if(scrollP != currentP){
				if(scrollP >= headerAdb){
					jQuery('.admin-bar .sidebar-mobile').addClass('under600');
				} else {
					jQuery('.admin-bar .sidebar-mobile').removeClass('under600');
				}
				currentP = jQuery(window).scrollTop();
			}
		}
		if(jQuery(window).width() > 300){
			if(scrollP != currentP){
				//Back to top
				if(scrollP >= headerH){
					jQuery('#back-top').addClass('show');
				} else {
					jQuery('#back-top').removeClass('show');
				}
				//Sticky header
				if(makali_sticky_header==true && jQuery('.header-sticky').length > 0){
					if(scrollP >= stickyOffset){
						jQuery('#back-top').addClass('show');
						jQuery('.header-sticky').addClass('ontop show');
						jQuery('.headerSpace').addClass('show');
						jQuery('.logo-scroll').show();
						jQuery('.hide-scroll').hide();
					} 
					if(scrollP == 0 || scrollP < stickyOffset){
						jQuery('#back-top').removeClass('show');
						jQuery('.header-sticky').removeClass('ontop show');
						jQuery('.headerSpace').removeClass('show');
						jQuery('.hide-scroll').show();
						jQuery('.logo-scroll').hide();
					}
				}
				currentP = jQuery(window).scrollTop();
			}
		}
	});
	jQuery(window).load(function(){
		//Projects filter with shuffle.js
		jQuery('.list_projects #projects_list').shuffle( { itemSelector: '.project' });
		jQuery('.filter-options .btn').on('click', function() {
			var filterBtn = jQuery(this),
				isActive = filterBtn.hasClass( 'active' ),
				group = isActive ? 'all' : filterBtn.data('group');
			// Hide current label, show current label in title
			if ( !isActive ) {
				jQuery('.filter-options .active').removeClass('active');
			}
			filterBtn.toggleClass('active');
			// Filter elements
			jQuery('.list_projects #projects_list').shuffle( 'shuffle', group );
		});
	});
})(jQuery);
"use strict";
function RoadgetParameterByName(name, string) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(string);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
//Product tabs carousel
function roadtabCarousel(element, itemnumbers1, itemnumbers2, itemnumbers3, itemnumbers4, itemnumbers5) {
	jQuery(element).addClass('owl-carousel').owlCarousel({
		items      : itemnumbers1,
		margin     : 30,
		nav        : true,
		dots       : false,
		responsive : {
			0 : {
				items: itemnumbers5,
			},
			480 : {
				items: itemnumbers4,
			},
			768 : {
				items: itemnumbers3,
			},
			992 : {
				items: itemnumbers2,
			},
			1200 : {
				items: itemnumbers1,
			},
		} 
	});
}
//remove item from mini cart by ajax
function roadMiniCartRemove(url, itemid) {
	jQuery('.mini_cart_content').addClass('loading');
	jQuery('.cart-form').addClass('loading');
	jQuery.get( url, function(data,status){
		if(status=='success'){
			//update mini cart info
			jQuery.post(
				ajaxurl,
				{
					'action': 'get_cartinfo'
				}, 
				function(response){
					var cartinfo = response.split("|");
					var itemAmount = cartinfo[0];
					var cartTotal = cartinfo[1];
					var orderTotal = cartinfo[2];
					jQuery('.cart-quantity').html(itemAmount);
					jQuery('.cart-total .amount').html(cartTotal);
					jQuery('.total .amount').html(cartTotal);
					jQuery('.cart-subtotal .amount').html(cartTotal);
					jQuery('.order-total .amount').html(orderTotal);
				}
			);
			//remove item line from mini cart & cart page
			jQuery('#mcitem-' + itemid).animate({'height': '0', 'margin-bottom': '0', 'padding-bottom': '0', 'padding-top': '0'});
			setTimeout(function(){
				jQuery('#mcitem-' + itemid).remove();
				jQuery('#lcitem-' + itemid).remove();
				//set new height
				var mCartHeight = jQuery('.mini_cart_inner').outerHeight();
				jQuery('.mini_cart_content').animate({'height': mCartHeight});
			}, 1000);
			jQuery('.mini_cart_content').removeClass('loading');
			jQuery('.cart-form').removeClass('loading');
		}
	});
}
function makalitip(element, content) {
	if(content=='html'){
		var tipText = element.html();
	} else {
		var tipText = element.attr('title');
	}
	element.on('mouseover', function(){
		if(jQuery('.makalitip').length == 0) {
			element.before('<span class="makalitip" style="display: none;">'+tipText+'</span>');
			var tipWidth = jQuery('.makalitip').outerWidth();
			var tipPush = -(tipWidth/2 - element.outerWidth()/2);
			jQuery('.makalitip').css('margin-left', tipPush);
			jQuery('.makalitip').fadeIn();
		}
	});
	element.on('mouseleave', function(){
		jQuery('.makalitip').fadeOut();
		jQuery('.makalitip').remove();
	});
}
function showQuickView(productID, quickviewArr){
	//jQuery('#quickview-content').html(''); /*clear content*/
	//change id for next/prev buttons
	prevArrID = quickviewArr[quickviewArr.indexOf(productID) - 1];
	nextArrID = quickviewArr[quickviewArr.indexOf(productID) + 1];
	jQuery('.qvprev').attr('data-quick-id', prevArrID);
	jQuery('.qvnext').attr('data-quick-id', nextArrID);
	jQuery('body').addClass('quickview');
	window.setTimeout(function(){
		jQuery('.quickview-wrapper').addClass('open');
		jQuery('.qvloading').fadeIn();
		jQuery.post(
			ajaxurl, 
			{
				'action': 'product_quickview',
				'data':   productID
			}, 
			function(response){
				jQuery('#quickview-content').html(response);
				jQuery('.qvloading').fadeOut();
				/*variable product form*/
				jQuery( '.variations_form' ).wc_variation_form();
				jQuery( '.variations_form .variations select' ).change();
				/*thumbnails carousel*/
				jQuery('.quick-thumbnails').addClass('owl-carousel').owlCarousel({
					items: 4, 
					margin: 10,
					nav : false,
					dots : true,
				});
				/*thumbnail click*/
				jQuery('.quick-thumbnails a').each(function(){
					var quickThumb = jQuery(this);
					var quickImgSrc = quickThumb.attr('href');
					quickThumb.on('click', function(event){
						event.preventDefault();
						jQuery('.main-image').find('img').attr('src', quickImgSrc);
					});
				});
				/*review link click*/
				jQuery('.woocommerce-review-link').on('click', function(event){
					event.preventDefault();
					var reviewLink = jQuery('.see-all').attr('href');
					window.location.href = reviewLink + '#reviews';
				});
			}
		);
	}, 300);
}
function hideQuickView(){
	jQuery('.quickview-wrapper').removeClass('open');
	window.setTimeout(function(){
		jQuery('body').removeClass('quickview');
	}, 500);
}
// click to open menu (Home Watches)
function clickToOpenMenu(){
	jQuery('.btn-menu-header button').toggleClass('close');
	if(jQuery('.header-container .main-menu-wrapper:visible').length){
		jQuery('.header-container .main-menu-wrapper').hide();
	} else{
		jQuery('.header-container .main-menu-wrapper').show();
	};
}