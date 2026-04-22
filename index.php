<?php
/**
 * The main template file
 *
 * @package GNN-antigravity
 */

get_header();
?>

<div class="post-canvas" data-swup-scroll>
    <?php if (have_posts()): ?>
        <div class="canvas-grid">
            <?php while (have_posts()):
                the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php body_class('canvas-item'); ?>>

                    <?php if (get_theme_mod('show_post_title', true) && !get_post_meta(get_the_ID(), '_gnn_hide_title', true)): ?>
                        <header class="entry-header">
                            <h2 class="entry-title glitch" data-text="<?php echo esc_attr(get_the_title()); ?>">
                                <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                        </header>
                    <?php endif; ?>

                    <div class="entry-meta"
                        style="margin: 1rem 0; font-size: 0.8rem; text-transform: uppercase; color: var(--gray);">
                        <?php if (get_theme_mod('show_post_date', true) && !get_post_meta(get_the_ID(), '_gnn_hide_date', true)): ?>
                            <span class="posted-on"><?php echo esc_html(get_the_date()); ?></span>
                        <?php endif; ?>

                        <?php if (get_theme_mod('show_post_author', false) && !get_post_meta(get_the_ID(), '_gnn_hide_author', true)): ?>
                            <span class="byline"> / <?php echo esc_html(get_the_author()); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="entry-content">
                        <?php
                        the_content(
                            sprintf(
                                wp_kses(
                                    /* translators: %s: Name of current post. Only visible to screen readers */
                                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'gnn-antigravity'),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                wp_kses_post(get_the_title())
                            )
                        );

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'gnn-antigravity'),
                            'after' => '</div>',
                        ));
                        ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <section class="no-results not-found">
            <h2 class="page-title"><?php esc_html_e('Nothing Found', 'gnn-antigravity'); ?></h2>
        </section>
    <?php endif; ?>
</div>

<?php
get_footer();