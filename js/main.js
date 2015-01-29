(function () {
  "use strict";

  function l(data) {
    console.log(data);
  }

  var largeImagesWidth = 1500,

    basicPosts = $('.basic-post'),
    lazyThumbnails = $('.lazy-thumb'),
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
      'padding-bottom': (ratio * 100) + '%'
    });
  }

  $(document).ready(function () {

    if (hash) {
      var   vimeoId = hash.substr(1, hash.length);
      window.location.hash = '';
      loadVimeoPlayer(vimeoId);

      l('Load vimeo from hash: ' + vimeoId);
    }

    if (basicPosts.length) {
      basicPosts.each(function (index, item) {
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

    // NEWS POSTS

    $('.news-read-more').on('click', function (e) {
      e.preventDefault();
      $(this).ScrollTo();
      $(this).find('.news-copy').slideToggle();
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
      tagFilters.removeClass('highlight');
      $(this).addClass('highlight');

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