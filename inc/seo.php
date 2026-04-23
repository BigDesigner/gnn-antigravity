<?php
/**
 * GNN SEO Module
 *
 * Provides native meta tag management, Open Graph tags,
 * canonical URLs, and structured data output without
 * relying on any 3rd party SEO plugin.
 *
 * If a major SEO plugin (Yoast, Rank Math, AIOSEO) is detected,
 * this module gracefully disables itself to prevent conflicts.
 *
 * @package GNN-antigravity
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if a known SEO plugin is active.
 *
 * Detects Yoast SEO, Rank Math, All in One SEO, and SEOPress.
 * When any of these is active, GNN's native SEO output is suppressed
 * to avoid duplicate meta tags in the document head.
 *
 * @since 1.3.0
 * @return bool True if a 3rd party SEO plugin is handling meta tags.
 */
function gnn_seo_plugin_active() {
    // Yoast SEO
    if ( defined( 'WPSEO_VERSION' ) ) {
        return true;
    }
    // Rank Math
    if ( class_exists( 'RankMath' ) ) {
        return true;
    }
    // All in One SEO
    if ( defined( 'AIOSEO_VERSION' ) ) {
        return true;
    }
    // SEOPress
    if ( defined( 'SEOPRESS_VERSION' ) ) {
        return true;
    }
    return false;
}

