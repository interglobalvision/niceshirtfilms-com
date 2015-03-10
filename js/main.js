function l(data) {
  console.log(data);
}

var largeImagesWidth = 1500,

  basicAnimationSpeed = 600,

  scrollTimer,

  lazyHomeBackground = $('.js-lazy-home-background'),

  lazyThumbnails = $('.lazy-thumb'),
  lazyBackgrounds = $('.js-lazy-background'),

  inlineVimeoPlayer = $('#vimeo-player'),

  overlay = $('#video-overlay'),
  overlayVimeoPlayer = $('#video-overlay-player'),
  overlayDirector = $('#video-overlay-director'),
  overlayTitle = $('#video-overlay-title'),
  overlayBrand = $('#video-overlay-brand'),

  directorShowreelVideos = $('.director-showreel-video'),
  directorShowreelLength = directorShowreelVideos.length,

  sidebar = $('#sidebar'),
  sidebarButton = $('#sidebar-toggle'),

  tagFilters = $('.filter-tag'),
  directorArchiveVideos = $('.director-archive-video'),

  pathArray = window.location.pathname.split( '/' );

function ifLargeImages() {
  if ($(window).width() > largeImagesWidth) {
    return true;
  }
  return false;
}

//   HOMEPAGE FUNCTIONS

function closeAllPosts() {
  $('.home-post').removeClass('active');
  $('.post-main').each(function (i, el) {
    el.style.height = '';
  });
  $('.home-video-player').html('');
}

//   DIRECTOR SINGLE FUNCTIONS

  // OVERLAY PLAYER

function loadOverlayVimeoPlayer(postData, postIndex) {
  var ratio;
  if (postData.vimeoRatio === undefined) {
    ratio = 0.5625;
  } else {
    ratio = postData.vimeoRatio;
  }
  overlay.show().data('now-playing', postIndex);
  overlayVimeoPlayer.html('<iframe id="overlay-vimeo-player-embed" src="//player.vimeo.com/video/' + postData.vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0&player_id=overlay-vimeo-player-embed" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
    'padding-top': (ratio * 100) + '%'
  });
  overlayDirector.html(postData.director);
  overlayTitle.html(postData.title);
  overlayBrand.html(postData.brand);
  var iframe = $('#overlay-vimeo-player-embed')[0],
    player = $f(iframe);
  player.addEvent('ready', function () {
    player.addEvent('finish', function () {
      overlayVimeoPlayerNext();
    });
  });
}

function overlayVimeoPlayerNext() {
  var nowPlayingIndex = overlay.data('now-playing'),
    nextIndex = nowPlayingIndex + 1;
  if (directorShowreelLength > nextIndex) {
    loadOverlayVimeoPlayer(directorShowreelVideos.eq(nextIndex).data(), nextIndex);
  } else {
    loadOverlayVimeoPlayer(directorShowreelVideos.eq(0).data(), 0);
  }
}

function overlayVimeoPlayerPrevious() {
  var nowPlayingIndex = overlay.data('now-playing'),
    prevousIndex = nowPlayingIndex - 1;
  if (prevousIndex === -1) {
    loadOverlayVimeoPlayer(directorShowreelVideos.eq(directorShowreelLength - 1).data(), directorShowreelLength - 1);
  } else {
    loadOverlayVimeoPlayer(directorShowreelVideos.eq(prevousIndex).data(), prevousIndex);
  }
}

function closeOverlayVimeoPlayer() {
  overlay.hide().data('now-playing', '');
  overlayVimeoPlayer.html('');
}

    // INLINE PLAYER

function loadInlineVimeoPlayer(archiveVideo) {

  var archiveVideoData = archiveVideo.data(),
    ratio = archiveVideoData.vimeoRatio;
  if (ratio === undefined) {
    ratio = 0.5625;
  }

  inlineVimeoPlayer.data('now-playing-id', archiveVideo[0].id).html('<iframe id="inline-vimeo-player-embed" src="//player.vimeo.com/video/' + archiveVideoData.vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0&player_id=inline-vimeo-player-embed" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
    'padding-top': (ratio * 100) + '%'
  });
  $('html').addClass('cinema-mode');
  $('#main-content').ScrollTo();

  var iframe = $('#inline-vimeo-player-embed')[0],
    player = $f(iframe);
  player.addEvent('ready', function () {
    player.addEvent('finish', function () {
      inlineVimeoPlayerNext();
    });
  });

}

function inlineVimeoPlayerNext() {

  var nowPlayingId = inlineVimeoPlayer.data('now-playing-id'),
    currentPlaylist = $('.director-archive-video:visible'),
    currentPlaylistLength = currentPlaylist.length,
    nowPlayingIndex;

  currentPlaylist.each(function (index, element) {
    if (nowPlayingId === $(element)[0].id) {
      nowPlayingIndex = index;
    }
  });

  if (nowPlayingIndex === undefined || nowPlayingIndex === (currentPlaylistLength - 1)) {
    loadInlineVimeoPlayer($(currentPlaylist[0]));
  } else {
    loadInlineVimeoPlayer($(currentPlaylist[(nowPlayingIndex + 1)]));
  }

}

