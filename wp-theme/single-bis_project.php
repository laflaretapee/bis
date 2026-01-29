<?php
get_header();
?>

<main class="project-single">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $project_id = get_the_ID();
            $details = bis_get_project_details($project_id);
            $banner_image = bis_get_project_banner_image($project_id);
            $layers = bis_get_project_banner_layers($project_id);
            $gallery = bis_get_project_gallery($project_id);

            if (empty($layers)) {
                $layers = array();
                $layers[] = array(
                    'text' => get_the_title(),
                    'size' => 'xl',
                    'align' => 'left',
                    'desktop_x' => 22,
                    'desktop_y' => 30,
                    'mobile_x' => 28,
                    'mobile_y' => 18,
                );

                if (!empty($details['year'])) {
                    $layers[] = array(
                        'text' => $details['year'] . "\nГод реализации",
                        'size' => 'sm',
                        'align' => 'left',
                        'desktop_x' => 22,
                        'desktop_y' => 58,
                        'mobile_x' => 26,
                        'mobile_y' => 55,
                    );
                }

                if (!empty($details['address'])) {
                    $layers[] = array(
                        'text' => $details['address'] . "\nАдрес",
                        'size' => 'md',
                        'align' => 'left',
                        'desktop_x' => 22,
                        'desktop_y' => 72,
                        'mobile_x' => 26,
                        'mobile_y' => 68,
                    );
                }

                if (!empty($details['area'])) {
                    $layers[] = array(
                        'text' => $details['area'] . " м²\nПлощадь",
                        'size' => 'lg',
                        'align' => 'center',
                        'desktop_x' => 72,
                        'desktop_y' => 52,
                        'mobile_x' => 70,
                        'mobile_y' => 40,
                    );
                }
            }

            $all_projects = get_posts(array(
                'post_type' => 'bis_project',
                'posts_per_page' => -1,
                'orderby' => array('menu_order' => 'ASC', 'title' => 'ASC'),
                'fields' => 'ids',
            ));
            $next_project_id = null;
            if (!empty($all_projects)) {
                $current_index = array_search($project_id, $all_projects, true);
                if ($current_index !== false && isset($all_projects[$current_index + 1])) {
                    $next_project_id = $all_projects[$current_index + 1];
                }
            }
            ?>

            <section class="project-hero">
                <div class="project-hero__media" style="background-image: url('<?php echo esc_url($banner_image); ?>');"></div>
                <div class="project-hero__overlay">
                    
                    <div class="project-hero__layers">
                        <?php foreach ($layers as $layer) :
                            $text = isset($layer['text']) ? $layer['text'] : '';
                            if ($text === '') {
                                continue;
                            }
                            $size = isset($layer['size']) ? $layer['size'] : 'md';
                            $align = isset($layer['align']) ? $layer['align'] : 'left';
                            $dx = isset($layer['desktop_x']) ? $layer['desktop_x'] : 50;
                            $dy = isset($layer['desktop_y']) ? $layer['desktop_y'] : 50;
                            $mx = isset($layer['mobile_x']) ? $layer['mobile_x'] : $dx;
                            $my = isset($layer['mobile_y']) ? $layer['mobile_y'] : $dy;
                            $style = sprintf('--x:%s%%; --y:%s%%; --mx:%s%%; --my:%s%%;', $dx, $dy, $mx, $my);
                            ?>
                            <div class="project-hero__layer is-<?php echo esc_attr($size); ?> is-align-<?php echo esc_attr($align); ?>" style="<?php echo esc_attr($style); ?>">
                                <?php echo esc_html($text); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <?php if (trim(get_the_content())) : ?>
                <?php the_content(); ?>
            <?php endif; ?>
            
            
            <section class="breadcrumbs-section">
            <nav class="project-breadcrumbs">
                        <a href="<?php echo esc_url(home_url('/')); ?>">Главная</a>
                        <span class="breadcrumbs-delimiter">/</span>
                        <a href="<?php echo esc_url(home_url('/#experience')); ?>">Проекты</a>
                        <span class="breadcrumbs-delimiter">/</span>
                        <span><?php the_title(); ?></span>
                    </nav>
            </section>
            <?php if (!empty($gallery)) : ?>
                <section class="project-gallery" data-project-gallery>
                    <div class="project-gallery__header">
                        <h2 class="project-gallery__title">Галерея проекта</h2>
                        <div class="project-gallery__nav">
                            <button type="button" data-gallery-prev aria-label="Предыдущее фото">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button type="button" data-gallery-next aria-label="Следующее фото">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="project-gallery__track" data-gallery-track>
                        <?php foreach ($gallery as $index => $image) : ?>
                            <button type="button" class="project-gallery__slide" data-gallery-slide data-full="<?php echo esc_url($image); ?>" aria-label="<?php echo esc_attr(get_the_title() . ' — фото ' . ($index + 1)); ?>">
                                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title() . ' — фото ' . ($index + 1)); ?>" loading="lazy">
                            </button>
                        <?php endforeach; ?>
                    </div>
                    <div class="project-gallery__dots" data-gallery-dots></div>
                </section>

                <div class="project-lightbox" id="projectLightbox" aria-hidden="true">
                    <div class="project-lightbox__content">
                        <button type="button" class="project-lightbox__close" data-lightbox-close aria-label="Закрыть">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button type="button" class="project-lightbox__nav project-lightbox__nav--prev" data-lightbox-prev aria-label="Предыдущее фото">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <img class="project-lightbox__image" data-lightbox-image alt="">
                        <button type="button" class="project-lightbox__nav project-lightbox__nav--next" data-lightbox-next aria-label="Следующее фото">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div class="project-lightbox__caption" data-lightbox-caption></div>
                    </div>
                </div>
            <?php endif; ?>

            <section class="project-consultation">
                <?php if ($next_project_id) : ?>
                    <a class="project-consultation__next" href="<?php echo esc_url(get_permalink($next_project_id)); ?>">Следующий проект -></a>
                <?php endif; ?>

                <div class="project-consultation__header">
                    <h2 class="project-consultation__title">Получить консультацию</h2>
                    <p class="project-consultation__subtitle">Заполните форму — мы обязательно вам ответим.</p>
                </div>

                <form class="project-consultation__form" id="projectConsultationForm">
                    <input type="hidden" name="project_id" value="<?php echo esc_attr($project_id); ?>">

                    <div class="project-consultation__field">
                        <label for="projectName">ФИО *</label>
                        <input type="text" id="projectName" name="name" required>
                    </div>

                    <div class="project-consultation__field">
                        <label for="projectPhone">Телефон *</label>
                        <input type="tel" id="projectPhone" name="phone" required>
                    </div>

                    <div class="project-consultation__field">
                        <label for="projectEmail">Рабочий E-mail *</label>
                        <input type="email" id="projectEmail" name="email" required>
                    </div>

                    <div class="project-consultation__field">
                        <label for="projectCompany">Компания *</label>
                        <input type="text" id="projectCompany" name="company" required>
                    </div>

                    <div class="project-consultation__field">
                        <label for="projectPosition">Должность *</label>
                        <input type="text" id="projectPosition" name="position" required>
                    </div>

                    <div class="project-consultation__field">
                        <label for="projectTopic">Тема вопроса *</label>
                        <select id="projectTopic" name="topic" required>
                            <option value="">Выберите тему</option>
                            <option value="Пусконаладка систем">Пусконаладка систем</option>
                            <option value="Техническое обслуживание">Техническое обслуживание</option>
                            <option value="Диагностика и аудит">Диагностика и аудит</option>
                            <option value="Другое">Другое</option>
                        </select>
                    </div>

                    <div class="project-consultation__field full">
                        <label for="projectDetails">Подробнее о задачах</label>
                        <textarea id="projectDetails" name="details" placeholder="Опишите задачу или оставьте комментарий"></textarea>
                    </div>

                    <div class="project-consultation__field full project-consultation__consent">
                        <label>
                            <input type="checkbox" name="privacy" required>
                            Я соглашаюсь с обработкой персональных данных, в соответствии с Политикой конфиденциальности.
                        </label>
                        <label>
                            <input type="checkbox" name="marketing">
                            Я соглашаюсь на получение информационных рассылок и другой коммуникации от БИС.
                        </label>
                    </div>

                    <div class="project-consultation__actions full">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>
                </form>
            </section>
        <?php endwhile; ?>
    <?php else : ?>
        <section class="project-content">
            <div class="project-content__body">
                <p>Проект не найден.</p>
            </div>
        </section>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
