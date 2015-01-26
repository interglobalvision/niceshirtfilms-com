function l(data) {
	console.log(data);
}

var largeImagesWidth = 2000;

var basicPosts = $('.basic-post');
var lazyThumbnails = $('.lazy-thumb');
var vimeoPlayer = $('#vimeo-player');
var loadVimeoLinks = $('.js-load-vimeo');

var hash = window.location.hash;

jQuery(document).ready(function() {

  if (hash) {
    vimeoId = hash.substr(1,hash.length);
    window.location.hash = '';
    loadVimeoPlayer(vimeoId);

    l('Load vimeo from hash: ' + vimeoId);
  }

  if (basicPosts.length) {
    basicPosts.each(function(index, item) {
      var background = $(item).data('background');
      $(item).css({
        'background-image': 'url('+background+')'
      });
    });
  }

  if (lazyThumbnails.length) {
    lazyThumbnails.each(function(index, item) {
      var thumb = $(item).data('thumb');
      var thumbLarge = $(item).data('thumb-large');
      if (ifLargeImages()) {
        $(item).attr('src', thumbLarge);
      } else {
        $(item).attr('src', thumb);
      }
    });
  }

  // NEWS POSTS

  $('.news-read-more').on('click', function(e) {
    e.preventDefault();
    $(this).find('.news-copy').slideToggle();
  });

  // DIRECTOR PAGE

  $('#director-bio-toggle').on('click', function(e) {
    $('#director-biography').slideToggle();
  });

  loadVimeoLinks.on('click', function(e) {
    e.preventDefault();
    loadVimeoPlayer($(this).data('vimeo-id'));
  })

});

  // TOGGLE SIDEBAR

  $('#sidebarBtn').on('click', function() {
    if ($('#sidebar').hasClass('open')) {
      $('#sidebar').css('transform','translateX(-100%)').removeClass('open');
    } else {
      $('#sidebar').css('transform','translateX(0%)').addClass('open');
    }
  })

function ifLargeImages() {
  if ($(window).width() > largeImagesWidth) {
    return true;
  } else {
    return false;
  }
}

function loadVimeoPlayer(vimeoId) {
  var ratio = $(this).data('video-ratio');
  if (typeof ratio == 'undefined') {
    ratio = 0.5625;
  }
  vimeoPlayer.html('<iframe src="//player.vimeo.com/video/'+vimeoId+'?autoplay=1&badge=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
    'padding-bottom': (ratio*100)+'%'
  });
}