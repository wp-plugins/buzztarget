jQuery(document).ready(function($) {
	$('div[id=bt-listing-thumbnails-container] a').click(function (e) {
		e.preventDefault();

		var img = $(this).children('img').attr('src');

		$('div[id=bt-listing-image] img')
		.attr('src', img)
		.parent()
		.find('div[id=bt-listing-image-enlarge-photo] a')
		.attr('href', img);
	});
});