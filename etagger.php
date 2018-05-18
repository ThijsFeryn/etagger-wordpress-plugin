<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Etagger
 *
 * @wordpress-plugin
 * Plugin Name:       Etagger
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       This plugin returns an Etag for posts, pages, and attachments. It also returns a "304 Not Modified" for subsequent requests where the Etag matches
 * Version:           1.0.0
 * Author:            Thijs Feryn
 * Author URI:        https://feryn.eu/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       etagger
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ETAGGER_VERSION', '1.0.0' );

register_activation_hook( __FILE__, function() {
    global $wp_filesystem;
    if (file_exists(WP_CONTENT_DIR . '/advanced-cache.php')){
        if (WP_Filesystem(request_filesystem_credentials( '' ))) {
            $wp_filesystem->copy(plugin_dir_path( __FILE__ ) . '/includes/advanced-cache.php', WP_CONTENT_DIR . '/advanced-cache.php', true);
        }
    }
});
register_deactivation_hook( __FILE__, function() {
    global $wp_filesystem;
    if (file_exists(WP_CONTENT_DIR . '/advanced-cache.php')){
        if (WP_Filesystem(request_filesystem_credentials( '' ))) {
            $wp_filesystem->delete(WP_CONTENT_DIR . '/advanced-cache.php');
        }
    }
});

add_action('template_redirect', function() {
    global $post;
    if (
        (defined('WP_CACHE') && WP_CACHE) &&
        !(defined('DOING_AJAX') && DOING_AJAX) &&
        !(defined('DOING_CRON') && DOING_CRON) &&
        !(defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) &&
        !(defined('REST_REQUEST') && REST_REQUEST) &&
        !is_admin() &&
        in_array($post->post_type,array('post','page','attachment'))
    ) {
        $etag = sha1($post->post_content);
        wp_cache_add(sha1($_SERVER['REQUEST_URI']), $etag, 'etag');
        header('Etag: '.$etag);
    }
});