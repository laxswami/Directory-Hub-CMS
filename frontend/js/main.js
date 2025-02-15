jQuery(document).ready(function($) {

	"use strict";

	var siteMenuClone = function() {

		$('.js-clone-nav').each(function() {
			var $this = $(this);
			$this.clone().attr('class', 'site-nav-wrap').appendTo('.site-mobile-menu-body');
		});


		setTimeout(function() {

		var counter = 0;

	  $('.site-mobile-menu .has-children').each(function(){
		var $this = $(this);

		$this.prepend('<span class="arrow-collapse collapsed">');

		$this.find('.arrow-collapse').attr({
		  'data-toggle' : 'collapse',
		  'data-target' : '#collapseItem' + counter,
		});

		$this.find('> ul').attr({
		  'class' : 'collapse',
		  'id' : 'collapseItem' + counter,
		});

		counter++;

	  });

	}, 1000);

		$('body').on('click', '.arrow-collapse', function(e) {
		  var $this = $(this);
		  if ( $this.closest('li').find('.collapse').hasClass('show') ) {
			$this.removeClass('active');
		  } else {
			$this.addClass('active');
		  }
		  e.preventDefault();

    });

		$(window).resize(function() {
			var $this = $(this),
				w = $this.width();

			if ( w > 768 ) {
				if ( $('body').hasClass('offcanvas-menu') ) {
					$('body').removeClass('offcanvas-menu');
				}
			}
		})

		$('body').on('click', '.js-menu-toggle', function(e) {
			var $this = $(this);
			e.preventDefault();

			if ( $('body').hasClass('offcanvas-menu') ) {
				$('body').removeClass('offcanvas-menu');
				$this.removeClass('active');
			} else {
				$('body').addClass('offcanvas-menu');
				$this.addClass('active');
			}
		})

		// click outisde offcanvas
		$(document).mouseup(function(e) {
	    var container = $(".site-mobile-menu");
	    if (!container.is(e.target) && container.has(e.target).length === 0) {
	      if ( $('body').hasClass('offcanvas-menu') ) {
					$('body').removeClass('offcanvas-menu');
				}
	    }
		});
	};
	siteMenuClone();

	var siteCarousel = function () {
		if ( $('.nonloop-block-13').length > 0 ) {
			$('.nonloop-block-13').owlCarousel({
		    center: false,
		    items: 1,
		    loop: true,
				stagePadding: 10,
		    margin: 30,
		    smartSpeed: 1000,
		    autoplay:true,
				autoplayTimeout:1000,
				autoplayHoverPause:false,

		    nav: true,
				navText: ['<span class="icon-keyboard_arrow_left">', '<span class="icon-keyboard_arrow_right">'],
		    responsive:{
	        600:{
	        	margin: 30,
	        	nav: true,
	          items: 2
	        },
	        1000:{
	        	margin: 30,
	        	nav: true,
	          items: 3
	        },
	        1200:{
	        	margin: 30,
	        	nav: true,
	          items: 4
	        }
		    }
			});
		}

		$('.slide-one-item').owlCarousel({
	    center: false,
	    items: 1,
	    loop: true,
			stagePadding: 0,
			smartSpeed: 1000,
	    margin: 0,
	    autoplay: true,
	    pauseOnHover: false,
	    nav: true,
	    navText: ['<span class="icon-keyboard_arrow_left">', '<span class="icon-keyboard_arrow_right">'],
	  });
	};
	siteCarousel();

	var siteStellar = function() {
		$(window).stellar({
	    responsive: false,
	    parallaxBackgrounds: true,
	    parallaxElements: true,
	    horizontalScrolling: false,
	    hideDistantElements: false,
	    scrollProperty: 'scroll'
	  });
	};
	siteStellar();

	/**
	 * The front-end form disable submit button UI
	 */
	$("form").on("submit", function () {
		$("form :submit").attr("disabled", true);
		return true;
	});

	/**
	 * Start initial rating stars for listing box elements
	 */
	/*
     * NOTE: You should listen for the event before calling `rateYo` on the element
     *       or use `onInit` option to achieve the same thing
     */
	$(".rating_stars").on("rateyo.init", function (e, data) {

		console.log(e.target.getAttribute('data-id'));
		console.log(e.target.getAttribute('data-rating'));
		console.log("RateYo initialized! with " + data.rating);

		var $rateYo = $("." + e.target.getAttribute('data-id')).rateYo();
		$rateYo.rateYo("rating", e.target.getAttribute('data-rating'));

		/* set the option `multiColor` to show Multi Color Rating */
		$rateYo.rateYo("option", "spacing", "2px");
		$rateYo.rateYo("option", "starWidth", "15px");
		$rateYo.rateYo("option", "readOnly", true);

	});

	$(".rating_stars").rateYo({
		spacing: "2px",
		starWidth: "15px",
		readOnly: true,
		rating: 0
	});
	/**
	 * End initial rating stars for listing box elements
	 */

	/**
	 * Start event for footer language selector modal
	 */
	$('#btn-language-selector').on('click', function(){

		$('#language-selector-modal').modal('show');
	});
	/**
	 * End event for footer language selector modal
	 */

});
