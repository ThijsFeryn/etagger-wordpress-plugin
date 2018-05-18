<?php
if (
    !(defined('DOING_AJAX') && DOING_AJAX) &&
    !(defined('DOING_CRON') && DOING_CRON) &&
    !(defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) &&
    !(defined('REST_REQUEST') && REST_REQUEST) &&
    !is_admin()
) {
    wp_start_object_cache();
    $etag = wp_cache_get(sha1($_SERVER['REQUEST_URI']),'etag' );
    if($etag !== false && $etag == $_SERVER['HTTP_IF_NONE_MATCH']) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
}