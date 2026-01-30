<?php
/*
Template Name: Проекты
*/
get_header();
?>

<main class="projects-page">
    <section class="projects-hero" style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>');background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
        height: 95vh;">
        <div class="projects-hero__overlay" style="height: 100%;">
            <h1 class="projects-hero__title"><?php the_title(); ?></h1>
        </div>
    </section>
    <section class="breadcrumbs-section">
    <nav class="project-breadcrumbs mw-1400px">
                <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                <span class="breadcrumbs-delimiter">/</span>
                <span><?php the_title(); ?></span>
            </nav>
            </section>
    <section class="projects-list">
        <div class="experience-grid projects-grid">
            <?php
            $projects = new WP_Query(array(
                'post_type'      => 'bis_project',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
            ));
            ?>

            <?php if ($projects->have_posts()) : ?>
                <?php while ($projects->have_posts()) : $projects->the_post(); ?>
                    <?php
                    $project_id = get_the_ID();
                    $image_url = bis_get_project_image_url($project_id);
                    $description = bis_get_project_description($project_id);
                    $description_excerpt = $description ? wp_trim_words($description, 22, '…') : '';
                    $is_featured = get_post_meta($project_id, 'bis_project_is_featured', true) === '1';
                    ?>
                    <div class="experience-card">
                        <div class="experience-image" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
                        <div class="experience-content">
                            <?php if ($is_featured) : ?>
                                <span class="experience-badge">Ключевой проект</span>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <?php if ($description_excerpt) : ?>
                                <p class="experience-description"><?php echo esc_html($description_excerpt); ?></p>
                            <?php endif; ?>
                            <a class="experience-more" href="<?php echo esc_url(get_permalink($project_id)); ?>">Подробнее<span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p class="section-subtitle">Добавьте проекты в админке, чтобы показать их здесь.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>
