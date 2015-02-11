(function () {
  "use strict";

  function l(data) {
    console.log(data);
  }

  var largeImagesWidth = 1500,

    lazyHomeBackground = $('.js-lazy-home-background'),

    lazyThumbnails = $('.lazy-thumb'),
    lazyBackgrounds = $('.js-lazy-background'),

    inlineVimeoPlayer = $('#vimeo-player'),

    overlay = $('#video-overlay'),
    overlayVimeoPlayer = $('#video-overlay-player'),
    overlayDirector = $('#video-overlay-director'),
    overlayTitle = $('#video-overlay-title'),
    overlayBrand = $('#video-overlay-brand'),
    loadOverlayVimeoLinks = $('.js-load-overlay-vimeo'),

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

//   HOMEPAGE FUNCTIONS

  function closeAllPosts() {
    $('.home-post').removeClass('active');
    $('.post-main').each(function(i, el) {
      el.style.height = '';
    });
    $('.home-video-player').html('');
  }

//   DIRECTOR SINGLE FUNCTIONS

  function loadOverlayVimeoPlayer(postData, postIndex) {
    var ratio;
    if (postData.vimeoRatio === undefined) {
      ratio = 0.5625;
    } else {
      ratio = postData.vimeoRatio;
    }

    l(postData);

    overlay.show();
    overlayVimeoPlayer.html('<iframe id="overlay-vimeo-player-embed" src="//player.vimeo.com/video/' + postData.vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
      'padding-top': (ratio * 100) + '%'
    });
    overlayDirector.html(postData.director);
    overlayTitle.html(postData.title);
    overlayBrand.html(postData.brand);

  }

  function closeOverlayVimeoPlayer() {
    overlay.hide();
    overlayVimeoPlayer.html('');
  }

  function loadInlineVimeoPlayer(vimeoId, vimeoRatio) {
    var ratio = vimeoRatio;
    if (ratio === undefined) {
      ratio = 0.5625;
    }
    inlineVimeoPlayer.html('<iframe id="inline-vimeo-player-embed" src="//player.vimeo.com/video/' + vimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>').css({
      'padding-top': (ratio * 100) + '%'
    });
    $('html').addClass('cinema-mode');
    $('#main-content').ScrollTo();
  }

  function closeinlineVimeoPlayer() {
    $('html').removeClass('cinema-mode');
    inlineVimeoPlayer.html('').css({
      'padding-top': '0%'
    });
  }

  $(document).ready(function () {

    if (hash) {
      var vimeoId = hash.substr(1, hash.length);
      window.location.hash = '';
      loadInlineVimeoPlayer(vimeoId);
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
      var post = $(this).ScrollTo().parent(),
        postVimeoId = post.data('vimeo-id'),
        postVimeoRatio = post.data('video-ratio');
      if (postVimeoRatio == 0) {
        postVimeoRatio = 0.5625;
      }
      var postVimeoHeight = (post.width() * postVimeoRatio);
      post.addClass('active');
      post.find('.post-main').height(postVimeoHeight);
      post.find('.home-video-player').height(postVimeoHeight).html('<iframe class="home-vimeo-embed" width="100%" height="100%" src="//player.vimeo.com/video/' + postVimeoId + '?api=1&autoplay=1&badge=0&byline=0&portrait=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
    });

    // HOME NEWS POSTS OPEN

    $('.news-read-more .post-main').on('click', function (e) {
      e.preventDefault();
      closeAllPosts();
      $(this).ScrollTo().parent().addClass('active');
    });

    // DIRECTOR SINGLE

      // MENU TABS

    $('.director-menu-item').on('click', function (e) {
      var target = $(this).data('target');
      if ($('#director-menu').data('active') != target) {
        closeinlineVimeoPlayer();
        $('.director-section').slideUp();
        $('#director-' + target).slideDown();
        $('.director-menu-item').removeClass('active')
        $(this).addClass('active');
        $('#director-menu').data('active', target);
      }
    });

      // LOAD SHOWREEL VIDEOS IN OVERLAY

    $('.js-load-overlay-vimeo').on('click', function () {
      loadOverlayVimeoPlayer($(this).data(), $(this).index());
    });

      // CLOSE OVERLAY

    $('#video-overlay-close').on('click', function () {
      closeOverlayVimeoPlayer();
    });

      // LOAD ARCHIVE VIDEOS INLINE

    $('.js-load-inline-vimeo').on('click', function () {
      loadInlineVimeoPlayer($(this).data('vimeo-id'), $(this).data('vimeo-ratio'));
    });

      // TAG FILTERS

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