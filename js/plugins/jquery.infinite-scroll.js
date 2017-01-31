(function( $ ){
   $.fn.infiniteScroll = function(config, endReachFunction) {
   		var delay = (config.delay > 0) ? config.delay : 5000;
     	$(this).on('scroll', function() {
        	if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
         	   setTimeout(endReachFunction(),delay);
        	}
    	});
      return this;
   }; 

})( jQuery );