/**
 * Output meta description tag.
 *
 * Generates context-aware meta descriptions:
 * - Single post/page: Uses custom meta field, excerpt, or auto-generated from content.
 * - Front page: Uses Customizer setting.
 * - Archive: Uses term description.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_seo_meta_description() {
    if ( gnn_seo_plugin_active() ) {
        return;
    }

    $description = '';

    if ( is_singular() ) {
        // Priority: custom meta > excerpt > trimmed content
        $custom_desc = get_post_meta( get_the_ID(), '_gnn_seo_description', true );
        if ( ! empty( $custom_desc ) ) {
            $description = $custom_desc;
        } elseif ( has_excerpt() ) {
            $description = get_the_excerpt();
        } else {
            $description = wp_trim_words( get_the_content(), 25, '...' );
        }
    } elseif ( is_front_page() || is_home() ) {
        $description = get_theme_mod( 'gnn_seo_home_description', get_bloginfo( 'description' ) );
    } elseif ( is_category() || is_tag() || is_tax() ) {
        $description = term_description();
    } elseif ( is_author() ) {
        $author_id   = get_queried_object_id();
        $description = get_the_author_meta( 'description', $author_id );
    }

    $description = wp_strip_all_tags( $description );
    $description = mb_substr( $description, 0, 160 );

    if ( ! empty( $description ) ) {
        printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
    }
}
add_action( 'wp_head', 'gnn_seo_meta_description', 1 );

/**
 * Output Open Graph meta tags.
 *
 * Provides og:title, og:description, og:image, og:url, og:type,
 * and og:site_name for social media sharing previews.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_seo_open_graph() {
    if ( gnn_seo_plugin_active() ) {
        return;
    }

    // og:site_name (always output)
    printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );

    // og:locale
    printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( get_locale() ) );

    if ( is_singular() ) {
        // og:type
        echo '<meta property="og:type" content="article">' . "\n";

        // og:title
        $og_title = get_post_meta( get_the_ID(), '_gnn_seo_title', true );
        if ( empty( $og_title ) ) {
            $og_title = get_the_title();
        }
        printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $og_title ) );

        // og:description
        $og_desc = get_post_meta( get_the_ID(), '_gnn_seo_description', true );
        if ( empty( $og_desc ) ) {
            $og_desc = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 25, '...' );
        }
        printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( $og_desc ) ) );

        // og:url
        printf( '<meta property="og:url" content="%s">' . "\n", esc_url( get_permalink() ) );

        // og:image
        $og_image = get_post_meta( get_the_ID(), '_gnn_seo_image', true );
        if ( empty( $og_image ) && has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        }
        if ( ! empty( $og_image ) ) {
            printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $og_image ) );
        }

        // article:published_time
        printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( 'c' ) ) );
        printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( get_the_modified_date( 'c' ) ) );

    } else {
        echo '<meta property="og:type" content="website">' . "\n";
        printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( wp_get_document_title() ) );
        printf( '<meta property="og:url" content="%s">' . "\n", esc_url( home_url( $_SERVER['REQUEST_URI'] ) ) );

        $home_desc = get_theme_mod( 'gnn_seo_home_description', get_bloginfo( 'description' ) );
        if ( ! empty( $home_desc ) ) {
            printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $home_desc ) );
        }
    }
}
add_action( 'wp_head', 'gnn_seo_open_graph', 2 );

/**
 * Output Twitter Card meta tags.
 *
 * Uses summary_large_image card type when a featured image is available,
 * falls back to summary card otherwise.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_seo_twitter_card() {
    if ( gnn_seo_plugin_active() ) {
        return;
    }

    $card_type = 'summary';

    if ( is_singular() && has_post_thumbnail() ) {
        $card_type = 'summary_large_image';
    }

    printf( '<meta name="twitter:card" content="%s">' . "\n", esc_attr( $card_type ) );

    $twitter_handle = get_theme_mod( 'gnn_seo_twitter_handle', '' );
    if ( ! empty( $twitter_handle ) ) {
        printf( '<meta name="twitter:site" content="@%s">' . "\n", esc_attr( ltrim( $twitter_handle, '@' ) ) );
    }
}
add_action( 'wp_head', 'gnn_seo_twitter_card', 2 );

/**
 * Output canonical URL.
 *
 * WordPress 5.x+ handles rel=canonical natively via wp_head().
 * This function adds it only for older WP versions as a safety net.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_seo_canonical() {
    if ( gnn_seo_plugin_active() ) {
        return;
    }

    // WordPress 5.0+ outputs canonical via wp_head() already.
    // Only add if the native function doesn't exist (very old WP).
    if ( function_exists( 'rel_canonical' ) ) {
        return;
    }

    if ( is_singular() ) {
        printf( '<link rel="canonical" href="%s">' . "\n", esc_url( get_permalink() ) );
    }
}
add_action( 'wp_head', 'gnn_seo_canonical', 3 );

/**
 * Output JSON-LD structured data (Schema.org).
 *
 * Provides WebSite schema for the homepage and Article schema
 * for individual posts/pages. This helps search engines understand
 * the content structure and display rich snippets.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_seo_schema_markup() {
    if ( gnn_seo_plugin_active() ) {
        return;
    }

    if ( is_front_page() || is_home() ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'WebSite',
            'name'     => get_bloginfo( 'name' ),
            'url'      => home_url( '/' ),
        );

        // Add search action for sitelinks searchbox
        $schema['potentialAction'] = array(
            '@type'       => 'SearchAction',
            'target'      => home_url( '/?s={search_term_string}' ),
            'query-input' => 'required name=search_term_string',
        );

        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
        );

    } elseif ( is_singular() ) {
        $schema = array(
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'url'           => get_permalink(),
            'author'        => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher'     => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
            ),
        );

        // Add featured image if available
        if ( has_post_thumbnail() ) {
            $schema['image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        }

        // Add description
        $desc = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 25, '...' );
        if ( ! empty( $desc ) ) {
            $schema['description'] = wp_strip_all_tags( $desc );
        }

        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
        );
    }
}
add_action( 'wp_head', 'gnn_seo_schema_markup', 5 );

/**
 * Add per-post SEO meta fields to the existing GNN metabox.
 *
 * Adds SEO Title, SEO Description, and SEO Image fields
 * to the post/page editor sidebar for granular control.
 *
 * @since 1.3.0
 * @return void
 */
function gnn_seo_add_metabox() {
    $screens = array( 'post', 'page' );
    foreach ( $screens as $screen ) {
        add_meta_box(
            'gnn_seo_metabox',
            esc_html__( 'SEO Settings', 'gnn-antigravity' ),
            'gnn_seo_render_metabox',
            $screen,
            'normal',
            'low'
        );
    }
}
add_action( 'add_meta_boxes', 'gnn_seo_add_metabox' );

