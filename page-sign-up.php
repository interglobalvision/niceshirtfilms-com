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

  <article id="page" <?php post_class(); ?>>

    <header class="page-header u-cf">

      <div class="col col2 colpad1left">

        <h3><?php the_title(); ?></h3>

      </div>

    </header>

    <form id="subForm" action="http://modernactivity.createsend.com/t/y/s/fkiuil/" method="post">

      <section class="row u-cf">

        <div class="col col1">

                <label for="fieldName">First name</label>

                <label for="fieldckikjh">Last name</label>

                <label for="fieldEmail">Email address*</label>

                <label for="fieldckikjk">What is your favourite ad right now?</label>

                <label>
                  * = mandatory field for submission
                </label>

        </div>

        <div class="col col2">

                <label for="fieldName" class="mobile-label">First name</label>
                <input id="fieldName" name="cm-name" type="text" />

                <label for="fieldckikjh" class="mobile-label">Last name</label>
                <input id="fieldckikjh" name="cm-f-ckikjh" type="text" />

                <label for="fieldEmail" class="mobile-label">Email address*</label>
                <input id="fieldEmail" name="cm-fkiuil-fkiuil" required="" type="email" />

                <label for="fieldckikjk" class="mobile-label">What is your favourite ad right now?</label>
                <input id="fieldckikjk" name="cm-f-ckikjk" type="text" />

                <button type="submit">Submit Form</button>

                <label class="mobile-label">
                  * = mandatory field for submission
                </label>

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