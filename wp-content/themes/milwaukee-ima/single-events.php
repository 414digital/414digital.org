<?php get_header(); ?>



<?php if( ! is_page() ) { ?>
    <section id="main">
    <div class="container">
    <div class="row">
    <div class="col-lg-12">
    <div id="primary" class="content-area">
<?php } ?>


    <section id="page">
        <div class="entry-thumbnail">
            <?php the_post_thumbnail(); ?>
            <div class="event-title">
                <h1><?php echo get_the_title(); ?></h1>
                <h2><?php the_field('sub_title'); ?></h2>
                <div class="event-date"><?php
                    $date = DateTime::createFromFormat('Ymd', get_field('event_date_time'));
                    echo $date->format('l, F jS, Y');?></div>
                <div class="event-time"><?php the_field('event_time'); ?></div>
                <a class="register-button" href="#">Register</a>
            </div>

        </div>
        <div class="container">
            <div id="content" class="site-content" role="main">
                <?php /* The loop */ ?>
                <?php while ( have_posts() ) { the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php edit_post_link( __( 'Edit', ZEETEXTDOMAIN ), '<small class="edit-link pull-right ">', '</small><div class="clearfix"></div>' ); ?>
                        <?php if ( has_post_thumbnail() && ! post_password_required() ) { ?>

                        <?php } ?>
                        <div class="entry-content">
                            <?php the_content(); ?>
                            <?php zee_link_pages(); ?>
                        </div>
                    </article>
                    <?php comments_page(); ?>
                <?php } ?>
            </div><!--/#content-->
            <div id="sidebar">
                <div class="sidebar-content">
                    <h2>Details</h2>
                    <h3>Schedule</h3>
                    <p>4:30 - 5:30 Networking</p>
                    <p>5:30 - 6:30 Keynote</p>
                    <p>6:30 - 7:00 More Drinking</p>
                    <h3>Location</h3>
                    <p>Hiltion Gaden Inn</p>
                    <p>15356 W Wisconsin Ave</p>
                    <p>Milwaukee, WI 53202</p>
                </div>


            </div>
        </div>
    </section><!--/#page-->
<?php get_footer();