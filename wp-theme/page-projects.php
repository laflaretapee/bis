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
$selected_type = isset($_GET['project_type']) ? sanitize_title(wp_unslash($_GET['project_type'])) : '';

$project_types = get_terms(array(
    'taxonomy' => 'bis_project_type',
    'hide_empty' => true,
    'orderby' => 'name',
    'order' => 'ASC',
));

if (is_wp_error($project_types)) {
    $project_types = array();
}

$selected_type_term = null;
if ($selected_type !== '') {
    $selected_type_term = get_term_by('slug', $selected_type, 'bis_project_type');
    if (!($selected_type_term instanceof WP_Term)) {
        $selected_type_term = null;
    }
}

$projects_args = array(
    'post_type'      => 'bis_project',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
);

if ($selected_type !== '') {
    $projects_args['tax_query'] = array(
        array(
            'taxonomy' => 'bis_project_type',
            'field'    => 'slug',
            'terms'    => array($selected_type),
        ),
    );
}

$projects = new WP_Query($projects_args);
?>

<main class="projects-page">
    <section class="news-hero news-hero--page">
        <?php if ($banner_image) : ?>
            <div class="news-hero__media" style="background-image: url('<?php echo esc_url($banner_image); ?>');"></div>
        <?php endif; ?>
        <div class="news-hero__overlay">
            <div class="mw-1400px projects-page-hero__content">
                <h1 class="news-hero__title"><?php echo esc_html($banner_title); ?></h1>
                <?php if (!empty($banner_subtitle)) : ?>
                    <p class="news-hero__text"><?php echo esc_html($banner_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="breadcrumbs-section">
        <nav class="project-breadcrumbs mw-1400px">
            <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
            <span class="breadcrumbs-delimiter">/</span>
            <span><?php echo esc_html($banner_title); ?></span>
        </nav>
    </section>

    <section class="projects-types">
        <nav class="projects-types__list mw-1400px" aria-label="Типы проектов">
            <a class="projects-types__link <?php echo $selected_type === '' ? 'is-active' : ''; ?>" href="<?php echo esc_url(get_permalink($page_id)); ?>">Все проекты</a>
            <?php foreach ($project_types as $type) : ?>
                <a class="projects-types__link <?php echo $selected_type === $type->slug ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('project_type', $type->slug, get_permalink($page_id))); ?>">
                    <?php echo esc_html($type->name); ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </section>

    <section class="projects-list">
        <div class="experience-grid projects-grid">
            <?php if ($projects->have_posts()) : ?>
                <?php while ($projects->have_posts()) : $projects->the_post(); ?>
                    <?php
                    $project_id = get_the_ID();
                    $image_url = bis_get_project_image_url($project_id);
                    $description = bis_get_project_description($project_id);
                    $is_featured = get_post_meta($project_id, 'bis_project_is_featured', true) === '1';
                    $project_type_terms = get_the_terms($project_id, 'bis_project_type');
                    $project_type_names = array();

                    if (is_array($project_type_terms) && !is_wp_error($project_type_terms)) {
                        foreach ($project_type_terms as $project_type_term) {
                            $project_type_names[] = $project_type_term->name;
                        }
                    }
                    ?>
                    <div class="experience-card">
                        <div class="experience-image" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                        <div class="experience-content">
                            <?php if ($is_featured) : ?>
                                <span class="experience-badge">Ключевой проект</span>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if (!empty($project_type_names)) : ?>
                                <p class="experience-project-type"><?php echo esc_html(implode(', ', $project_type_names)); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($description)) : ?>
                                <p class="experience-description"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                            <a class="experience-more" href="<?php echo esc_url(get_permalink($project_id)); ?>">Подробнее<span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="team-empty">
                    <span class="team-empty__label">Проекты</span>
                    <?php if ($selected_type_term) : ?>
                        <p>Проекты типа «<?php echo esc_html($selected_type_term->name); ?>» пока не найдены.</p>
                    <?php else : ?>
                        <p>Мы готовим презентацию наших проектов.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>
