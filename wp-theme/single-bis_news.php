<?php
get_header();
?>

<main class="news-single">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php $cover = get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>
            <div class="news-single__container news-single__intro">
                <a class="news-single__back" href="<?php echo esc_url(get_post_type_archive_link('bis_news') ?: home_url('/')); ?>">
                    <span aria-hidden="true">&larr;</span>
                    <span>Ко всем новостям</span>
                </a>
                <p class="news-single__badge">Новости</p>
                <h1 class="news-single__title"><?php the_title(); ?></h1>
                <div class="news-single__meta">
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d.m.Y')); ?></time>
                    <?php if (get_the_author_meta('display_name')) : ?>
                        <span>· <?php echo esc_html(get_the_author()); ?></span>
                    <?php endif; ?>
                </div>
                <?php if (has_excerpt()) : ?>
                    <p class="news-single__lead"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
            </div>

            <?php if ($cover) : ?>
                <div class="news-single__cover">
                    <img src="<?php echo esc_url($cover); ?>" alt="<?php the_title_attribute(); ?>">
                </div>
            <?php endif; ?>

            <div class="news-single__container">
                <article class="news-single__content">
                    <?php the_content(); ?>
                </article>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="news-single__container">
            <p>Новость не найдена.</p>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
