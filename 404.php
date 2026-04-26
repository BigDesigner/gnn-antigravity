<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package GNN-antigravity
 */

get_header();
?>

<div class="post-canvas" data-swup-scroll>
    <section class="error-404 not-found canvas-item" style="text-align: center; padding: 4rem 1rem;">
        <header class="page-header" style="margin-bottom: 2rem;">
            <h1 class="page-title glitch" data-text="404" style="font-size: 6rem; line-height: 1;">
                <?php esc_html_e('404', 'gnn-antigravity'); ?>
            </h1>
            <p style="font-size: 1.5rem; color: var(--gray); margin-top: 1rem;">
                <?php esc_html_e('Oops! That page can&rsquo;t be found.', 'gnn-antigravity'); ?>
            </p>
        </header>

        <div class="page-content" style="max-width: 500px; margin: 0 auto;">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'gnn-antigravity'); ?></p>
            <?php get_search_form(); ?>
            
            <div style="margin-top: 3rem;">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="gnn-button" style="padding: 1rem 2rem; background: var(--primary-color, #fff); color: var(--bg-color, #000); text-decoration: none; border-radius: 4px; font-weight: bold;">
                    <?php esc_html_e('Return Home', 'gnn-antigravity'); ?>
                </a>
            </div>
        </div>
    </section>
</div>

<?php
get_footer();
