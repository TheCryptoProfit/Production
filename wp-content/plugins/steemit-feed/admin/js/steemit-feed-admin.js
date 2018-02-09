(function( $ ) {
	'use strict';

	/**
	 * This enables you to define handlers, for when the DOM is ready:
	 */
	 
	 $(function() {
	 	
		/**
		 * Toggle visibility of source type
		 */
		/*var data_source_type = $('input[name="feed-items-type"]:checked').val();
		if (data_source_type == 'pd')
		{
			$('#steemitfeed-admin-metabox-data-source-dynamic').removeClass('hidden');	
		}	
		if (data_source_type == 'ps')
		{
			$('#steemitfeed-admin-metabox-data-source-specific').removeClass('hidden');	
		}
		
		$('input[name="feed-items-type"]').change(function() 
		{
			var data_source_type = $(this).val();
			if (data_source_type == 'pd')
			{
				$('#steemitfeed-admin-metabox-data-source-dynamic').removeClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-specific').addClass('hidden');	
			}
			if (data_source_type == 'ps')
			{
				$('#steemitfeed-admin-metabox-data-source-dynamic').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-specific').removeClass('hidden');	
			}
		});*/
		
		/**
		 * Toggle visibility of items type
		 */
		/*var items_type = $('select[name="feed-post-type"]').val();
		if (items_type == 'post')
		{
			$('#steemitfeed-admin-metabox-data-source-posts').removeClass('hidden');	
		}	
		if (items_type == 'attachment')
		{
			$('#steemitfeed-admin-metabox-data-source-media').removeClass('hidden');	
		}
		if (items_type == 'page')
		{
			$('#steemitfeed-admin-metabox-data-source-pages').removeClass('hidden');	
		}
		if (items_type == 'product')
		{
			$('#steemitfeed-admin-metabox-data-source-products').removeClass('hidden');	
		}
		
		$('select[name="feed-post-type"]').change(function() 
		{
			var items_type = $(this).val();
			if (items_type == 'post')
			{
				$('#steemitfeed-admin-metabox-data-source-posts').removeClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-media').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-pages').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-products').addClass('hidden');	
			}
			if (items_type == 'attachment')
			{
				$('#steemitfeed-admin-metabox-data-source-posts').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-media').removeClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-pages').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-products').addClass('hidden');	
			}
			if (items_type == 'page')
			{
				$('#steemitfeed-admin-metabox-data-source-posts').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-media').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-pages').removeClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-products').addClass('hidden');	
			}
			if (items_type == 'product')
			{
				$('#steemitfeed-admin-metabox-data-source-posts').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-media').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-pages').addClass('hidden');	
				$('#steemitfeed-admin-metabox-data-source-products').removeClass('hidden');	
			}
		});*/
		
		/**
		 * Masonry Grid radios
		 */
		checkGridRadio();
		 
		function checkGridRadio()
		{
			$('.grid-radio-input:checked').parents('.grid-radio').addClass('active');
			
			$('.grid-radio-input').change(function() {     
				$(this).parents('.form-table').find('.grid-radio').removeClass('active');
				var checked = $(this).attr('checked', true);
				if(checked){ 
					$(this).parents('.grid-radio').addClass('active');
				}
			});
		}
	 
	 });
	 
	 /**
	 * When the window is loaded:
	 */
	 
	 /*$( window ).load(function() {
	 
	 });*/

})( jQuery );
