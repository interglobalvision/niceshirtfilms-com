/* jshint browser: true, devel: true, indent: 2, curly: true, eqeqeq: true, futurehostile: true, latedef: true, undef: true, unused: true */
/* global $, document, wp_variables, google, $f */

var largeImagesTriggerWidth = 700,
  extraLargeImagesTriggerWidth = 1100,

  basicAnimationSpeed = 600,
  fastAnimationSpeed = 200,

  scrollTimer,

  directorShowreelVideos = $('.director-showreel-video'),
  directorShowreelLength = directorShowreelVideos.length,

  directorStills = $('.still'),
  directorStillsLength = directorStills.length,


  sidebar = $('#sidebar'),
  sidebarButton = $('#sidebar-toggle'),

  tagFilters = $('.filter-tag'),
  tagFilterTimeout,
  tagFilterTimeout2,

  clickedMenuItem;

// GENERIC FUNCTIONS

function videoBackgrounds() {
  $('.home-news').each(function (index, item) {
    var container = $(item).find('.u-holder');
    var video = $(item).find('video');

    video.css('min-width', '');
    if( container.height() > video.height() ) {
      var newHeight = container.height() / video.height() * 103;
      video.css('min-width', newHeight + '%');
    }
  });
}

function lazyLoadBackgrounds() {
  $('.js-lazy-background').each(function (index, item) {
    if ($(window).width() > extraLargeImagesTriggerWidth) {
      $(item).css({
        'background-image': 'url(' + $(item).data('thumb-extra-large') + ')'
      });
    } else if ($(window).width() > largeImagesTriggerWidth) {
      $(item).css({
        'background-image': 'url(' + $(item).data('thumb-large') + ')'
      });
    } else {
      $(item).css({
        'background-image': 'url(' + $(item).data('thumb') + ')'
      });
    }
  });
}

function layoutFixSearchInput() {
  var width = $('#search-form').width();
  var minus = $('#search-label').width();
  $('#search-input').width(width - minus - 20);
}

//   HOMEPAGE FUNCTIONS

function closeAllPosts() {
  $('.home-post').removeClass('active');
  $('.post-main').each(function (i, el) {
    el.style.height = '';
  });
  $('.home-video-player').html('');
  $('.embed-holder').html('');
  $('.post-copy').slideUp(fastAnimationSpeed);
}

//  TEMPLATE INITS

function sidebarInit() {
  // TOGGLE SIDEBAR
  sidebarButton.on('click', function() {
    if (sidebar.hasClass('open')) {
      sidebar.removeClass('open');
      // jQuery cant add/removeClass on SVG elements [wtf]
      sidebarButton.attr('class', 'u-pointer');
    } else {
      sidebar.addClass('open');
      sidebarButton.attr('class', 'u-pointer rotate');
    }
  });
}

// POSTS INIT

