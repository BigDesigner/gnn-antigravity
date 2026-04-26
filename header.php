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
            position: fixed !important;
            width: 100%;
            left: 0;
            top: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2000;
            padding: 0 2rem;
            height: var(--header-height, 80px);
            background: transparent;
            transition: all 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        }

        .hero-title {
            font-size: clamp(3rem, 10vw, 8rem);
            font-weight: 700;
            text-transform: uppercase;
            line-height: 0.9;
            margin: 0;
        }

        .corner-nav {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
        }

.site-top-bar.is-scrolled {
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    height: calc(var(--header-height, 80px) * 0.8);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
    <a class="skip-link screen-reader-text" href="#content-area"><?php esc_html_e( 'Skip to content', 'gnn-antigravity' ); ?></a>

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
            <nav class="corner-nav top-right desktop-only" aria-label="<?php esc_attr_e('Primary Menu', 'gnn-antigravity'); ?>">
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
                <div id="hamburger-menu" class="mobile-only" 
                     role="button" 
                     tabindex="0" 
                     aria-expanded="false" 
                     aria-label="<?php esc_attr_e('Toggle Menu', 'gnn-antigravity'); ?>">
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
        $enable_slider = get_theme_mod('enable_hero_slider', false);

        if ((is_front_page() || is_home()) && $enable_slider):
            $slider_speed = get_theme_mod('slider_speed', 6000);
            $slider_pause = get_theme_mod('slider_pause_hover', true) ? 'true' : 'false';
            $show_nav = get_theme_mod('slider_show_nav', true);
            $show_dots = get_theme_mod('slider_show_dots', true);
            ?>
            <div class="gnn-hero-slider-wrapper" 
                 tabindex="0"
                 aria-label="<?php esc_attr_e('Hero Slider', 'gnn-antigravity'); ?>"
                 data-speed="<?php echo esc_attr($slider_speed); ?>" 
                 data-pause="<?php echo esc_attr($slider_pause); ?>">
                <div class="gnn-hero-slider">
                    <?php for ($i = 1; $i <= 3; $i++): 
                        $img = get_theme_mod("slider_image_{$i}");
                        $title = get_theme_mod("slider_title_{$i}");
                        $sub = get_theme_mod("slider_subtitle_{$i}");
                        $link = get_theme_mod("slider_link_{$i}");
                        if (!$img && !$title) continue;
                    ?>
                    <div class="gnn-slide" aria-label="<?php printf(esc_attr__('Slide %d', 'gnn-antigravity'), $i); ?>">
                        <?php if ($img): ?>
                            <div class="slide-bg" style="background-image: url('<?php echo esc_url($img); ?>');"></div>
                        <?php endif; ?>
                        <div class="hero-media-overlay"></div>
                        <div class="hero-content-wrapper">
                            <?php if ($title): ?>
                                <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
                            <?php endif; ?>
                            <?php if ($sub): ?>
                                <p class="hero-subtitle"><?php echo esc_html($sub); ?></p>
                            <?php endif; ?>
                            <?php if ($link): ?>
                                <a href="<?php echo esc_url($link); ?>" class="gnn-btn slide-btn"><?php esc_html_e('Explore', 'gnn-antigravity'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

                <?php if ($show_nav): ?>
                <div class="slider-nav">
                    <button class="slider-prev" aria-label="<?php esc_attr_e('Previous slide', 'gnn-antigravity'); ?>">←</button>
                    <button class="slider-next" aria-label="<?php esc_attr_e('Next slide', 'gnn-antigravity'); ?>">→</button>
                </div>
                <?php endif; ?>

                <?php if ($show_dots): ?>
                <div class="slider-dots"></div>
                <?php endif; ?>
            </div>

        <?php elseif ((is_front_page() || is_home()) && get_theme_mod('hero_static_image')):
            // Static Hero Image — shown on front page when slider is disabled and an image is uploaded
            $static_img = get_theme_mod('hero_static_image');
            $overlay_opacity = floatval(get_theme_mod('hero_static_overlay_opacity', 0.4));
            ?>
            <div class="gnn-hero-static-wrapper" aria-label="<?php esc_attr_e('Hero Image', 'gnn-antigravity'); ?>">
                <div class="gnn-hero-static-bg" style="background-image: url('<?php echo esc_url($static_img); ?>');"></div>
                <div class="hero-media-overlay" style="opacity: <?php echo esc_attr($overlay_opacity); ?>;"></div>
                <div class="hero-content-wrapper">
                    <h1 class="hero-title">
                        <?php echo esc_html(get_theme_mod('hero_title', 'Build the new way.')); ?>
                    </h1>
                    <p class="hero-subtitle">
                        <?php echo esc_html(get_theme_mod('hero_subtitle', 'Experimental workspace for agentic development.')); ?>
                    </p>
                </div>
            </div>

        <?php elseif ($hero_type !== 'hidden'): ?>
            <div class="hero-container drift <?php echo 'hero-' . esc_attr($hero_type); ?>">
                <!-- Static Hero Logic -->
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