<?php get_header(); ?>

<main id="main-content" class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header mb-4">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-thumbnail mb-4">
                            <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links"><span class="d-block mb-2">' . __('Pages:', 'rankandrent') . '</span><nav aria-label="Page navigation"><ul class="pagination justify-content-center">',
                            'after'  => '</ul></nav></div>',
                            'link_before' => '<li class="page-item"><span class="page-link">',
                            'link_after'  => '</span></li>',
                        ));
                        ?>
                    </div>
                </article>
            <?php
            endwhile;
            ?>
        </div>
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
