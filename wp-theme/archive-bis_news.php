<?php
/*
Template Name: Новости
*/
get_header();
?>

<main class="news-archive-page">
    <section class="news-hero">
        <div class="news-hero__media" style="background-image: url('<?php echo esc_url($cover); ?>');"></div>
        <div class="news-hero__overlay">
            <h1 class="news-hero__title">Новости</h1>
            <p class="news-hero__text">Комплексная экспертиза в инженерных системах, исследования и практические кейсы — рассказываем о проектах и жизни команды «БИС — Баланс Инженерных Систем».</p>
        </div>
    </section>

    <section class="breadcrumbs-section">
        <nav class="project-breadcrumbs mw-1400px">
            <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
            <span class="breadcrumbs-delimiter">/</span>
            <span>Новости</span>
        </nav>
    </section>

    <section class="news-list">
        <div class="news-list__container">
            <?php if (have_posts()) : ?>
                <div class="news-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php $image_url = bis_get_news_image_url(get_the_ID()); ?>
                        <article class="news-item">
                            <a class="news-item__image" href="<?php the_permalink(); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                            </a>
                            <div class="news-item__body">
                                <time class="news-item__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d.m.Y')); ?></time>
                                <h3 class="news-item__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p class="news-item__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="news-pagination">
                    <?php
                    the_posts_pagination(array(
                        'prev_text' => '&larr; Предыдущие',
                        'next_text' => 'Следующие &rarr;',
                    ));
                    ?>
                </div>
            <?php else : ?>
                <div class="team-empty">
                    <span class="team-empty__label">Новости</span>
                    <p>Мы готовим подборку новостей компании.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>
