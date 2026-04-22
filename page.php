<?php
/**
 * The template for displaying all pages
 *
 * @package GNN-antigravity
 */

get_header();
?>

<div class="post-canvas" data-swup-scroll>
    <?php
    while (have_posts()):
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('canvas-item'); ?>>

            <?php if (get_theme_mod('show_post_title', true) && !get_post_meta(get_the_ID(), '_gnn_hide_title', true)): ?>
                <header class="entry-header">
                    <h1 class="entry-title glitch" data-text="<?php echo esc_attr(get_the_title()); ?>">
                        <?php the_title(); ?>
                    </h1>
                </header>
            <?php endif; ?>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'gnn-antigravity'),
                    'after' => '</div>',
                ));
                ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();