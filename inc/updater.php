<?php
/**
 * GNN GitHub Theme Updater
 *
 * Checks the GitHub Releases API for new versions and integrates
 * with the WordPress theme update system. When a newer release is
 * detected, it appears in Appearance > Themes and Dashboard > Updates
 * just like themes from wordpress.org.
 *
 * Requirements:
 * - The GitHub repository must be PUBLIC (or a personal access token must be provided).
 * - GitHub Releases must have a tag matching `vX.Y.Z` format.
 * - Each release must include a `.zip` asset named `gnn-antigravity-X.Y.Z.zip`.
 *
 * @package GNN-antigravity
 * @since   1.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class GNN_GitHub_Updater
 *
 * Self-contained GitHub-based theme updater. Hooks into WordPress's
 * native update pipeline so the user can update from WP Admin.
 */
class GNN_GitHub_Updater {

    /**
     * GitHub repository owner/name.
     *
     * @var string
     */
    private $repo = 'BigDesigner/gnn-antigravity';

    /**
     * WordPress theme slug (directory name).
     *
     * @var string
     */
    private $theme_slug = 'gnn-antigravity';

    /**
     * Transient key for caching the GitHub API response.
     *
     * @var string
     */
    private $transient_key = 'gnn_github_update_check';

    /**
     * Cache duration in seconds (12 hours).
     *
     * @var int
     */
    private $cache_duration = 43200;

    /**
     * Personal access token for private repos (optional).
     * Leave empty for public repositories.
     *
     * @var string
     */
    private $access_token = '';

    /**
     * Initialize hooks.
     */
    public function __construct() {
        add_filter( 'pre_set_site_transient_update_themes', array( $this, 'check_for_update' ) );
        add_filter( 'themes_api', array( $this, 'theme_info' ), 20, 3 );
        add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );

