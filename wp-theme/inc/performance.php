<?php

function bis_add_resource_hints($urls, $relation_type) {
    if ('preconnect' !== $relation_type) {
        return $urls;
    }

    $urls[] = 'https://fonts.googleapis.com';
    $urls[] = array(
        'href' => 'https://fonts.gstatic.com',
        'crossorigin' => 'anonymous',
    );

    return $urls;
}
add_filter('wp_resource_hints', 'bis_add_resource_hints', 10, 2);

function bis_get_asset_version($relative_path, $fallback = '1.0') {
    $absolute_path = get_template_directory() . '/' . ltrim($relative_path, '/');

    if (!file_exists($absolute_path)) {
        return $fallback;
    }

    $modified_at = filemtime($absolute_path);
    return $modified_at ? (string) $modified_at : $fallback;
}
