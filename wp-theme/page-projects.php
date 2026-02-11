<?php
/*
Template Name: Проекты
*/
get_header();
?>

<?php
$page_id = get_the_ID();
$banner_title = get_post_meta($page_id, 'bis_page_banner_title', true);
$banner_subtitle = get_post_meta($page_id, 'bis_page_banner_subtitle', true);
$banner_title = $banner_title ? $banner_title : get_the_title();
$banner_image = get_post_meta($page_id, 'bis_page_banner_image', true);
$banner_image = $banner_image ? $banner_image : get_the_post_thumbnail_url($page_id, 'full');

$selected_area = isset($_GET['project_area']) ? sanitize_text_field(wp_unslash($_GET['project_area'])) : (isset($_GET['area']) ? sanitize_text_field(wp_unslash($_GET['area'])) : '');
$selected_year = isset($_GET['project_year']) ? sanitize_text_field(wp_unslash($_GET['project_year'])) : (isset($_GET['year']) ? sanitize_text_field(wp_unslash($_GET['year'])) : '');
$selected_type = isset($_GET['project_type']) ? sanitize_text_field(wp_unslash($_GET['project_type'])) : '';
$selected_service = isset($_GET['service']) ? sanitize_text_field(wp_unslash($_GET['service'])) : '';

global $wpdb;
$areas = $wpdb->get_col($wpdb->prepare(
    "SELECT DISTINCT pm.meta_value
     FROM {$wpdb->postmeta} pm
     INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
     WHERE p.post_type = %s AND p.post_status = 'publish' AND pm.meta_key = %s AND pm.meta_value <> ''",
    'bis_project',
    'bis_project_area'
));
$years = $wpdb->get_col($wpdb->prepare(
    "SELECT DISTINCT pm.meta_value
     FROM {$wpdb->postmeta} pm
     INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
     WHERE p.post_type = %s AND p.post_status = 'publish' AND pm.meta_key = %s AND pm.meta_value <> ''",
    'bis_project',
    'bis_project_year'
));

$areas = is_array($areas) ? $areas : array();
$years = is_array($years) ? $years : array();

$areas = array_values(array_unique(array_filter(array_map('trim', $areas), 'strlen')));
$years = array_values(array_unique(array_filter(array_map('trim', $years), 'strlen')));

usort($areas, function ($a, $b) {
    $a_num = (int) preg_replace('/[^0-9]+/', '', $a);
    $b_num = (int) preg_replace('/[^0-9]+/', '', $b);
    if ($a_num === $b_num) {
        return strnatcasecmp($a, $b);
    }
    return $a_num <=> $b_num;
});

usort($years, function ($a, $b) {
    return (int) $b <=> (int) $a;
});

$project_types = get_terms(array(
    'taxonomy' => 'bis_project_type',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
));
$project_services = get_terms(array(
    'taxonomy' => 'bis_project_service',
    'hide_empty' => false,
    'orderby' => 'name',
    'order' => 'ASC',
));

if (is_wp_error($project_types)) {
    $project_types = array();
}
if (is_wp_error($project_services)) {
    $project_services = array();
}
?>