function postsInit() {

  // HOME POSTS CLOSE
  $('.post-copy-close').on('click', function(e) {
    e.preventDefault();
    $(this).parent('.post-copy').parent().removeClass('active');
    closeAllPosts();
  });

  // HOME VIDEO POSTS OPEN
  $('.home-video .post-main').on('click', function(e) {
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

  // NEWS POSTS OPEN
  $('.news-read-more .post-main').on('click', function(e) {
    e.preventDefault();
    closeAllPosts();

    var $post = $(this).parent();

    $post.find('.post-copy').slideDown(fastAnimationSpeed, function() {
      $post.addClass('active');

      scrollTimer = setTimeout(function () {
        $post.ScrollTo();
      }, (fastAnimationSpeed + 10));
    });

    if ($post.data('embed')) {
      var embed = JSON.parse($post.data('embed'));

      $post.find('.embed-holder').html(embed);
    }
  });
}

//   DIRECTOR SINGLE FUNCTIONS

function directorInit() {

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

    // LOAD SHOWREEL VIDEOS IN OVERLAY

  $('.js-load-overlay-vimeo').on('click', function () {
    overlayVimeoPlayer.load($(this).data(), $(this).index());
  });

    // LOAD STILLS IN OVERLAY

  $('.js-load-overlay-image').on('click', function () {
    overlayImage.load($(this).children('.full-still').clone(), $(this).data(), $(this).index());
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

  $('#image-overlay-close').on('click', function () {
    overlayImage.close();
  });

  $('#image-overlay-next, #image-overlay-viewer').on('click', function () {
    overlayImage.next();
  });

  $('#image-overlay-previous').on('click', function () {
    overlayImage.prev();
  });


    // INLINE PLAYER NAVS

  $('#inline-player-next').on('click', function () {
    inlineVimeoPlayer.playNext();
  });

  $('#inline-player-previous').on('click', function () {
    inlineVimeoPlayer.playPrev();
  });

}
// OVERLAY IMAGE
var overlayImage = {

  viewer: $('#image-overlay-viewer'),

  overlay: $('#image-overlay'),
  overlayInner: $('#image-overlay-inner'),

  overlayDirector: $('#image-overlay-director'),
  overlayTitle: $('#image-overlay-title'),

  timeout: 0,

  load: function(image, postData, postIndex) {
    var _this = this;

    image.attr('max-width', '100%').removeAttr('width').removeAttr('height');

    _this.overlay.fadeIn(fastAnimationSpeed).data('now-playing', postIndex);
    _this.viewer.html(image);

    _this.overlayDirector.html(postData.director);

    if( postData.caption.length !== 0 ) {
      _this.overlayTitle.html(postData.caption);
    } else {
      _this.overlayTitle.html('&nbsp');
    }

    // use fixHeight after animations
      _this.fixHeight();

    $(window).resize(function() {
      _this.fixHeight();
    });


  },

  next: function () {
    var nowPlayingIndex = this.overlay.data('now-playing'),
      nextIndex = nowPlayingIndex + 1;
    if (directorStillsLength > nextIndex) {
      var image = directorStills.eq(nextIndex).children('.full-still').clone();
      this.load(image, directorStills.eq(nextIndex).data(), nextIndex);
    } else {
      var image = directorStills.eq(0).children('.full-still').clone();
      this.load(image, directorStills.eq(0).data(), 0);
    }
  },

  prev: function () {
    var nowPlayingIndex = this.overlay.data('now-playing'),
      prevousIndex = nowPlayingIndex - 1;
    if (prevousIndex === -1) {
      var image = directorStills.eq(directorStillsLength - 1).children('.full-still').clone();
      this.load(image, directorStills.eq(directorStillsLength - 1).data(), directorStillsLength - 1);
    } else {
      var image = directorStills.eq(prevousIndex).children('.full-still').clone();
      this.load(image, directorStills.eq(prevousIndex).data(), prevousIndex);
    }
  },

  close: function () {
    this.overlay.fadeOut(fastAnimationSpeed).data('now-playing', '');
    this.viewer.html('');
  },

  fixHeight: function () {

    this.overlayInner.height('auto');
    this.overlayInner.width('80%');

    var windowHeight = $(window).height();
    var height = this.overlayInner.height();


    while (height > (windowHeight*0.95)) {
      this.overlayInner.width(this.overlayInner.width()*0.95);
      height = this.overlayInner.height();
    }
  }

};

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
    var _this = this;
    var ratio;

    if (postData.videoRatio === undefined) {
      ratio = 0.5625;
    } else {
      ratio = postData.videoRatio;
    }
    _this.overlay.fadeIn(fastAnimationSpeed).data('now-playing', postIndex);
    _this.player.html('<iframe id="overlay-vimeo-player-embed" src="//player.vimeo.com/video/' + postData.vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0&player_id=overlay-vimeo-player-embed" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
      'padding-top': (ratio * 100) + '%'
    });
    _this.overlayDirector.html(postData.director);
    _this.overlayTitle.html(postData.title);
    _this.overlayBrand.html(postData.brand);

    // hide middot if title or brand is blank
    _this.overlayMiddot.show();
    if (postData.title === '' || postData.brand === '') {
      _this.overlayMiddot.hide();
    }

    // use fixHeight after animations
    _this.timeout = setTimeout(function() {
      _this.fixHeight();
    }, (basicAnimationSpeed + fastAnimationSpeed + 1));
    $(window).resize(function() {
      this.timeout = setTimeout(function() {
        _this.fixHeight();
      }, 50);
    });

    var iframe = $('#overlay-vimeo-player-embed')[0],
      player = $f(iframe);
    player.addEvent('ready', function () {
      player.addEvent('finish', function () {
        _this.playNext();
      });
    });
  },

  playNext: function () {
    var nowPlayingIndex = this.overlay.data('now-playing'),
      nextIndex = nowPlayingIndex + 1;
    if (directorShowreelLength > nextIndex) {
      this.load(directorShowreelVideos.eq(nextIndex).data(), nextIndex);
    } else {
      this.load(directorShowreelVideos.eq(0).data(), 0);
    }
  },

  playPrev: function () {
    var nowPlayingIndex = this.overlay.data('now-playing'),
      prevousIndex = nowPlayingIndex - 1;
    if (prevousIndex === -1) {
      this.load(directorShowreelVideos.eq(directorShowreelLength - 1).data(), directorShowreelLength - 1);
    } else {
      this.load(directorShowreelVideos.eq(prevousIndex).data(), prevousIndex);
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

  player: $('#vimeo-player'),
  inlineTitle: $('#inline-title'),
  inlineBrand: $('#inline-brand'),
  inlineMiddot: $('#inline-middot'),

  load: function (archiveVideo) {

    this.player = $('#vimeo-player');
    this.inlineTitle = $('#inline-title');
    this.inlineBrand = $('#inline-brand');
    this.inlineMiddot = $('#inline-middot');

    var archiveVideoData = archiveVideo.data(),
      ratio = archiveVideoData.videoRatio;

    if (ratio === undefined) {
      ratio = 0.5625;
    }

    this.inlineTitle.html(archiveVideoData.title);
    this.inlineBrand.html(archiveVideoData.brand);
    // hide middot if title or brand is blank
    this.inlineMiddot.show();
    if (archiveVideoData.title === '' || archiveVideoData.brand === '') {
      this.inlineMiddot.hide();
    }

    this.player.data('now-playing-id', archiveVideo[0].id).html('<iframe id="inline-vimeo-player-embed" src="//player.vimeo.com/video/' + archiveVideoData.vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0&player_id=inline-vimeo-player-embed" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
      'padding-top': (ratio * 100) + '%'
    });
    $('html').addClass('cinema-mode');

    $(window).scrollTo($('#main-content'), basicAnimationSpeed);

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
      currentPlaylist = $('.director-showreel-video:visible').parent('a'),
      currentPlaylistLength = currentPlaylist.length,
      nowPlayingIndex;

    currentPlaylist.each(function (index, element) {
      if (nowPlayingId === $(element).children('.video')[0].id) {
        nowPlayingIndex = index;
      }
    });

    if (nowPlayingIndex === undefined || nowPlayingIndex === (currentPlaylistLength - 1)) {
      window.location.hash = $(currentPlaylist[0]).attr('href').slice(1);
    } else {
      window.location.hash = $(currentPlaylist[(nowPlayingIndex + 1)]).attr('href').slice(1);
    }

  },

  playPrev: function () {

    var nowPlayingId = this.player.data('now-playing-id'),
      currentPlaylist = $('.director-showreel-video:visible').parent('a'),
      currentPlaylistLength = currentPlaylist.length,
      nowPlayingIndex;

    currentPlaylist.each(function (index, element) {
      if (nowPlayingId === $(element).children('.video')[0].id) {
        nowPlayingIndex = index;
      }
    });

    if (nowPlayingIndex === undefined) {
      window.location.hash = $(currentPlaylist[0]).attr('href').slice(1);
    } else if (nowPlayingIndex === 0) {
      window.location.hash = $(currentPlaylist[(currentPlaylistLength - 1)]).attr('href').slice(1);
    } else {
      window.location.hash = $(currentPlaylist[(nowPlayingIndex - 1)]).attr('href').slice(1);
    }

  },

  close: function () {
    $('html').removeClass('cinema-mode');
    $('#now-playing-title').html('');
    this.player.html('').css({
      'padding-top': '0%'
    }).data('now-playing', '');
  },

};

// ROUTER

function router( page, hash ) {

  // Routes: Director Page
  if ( page === 'director') {
      $('.director-menu-item').removeClass('active');

    // play all showreel
    if ( hash === 'play-all' ) {

      // trigger inline load all
      // overlayVimeoPlayer.load( $('.director-showreel-video').eq(0).data(), 0 );

    // load archive & possible a specific video
    } else if ( hash.indexOf('video-') === 0 ) {

      if ( $('#director-menu').attr('data-active') !== 'showreel' ) {
        inlineVimeoPlayer.close();
        $('.director-section:not(#director-showreel)').slideUp(basicAnimationSpeed);
        $('#director-showreel').slideDown(basicAnimationSpeed);
        $('#director-menu').attr('data-active', 'showreel');
      } else if ( hash === 'showreel' ) {
        inlineVimeoPlayer.close();
      }

      // #video-XXXX
      if ( hash.indexOf('video-') === 0 ) {
        inlineVimeoPlayer.load( $("#director-archive-" + hash ) );
        $('#vimeo-player').slideDown(basicAnimationSpeed);
        $('#director-menu').attr('data-active', 'showreel');
      }

      $('#director-menu-showreel').addClass('active');

    // #stills
    } else if ( hash === 'stills' ) {
      // MASONRY
      $('#stills-container').imagesLoaded( function() {
        $('#stills-container').masonry({
          itemSelector: '.still',
        });
      });

      inlineVimeoPlayer.close();

      $('.director-section').slideUp(basicAnimationSpeed);

      $('#director-stills').slideDown(basicAnimationSpeed);
      $('#director-menu').attr('data-active', 'stills' );
      $('#director-menu-stills').addClass('active');

    // #biography
    } else if ( hash === 'biography' ) {
      inlineVimeoPlayer.close();
      $('#director-stills').slideUp(basicAnimationSpeed);

      $('#director-showreel').slideDown(basicAnimationSpeed);
      $('#director-biography').slideDown(basicAnimationSpeed);

      $(window).scrollTo($('#director-biography'), basicAnimationSpeed);

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
    icon: wp_variables.themeUrl + '/img/dist/ns-mapmarker.png'
  });
  infowindow.open(map,marker);
  google.maps.event.addListener(marker, 'click', function() {
    infowindow.open(map,marker);
  });
  $('#map-canvas').height(($(window).height()*0.5));
}

// AJAX FUNCTIONS

function ajaxBefore() {
  $('#main-content').fadeOut(fastAnimationSpeed);
  $('#sidebar li').removeClass('menu-active');
  $('#sidebar').removeClass('open');
  $('html').removeClass('cinema-mode');
}

function ajaxErrorHandler(jqXHR, textStatus, errorThrown) {
  console.log('ajaxy error');
  console.log(errorThrown);
}

function ajaxDirectorSuccess(data, url) {

  var title = $(data)[5].innerText;

  history.pushState(null, title, url);
  document.title = title;

  var content = $(data).find('#main-content');

  $('#main-content').html(content.html());
  $('#main-content').fadeIn(basicAnimationSpeed);

  clickedMenuItem.addClass('menu-active');

  lazyLoadBackgrounds();
  videoBackgrounds();

  // REINIT
  lazyThumbnails = $('.lazy-thumb');
  lazyBackgrounds = $('.js-lazy-background');
  directorShowreelVideos = $('.director-showreel-video');
  directorShowreelLength = directorShowreelVideos.length;
  tagFilters = $('.filter-tag');
  directorInit();

}

function ajaxIndexSuccess(data, url) {

  var title = $(data)[5].innerText;

  history.pushState(null, title, url);
  document.title = title;

  var content = $(data).find('#main-content');

  $('#main-content').html(content.html());
  $('#main-content').fadeIn(basicAnimationSpeed);

  clickedMenuItem.addClass('menu-active');

  lazyLoadBackgrounds();
  videoBackgrounds();

  // REINIT
  postsInit();

}

function ajaxPageSuccess(data, url) {

  var title = $(data)[5].innerText;

  history.pushState(null, title, url);
  document.title = title;

  var content = $(data).find('#main-content');

  $('#main-content').html(content.html());
  $('#main-content').fadeIn(basicAnimationSpeed);

  clickedMenuItem.addClass('menu-active');

  if ($('#map-canvas').length) {
    initializeMap();
  }

}

// DOC READY BRO

$(document).ready(function () {

  $('.js-ajax-director').on({
    'click': function(e) {
      e.preventDefault();

      var url = e.currentTarget.href;
      clickedMenuItem = $(this).parent();

      $.ajax(url, {
        beforeSend: function() {
          ajaxBefore();
        },
        dataType: 'html',
        error: function(jqXHR, textStatus, errorThrown) {
          ajaxErrorHandler(jqXHR, textStatus, errorThrown);
        },
        success: function(data) {
          ajaxDirectorSuccess(data, url);
        }
      });
    }
  });

  $('.js-ajax-index').on({
    'click': function(e) {
      e.preventDefault();

      var url = e.currentTarget.href;
      clickedMenuItem = $(this).parent();

      $.ajax(url, {
        beforeSend: function() {
          ajaxBefore();
        },
        dataType: 'html',
        error: function(jqXHR, textStatus, errorThrown) {
          ajaxErrorHandler(jqXHR, textStatus, errorThrown);
        },
        success: function(data) {
          ajaxIndexSuccess(data, url);
        }
      });
    }
  });

  $('.js-ajax-page').on({
    'click': function(e) {
      e.preventDefault();

      var url = e.currentTarget.href;
      clickedMenuItem = $(this).parent();

      $.ajax(url, {
        beforeSend: function() {
          ajaxBefore();
        },
        dataType: 'html',
        error: function(jqXHR, textStatus, errorThrown) {
          ajaxErrorHandler(jqXHR, textStatus, errorThrown);
        },
        success: function(data) {
          ajaxPageSuccess(data, url);
        }
      });
    }
  });

  layoutFixSearchInput();

  postsInit();
  sidebarInit();

  if ($('.js-lazy-background').length) {
    lazyLoadBackgrounds();
  }

  if ($('.webm-background-container').length) {
    videoBackgrounds();

    var resizeTimer;

    $(window).on('resize', function(e) {

      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
      videoBackgrounds();

      }, 500);

    });
  }

  if ( $('body').hasClass('single-director') ) {
    directorInit();
  }

  if ($('#map-canvas').length) {
    google.maps.event.addDomListener(window, 'load', initializeMap);
  }

});
