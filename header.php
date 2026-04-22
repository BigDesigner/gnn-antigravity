<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Critical Fallback Styles */
        body {
            background: #000;
            color: #fff;
            margin: 0;
        }

        .site-top-bar {
            position:
                <?php echo esc_attr(get_theme_mod('header_sticky', 'fixed')); ?>
                !important;
            width: 100%;
            left: 0;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2000;
            background:
                <?php echo (get_theme_mod('header_bg_type', 'transparent') == 'colored') ? esc_attr(get_theme_mod('header_bg_color', '#000000')) : 'transparent'; ?>
            ;
            transition: background 0.3s ease;
        }

        .site-bottom-bar {
            position: static !important;
            width: 100%;
            left: 0;
            bottom: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2000;
            background:
                <?php echo (get_theme_mod('footer_bg_type', 'transparent') == 'colored') ? esc_attr(get_theme_mod('footer_bg_color', '#000000')) : 'transparent'; ?>
            ;
            transition: background 0.3s ease;
        }
    </style>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="mobile-menu-overlay">
        <div class="mobile-menu-content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'header-menu',
                'container' => false,
                'items_wrap' => '<ul>%3$s</ul>',
            ));
            ?>
        </div>
    </div>

    <header class="site-top-bar">
        <div class="corner-nav top-left site-branding">
            <?php
            if (function_exists('the_custom_logo') && has_custom_logo()) {
                the_custom_logo();
            } else {
                ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title" style="font-weight:700;">
                    <?php echo esc_html(get_theme_mod('logo_text', get_bloginfo('name'))); ?>
                </a>
                <?php
            }
            ?>
        </div>


        <div class="nav-right-container" style="display: flex; align-items: center;">
            <nav class="corner-nav top-right desktop-only">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'header-menu',
                    'container' => false,
                    'fallback_cb' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                ));
                ?>
            </nav>
            <?php if (get_theme_mod('enable_mobile_menu', true)): ?>
                <div id="hamburger-menu" class="mobile-only">
                    <span></span><span></span>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main id="swup" class="transition-fade">
        <?php
        $obj_id = get_queried_object_id();
        $hero_type = get_post_meta($obj_id, '_gnn_hero_type', true) ?: 'default';
        $hero_media = get_post_meta($obj_id, '_gnn_hero_media', true);
        $hero_custom = get_post_meta($obj_id, '_gnn_hero_custom_text', true);

        if ($hero_type !== 'hidden'):
            ?>
            <div class="hero-container drift <?php echo 'hero-' . esc_attr($hero_type); ?>">

                <?php if ($hero_type === 'video' && $hero_media): ?>
                    <?php
                    $yt_id = gnn_get_youtube_id($hero_media);
                    if ($yt_id):
                        ?>
                        <div class="hero-video-bg">
                            <iframe
                                src="https://www.youtube.com/embed/<?php echo esc_attr($yt_id); ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo esc_attr($yt_id); ?>&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3"
                                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen
                                style="width: 100%; height: 100%; pointer-events: none;">
                            </iframe>
                        </div>
                    <?php else: ?>
                        <video class="hero-video-bg" autoplay muted loop playsinline>
                            <source src="<?php echo esc_url($hero_media); ?>" type="video/mp4">
                        </video>
                    <?php endif; ?>
                    <div class="hero-media-overlay"></div>
                <?php endif; ?>

                <?php if ($hero_type === 'image' && $hero_media): ?>
                    <div class="hero-image-bg" style="background-image: url('<?php echo esc_url($hero_media); ?>');"></div>
                    <div class="hero-media-overlay"></div>
                <?php endif; ?>

                <div class="hero-content-wrapper">
                    <h1 class="hero-title">
                        <?php
                        if ($hero_type === 'custom_text' && $hero_custom) {
                            echo nl2br(esc_html($hero_custom));
                        } elseif (is_front_page()) {
                            echo esc_html(get_theme_mod('hero_title', 'Build the new way.'));
                        } else {
                            echo esc_html(get_the_title($obj_id));
                        }
                        ?>
                    </h1>
                    <?php if (is_front_page() && $hero_type === 'default'): ?>
                        <p class="hero-subtitle">
                            <?php echo esc_html(get_theme_mod('hero_subtitle', 'Experimental workspace for agentic development.')); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div id="content-area">