<?php
get_header();
?>

<main class="service-single-page">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $service_id = get_the_ID();
            $cover = bis_get_service_image_url($service_id);
            $description = bis_get_service_description($service_id);
            ?>

            <section class="news-hero news-hero--single">
                <?php if ($cover) : ?>
                    <div class="news-hero__media">
                        <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>" decoding="async">
                    </div>
                <?php endif; ?>
                <div class="news-hero__overlay">
                    <h1 class="news-hero__title"><?php the_title(); ?></h1>
                </div>
            </section>

            <section class="breadcrumbs-section">
                <nav class="project-breadcrumbs mw-1400px">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                    <span class="breadcrumbs-delimiter">/</span>
                    <a href="<?php echo esc_url(get_post_type_archive_link('bis_service')); ?>">Услуги</a>
                    <span class="breadcrumbs-delimiter">/</span>
                    <span><?php the_title(); ?></span>
                </nav>
            </section>

            <section class="service-article">
                <div class="service-article__container">
                    <?php if ($description !== '') : ?>
                        <div class="service-article__header">
                            <p class="service-article__lead"><?php echo esc_html($description); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="news-article__content service-article__content">
                        <?php
                        if (trim((string) get_the_content()) !== '') {
                            the_content();
                        } elseif ($description !== '') {
                            echo wpautop(esc_html($description));
                        }
                        ?>
                    </div>
                </div>
            </section>

            <?php
            $related_services = new WP_Query(array(
                'post_type'      => 'bis_service',
                'post_status'    => 'publish',
                'posts_per_page' => 4,
                'post__not_in'   => array($service_id),
                'orderby'        => array('menu_order' => 'ASC', 'title' => 'ASC'),
            ));
            ?>

            <?php if ($related_services->have_posts()) : ?>
                <section class="service-related">
                    <div class="service-related__container">
                        <h2 class="section-title">Другие услуги</h2>
                        <div class="services-grid">
                            <?php while ($related_services->have_posts()) : $related_services->the_post(); ?>
                                <?php
                                $related_id = get_the_ID();
                                $related_image = bis_get_service_image_url($related_id);
                                $related_description = bis_get_service_description($related_id);
                                ?>
                                <article class="service-card">
                                    <a class="service-image" href="<?php the_permalink(); ?>">
                                        <img src="<?php echo esc_url($related_image); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" decoding="async">
                                    </a>
                                    <div class="service-content">
                                        <div class="service-content-main">
                                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <?php if ($related_description !== '') : ?>
                                                <p class="experience-description"><?php echo esc_html($related_description); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <a class="btn btn-primary service-card__link" href="<?php the_permalink(); ?>">Подробнее</a>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