        // Clear cache when user manually clicks "Check Again" on the updates page.
        add_action( 'load-update-core.php', array( $this, 'clear_cache' ) );
    }

    /**
     * Fetch the latest release data from GitHub API.
     *
     * Uses a WordPress transient to cache results and avoid
     * exceeding GitHub's unauthenticated rate limit (60 req/hr).
     *
     * @return object|false Release data or false on failure.
     */
    private function get_remote_release() {
        $cached = get_transient( $this->transient_key );
        if ( false !== $cached ) {
            return $cached;
        }

        $url = sprintf( 'https://api.github.com/repos/%s/releases/latest', $this->repo );

        $args = array(
            'headers' => array(
                'Accept'     => 'application/vnd.github.v3+json',
                'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
            ),
            'timeout' => 10,
        );

        // Add authorization header for private repos.
        if ( ! empty( $this->access_token ) ) {
            $args['headers']['Authorization'] = 'token ' . $this->access_token;
        }

        $response = wp_remote_get( $url, $args );

        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            // Cache the failure too, so we don't spam the API.
            set_transient( $this->transient_key, false, 300 );
            return false;
        }

        $body = json_decode( wp_remote_retrieve_body( $response ) );

        if ( empty( $body ) || ! isset( $body->tag_name ) ) {
            return false;
        }

        // Extract version from tag (strip leading 'v').
        $remote_version = ltrim( $body->tag_name, 'v' );

        // Find the zip asset in the release.
        $download_url = '';
        if ( ! empty( $body->assets ) ) {
            foreach ( $body->assets as $asset ) {
                if ( strpos( $asset->name, '.zip' ) !== false ) {
                    $download_url = $asset->browser_download_url;
                    break;
                }
            }
        }

        // Fallback to the auto-generated zipball if no asset found.
        if ( empty( $download_url ) ) {
            $download_url = $body->zipball_url;
        }

        $release_data = (object) array(
            'version'      => $remote_version,
            'download_url' => $download_url,
            'changelog'    => isset( $body->body ) ? $body->body : '',
            'published_at' => isset( $body->published_at ) ? $body->published_at : '',
            'html_url'     => isset( $body->html_url ) ? $body->html_url : '',
        );

        set_transient( $this->transient_key, $release_data, $this->cache_duration );

        return $release_data;
    }

    /**
     * Hook into the theme update check transient.
     *
     * Compares the remote GitHub version with the local theme version.
     * If the remote version is newer, injects update data into the
     * transient so WordPress shows the update notification.
     *
     * @param object $transient The update_themes transient object.
     * @return object Modified transient.
     */
    public function check_for_update( $transient ) {
        if ( empty( $transient->checked ) ) {
            return $transient;
        }

        $release = $this->get_remote_release();

        if ( false === $release || empty( $release->version ) ) {
            return $transient;
        }

        $local_version = wp_get_theme( $this->theme_slug )->get( 'Version' );

        if ( version_compare( $release->version, $local_version, '>' ) ) {
            $transient->response[ $this->theme_slug ] = array(
                'theme'       => $this->theme_slug,
                'new_version' => $release->version,
                'url'         => $release->html_url,
                'package'     => $release->download_url,
            );
        }

        return $transient;
    }

    /**
     * Provide theme information for the update details popup.
     *
     * When the user clicks "View version X.Y.Z details" in the
     * WordPress dashboard, this method supplies the changelog and
     * other metadata.
     *
     * @param false|object|array $result Default result.
     * @param string             $action API action ('theme_information').
     * @param object             $args   Arguments including slug.
     * @return false|object Theme info or false.
     */
    public function theme_info( $result, $action, $args ) {
        if ( 'theme_information' !== $action || $this->theme_slug !== $args->slug ) {
            return $result;
        }

        $release = $this->get_remote_release();

        if ( false === $release ) {
            return $result;
        }

        $theme = wp_get_theme( $this->theme_slug );

        return (object) array(
            'name'          => $theme->get( 'Name' ),
            'slug'          => $this->theme_slug,
            'version'       => $release->version,
            'author'        => $theme->get( 'Author' ),
            'homepage'      => $theme->get( 'ThemeURI' ),
            'download_link' => $release->download_url,
            'sections'      => array(
                'description' => $theme->get( 'Description' ),
                'changelog'   => nl2br( esc_html( $release->changelog ) ),
            ),
        );
    }

    /**
     * Rename the extracted directory after theme update.
     *
     * GitHub release zips extract to a folder like `gnn-antigravity-1.8.0/`
     * (from our workflow) or `BigDesigner-gnn-antigravity-abc1234/` (from
     * zipball). This hook renames it back to `gnn-antigravity/` so WordPress
     * recognizes the theme.
     *
     * @param bool  $response   Installation response.
     * @param array $hook_extra Extra info (contains the theme slug).
     * @param array $result     Installation result with destination paths.
     * @return array|WP_Error Modified result.
     */
    public function after_install( $response, $hook_extra, $result ) {
        // Only act on our theme.
        if ( ! isset( $hook_extra['theme'] ) || $hook_extra['theme'] !== $this->theme_slug ) {
            return $result;
        }

        global $wp_filesystem;

        $theme_dir = get_theme_root() . '/' . $this->theme_slug;

        // Move from extracted dir to proper theme directory.
        $wp_filesystem->move( $result['destination'], $theme_dir );
        $result['destination'] = $theme_dir;

        // Re-activate the theme if it was active before the update.
        if ( wp_get_theme()->get_stylesheet() === $this->theme_slug ) {
            switch_theme( $this->theme_slug );
        }

        // Clear the cache so the next check fetches fresh data.
        delete_transient( $this->transient_key );

        return $result;
    }

    /**
     * Clear the cached GitHub API response.
     *
     * Triggered when user visits the Updates page, ensuring
     * a fresh check is performed.
     *
     * @return void
     */
    public function clear_cache() {
        delete_transient( $this->transient_key );
    }
}

// Initialize the updater.
new GNN_GitHub_Updater();
