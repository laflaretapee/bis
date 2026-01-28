<?php
get_header();
?>

<main class="news-archive">
    <div class="news-archive__container news-archive__intro">
        <p class="news-archive__badge">Новости</p>
        <h1 class="news-archive__title">Новости компании «БИС»</h1>
        <p class="news-archive__description">
            Свежие проекты, экспертные материалы и важные обновления о нашей работе.
        </p>
    </div>

    <div class="news-archive__container">
        <?php if (have_posts()) : ?>
            <div class="news-archive__grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="news-card">
                        <a class="news-card__image" href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large'); ?>
                            <?php else : ?>
                                <div class="news-card__image-placeholder">
                                    <span>«БИС»</span>
                                </div>
                            <?php endif; ?>
                        </a>
                        <div class="news-card__body">
                            <div class="news-card__meta">
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date('d.m.Y')); ?>
                                </time>
                            </div>
                            <h2 class="news-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="news-card__excerpt">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 28)); ?>
                            </p>
                            <a class="news-card__link" href="<?php the_permalink(); ?>">Читать полностью</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="news-archive__pagination">
                <?php
                the_posts_pagination(array(
                    'prev_text' => '&larr; Предыдущие',
                    'next_text' => 'Следующие &rarr;',
                ));
                ?>
            </div>
        <?php else : ?>
            <p>Новости пока не опубликованы.</p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>