<main class="projects-page">
    <section class="news-hero news-hero--page">
        <?php if ($banner_image) : ?>
            <div class="news-hero__media" style="background-image: url('<?php echo esc_url($banner_image); ?>');"></div>
        <?php endif; ?>
        <div class="news-hero__overlay">
            <h1 class="news-hero__title"><?php echo esc_html($banner_title); ?></h1>
            <?php if (!empty($banner_subtitle)) : ?>
                <p class="news-hero__text"><?php echo esc_html($banner_subtitle); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <section class="breadcrumbs-section">
        <nav class="project-breadcrumbs mw-1400px">
            <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
            <span class="breadcrumbs-delimiter">/</span>
            <span><?php echo esc_html($banner_title); ?></span>
        </nav>
    </section>

    <section class="projects-list">
        <form class="projects-filters" method="get" action="<?php echo esc_url(get_permalink($page_id)); ?>">
            <div class="projects-filters__item">
                <label class="screen-reader-text" for="projects-filter-area">Площадь</label>
                <select id="projects-filter-area" name="project_area" class="projects-filter" onchange="this.form.submit()">
                    <option value="">Площадь</option>
                    <?php foreach ($areas as $area) : ?>
                        <option value="<?php echo esc_attr($area); ?>" <?php selected($selected_area, $area); ?>><?php echo esc_html($area); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="projects-filters__item">
                <label class="screen-reader-text" for="projects-filter-year">Год</label>
                <select id="projects-filter-year" name="project_year" class="projects-filter" onchange="this.form.submit()">
                    <option value="">Год</option>
                    <?php foreach ($years as $year) : ?>
                        <option value="<?php echo esc_attr($year); ?>" <?php selected($selected_year, $year); ?>><?php echo esc_html($year); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="projects-filters__item">
                <label class="screen-reader-text" for="projects-filter-type">Тип проекта</label>
                <select id="projects-filter-type" name="project_type" class="projects-filter" onchange="this.form.submit()">
                    <option value="">Тип проекта</option>
                    <?php foreach ($project_types as $type) : ?>
                        <option value="<?php echo esc_attr($type->slug); ?>" <?php selected($selected_type, $type->slug); ?>><?php echo esc_html($type->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="projects-filters__item">
                <label class="screen-reader-text" for="projects-filter-service">Услуга</label>
                <select id="projects-filter-service" name="service" class="projects-filter" onchange="this.form.submit()">
                    <option value="">Услуга</option>
                    <?php foreach ($project_services as $service) : ?>
                        <option value="<?php echo esc_attr($service->slug); ?>" <?php selected($selected_service, $service->slug); ?>><?php echo esc_html($service->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <div class="experience-grid projects-grid">
            <?php
            $projects_args = array(
                'post_type'      => 'bis_project',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
            );

            $meta_query = array();
            if ($selected_area !== '') {
                $meta_query[] = array(
                    'key'     => 'bis_project_area',
                    'value'   => $selected_area,
                    'compare' => '=',
                );
            }
            if ($selected_year !== '') {
                $meta_query[] = array(
                    'key'     => 'bis_project_year',
                    'value'   => $selected_year,
                    'compare' => '=',
                );
            }
            if (!empty($meta_query)) {
                $projects_args['meta_query'] = $meta_query;
            }

            $tax_query = array();
            if ($selected_type !== '') {
                $tax_query[] = array(
                    'taxonomy' => 'bis_project_type',
                    'field'    => 'slug',
                    'terms'    => array($selected_type),
                );
            }
            if ($selected_service !== '') {
                $tax_query[] = array(
                    'taxonomy' => 'bis_project_service',
                    'field'    => 'slug',
                    'terms'    => array($selected_service),
                );
            }
            if (!empty($tax_query)) {
                $projects_args['tax_query'] = $tax_query;
            }

            $projects = new WP_Query($projects_args);
            ?>

            <?php if ($projects->have_posts()) : ?>
                <?php while ($projects->have_posts()) : $projects->the_post(); ?>
                    <?php
                    $project_id = get_the_ID();
                    $image_url = bis_get_project_image_url($project_id);
                    $description = bis_get_project_description($project_id);
                    $is_featured = get_post_meta($project_id, 'bis_project_is_featured', true) === '1';
                    ?>
                    <div class="experience-card">
                        <div class="experience-image" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                        <div class="experience-content">
                            <?php if ($is_featured) : ?>
                                <span class="experience-badge">Ключевой проект</span>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if (!empty($description)) : ?>
                                <p class="experience-description"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                            <a class="experience-more" href="<?php echo esc_url(get_permalink($project_id)); ?>">Подробнее<span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <?php $has_filters = ($selected_area !== '' || $selected_year !== '' || $selected_type !== '' || $selected_service !== ''); ?>
                <div class="team-empty">
                    <span class="team-empty__label">Проекты</span>
                    <?php if ($has_filters) : ?>
                        <p>Проекты по выбранным фильтрам не найдены.</p>
                    <?php else : ?>
                        <p>Мы готовим презентацию наших проектов.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>