/**
 * Render SEO metabox fields.
 *
 * @since 1.3.0
 * @param WP_Post $post Current post object.
 * @return void
 */
function gnn_seo_render_metabox( $post ) {
    wp_nonce_field( 'gnn_seo_save_nonce', 'gnn_seo_nonce' );

    $seo_title = get_post_meta( $post->ID, '_gnn_seo_title', true );
    $seo_desc  = get_post_meta( $post->ID, '_gnn_seo_description', true );
    $seo_image = get_post_meta( $post->ID, '_gnn_seo_image', true );

    echo '<div class="gnn-seo-metabox" style="padding: 10px 0;">';

    // SEO Title
    echo '<p><label for="gnn_seo_title"><strong>' . esc_html__( 'SEO Title', 'gnn-antigravity' ) . '</strong></label></p>';
    printf(
        '<input type="text" id="gnn_seo_title" name="gnn_seo_title" value="%s" style="width:100%%" placeholder="%s">',
        esc_attr( $seo_title ),
        esc_attr__( 'Leave empty to use post title', 'gnn-antigravity' )
    );
    echo '<p class="description">' . esc_html__( 'Recommended: 50-60 characters.', 'gnn-antigravity' ) . '</p>';

    // SEO Description
    echo '<p style="margin-top:15px;"><label for="gnn_seo_description"><strong>' . esc_html__( 'SEO Description', 'gnn-antigravity' ) . '</strong></label></p>';
    printf(
        '<textarea id="gnn_seo_description" name="gnn_seo_description" rows="3" style="width:100%%" placeholder="%s">%s</textarea>',
        esc_attr__( 'Leave empty to use excerpt or auto-generated', 'gnn-antigravity' ),
        esc_textarea( $seo_desc )
    );
    echo '<p class="description">' . esc_html__( 'Recommended: 120-160 characters.', 'gnn-antigravity' ) . '</p>';

    // SEO Image (for Open Graph)
    echo '<p style="margin-top:15px;"><label for="gnn_seo_image"><strong>' . esc_html__( 'Social Sharing Image URL', 'gnn-antigravity' ) . '</strong></label></p>';
    printf(
        '<input type="url" id="gnn_seo_image" name="gnn_seo_image" value="%s" style="width:100%%" placeholder="%s">',
        esc_attr( $seo_image ),
        esc_attr__( 'Leave empty to use featured image', 'gnn-antigravity' )
    );

    echo '</div>';
}

/**
 * Save SEO metabox data.
 *
 * Validates nonce, permissions, and sanitizes all input
 * before storing as post meta.
 *
 * @since 1.3.0
 * @param int $post_id Post ID being saved.
 * @return void
 */
function gnn_seo_save_metabox( $post_id ) {
    // Security checks
    if ( ! isset( $_POST['gnn_seo_nonce'] ) || ! wp_verify_nonce( $_POST['gnn_seo_nonce'], 'gnn_seo_save_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array(
        'gnn_seo_title'       => 'sanitize_text_field',
        'gnn_seo_description' => 'sanitize_textarea_field',
        'gnn_seo_image'       => 'esc_url_raw',
    );

    foreach ( $fields as $field => $sanitize_func ) {
        if ( isset( $_POST[ $field ] ) && ! empty( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, call_user_func( $sanitize_func, $_POST[ $field ] ) );
        } else {
            delete_post_meta( $post_id, '_' . $field );
        }
    }
}
add_action( 'save_post', 'gnn_seo_save_metabox' );

/**
 * Filter the document title to use custom SEO title when set.
 *
 * @since 1.3.0
 * @param array $title Document title parts.
 * @return array Modified title parts.
 */
function gnn_seo_filter_document_title( $title ) {
    if ( gnn_seo_plugin_active() ) {
        return $title;
    }

    if ( is_singular() ) {
        $seo_title = get_post_meta( get_the_ID(), '_gnn_seo_title', true );
        if ( ! empty( $seo_title ) ) {
            $title['title'] = $seo_title;
        }
    }

    return $title;
}
add_filter( 'document_title_parts', 'gnn_seo_filter_document_title' );
