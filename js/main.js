(function () {
  "use strict";

  function l(data) {
    console.log(data);
  }

  var largeImagesWidth = 1500,

    lazyHomeBackground = $('.js-lazy-home-background'),

    lazyThumbnails = $('.lazy-thumb'),
    lazyBackgrounds = $('.js-lazy-background'),

    keepRatio = $('.js-keep-ratio'),

    vimeoPlayer = $('#vimeo-player'),
    loadVimeoLinks = $('.js-load-vimeo'),

    sidebar = $('#sidebar'),
    sidebarButton = $('#sidebar-toggle'),

    tagFilters = $('.filter-tag'),
    directorArchiveVideos = $('.director-archive-video'),

    hash = window.location.hash;

  function ifLargeImages() {
    if ($(window).width() > largeImagesWidth) {
      return true;
    } else {
      return false;
    }
  }

  function loadVimeoPlayer(vimeoId) {
    var ratio = $(this).data('video-ratio');
    if (ratio === undefined) {
      ratio = 0.5625;
    }
    vimeoPlayer.html('<iframe src="//player.vimeo.com/video/' + vimeoId + '?autoplay=1&badge=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
      'padding-top': (ratio * 100) + '%'
    });
    $('html').addClass('cinema-mode');
  }

  $(document).ready(function () {

    if (hash) {
      var vimeoId = hash.substr(1, hash.length);
      window.location.hash = '';
      loadVimeoPlayer(vimeoId);

      l('Load vimeo from hash: ' + vimeoId);
    }

    if (lazyHomeBackground.length) {
      lazyHomeBackground.each(function (index, item) {
        var background = $(item).data('background');
        $(item).css({
          'background-image': 'url(' + background + ')'
        });
      });
    }

/*
    if (keepRatio.length) {
      keepRatio.keepRatio();
      $(window).resize(function() {
        keepRatio.keepRatio();
      });
    }
*/

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

    // POSTS

    $('.post-copy-close').on('click', function (e) {
      e.preventDefault();
      $(this).parent('.post-copy').removeClass('open');
    });

    // HOME VIDEO POSTS

    $('.home-video .post-main').on('click', function (e) {
      e.preventDefault();
      $(this).ScrollTo().parent().find('.post-copy').addClass('open');
    });

    // NEWS POSTS

    $('.news-read-more .post-main').on('click', function (e) {
      e.preventDefault();
      $(this).ScrollTo().parent().find('.post-copy').addClass('open');
    });

    // DIRECTOR SINGLE

    $('#director-bio-toggle').on('click', function (e) {
      $('#director-biography').slideToggle();
    });

    loadVimeoLinks.on('click', function (e) {
      e.preventDefault();
      loadVimeoPlayer($(this).data('vimeo-id'));
    });

    tagFilters.on('click', function (e) {
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

  // END DOC READY

  });

})();