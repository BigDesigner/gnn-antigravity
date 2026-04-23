<?php
/**
 * GNN Helper Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if (!function_exists('gnn_get_youtube_id')) {
    /**
     * Extracts the YouTube video ID from a URL.
     * Supports various formats including shortened and embed URLs.
     */
    function gnn_get_youtube_id($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return isset($match[1]) ? $match[1] : false;
    }
}
