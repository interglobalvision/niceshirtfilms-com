function l(data) {
	console.log(data);
}

jQuery(document).ready(function() {

  if ($('.basic-post').length) {
    $('.basic-post').each(function(index, item) {
      var background = $(item).data('background');
      l(background);
      $(item).css({
        'background-image': 'url('+background+')'
      });
    });
  }

});