function inlineVimeoPlayerPrevious() {

  var nowPlayingId = inlineVimeoPlayer.data('now-playing-id'),
    currentPlaylist = $('.director-archive-video:visible'),
    currentPlaylistLength = currentPlaylist.length,
    nowPlayingIndex;

  currentPlaylist.each(function (index, element) {
    if (nowPlayingId === $(element)[0].id) {
      nowPlayingIndex = index;
    }
  });

  if (nowPlayingIndex === undefined) {
    loadInlineVimeoPlayer($(currentPlaylist[0]));
  } else if (nowPlayingIndex === 0) {
    loadInlineVimeoPlayer($(currentPlaylist[(currentPlaylistLength - 1)]));
  } else {
    loadInlineVimeoPlayer($(currentPlaylist[(nowPlayingIndex - 1)]));
  }

}

function closeinlineVimeoPlayer() {
  $('html').removeClass('cinema-mode');
  inlineVimeoPlayer.html('').css({
    'padding-top': '0%'
  }).data('now-playing', '');
}

// ROUTER

function router( page, hash ) {

  // Routes: Director Page
//   if ( page === 'director') {

    if ( hash === 'play-all' ) {

      // #play-all
      loadOverlayVimeoPlayer( $('.director-showreel-video').eq(0).data(), 0 );

    } else if ( hash.indexOf('video-') === 0 || hash === 'archive' ) {

      if ( $('#director-menu').attr('data-active') !== 'archive' ) {
        closeinlineVimeoPlayer();
        $('.director-section').slideUp(basicAnimationSpeed);
        $('#director-archive').slideDown(basicAnimationSpeed);
        $('#director-menu').attr('data-active', 'archive');
      }

      // #video-XXXX
      if ( hash.indexOf('video-') === 0 ) {
        loadInlineVimeoPlayer( $("#director-archive-" + hash ) );
        $('#vimeo-player').slideDown(basicAnimationSpeed);
        $('#director-menu').attr('data-active', 'archive');
      }

    } else if ( hash === 'biography' ) {

      // #biography
      if ( hash === 'biography' ) {
        closeinlineVimeoPlayer();
        $('.director-section').slideUp(basicAnimationSpeed);
        $('#director-biography').slideDown(basicAnimationSpeed);
        $('#director-menu').attr('data-active', 'biography' );
      }

    } else {
      closeinlineVimeoPlayer();
      $('#director-menu').attr('data-active', '');
      $('.director-section').slideUp(basicAnimationSpeed);
      $('#director-showreel').slideDown(basicAnimationSpeed);
    }

//   }
}

// MAP EMBED
function initializeMap() {
  var myLatlng = new google.maps.LatLng(51.513748, -0.139104);

  var mapOptions = {
    center: myLatlng,
    zoom: 14,
    scrollwheel: false,
    styles: [{
          "featureType": "administrative",
          "elementType": "labels.text.fill",
          "stylers": [{
              "color": "#444444"
          }]
      }, {
          "featureType": "landscape",
          "elementType": "all",
          "stylers": [{
              "color": "#f2f2f2"
          }]
      }, {
          "featureType": "poi",
          "elementType": "all",
          "stylers": [{
              "visibility": "off"
          }]
      }, {
          "featureType": "road",
          "elementType": "all",
          "stylers": [{
              "saturation": -100
          }, {
              "lightness": 45
          }]
      }, {
          "featureType": "road.highway",
          "elementType": "all",
          "stylers": [{
              "visibility": "simplified"
          }]
      }, {
          "featureType": "road.arterial",
          "elementType": "labels.icon",
          "stylers": [{
              "visibility": "off"
          }]
      }, {
          "featureType": "transit",
          "elementType": "all",
          "stylers": [{
              "visibility": "off"
          }]
      }, {
          "featureType": "water",
          "elementType": "all",
          "stylers": [{
              "color": "#01bcec"
          }, {
              "visibility": "on"
          }]
      }]
  };
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var contentString = $('#map-copy').html();

  var infowindow = new google.maps.InfoWindow({
      content: contentString
  });

  var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    icon: ''
  });

  infowindow.open(map,marker);

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });

  $('#map-canvas').height(($(window).height()*0.6));
}

if ($('#map-canvas').length) {
  google.maps.event.addDomListener(window, 'load', initializeMap);
}

// LAYOUT FIXES

function layoutFixSearchInput() {

  var width = $('#search-form').width();
  var minus = $('#search-label').width();
  $('#search-input').width(width - minus - 20);

}

