<?php
/**
 * Template Name: Elementor Full Width
 * Template Post Type: page, post
 *
 * A clean, full-width template designed for Elementor.
 * Removes all theme wrappers (hero, sidebars, entry-headers)
 * and provides a raw the_content() call so Elementor has
 * complete control over the page layout.
 *
 * @package GNN-antigravity
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<?php
while ( have_posts() ) :
    the_post();
    the_content();
endwhile;
?>

<?php
get_footer();
