<?php
/**
 * GNN Helper Functions
 *
 * @package GNN-antigravity
 * @since   1.0.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if (!function_exists('gnn_get_youtube_id')) {
    /**
     * Extracts the YouTube video ID from a URL.
     * 
     * Supports various formats including shortened (youtu.be), 
     * embed, and standard watch URLs.
     *
     * @param string $url The YouTube URL to parse.
     * @return string|false The 11-character video ID, or false if not found.
     */
    function gnn_get_youtube_id($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
        return isset($match[1]) ? $match[1] : false;
    }
}
