<?php
/*
Template Name: Услуги
*/
get_header();
?>

<main class="services-archive-page">
    <section class="news-hero" style="padding-inline: 8vw;">
        <div class="news-hero__overlay mx-1400px">
            <h1 class="news-hero__title">Услуги</h1>
            <p class="news-hero__text">Все услуги компании в одном списке без фильтрации.</p>
        </div>
    </section>

    <section class="breadcrumbs-section">
        <nav class="project-breadcrumbs mw-1400px">
            <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
            <span class="breadcrumbs-delimiter">/</span>
            <span>Услуги</span>
        </nav>
    </section>

    <section class="services-archive-list">
        <div class="services-archive-list__container">
            <?php if (have_posts()) : ?>
                <div class="services-grid" style="min-width: 100%;margin: 0;">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        $service_id = get_the_ID();
                        $image_url = bis_get_service_image_url($service_id);
                        $description = bis_get_service_description($service_id);
                        ?>
                        <article class="service-card">
                            <a class="service-image" href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async">
                            </a>
                            <div class="service-content">
                                <div class="service-content-main">
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <?php if ($description !== '') : ?>
                                        <p class="experience-description"><?php echo esc_html($description); ?></p>
                                    <?php endif; ?>
                                </div>
                                <a class="btn btn-primary service-card__link" href="<?php the_permalink(); ?>">Подробнее</a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="team-empty">
                    <span class="team-empty__label">Услуги</span>
                    <p>Список услуг пока пуст.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>
