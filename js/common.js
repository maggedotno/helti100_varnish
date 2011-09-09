$.noConflict();
jQuery(document).ready(function($) {

	var data = { getBlocks: {} };

	// add placeholders
	$('.placeholder').each(function() {
		data.getBlocks[$(this).attr('id')] = $(this).attr('rel');
		// alert('requesting block ' + $(this).attr('rel') + ' for id ' + $(this).attr('id'));
	});

	// add current product
	if (typeof CURRENTPRODUCTID !== 'undefined' && CURRENTPRODUCTID) {
		data.currentProductId = CURRENTPRODUCTID;
	}

	// E.T. phone home
	$.get(
		AJAXHOME_URL,
		data,
		function (data) {
			// alert('processing ' + data.blocks);
			for(var id in data.blocks) {
				// alert('setting ' + id + ' (' + $('#' + id) + ')');
				$('#' + id).html(data.blocks[id]);
			}
			// alert('cookie: ' + data.sid);
			$.cookie('frontend', data.sid, { path: '/' });
		},
		'json'
	);

});