// DOC READY BRO

$(document).ready(function () {

  layoutFixSearchInput();

  if (lazyHomeBackground.length) {
    lazyHomeBackground.each(function (index, item) {
      var background = $(item).data('background');
      $(item).css({
        'background-image': 'url(' + background + ')'
      });
    });
  }

  if (lazyThumbnails.length) {
    lazyThumbnails.each(function (index, item) {
      var thumb = $(item).data('thumb'),
        thumbLarge = $(item).data('thumb-large');
      if (ifLargeImages()) {
        $(item).attr('src', thumbLarge);
      } else {
        $(item).attr('src', thumb);
      }
    });
  }

  if (lazyBackgrounds.length) {
    lazyBackgrounds.each(function (index, item) {
      var thumb = $(item).data('thumb'),
        thumbLarge = $(item).data('thumb-large');
      if (ifLargeImages()) {
        $(item).css({
          'background-image': 'url(' + thumbLarge + ')'
        });
      } else {
        $(item).css({
          'background-image': 'url(' + thumb + ')'
        });
      }
    });
  }

  // HOME POSTS CLOSE

  $('.post-copy-close').on('click', function (e) {
    e.preventDefault();
    $(this).parent('.post-copy').parent().removeClass('active');
    closeAllPosts();
  });

  // HOME VIDEO POSTS OPEN

  $('.home-video .post-main').on('click', function (e) {
    e.preventDefault();
    closeAllPosts();
    var post = $(this).parent(),
      postVimeoId = post.data('vimeo-id'),
      postVimeoRatio = post.data('video-ratio');
    if (postVimeoRatio === 0) {
      postVimeoRatio = 0.5625;
    }
    var postVimeoHeight = (post.width() * postVimeoRatio);
    post.addClass('active');
    post.find('.post-main').height(postVimeoHeight);
    post.find('.home-video-player').height(postVimeoHeight).html('<iframe id="home-vimeo-embed" width="100%" height="100%" src="//player.vimeo.com/video/' + postVimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0&player_id=home-vimeo-embed" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');

    var iframe = $('#home-vimeo-embed')[0],
      player = $f(iframe);
    player.addEvent('ready', function () {
      player.addEvent('finish', function () {
        post.find('.home-video-player').html('');
      });
    });

    scrollTimer = setTimeout(function () {
      post.ScrollTo();
    }, basicAnimationSpeed);

  });

  // HOME NEWS POSTS OPEN

  $('.news-read-more .post-main').on('click', function (e) {
    e.preventDefault();
    closeAllPosts();
    $(this).ScrollTo().parent().addClass('active');
  });

  // DIRECTOR SINGLE

    // TAG FILTER FIX

  $('.filter-tag').each(function(index, item) {
    var tag = $(this).data('tag-slug');
    if (tag != 'all') {
      if ($('.tag-'+tag).length === 0) {
        $(this).remove();
      }
    }
  });

    // LOAD SHOWREEL VIDEOS IN OVERLAY

  $('.js-load-overlay-vimeo').on('click', function () {
    loadOverlayVimeoPlayer($(this).data(), $(this).index());
  });

    // OVERLAY NAVS

  $('#video-overlay-close').on('click', function () {
    closeOverlayVimeoPlayer();
  });

  $('#video-overlay-next').on('click', function () {
    overlayVimeoPlayerNext();
  });

  $('#video-overlay-previous').on('click', function () {
    overlayVimeoPlayerPrevious();
  });

    // INLINE PLAYER NAVS

  $('#inline-player-next').on('click', function () {
    inlineVimeoPlayerNext();
  });

  $('#inline-player-previous').on('click', function () {
    inlineVimeoPlayerPrevious();
  });

    // TAG FILTERS

  tagFilters.on('click', function () {
    tagFilters.removeClass('filter-tag-active');
    $(this).addClass('filter-tag-active');

    var tag = $(this).data('tag-slug');
    if (tag !== 'all') {
      directorArchiveVideos.removeClass('active').filter('.tag-' + tag).addClass('active');
    } else {
      directorArchiveVideos.addClass('active');
    }
  });

  // TOGGLE SIDEBAR

  sidebarButton.on('click', function () {
    if (sidebar.hasClass('open')) {
      sidebar.removeClass('open');
//    jQuery cant add/removeClass on SVG elements [wtf]
      sidebarButton.attr('class', 'u-pointer');
    } else {
      sidebar.addClass('open');
      sidebarButton.attr('class', 'u-pointer rotate');
    }
  });

  // Router: on change
  window.onhashchange = function () {
    var hash = window.location.hash.replace("#",'');
    router( pathArray[1], hash );
  };

  // Router: on load
  if ( window.location.hash ) {
    var hash = window.location.hash.replace("#",'');
    router( pathArray[1], hash );
  }

// END DOC READY

});
