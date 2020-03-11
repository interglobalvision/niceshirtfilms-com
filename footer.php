  </section>

  <section id="image-overlay">
    <nav id="image-overlay-close" class="video-overlay-nav u-pointer">
      <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/ns-close.svg'); ?>
    </nav>
    <nav id="image-overlay-next" class="video-overlay-nav u-pointer">
      <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/ns-arrow-right.svg'); ?>
    </nav>
    <nav id="image-overlay-previous" class="video-overlay-nav u-pointer">
      <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/ns-arrow-left.svg'); ?>
    </nav>
    <div class="u-holder">
      <div class="u-held">
        <div id="image-overlay-inner">
          <div id="image-overlay-viewer" class="u-pointer"></div>
            <div id="video-overlay-text" class="u-align-center">
              <h3 id="image-overlay-director"></h3>
              <h4 id="image-overlay-title"></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="video-overlay">
    <nav id="video-overlay-close" class="video-overlay-nav u-pointer">
      <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/ns-close.svg'); ?>
    </nav>
    <nav id="video-overlay-next" class="video-overlay-nav u-pointer">
      <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/ns-arrow-right.svg'); ?>
    </nav>
    <nav id="video-overlay-previous" class="video-overlay-nav u-pointer">
      <?php echo url_get_contents(get_bloginfo('stylesheet_directory') . '/img/dist/ns-arrow-left.svg'); ?>
    </nav>
    <div class="u-holder">
      <div class="u-held">
        <div id="video-overlay-inner">
          <div id="video-overlay-player"></div>
          <div id="video-overlay-text" class="u-align-center">
            <h3 id="video-overlay-director"></h3>
            <h4><span id="video-overlay-title" class="font-italic"></span><span id="video-overlay-middot"> &middot; </span><span id="video-overlay-brand"></span></h4>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="scripts">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php bloginfo('stylesheet_directory'); ?>/js/vendor/jquery-2.1.1.min.js"><\/script>')</script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1dtJVKkbFoIGINO734bpSLHV4fg1mYKg"></script>
    <script src="https://f.vimeocdn.com/js/froogaloop2.min.js"></script>
    <?php wp_footer(); ?>

    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-8386387-1']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
  </section>
  </body>
</html>
