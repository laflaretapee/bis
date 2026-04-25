<?php

function bis_build_public_query_args($post_type, array $args = array()) {
    $defaults = array(
        'post_type'           => $post_type,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
    );

    return wp_parse_args($args, $defaults);
}

function bis_build_collection_query_args($post_type, array $args = array()) {
    return bis_build_public_query_args($post_type, wp_parse_args($args, array(
        'no_found_rows' => true,
    )));
}
