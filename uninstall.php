<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;

global $wp_filesystem;

if (file_exists(WP_CONTENT_DIR . '/advanced-cache.php')){
    if (WP_Filesystem(request_filesystem_credentials( '' ))) {
        $wp_filesystem->delete(WP_CONTENT_DIR . '/advanced-cache.php');
    }
}