function l(data) {
  console.log(data);
}

var largeImagesTriggerWidth = 700,

  basicAnimationSpeed = 600,
  fastAnimationSpeed = 200,

  scrollTimer,

  lazyHomeBackground = $('.js-lazy-home-background'),

  lazyThumbnails = $('.lazy-thumb'),
  lazyBackgrounds = $('.js-lazy-background'),

  directorShowreelVideos = $('.director-showreel-video'),
  directorShowreelLength = directorShowreelVideos.length,

  sidebar = $('#sidebar'),
  sidebarButton = $('#sidebar-toggle'),

  tagFilters = $('.filter-tag'),
  tagFilterTimeout,
  tagFilterTimeout2,
  directorArchiveVideos = $('.director-archive-video');

function ifLargeImages() {
  if ($(window).width() > largeImagesTriggerWidth) {
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
  $('.post-copy').slideUp(fastAnimationSpeed);
}

//   DIRECTOR SINGLE FUNCTIONS

// OVERLAY PLAYER
var overlayVimeoPlayer = {

  player: $('#video-overlay-player'),

  overlay: $('#video-overlay'),
  overlayInner: $('#video-overlay-inner'),

  overlayDirector: $('#video-overlay-director'),
  overlayTitle: $('#video-overlay-title'),
  overlayMiddot: $('#video-overlay-middot'),
  overlayBrand: $('#video-overlay-brand'),

  timeout: 0,

  load: function (postData, postIndex) {
    var ratio;
    if (postData.vimeoRatio === undefined) {
      ratio = 0.5625;
    } else {
      ratio = postData.vimeoRatio;
    }
    this.overlay.fadeIn(fastAnimationSpeed).data('now-playing', postIndex);
    this.player.html('<iframe id="overlay-vimeo-player-embed" src="//player.vimeo.com/video/' + postData.vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0&player_id=overlay-vimeo-player-embed" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
      'padding-top': (ratio * 100) + '%'
    });
    this.overlayDirector.html(postData.director);
    this.overlayTitle.html(postData.title);
    this.overlayBrand.html(postData.brand);

    // hide middot if title or brand is blank
    this.overlayMiddot.show();
    if (postData.title === '' || postData.brand === '') {
      this.overlayMiddot.hide();
    }

    // use fixHeight after animations
    // : could be improved significantly
    var that = this;
    this.timeout = setTimeout(function() {
      that.fixHeight();
    }, (basicAnimationSpeed + fastAnimationSpeed + 1));
    $(window).resize(function() {
      this.timeout = setTimeout(function() {
        that.fixHeight();
      }, 50);
    });

    var iframe = $('#overlay-vimeo-player-embed')[0],
      player = $f(iframe);
    player.addEvent('ready', function () {
      player.addEvent('finish', function () {
        this.playNext();
      });
    });
  },

  playNext: function () {
    var nowPlayingIndex = overlay.data('now-playing'),
      nextIndex = nowPlayingIndex + 1;
    if (directorShowreelLength > nextIndex) {
      this.overlayVimeoPlayer.load(directorShowreelVideos.eq(nextIndex).data(), nextIndex);
    } else {
      this.overlayVimeoPlayer.load(directorShowreelVideos.eq(0).data(), 0);
    }
  },

  playPrev: function () {
    var nowPlayingIndex = overlay.data('now-playing'),
      prevousIndex = nowPlayingIndex - 1;
    if (prevousIndex === -1) {
      this.overlayVimeoPlayer.load(directorShowreelVideos.eq(directorShowreelLength - 1).data(), directorShowreelLength - 1);
    } else {
      this.overlayVimeoPlayer.load(directorShowreelVideos.eq(prevousIndex).data(), prevousIndex);
    }
  },

  close: function () {
    this.overlay.fadeOut(fastAnimationSpeed).data('now-playing', '');
    this.player.html('');
  },

  fixHeight: function () {

    var windowHeight = $(window).height(),
      height = this.overlayInner.height();

    while (height > (windowHeight*0.95)) {
      this.overlayInner.width(this.overlayInner.width()*0.95);
      height = this.overlayInner.height();
    }
  }

};

