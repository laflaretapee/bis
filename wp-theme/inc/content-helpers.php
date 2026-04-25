<?php

function bis_get_project_image_url($post_id) {
    $banner = get_post_meta($post_id, 'bis_project_banner_image', true);
    if ($banner) {
        return bis_get_optimized_image_url($banner, 'bis-card');
    }

    $legacy = get_post_meta($post_id, 'bis_project_image', true);
    if ($legacy) {
        return bis_get_optimized_image_url($legacy, 'bis-card');
    }

    return bis_get_post_thumbnail_optimized_url($post_id, 'bis-card');
}

function bis_get_project_details($post_id) {
    return array(
        'address' => get_post_meta($post_id, 'bis_project_address', true),
        'area'    => get_post_meta($post_id, 'bis_project_area', true),
        'year'    => get_post_meta($post_id, 'bis_project_year', true),
    );
}

function bis_get_project_banner_image($post_id) {
    $banner = get_post_meta($post_id, 'bis_project_banner_image', true);
    if ($banner) {
        return bis_get_optimized_image_url($banner, 'bis-banner');
    }

    $legacy = get_post_meta($post_id, 'bis_project_image', true);
    if ($legacy) {
        return bis_get_optimized_image_url($legacy, 'bis-banner');
    }

    return bis_get_post_thumbnail_optimized_url($post_id, 'bis-banner');
}

function bis_get_project_banner_title($post_id) {
    $title = get_post_meta($post_id, 'bis_project_banner_title', true);
    if ($title) {
        return $title;
    }

    return get_the_title($post_id);
}

function bis_get_project_banner_blocks($post_id) {
    $blocks = get_post_meta($post_id, 'bis_project_banner_blocks', true);
    return is_array($blocks) ? $blocks : array();
}

function bis_get_project_description($post_id) {
    $description = get_post_meta($post_id, 'bis_project_description', true);
    return is_string($description) ? trim($description) : '';
}

function bis_get_project_gallery($post_id) {
    $gallery = get_post_meta($post_id, 'bis_project_gallery', true);
    return is_array($gallery) ? $gallery : array();
}

function bis_get_gratitude_image_url($post_id) {
    $custom = get_post_meta($post_id, 'bis_gratitude_image', true);
    if ($custom) {
        return bis_get_optimized_image_url($custom, 'bis-card');
    }

    return bis_get_post_thumbnail_optimized_url($post_id, 'bis-card');
}

function bis_get_news_image_url($post_id) {
    $custom = get_post_meta($post_id, 'bis_news_image', true);
    if ($custom) {
        return bis_get_optimized_image_url($custom, 'bis-card');
    }

    $thumb = bis_get_post_thumbnail_optimized_url($post_id, 'bis-card');
    if ($thumb) {
        return $thumb;
    }

    return 'https://placehold.co/600x400';
}

function bis_get_service_image_url($post_id) {
    $custom = get_post_meta($post_id, 'bis_service_image', true);
    if ($custom) {
        return bis_get_optimized_image_url($custom, 'bis-card');
    }

    return bis_get_post_thumbnail_optimized_url($post_id, 'bis-card');
}

function bis_get_equipment_image_url($post_id) {
    $custom = get_post_meta($post_id, 'bis_equipment_image', true);
    if ($custom) {
        return bis_get_optimized_image_url($custom, 'bis-card');
    }

    return bis_get_post_thumbnail_optimized_url($post_id, 'bis-card');
}

function bis_get_team_members() {
    $members = get_option('bis_team_members', array());
    if (!is_array($members)) {
        return array();
    }

    $filtered = array();
    foreach ($members as $member) {
        if (!is_array($member)) {
            continue;
        }
        $filtered[] = $member;
    }

    return $filtered;
}

function bis_get_phone_href($phone) {
    $phone = trim((string) $phone);
    if ($phone === '') {
        return '';
    }

    $digits = preg_replace('/\D+/', '', $phone);
    if ($digits === '') {
        return '';
    }

    if ('8' === substr($digits, 0, 1)) {
        $digits = '7' . substr($digits, 1);
    }

    if ('+' !== substr($phone, 0, 1)) {
        $digits = '+' . $digits;
    } else {
        $digits = '+' . ltrim($digits, '+');
    }

    return 'tel:' . $digits;
}
