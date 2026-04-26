<?php
/**
 * The template for displaying archive pages
 *
 * @package GNN-antigravity
 */

get_header();
?>

<div class="post-canvas" data-swup-scroll>
    <?php if (have_posts()): ?>
        <header class="page-header" style="margin-bottom: 2rem;">
            <?php
            the_archive_title('<h1 class="page-title glitch" data-text="' . esc_attr(strip_tags(get_the_archive_title())) . '">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
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
                    <div class="entry-meta" style="font-size: 0.8rem; margin: 0.5rem 0; color: var(--gray);">
                        <span class="posted-on"><?php echo esc_html(get_the_date()); ?></span>
                    </div>
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        
        <?php the_posts_navigation(); ?>

    <?php else: ?>
        <section class="no-results not-found">
            <h2 class="page-title"><?php esc_html_e('Nothing Found', 'gnn-antigravity'); ?></h2>
        </section>
    <?php endif; ?>
</div>

<?php
get_footer();
