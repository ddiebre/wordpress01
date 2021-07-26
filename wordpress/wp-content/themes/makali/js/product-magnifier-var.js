"use strict";
// product-magnifier var
var makali_magnifier_vars;
var yith_magnifier_options = {
		sliderOptions: {
			responsive: makali_magnifier_vars.responsive,
			circular: makali_magnifier_vars.circular,
			infinite: makali_magnifier_vars.infinite,
			direction: 'up',
			debug: false,
			auto: false,
			align: 'left',
			height: "auto", //turn vertical
			// width: 100,
			prev    : {
				button  : "#slider-prev",
				key     : "left"
			},
			next    : {
				button  : "#slider-next",
				key     : "right"
			},
			scroll : {
				items     : 1,
				pauseOnHover: true
			},
			items   : {
				visible: Number(makali_magnifier_vars.visible),
			},
			swipe : {
				onTouch:    true,
				onMouse:    true
			},
			mousewheel : {
				items: 1
			}
		},
		showTitle: false,
		zoomWidth: makali_magnifier_vars.zoomWidth,
		zoomHeight: makali_magnifier_vars.zoomHeight,
		position: makali_magnifier_vars.position,
		lensOpacity: makali_magnifier_vars.lensOpacity,
		softFocus: makali_magnifier_vars.softFocus,
		adjustY: 0,
		disableRightClick: false,
		phoneBehavior: makali_magnifier_vars.phoneBehavior,
		loadingLabel: makali_magnifier_vars.loadingLabel,
	};