  </section>

<?php
if (is_single()) {
  if (is_single_type('director', $post)) {
?>
  <section id="video-overlay">
    <nav id="video-overlay-close" class="video-overlay-nav u-pointer">
      <?php echo file_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-close.svg'); ?>
    </nav>
    <nav id="video-overlay-next" class="video-overlay-nav u-pointer">
      <?php echo file_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-arrow-right.svg'); ?>
    </nav>
    <nav id="video-overlay-previous" class="video-overlay-nav u-pointer">
      <?php echo file_get_contents(get_bloginfo('stylesheet_directory') . '/img/optimized/ns-arrow-left.svg'); ?>
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
<?php
  }
}
?>

  <section id="scripts">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php bloginfo('stylesheet_directory'); ?>/js/vendor/jquery-2.1.1.min.js"><\/script>')</script>
<?php
if (is_page('contact')) {
?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1dtJVKkbFoIGINO734bpSLHV4fg1mYKg"></script>
<?php
}
?>
    <script src="//f.vimeocdn.com/js/froogaloop2.min.js"></script>
    <?php wp_footer(); ?>
  </section>
  </body>
</html>