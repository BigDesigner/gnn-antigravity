<?php
/**
 * Template Name: Elementor Canvas
 * Template Post Type: page
 *
 * A completely blank canvas template for Elementor.
 * No header, no footer, no theme chrome whatsoever.
 * Only wp_head() and wp_footer() are called for proper
 * script/style loading and Elementor editor functionality.
 *
 * Use this when building standalone landing pages, coming-soon
 * pages, or any page where the theme frame should be invisible.
 *
 * @package GNN-antigravity
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'gnn-elementor-canvas' ); ?>>
    <?php wp_body_open(); ?>

    <?php
    while ( have_posts() ) :
        the_post();
        the_content();
    endwhile;
    ?>

    <?php wp_footer(); ?>
</body>

</html>
