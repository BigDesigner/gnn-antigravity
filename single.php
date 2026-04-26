<?php
/**
 * The template for displaying all single posts
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

            <div class="entry-meta" style="margin: 1rem 0; font-size: 0.8rem; text-transform: uppercase; color: var(--gray);">
                <?php if (get_theme_mod('show_post_date', true) && !get_post_meta(get_the_ID(), '_gnn_hide_date', true)): ?>
                    <span class="posted-on"><?php echo esc_html(get_the_date()); ?></span>
                <?php endif; ?>
                <?php if (get_theme_mod('show_post_author', false) && !get_post_meta(get_the_ID(), '_gnn_hide_author', true)): ?>
                    <span class="byline"> / <?php echo esc_html(get_the_author()); ?></span>
                <?php endif; ?>
                <?php
                $categories_list = get_the_category_list(esc_html__(', ', 'gnn-antigravity'));
                if ($categories_list) {
                    echo '<span class="cat-links"> / ' . wp_kses_post($categories_list) . '</span>';
                }
                ?>
            </div>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'gnn-antigravity'),
                    'after' => '</div>',
                ));
                ?>
            </div>

            <footer class="entry-footer" style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                <?php
                $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'gnn-antigravity'));
                if ($tags_list) {
                    printf('<span class="tags-links">' . esc_html__('Tags: %1$s', 'gnn-antigravity') . '</span>', $tags_list);
                }
                ?>
            </footer>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();