// INLINE PLAYER
var inlineVimeoPlayer = {

  player : $('#vimeo-player'),

  load: function (archiveVideo) {

    var archiveVideoData = archiveVideo.data(),
      ratio = archiveVideoData.vimeoRatio;
    if (ratio === undefined) {
      ratio = 0.5625;
    }

    this.player.data('now-playing-id', archiveVideo[0].id).html('<iframe id="inline-vimeo-player-embed" src="//player.vimeo.com/video/' + archiveVideoData.vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0&player_id=inline-vimeo-player-embed" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
      'padding-top': (ratio * 100) + '%'
    });
    $('html').addClass('cinema-mode');
    $('#main-content').ScrollTo();

    var iframe = $('#inline-vimeo-player-embed')[0],
      player = $f(iframe);
    player.addEvent('ready', function () {
      player.addEvent('finish', function () {
        inlineVimeoPlayer.playNext();
      });
    });

  },

  playNext: function () {

    var nowPlayingId = this.player.data('now-playing-id'),
      currentPlaylist = $('.director-archive-video:visible'),
      currentPlaylistLength = currentPlaylist.length,
      nowPlayingIndex;

    currentPlaylist.each(function (index, element) {
      if (nowPlayingId === $(element)[0].id) {
        nowPlayingIndex = index;
      }
    });

    if (nowPlayingIndex === undefined || nowPlayingIndex === (currentPlaylistLength - 1)) {
      inlineVimeoPlayer.load($(currentPlaylist[0]));
    } else {
      inlineVimeoPlayer.load($(currentPlaylist[(nowPlayingIndex + 1)]));
    }

  },

  playPrev: function () {

    var nowPlayingId = this.player.data('now-playing-id'),
      currentPlaylist = $('.director-archive-video:visible'),
      currentPlaylistLength = currentPlaylist.length,
      nowPlayingIndex;

    currentPlaylist.each(function (index, element) {
      if (nowPlayingId === $(element)[0].id) {
        nowPlayingIndex = index;
      }
    });

    if (nowPlayingIndex === undefined) {
      inlineVimeoPlayer.load($(currentPlaylist[0]));
    } else if (nowPlayingIndex === 0) {
      inlineVimeoPlayer.load($(currentPlaylist[(currentPlaylistLength - 1)]));
    } else {
      inlineVimeoPlayer.load($(currentPlaylist[(nowPlayingIndex - 1)]));
    }

  },

  close: function () {
    $('html').removeClass('cinema-mode');
    this.player.html('').css({
      'padding-top': '0%'
    }).data('now-playing', '');
  }

};

// ROUTER

