</div> <!-- End content-area -->
</main> <!-- End swup -->

<?php if (get_theme_mod('footer_show_back_to_top', false)): ?>
    <button id="gnn-back-to-top" class="gnn-back-to-top" aria-label="<?php esc_attr_e('Back to top', 'gnn-antigravity'); ?>">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>
<?php endif; ?>

<?php if (get_theme_mod('enable_custom_cursor', true)): ?>
    <div class="gnn-cursor"></div>
    <div class="gnn-cursor-follower"></div>
<?php endif; ?>


<footer class="site-bottom-bar">
    <nav class="corner-nav bottom-left" aria-label="<?php esc_attr_e('Footer Menu', 'gnn-antigravity'); ?>">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer-menu',
            'container'      => false,
            'fallback_cb'    => false,
            'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        ));
        ?>
    </nav>
    <div class="corner-nav bottom-right">
        <a href="<?php echo esc_url(get_theme_mod('copyright_url', 'https://gnn.tr')); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo esc_html(get_theme_mod('copyright_text', '© GNNcreative')); ?>
        </a>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>