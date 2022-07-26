/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function($) {

  // Use this variable to set up the common and page specific functions. If you
  // rename this variable, you will also need to rename the namespace below.
  var Sage = {
    // All pages
    'common': {
      init: function() {
        // social share popup
        $("a.share").click(function (event) {
          event.preventDefault();
          window.open($(this).attr('href'), "", "width=600, height=400");
        });

        // slick-slider init
        $('.keybenefits .slick-carousel').slick({
          dots: false,
          arrows: false,
          slidesToShow: 4,
          slidesToScroll: 1,
          infinite: true,
          autoplay: true,
          cssEase: 'linear',
          responsive: [
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
              }
            }
          ]
        });

        /*
        (function ($) {
          var options = $.lazyLoadXT,
              bgAttr = options.bgAttr || 'data-bg';

          options.selector += ',[' + bgAttr + ']';

          $(document).on('lazyshow', function (e) {
            var $this = $(e.target),
                url = $this.attr(bgAttr);
            if (!!url) {
              $this
                  .css('background-image', "url('" + url + "')")
                  .removeAttr(bgAttr)
                  .triggerHandler('load');
            }
          });

        })(window.jQuery || window.Zepto || window.$);
        */
        function imagePreLoad(elem) {
          var downloadingImage = new Image();
          downloadingImage.onload = function(){
            elem.css("background-image", "url('" + this.src + "')").animate({
              opacity: 1
            }, 600).removeAttr('data-bg');
          };
          downloadingImage.src = elem.attr('data-bg');
          return false;
        }

        // slick-slider init
        $('.contact .slick-carousel').slick({
          dots: false,
          autoplay: true,
          cssEase: 'ease-out',
          easing: 'easeOutCirc',
          speed: 1000,
          autoplaySpeed: 6000
        });
        $('.gallery .slick-carousel').on('init', function (event, slick) {
          var $this = $(slick.$slides.get(0)).find('.img-inner[data-bg]');
          if ( $this.length > 0 ) {
            //console.log( 'found' );
            imagePreLoad($this);
          } else {
            //console.log( 'already changed' );
          }
        });
        $('.gallery .slick-carousel').on('beforeChange', function(event, slick, currentSlide, nextSlide){
          //$(slick.$slides.get(nextSlide)).lazyLoadXT({show: true});
          $(slick.$slides.get(currentSlide)).find('h3.subtitle').animate({
            opacity: 0
          }, 350 );
          //$(nextSlide).find('.img-inner[data-bg]').each(function () {
          var $this = $(slick.$slides.get(nextSlide)).find('.img-inner[data-bg]');
          if ( $this.length > 0 ) {
            //console.log( 'found' );
            imagePreLoad($this);
          } else {
            //console.log( 'already changed' );
          }
          //});
        });
        $('.gallery .slick-carousel').on('afterChange', function(event, slick, currentSlide, nextSlide){
          //console.log($(slick.$slides.get(nextSlide)));
          $(slick.$slides.get(currentSlide)).find('h3.subtitle').animate({
            opacity: 1
          }, 1000 );
        });
        //$('.gallery .slick-carousel .img-inner').first().imagesLoaded().always( { background: true }, function( instance ) {
            $('.gallery .slick-carousel').slick({
              dots: false,
              //autoplay: false,
              cssEase: 'ease-out',
              easing: 'easeOutCirc',
              speed: 1000,
              asNavFor: '.gallery-caption .slick-carousel',
              autoplaySpeed: 6000
            });
            $('.section.gallery').animate({
              opacity: 1
            }, 500);
        //});
        $('.gallery-caption .slick-carousel').slick({
          arrows: false,
          dots: false,
          //autoplay: false,
          cssEase: 'ease-out',
          easing: 'easeOutCirc',
          speed: 1000,
          asNavFor: '.gallery .slick-carousel',
          autoplaySpeed: 6000
        });
        if ( $('.section.gallery').length !== 0 ) {
          $(document).on('scroll', function() {
            if ( $(this).scrollTop() >= $('.section.gallery').position().top ) {
              //console.log('autoplay is on');
              $('.gallery .slick-carousel').slick('slickPlay');
              //$('.gallery-caption .slick-carousel').slick('slickPlay');
            }
          });
        }
        $(window).on('load', function () {
          //console.log('page is loaded');
          setTimeout(function () {
            //console.log('0.1s passed');
            $('.gallery .slick-carousel').find('.img-inner[data-bg]').each(function () {
              var $this = $(this);
              if ( $this.length > 0 ) {
                //console.log( 'found' );
                imagePreLoad($this);
              } else {
                //console.log( 'already changed' );
              }
            });
          }, 100);
        });

        // slide counter
        /*
        $('.gallery .slick-carousel').on('init reInit afterChange', function (event, slick, currentSlide, nextSlide){
          var i = (currentSlide ? currentSlide : 0) + 1;
          $('.gallery .counter').text(i + ' / ' + slick.slideCount);
        });
        */

        $('.contact .slick-carousel').on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
          var color = $(slick.$slides.get(currentSlide)).data('color');
          var section = $(this).closest('.section');
          section.animate({
            backgroundColor: color
          }, 1000 );
          section.find('textarea, input:not([class*=btn])').animate({
            backgroundColor: color
          }, 1000 );
        });

        $('.contact .slick-carousel').on('click', function (event) {
          $('.contact .slick-carousel').slick('slickPause');
          console.log('Contact slide paused.');
        });

        $('.wpcf7').on('wpcf7:submit', function(event){
          $('.contact .slick-carousel').slick('slickPause');
        });

        $('.wpcf7').on('wpcf7:spam spam:wpcf7 wpcf7:invalid invalid.wpcf7 wpcf7:mailfailed mailfailed.wpcf7', function(event){
          $('.contact .slick-carousel').slick('slickPause');
          $(this).find('form > *:not(.wpcf7-response-output)').hide(0).delay(5000).show(0);
          $(this).find('form .wpcf7-response-output').show(0).delay(5000).hide(0);
        });

        $('.wpcf7').on('wpcf7:mailsent mailsent.wpcf7', function(event){
          $('.contact .slick-carousel').slick('slickPause');
          $(this).find('form > *:not(.wpcf7-response-output)').hide(0);
          var response = $(this).find('form > .wpcf7-response-output');
          if ( response.find('.success').length ) {
          } else {
            response.prepend('<div class="success"><span class="icon icon-smile"></span><h3 class="text-uppercase text-primary">Herzlichen Dank für Deine Anfrage!</h3></div>');
          }
          response.show(0);
        });

        // öffnungszeiten toggle button
        $('a[href=#opening]').click(function (e) {
          e.preventDefault();
          $('#opening').toggleClass('in');
        });

        $('a[href=#newsletter]').click(function (e) {
          e.preventDefault();
          $('#newsletter').toggleClass('in');
        });

        // swap menu positions
        function menu_swap() {
          var first_nav = $('.nav-row:first-of-type > div');
          if (
              (Modernizr.mq('(min-width: 992px)') && first_nav.hasClass('menu-menu-2-container')) ||
              (Modernizr.mq('(max-width: 991px)') && first_nav.hasClass('menu-menu-1-container'))
          ) {
            $('.nav-row:nth-child(2)').each(function () {
              if (!$(this).text().match(/^\s*$/)) {
                $(this).insertBefore($(this).prev('.nav-row'));
              }
            });
          }
          $('header').animate({
            opacity: 1
          }, 350);
          $('.page.home .section.teaser').animate({
            opacity: 1
          }, 400);
         return true;
        }

        // add a class to the header if navbar open
        function navbar_helper() {
          if ( !$('.navbar-collapse').hasClass('in') ) {
            $('body > header').removeClass('opened');
          }
          if ( Modernizr.mq('(max-width: 991px)') ) {
            $('.navbar-toggle').on('click', function () {
              if ( !$('.navbar-collapse').hasClass('in') ) {
                $('body > header').addClass('opened');
              } else {
                $('body > header').removeClass('opened');
              }
            });
          }
        }

        // font size responsive helper
        $.fn.fontSize = function(sm, md) {
          var $this = this,
              size;
          var initialSize = parseInt($(this).css('fontSize'));
          $(window).on('load resize', function() {
            if ( Modernizr.mq('(max-width: 479px)') ) {
              size = sm/2;
            } else if ( Modernizr.mq('(min-width: 480px)') && Modernizr.mq('(max-width: 991px)') ) {
              size = ( initialSize < sm || initialSize > md ) ? sm : initialSize;
            } else if ( Modernizr.mq('(min-width: 992px)') ) {
              size = ( initialSize < md ) ? md : initialSize;
            } else {
              size = ( initialSize < (sm/2) ) ? sm/2 : initialSize;
            }
            $this.css('font-size', ( size <= 14 ? 14 : size ) + 'px');
          }).trigger('resize');
        };

        // that's a hell of an inconsistent font size setup by the mobile + desktop psd...
        $('.main h1, footer .section.top h1').fontSize(58, 58);
        $('#opening h2, .main h3:not(.post-title), footer .section.top h3, .main .trainer h2').fontSize(42, 32);
        $('.main h3.post-title').fontSize(36, 32);
        $('.main h4.subtitle, footer .section.top h4.subtitle').fontSize(36, 32);
        $('.main h4:not(.subtitle):not(.image-caption), footer .section.top h4:not(.subtitle)').fontSize(36, 24);
        $('#opening h4, .grid-item.trainer h4').fontSize(32, 20);
        $('.main li > h4').fontSize(32, 24);
        $('.main .btn, .main .trainer:not(.grid-item) h4, footer .section.top .btn').fontSize(30, 20);
        $('.main .club-prices h5').fontSize(28,20);
        $('article .post-excerpt').fontSize(24, 20);
        $('.main p, .main p > span, footer .section.top p, footer .section.top p > span, .main ul:not(.dropdown-menu) li').fontSize(24, 16);
        $('#opening p').fontSize(22, 16);
        $('article .entry-content').fontSize(20, 18);

        // IE object-fit helper
        if (!Modernizr.objectfit) {
          $('.gallery.section .image-container, .trainer.grid-item .image-container, .contact .image-container').each( function () {
            var $container = $(this),
                $imgSrc = $container.find('img').prop('src');
            if ($imgSrc) {
              $container.css('background-image', 'url(' + $imgSrc + ')').addClass('compat-object-fit');
            }
          });
        }

        // activate parent menu
        $('.current_page_parent.dropdown>a:first').click();

        // form inputs (textarea, input) inherits parent section's background color
        $('.contact textarea, .contact input:not([class*=btn])').each(function () {
          var $this = $(this);
          var color = $this.closest('.section').css('background-color');
          $this.css('background-color', color);
        });

        // load and resize event
        $(window).on('resize', function () {
          $('header').css('opacity', 0);
        });

        $(window).on('load resize', function () {
          menu_swap();
          navbar_helper();

          if ( Modernizr.mq('(min-width: 992px)') ) {
            $('body > header').removeClass('navbar-static-top').addClass('navbar-fixed-top');//
          } else {
            $('body > header').addClass('navbar-static-top').removeClass('navbar-fixed-top');
          }

          if ( Modernizr.mq('(min-width: 992px)') && $('article .post-featured').length !== 0 ) {
            $('article .post-excerpt').each(function () {
              if ( $(this).siblings('.post-featured').length !== 0 ) {
                $(this).css('min-height', $(this).siblings('.post-featured').outerHeight());
              }
            });
          } else {
            $('article .post-excerpt').css('min-height', 'initial');
          }

          // slick height helper
          var stHeight = $('.keybenefits .slick-track').height();
          $('.keybenefits .slick-slide').css('height',stHeight + 'px' );

          // ql modules helper
          var max_height;
          $('.ql .eq-height .image-container + div > h3').height('auto');
          if ( Modernizr.mq('(min-width: 992px)') ) {
            max_height = Math.max.apply(Math, $('.ql .eq-height .image-container + div > h3').map(function () {
              return $(this).outerHeight();
            }).get());
          } else {
            max_height = 'auto';
          }
          $('.ql .eq-height .image-container + div > h3').height(max_height);

          $('.ql .eq-height .image-container + div').height('auto');
          if ( Modernizr.mq('(min-width: 992px)') ) {
            max_height = Math.max.apply(Math, $('.ql .eq-height .image-container + div').map(function () {
              return $(this).outerHeight();
            }).get());
            max_height = max_height + 25;
          } else {
            max_height = 'auto';
          }
          $('.ql .eq-height .image-container + div').height(max_height);

        }).trigger('resize');

        $.fn.invisible = function() {
          return this.each(function() {
            $(this).css("visibility", "hidden");
          });
        };
        $.fn.visible = function() {
          return this.each(function() {
            $(this).css("visibility", "visible");
          });
        };

        $('footer .filters-button-group').on( 'click', 'li', function() {
          $( this ).parent().siblings('.selected').find('span:first-child').text($( this ).text());
        });

        $(window).on('load resize', function () {
          $('body:not(.home) .menu-menu-2-container .menu-item.dropdown').each(function () {
            var link = $(this);
            if (!link.hasClass('open') && !link.hasClass('active')) {
              var sublink = link.find('.dropdown-menu li:first-of-type a');
              if (Modernizr.mq('(min-width: 992px)')) {
                link.find('a').first().on('click', function (e) {
                  e.preventDefault();
                  window.location.href = sublink.attr('href');
                });
              }
            }
          });
          if (Modernizr.mq('(min-width: 992px)')) {
            $('#menu-menu-2 > .menu-item').hover(function () {
              $('#menu-menu-2 .dropdown .dropdown-menu').css('opacity', 0);
              if ( $(this).hasClass('dropdown') ) {
                $(this).find('.dropdown-menu').css('opacity', 1);
              }
            }, function() {
              $('#menu-menu-2 .dropdown .dropdown-menu').css('opacity', 0);
              $('#menu-menu-2 .dropdown.current_page_parent .dropdown-menu').css('opacity', 1);
            });
          }

        });
      },
      finalize: function() {
        // JavaScript to be fired on all pages, after page specific JS is fired
      }
    },
    // Home page
    'home': {
      init: function() {
        // JavaScript to be fired on the home page
        
        // seriously...not advised to touch this...
        $(window).on('load resize', function() {
          var counter = 112, dropdown_index = 0;
          var meh = [112, 312, 577, 888, 250];
          $('.menu-menu-2-container .menu-item').each(function (i) {
            var link = $(this), rel, position;
            rel = $(this).children('a').attr('rel');
            if (rel !== undefined && $('#' + rel) !== 0) {
              $('#'+rel).attr('data-menu-offset', (0 - $('body > header').outerHeight()));
            }
            if ( Modernizr.mq('(min-width: 992px)') ) {
              if (rel !== undefined && $('#' + rel) !== 0) {
                link.find('a').first().attr('href', '#'+rel);
              }
            } else if ( Modernizr.mq('(max-width: 991px)') ) {
              if (rel !== undefined && $('#' + rel) !== 0) {
                link.find('a').first().attr('href', '#');
              }
            }

            /*link.on('click', function () {
             if ( $(this).hasClass('open') ) {
             $(this).data('clicked', true);
             }
             });*/

            /*
            if ( !link.hasClass('dropdown') ) {
              link.on('click', function (e) {
                rel = $(this).children('a').attr('rel');
                if ( $('#'+rel) !== 0 ) {
                  //position = $('#'+rel).offset().top;
                  position = $('#'+rel);
                  e.preventDefault();
                  if ( Modernizr.mq('(min-width: 992px)') ) {
                    //position = position - $('body > header').outerHeight();
                    position = $('#'+rel);
                  }
                  if ( Modernizr.mq('(min-width: 992px)') ) {
                    skrollr.menu.click(position);
                     $('html, body').animate({
                     scrollTop: position
                     }, 1000);
                  }
                }
              });
            }*/

            /*
            link.on('show.bs.dropdown', function () {
              if ( Modernizr.mq('(min-width: 992px)') ) {
                $(this).data('clicked', true);
                //rel = $(this).children('a').attr('rel');
              }
            });
            */

            /*
            link.on('shown.bs.dropdown', function (e) {
              rel = $(this).children('a').attr('rel');
              //position = $('#'+rel).offset().top;
              position = $('#'+rel);
              if ( Modernizr.mq('(min-width: 992px)') ) {
                //position = position - $('body > header').outerHeight();
                //$('#'+rel).attr('data-menu-offset', (0 - $('body > header').outerHeight() - 280));

                 } else {
                 position = $('#'+rel).offset().top - $(this).find('.dropdown-menu').outerHeight();

              }
              if ( Modernizr.mq('(min-width: 992px)') && $(this).data('clicked') ) {
                skrollr.menu.click(link.find('a').first());

                 $('html, body').animate({
                 scrollTop: position
                 }, 1000);

                $(this).data('clicked', false);
              }

            });
        */
          });
        });


        $(window).on('load resize', function () {
          if( ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) ) {
            $('.section.teaser').imagesLoaded().always( { background: true }, function( instance ) {
              $('.section.teaser').animate({
                opacity: 1
              }, 500);
            });
          } else {
            var s = skrollr.init({
              forceHeight: false,
              smoothScrolling: false,
              render: function(data) {
                //Log the current scroll position.
                //console.log(data.curTop);
              }
            });
            if ( Modernizr.mq('(min-width: 992px)') ) {
              s = skrollr.init({
                forceHeight: false,
                smoothScrolling: false,
                render: function(data) {
                  //Log the current scroll position.
                  //console.log(data.curTop);
                }
              });
              skrollr.menu.init(s, {
                updateUrl: false
              });
            } else if ( Modernizr.mq('(max-width: 991px)') ) {
              s.destroy();
            }
            $('.section.teaser').each(function() {
              var $this = $(this);
              //$this.css('opacity', 1);
              $this.imagesLoaded( { background: true } ).progress( function( instance, image ) {
                //console.log(image.img.src + ' loaded');
                $this.css('background-image', 'initial');
                if ( image.isLoaded ) {
                  $this.css('background-image', 'url(' + image.img.src +')');
                  $this.animate({
                    opacity: 1
                  }, 500);
                }
              });
            });
          }
        });
      },
      finalize: function() {
        // JavaScript to be fired on the home page, after the init JS
      }
    },
    // Trainer page
    'page_template_template_trainer': {
      init: function() {
        // JavaScript to be fired on the trainer page
        var $grid = $('.grid');
        $grid.imagesLoaded( function(){
          $grid.isotope({
            itemSelector: '.grid-item',
            percentPosition: true,
            masonry: {
              // use outer width of grid-sizer for columnWidth
              columnWidth: '.grid-item'
            }
          });
        });

        var filterFns = {
          // show if number is greater than 50
          numberGreaterThan50: function() {
            var number = $(this).find('.number').text();
            return parseInt( number, 10 ) > 50;
          },
          // show if name ends with -ium
          ium: function() {
            var name = $(this).find('.name').text();
            return name.match( /ium$/ );
          }
        };

        $('.main .filters-button-group').on( 'click', 'li', function() {
          var filterValue = $( this ).attr('data-filter');
          // use filterFn if matches value
          filterValue = filterFns[ filterValue ] || filterValue;
          $grid.isotope({ filter: filterValue });
          $( this ).parent().siblings('.selected').find('span:first-child').text($( this ).text());
        });
      }
    },
    // Downloads page
    'page_template_template_list': {
      init: function() {
        // JavaScript to be fired on the downloads page
        var $grid = $('.grid');
        $grid.isotope({
          itemSelector: '.grid-item',
          percentPosition: true,
          masonry: {
            // use outer width of grid-sizer for columnWidth
            columnWidth: '.grid-item'
          }
        });

        var filterFns = {
          // show if number is greater than 50
          numberGreaterThan50: function() {
            var number = $(this).find('.number').text();
            return parseInt( number, 10 ) > 50;
          },
          // show if name ends with -ium
          ium: function() {
            var name = $(this).find('.name').text();
            return name.match( /ium$/ );
          }
        };

        $('.main .filters-button-group').on( 'click', 'li', function() {
          var filterValue = $( this ).attr('data-filter');
          // use filterFn if matches value
          filterValue = filterFns[ filterValue ] || filterValue;
          $grid.isotope({ filter: filterValue });
          $( this ).parent().siblings('.selected').find('span:first-child').text($( this ).text());
        });
      }
    },
    'single_post': {
      init: function () {
        $('.main a[href^="http"], .main a[href^="//"]').attr('target', function() {
          return (this.host === location.host) ? '_self' : '_blank';
        });
      }
    }
  };

  // The routing fires all common scripts, followed by the page specific scripts.
  // Add additional events for more control over timing e.g. a finalize event
  var UTIL = {
    fire: function(func, funcname, args) {
      var fire;
      var namespace = Sage;
      funcname = (funcname === undefined) ? 'init' : funcname;
      fire = func !== '';
      fire = fire && namespace[func];
      fire = fire && typeof namespace[func][funcname] === 'function';

      if (fire) {
        namespace[func][funcname](args);
      }
    },
    loadEvents: function() {
      // Fire common init JS
      UTIL.fire('common');

      // Fire page-specific init JS, and then finalize JS
      $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function(i, classnm) {
        UTIL.fire(classnm);
        UTIL.fire(classnm, 'finalize');
      });

      // Fire common finalize JS
      UTIL.fire('common', 'finalize');
    }
  };

  // Load Events
  $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
