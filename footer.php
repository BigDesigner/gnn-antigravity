</div> <!-- End content-area -->
</main> <!-- End swup -->
<footer class="site-bottom-bar">
    <nav class="corner-nav bottom-left">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'footer-menu',
            'container' => false,
            'fallback_cb' => false,
            'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        ));
        ?>
    </nav>
    <div class="corner-nav bottom-right">
        <a href="https://gnn.tr" target="_blank">
            <?php echo esc_html(get_theme_mod('copyright_text', '© GNNcreative')); ?>
        </a>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>