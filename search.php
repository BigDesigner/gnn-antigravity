<?php
/**
 * The template for displaying search results pages
 *
 * @package GNN-antigravity
 */

get_header();
?>

<div class="post-canvas" data-swup-scroll>
    <?php if (have_posts()): ?>
        <header class="page-header" style="margin-bottom: 2rem;">
            <h1 class="page-title glitch" data-text="<?php echo esc_attr(sprintf(__('Search Results for: %s', 'gnn-antigravity'), get_search_query())); ?>">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'gnn-antigravity'), '<span>' . esc_html(get_search_query()) . '</span>');
                ?>
            </h1>
        </header>

        <div class="canvas-grid">
            <?php while (have_posts()):
                the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('canvas-item'); ?>>
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    </header>
                    <div class="entry-summary" style="margin-top: 1rem;">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <?php the_posts_navigation(); ?>

    <?php else: ?>
        <section class="no-results not-found">
            <header class="page-header" style="margin-bottom: 2rem;">
                <h1 class="page-title"><?php esc_html_e('Nothing Found', 'gnn-antigravity'); ?></h1>
            </header>
            <div class="page-content">
                <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'gnn-antigravity'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php
get_footer();
