<?php
get_header();
?>

<!-- main content -->

<main id="main-content">

<?php
if( have_posts() ) {
  while( have_posts() ) {
    the_post();
    $meta = get_post_meta($post->ID);
?>

  <article id="page" class="<?php post_class(); ?>">

    <header class="page-header u-cf">

      <div class="col col1 colpad1left">

        <h3><?php the_title(); ?></h3>

      </div>

    </header>

    <form id="subForm" action="http://modernactivity.createsend.com/t/y/s/fkiuil/" method="post">

      <section class="row cf">

        <div class="col col1">

                <label for="fieldName">Name</label>

                <label for="fieldckikjh">last name</label>

                <label for="fieldEmail">Email</label>

                <label for="fieldckikjk">Fav film</label>

        </div>

        <div class="col col1">

                <input id="fieldName" name="cm-name" type="text" />

                <input id="fieldckikjh" name="cm-f-ckikjh" type="text" />

                <input id="fieldEmail" name="cm-fkiuil-fkiuil" required="" type="email" />

                <input id="fieldckikjk" name="cm-f-ckikjk" type="text" />

                <button type="submit">Subscribe</button>

        </div>

      </section>

    </form>

  </article>

<?php
  }
} else {
?>
  <section class="error">
    <article class="u-alert"><?php _e('Sorry, nothing matched your criteria :{'); ?></article>
  </section>
<?php
} ?>

<!-- end main-content -->

</main>

<?php
get_footer();
?>