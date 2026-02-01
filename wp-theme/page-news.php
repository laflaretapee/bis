<?php
/*
Template Name: Новости
*/
get_header();

$page_id = get_the_ID();
$banner_title = get_post_meta($page_id, 'bis_page_banner_title', true);
$banner_subtitle = get_post_meta($page_id, 'bis_page_banner_subtitle', true);
$banner_title = $banner_title ? $banner_title : get_the_title();
$banner_subtitle = $banner_subtitle ? $banner_subtitle : 'Комплексная экспертиза в инженерных системах, исследования и практические кейсы — рассказываем о проектах и жизни команды «БИС».\nСвязаться с пресс-службой: pr@bis-rf.ru';
$banner_image = get_post_meta($page_id, 'bis_page_banner_image', true);
$banner_image = $banner_image ? $banner_image : get_the_post_thumbnail_url($page_id, 'full');

$paged = max(1, get_query_var('paged') ? get_query_var('paged') : get_query_var('page'));
$news_query = new WP_Query(array(
    'post_type'      => 'bis_news',
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'paged'          => $paged,
));
?>

<main class="news-archive-page">
    <section class="news-hero">
        <?php if ($banner_image) : ?>
            <div class="news-hero__media" style="background-image: url('<?php echo esc_url($banner_image); ?>');"></div>
        <?php endif; ?>
        <div class="news-hero__overlay">
            <h1 class="news-hero__title"><?php echo esc_html($banner_title); ?></h1>
            <?php if (!empty($banner_subtitle)) : ?>
                <p class="news-hero__text"><?php echo nl2br(esc_html($banner_subtitle)); ?></p>
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

    <section class="news-list">
        <div class="news-list__container">
            <?php if ($news_query->have_posts()) : ?>
                <div class="news-grid">
                    <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
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

                <?php
                $pagination = paginate_links(array(
                    'total'     => $news_query->max_num_pages,
                    'current'   => $paged,
                    'prev_text' => '&larr; Предыдущие',
                    'next_text' => 'Следующие &rarr;',
                    'type'      => 'array',
                ));
                ?>
                <?php if (!empty($pagination)) : ?>
                    <div class="news-pagination">
                        <?php echo wp_kses_post(implode('', $pagination)); ?>
                    </div>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
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
