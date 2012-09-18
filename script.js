(function($) { 
	$(function() {

		lggrababadge = {

			params: {
				badges_visible:3,
				single_width:160
			},

			funcs: {
				get_badges_visible: function(){
					var w = $('.badgeScroller .wrapper').innerWidth();
					var s = $('.badgeScroller .wrapper ul li:first').outerWidth();
					var v = Math.floor( w / s);
					return v;
				},

				get_single_width: function(){
					var s = $('.badgeScroller .wrapper ul li:first').outerWidth();
					return s;
				},
				refresh_widget: function(){
					
					var ww = $(window).width();

					if (ww > 638) {

						$('.lggrababadge').css('width',638 );
						$('.badgeScroller').css('width',605 );
						$('.badgeScroller .wrapper').css('width',505 );
						$('.badgeScroller .wrapper ul li').css('width',170 );
						$('.badgeScroller .wrapper ul li img').css('width',146 );
						
						$('.badgeScroller .wrapper ul .shadow').css({visibility: "visible"}).filter(':not(:animated)').animate({
					    	opacity: .1
						}, 100);

					} else if (ww < 638 && ww > 265){


						$('.badgeScroller .wrapper ul .shadow').filter(':not(:animated)').animate({
					    	opacity: 0
						}, 100).css({opacity: 0.0, visibility: "hidden"});

						$('.lggrababadge').css('width',ww );
						$('.badgeScroller').css('width',ww );
						$('.badgeScroller .wrapper').css('width',(ww-100) );
						$('.badgeScroller .wrapper ul li').css('width',(.281*(ww)) );
						$('.badgeScroller .wrapper ul li img').css('width',(.241322*(ww)) );
						$('.badgeScroller .wrapper ul li img').css('height',(.2281*(ww)) );
						$('.badgeScroller .arrow').css('top',(60*(ww/850)) );
					}
					

		            lggrababadge.params.badges_visible = lggrababadge.funcs.get_badges_visible();
					lggrababadge.params.single_width = lggrababadge.funcs.get_single_width();
				
				}
			},

			init: function() {
				
				$.fn.badgeScroller = function () {

				    function repeat(str, num) {
				        return new Array( num + 1 ).join( str );
				    }
				  
				    return this.each(function () {
				        var $wrapper = $('> div', this).css('overflow', 'hidden'),
				            $slider = $wrapper.find('> ul'),
				            $items = $slider.find('> li'),
				            $single = $items.filter(':first'),
				            
				            //singleWidth = lggrababadge.params.single_width, 
				            //visible = Math.ceil($wrapper.innerWidth() / singleWidth),
				           // visible = lggrababadge.params.badges_visible,
				            currentPage = 1,
				            pages = Math.ceil($items.length / lggrababadge.params.badges_visible);     
				                
				        if (($items.length % lggrababadge.params.badges_visible) != 0) {
				            $slider.append(repeat('<li class="empty" />', lggrababadge.params.badges_visible - ($items.length % lggrababadge.params.badges_visible)));
				            $items = $slider.find('> li');
				        }

				        $items.filter(':first').before($items.slice(- lggrababadge.params.badges_visible).clone().addClass('cloned'));
				        $items.filter(':last').after($items.slice(0, lggrababadge.params.badges_visible).clone().addClass('cloned'));
				        $items = $slider.find('> li'); 
				        
				        $wrapper.scrollLeft(lggrababadge.params.single_width * lggrababadge.params.badges_visible);
				        
				        function gotoPage(page) {
				            var dir = page < currentPage ? -1 : 1,
				                n = Math.abs(currentPage - page),
				                left = lggrababadge.params.single_width * dir * lggrababadge.params.badges_visible * n;
				            
				            $wrapper.filter(':not(:animated)').animate({
				                scrollLeft : '+=' + left
				            }, 500, function () {
				                if (page == 0) {
				                    $wrapper.scrollLeft(lggrababadge.params.single_width * lggrababadge.params.badges_visible * pages);
				                    page = pages;
				                } else if (page > pages) {
				                    $wrapper.scrollLeft(lggrababadge.params.single_width * lggrababadge.params.badges_visible);
				                    page = 1;
				                } 

				                currentPage = page;
				            });                
				            
				            return false;
				        }
				        
				        $wrapper.after('<a class="arrow back w1">&lt;</a><a class="arrow forward w1">&gt;</a>');
				        
				        $('a.back', this).click(function () {
				            return gotoPage(currentPage - 1);                
				        });
				        
				        $('a.forward', this).click(function () {
				            return gotoPage(currentPage + 1);
				        });
				        
				        $(this).bind('goto', function (event, page) {
				            gotoPage(page);
				        });
	   			 	}); 
	   			 };

					
				this.funcs.refresh_widget();
				
				$(document).ready(function () {
					$('.badgeScroller').badgeScroller();

					$(window).resize(function() {
						lggrababadge.funcs.refresh_widget();
					});
					
					$(".badgeScroller ul li").mouseenter(function(){
						$(this).children(".badge").stop().animate({ opacity: .90 }, 200);
						}).mouseleave(function(){
						$(this).children(".badge").animate({opacity: 1 }, 150);
					});
					
					$(".badgeScroller ul li").mouseenter(function(){
						$(this).children(".shadow").stop().animate({ opacity: .16 }, 300);
						}).mouseleave(function(){
						$(this).children(".shadow").animate({opacity: .08 }, 100);
					});

					$(".badgeScroller .arrow").mouseenter(function(){
						$(this).stop().animate({ opacity: .75 }, 200);
						}).mouseleave(function(){
						$(this).animate({opacity: 1 }, 150);
					});


				});
			}
		}

		lggrababadge.init();

	});
})(jQuery);
