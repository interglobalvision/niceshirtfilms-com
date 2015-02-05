(function () {
  "use strict";

  function l(data) {
    console.log(data);
  }

  var largeImagesWidth = 1500,

    lazyHomeBackground = $('.js-lazy-home-background'),

    lazyThumbnails = $('.lazy-thumb'),
    lazyBackgrounds = $('.js-lazy-background'),

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
    }
    return false;
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
    $('#main-content').ScrollTo();
  }

  function closeVimeoPlayer() {
    $('html').removeClass('cinema-mode');
    vimeoPlayer.html('').css({
      'padding-top': '0%'
    });
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
      $(this).parent('.post-copy').parent().removeClass('active');
      var postMain = $(this).parent('.post-copy').siblings('.post-main');
      postMain[0].style.height = '';
      postMain.children('.home-video-player').html('');
    });

    // HOME VIDEO POSTS

    $('.home-video .post-main').on('click', function (e) {
      e.preventDefault();
      var post = $(this).ScrollTo().parent(),
        postVimeoId = post.data('vimeo-id'),
        postVimeoRatio = post.data('video-ratio');

      if (postVimeoRatio == 0) {
        postVimeoRatio = 0.5625;
      }

      var postVimeoHeight = (post.width() * postVimeoRatio);

      post.addClass('active');

      post.find('.post-main').height(postVimeoHeight);
      post.find('.home-video-player').height(postVimeoHeight).html('<iframe width="100%" height="100%" src="//player.vimeo.com/video/' + postVimeoId + '?autoplay=1&badge=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
    });

    // NEWS POSTS

    $('.news-read-more .post-main').on('click', function (e) {
      e.preventDefault();
      $(this).ScrollTo().parent().addClass('active');
    });

    // DIRECTOR SINGLE

    $('.director-menu-item').on('click', function (e) {
      var target = $(this).data('target');
      if ($('#director-menu').data('active') != target) {
        closeVimeoPlayer();
        $('.director-section').slideUp();
        $('#director-' + target).slideDown();
        $('.director-menu-item').removeClass('active')
        $(this).addClass('active');
        $('#director-menu').data('active', target);
      }
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