function router( page, hash ) {

  // Routes: Director Page
  if ( page === 'director') {
      $('.director-menu-item').removeClass('active');

    // play all showreel
    if ( hash === 'play-all' ) {

      overlayVimeoPlayer.load( $('.director-showreel-video').eq(0).data(), 0 );

    // load direct to showreel
    } else if ( hash.indexOf('showreel-') === 0) {

      var video = hash.substring(9),
        videoElement = $('.director-showreel-video[data-slug="' + video + '"]');

      if (videoElement) {
        overlayVimeoPlayer.load(videoElement.data(), videoElement.index());
      }

    // load archive & possible a specific video
    } else if ( hash.indexOf('video-') === 0 || hash === 'archive' ) {

      if ( $('#director-menu').attr('data-active') !== 'archive' ) {
        inlineVimeoPlayer.close();
        $('.director-section').slideUp(basicAnimationSpeed);
        $('#director-archive').slideDown(basicAnimationSpeed);
        $('#director-menu').attr('data-active', 'archive');
      } else if ( hash === 'archive' ) {
        inlineVimeoPlayer.close();
      }

      // #video-XXXX
      if ( hash.indexOf('video-') === 0 ) {
        inlineVimeoPlayer.load( $("#director-archive-" + hash ) );
        $('#vimeo-player').slideDown(basicAnimationSpeed);
        $('#director-menu').attr('data-active', 'archive');
      }

      $('#director-menu-archive').addClass('active');

    // #biography
    } else if ( hash === 'biography' ) {

      inlineVimeoPlayer.close();
      $('.director-section').slideUp(basicAnimationSpeed);
      $('#director-biography').slideDown(basicAnimationSpeed);
      $('#director-menu').attr('data-active', 'biography' );

      $('#director-menu-biography').addClass('active');

    } else {
      inlineVimeoPlayer.close();
      $('#director-menu').attr('data-active', '');
      $('.director-section').slideUp(basicAnimationSpeed);
      $('#director-showreel').slideDown(basicAnimationSpeed);
    }

   }
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
    icon: wp_variables.themeUrl + '/img/optimized/ns-mapmarker.png'
  });

  infowindow.open(map,marker);

  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });

  $('#map-canvas').height(($(window).height()*0.5));
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
      var background = $(item).data('background'),
        backgroundLarge = $(item).data('background-large');

      if (ifLargeImages()) {
        $(item).css({
          'background-image': 'url(' + backgroundLarge + ')'
        });
      } else {
        $(item).css({
          'background-image': 'url(' + background + ')'
        });
      }

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

    post.find('.post-copy').slideDown(fastAnimationSpeed);

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

    var post = $(this).parent()

    post.find('.post-copy').slideDown(fastAnimationSpeed);
    post.addClass('active');

    scrollTimer = setTimeout(function () {
      post.ScrollTo();
    }, (fastAnimationSpeed+10));

  });

  // DIRECTOR SINGLE

    // LOAD SHOWREEL VIDEOS IN OVERLAY

  $('.js-load-overlay-vimeo').on('click', function () {
    overlayVimeoPlayer.load($(this).data(), $(this).index());
  });

    // OVERLAY NAVS

  $('#video-overlay-close').on('click', function () {
    overlayVimeoPlayer.close();
  });

  $('#video-overlay-next').on('click', function () {
    overlayVimeoPlayer.playNext();
  });

  $('#video-overlay-previous').on('click', function () {
    overlayVimeoPlayer.playPrev();
  });

    // INLINE PLAYER NAVS

  $('#inline-player-next').on('click', function () {
    inlineVimeoPlayer.playNext();
  });

  $('#inline-player-previous').on('click', function () {
    inlineVimeoPlayer.playPrev();
  });

    // TAG FILTERS

      // FIX

  tagFilters.each(function(index, item) {
    var tag = $(this).data('tag-slug');
    if (tag != 'all') {
      if ($('.tag-'+tag).length === 0) {
        $(this).remove();
      }
    }
  });

    // FILTERING

  tagFilters.on('click', function () {
    tagFilters.removeClass('filter-tag-active');
    $(this).addClass('filter-tag-active');

    var tag = $(this).data('tag-slug');
    if (tag !== 'all') {
//       directorArchiveVideos.removeClass('active').filter('.tag-' + tag).addClass('active');

      clearTimeout(tagFilterTimeout);
      clearTimeout(tagFilterTimeout2);

      directorArchiveVideos.css({
        'transform': 'scale(0)'
      });
      tagFilterTimeout = setTimeout(function() {
        directorArchiveVideos.hide().filter('.tag-' + tag).show();

        tagFilterTimeout2 = setTimeout(function() {
          directorArchiveVideos.filter('.tag-' + tag).css({
            'transform': 'scale(1)'
          });
        }, fastAnimationSpeed);

      }, fastAnimationSpeed);

    } else {
//       directorArchiveVideos.addClass('active');

      clearTimeout(tagFilterTimeout);
      clearTimeout(tagFilterTimeout2);

      directorArchiveVideos.css({
        'transform': 'scale(0)'
      });
      tagFilterTimeout = setTimeout(function() {
        directorArchiveVideos.hide().show();

        tagFilterTimeout2 = setTimeout(function() {
          directorArchiveVideos.css({
            'transform': 'scale(1)'
          });
        }, fastAnimationSpeed);

      }, fastAnimationSpeed);

    }
  });

  // TOGGLE SIDEBAR

  sidebarButton.on('click', function () {
    if (sidebar.hasClass('open')) {
      sidebar.removeClass('open');
      // jQuery cant add/removeClass on SVG elements [wtf]
      sidebarButton.attr('class', 'u-pointer');
    } else {
      sidebar.addClass('open');
      sidebarButton.attr('class', 'u-pointer rotate');
    }
  });

  if ( $('body').hasClass('single-director') ) {
    // Router: on change
    window.onhashchange = function () {
      var hash = window.location.hash.replace("#",'');
      router( 'director', hash );
    };

    // Router: on load
    if ( window.location.hash ) {
      var hash = window.location.hash.replace("#",'');
      router( 'director', hash );
    }
  }

// END DOC READY

});