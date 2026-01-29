<?php
function bis_theme_scripts() {
    // Enqueue Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null);

    // Enqueue Main Stylesheet
    wp_enqueue_style('bis-style', get_template_directory_uri() . '/assets/css/style.css', array(), '20251203120000');

    // Enqueue Main Script
    wp_enqueue_script('bis-script', get_template_directory_uri() . '/assets/js/script.js', array(), '20251203120000', true);

    // Enqueue Slider Script
    if (is_front_page()) {
        wp_enqueue_script('bis-slider', get_template_directory_uri() . '/assets/js/slider.js', array(), '1.0', true);

        $revenue = bis_get_revenue_settings();
        $revenue_points = array();

        if (!empty($revenue['points']) && is_array($revenue['points'])) {
            foreach ($revenue['points'] as $point) {
                if (!isset($point['label'], $point['value'])) {
                    continue;
                }
                $label = sanitize_text_field($point['label']);
                $value = floatval($point['value']);
                if ($label === '') {
                    continue;
                }
                $revenue_points[] = array(
                    'label' => $label,
                    'value' => $value,
                );
            }
        }

        $revenue['points'] = $revenue_points;
        wp_localize_script('bis-script', 'bisRevenueData', $revenue);
    }
}
add_action('wp_enqueue_scripts', 'bis_theme_scripts');

function bis_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'bis_theme_setup');

/**
 * Registers the "Новости" admin section with full editor, featured image and excerpt support.
 */
function bis_register_news_cpt() {
    $labels = array(
        'name'                     => 'Новости',
        'singular_name'            => 'Новость',
        'add_new'                  => 'Добавить новость',
        'add_new_item'             => 'Добавить новую новость',
        'edit_item'                => 'Редактировать новость',
        'new_item'                 => 'Новая новость',
        'view_item'                => 'Просмотр новости',
        'search_items'             => 'Поиск новостей',
        'not_found'                => 'Новости не найдены',
        'not_found_in_trash'       => 'В корзине нет новостей',
        'all_items'                => 'Все новости',
        'archives'                 => 'Архив новостей',
        'attributes'               => 'Атрибуты новости',
        'insert_into_item'         => 'Вставить в новость',
        'uploaded_to_this_item'    => 'Загружено для этой новости',
        'menu_name'                => 'Новости',
        'filter_items_list'        => 'Фильтровать новости',
        'items_list_navigation'    => 'Навигация по новостям',
        'items_list'               => 'Список новостей',
        'name_admin_bar'           => 'Новость',
    );

    register_post_type('bis_news', array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array('slug' => 'news'),
        'menu_icon'          => 'dashicons-media-document',
        'show_in_rest'       => true, // Enables the block editor
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
    ));
}
add_action('init', 'bis_register_news_cpt');

/**
 * Registers projects custom post type to manage portfolio objects.
 */
function bis_register_projects_cpt() {
    $labels = array(
        'name'               => 'Проекты',
        'singular_name'      => 'Проект',
        'add_new'            => 'Добавить проект',
        'add_new_item'       => 'Добавить новый проект',
        'edit_item'          => 'Редактировать проект',
        'new_item'           => 'Новый проект',
        'view_item'          => 'Просмотр проекта',
        'search_items'       => 'Искать проекты',
        'not_found'          => 'Проекты не найдены',
        'not_found_in_trash' => 'В корзине нет проектов',
        'all_items'          => 'Все проекты',
        'menu_name'          => 'Проекты',
        'name_admin_bar'     => 'Проект',
    );

    register_post_type('bis_project', array(
        'labels'        => $labels,
        'public'        => true,
        'has_archive'   => false,
        'menu_icon'     => 'dashicons-portfolio',
        'show_in_rest'  => true,
        'supports'      => array('title'),
    ));
}
add_action('init', 'bis_register_projects_cpt');

function bis_disable_project_block_editor($use_block_editor, $post_type) {
    if ('bis_project' === $post_type) {
        return false;
    }

    return $use_block_editor;
}
add_filter('use_block_editor_for_post_type', 'bis_disable_project_block_editor', 10, 2);

/**
 * Meta box with project details (address, area, year, featured flag, image).
 */
function bis_add_project_meta_boxes() {
    add_meta_box(
        'bis_project_details',
        'Детали проекта',
        'bis_project_details_metabox',
        'bis_project',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'bis_add_project_meta_boxes');

function bis_project_details_metabox_legacy($post) {
    wp_nonce_field('bis_project_details_nonce', 'bis_project_details_nonce_field');

    $address   = get_post_meta($post->ID, 'bis_project_address', true);
    $area      = get_post_meta($post->ID, 'bis_project_area', true);
    $year      = get_post_meta($post->ID, 'bis_project_year', true);
    $is_key    = get_post_meta($post->ID, 'bis_project_is_featured', true);
    $image_url = get_post_meta($post->ID, 'bis_project_image', true);
    $banner_image = get_post_meta($post->ID, 'bis_project_banner_image', true);
    $banner_layers = get_post_meta($post->ID, 'bis_project_banner_layers', true);
    $gallery = get_post_meta($post->ID, 'bis_project_gallery', true);

    if (!is_array($banner_layers)) {
        $banner_layers = array();
    }

    if (!is_array($gallery)) {
        $gallery = array();
    }

    $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
    $banner_preview = $banner_image ? $banner_image : ($image_url ? $image_url : $thumbnail_url);
    ?>
    <div class="bis-project-box">
        <div class="bis-project-box__header">
            <div>
                <h3>Детали проекта</h3>
                <p>Заполните основные параметры и выберите фото. Эти данные попадут на главную и в модальное окно проектов.</p>
            </div>
            <div class="bis-project-box__status <?php echo $is_key ? 'is-featured' : ''; ?>" data-featured-badge>
                <?php echo $is_key ? 'Ключевой проект' : 'Обычный проект'; ?>
            </div>
        </div>

        <div class="bis-project-grid">
            <div class="bis-field">
                <label for="bis_project_address">Адрес / локация</label>
                <input type="text" id="bis_project_address" name="bis_project_address" value="<?php echo esc_attr($address); ?>" placeholder="Москва, Кутузовский проспект">
                <p class="bis-field__hint">Город, бизнес-центр или точная площадка.</p>
            </div>

            <div class="bis-field">
                <label for="bis_project_area">Площадь (м²)</label>
                <input type="text" id="bis_project_area" name="bis_project_area" value="<?php echo esc_attr($area); ?>" placeholder="45 000">
                <p class="bis-field__hint">Укажите цифрой, без м² — мы добавим автоматически.</p>
            </div>

            <div class="bis-field">
                <label for="bis_project_year">Год</label>
                <input type="text" id="bis_project_year" name="bis_project_year" value="<?php echo esc_attr($year); ?>" placeholder="2024">
                <p class="bis-field__hint">Год завершения или активной фазы.</p>
            </div>
        </div>

        <div class="bis-project-media">
            <div class="bis-project-media__preview" data-project-preview style="background-image: url('<?php echo esc_url($image_url); ?>');">
                <?php if (!$image_url) : ?>
                    <span class="bis-project-media__placeholder">Нет изображения</span>
                <?php endif; ?>
            </div>
            <div class="bis-project-media__controls">
                <label for="bis_project_image">Фото проекта</label>
                <input type="text" id="bis_project_image" name="bis_project_image" value="<?php echo esc_url($image_url); ?>" placeholder="https://">
                <div class="bis-project-media__buttons">
                    <button type="button" class="button button-primary bis-project-image-upload" data-target="bis_project_image" data-preview="project">Выбрать в медиабиблиотеке</button>
                    <button type="button" class="button bis-project-image-clear" data-target="bis_project_image" data-preview="project">Убрать фото</button>
                </div>
                <p class="bis-field__hint">Лучше использовать горизонтальные изображения 1200px+ для четкой обложки.</p>
            </div>
        </div>

        <div class="bis-project-section">
            <div class="bis-project-section__header">
                <h4>Страница проекта</h4>
                <p class="bis-field__hint">Задайте баннер и тексты, которые будут показаны на странице проекта. Перетаскивайте текст прямо в превью.</p>
            </div>

            <div class="bis-project-media bis-project-media--banner">
                <div class="bis-project-media__preview <?php echo $banner_preview ? '' : 'is-empty'; ?>" data-banner-image-preview style="background-image: url('<?php echo esc_url($banner_preview); ?>');">
                    <?php if (!$banner_preview) : ?>
                        <span class="bis-project-media__placeholder">Нет изображения</span>
                    <?php endif; ?>
                </div>
                <div class="bis-project-media__controls">
                    <label for="bis_project_banner_image">Баннер проекта</label>
                    <input type="text" id="bis_project_banner_image" name="bis_project_banner_image" value="<?php echo esc_url($banner_image); ?>" placeholder="https://">
                    <div class="bis-project-media__buttons">
                        <button type="button" class="button button-primary bis-project-image-upload" data-target="bis_project_banner_image" data-preview="banner">Выбрать баннер</button>
                        <button type="button" class="button bis-project-image-clear" data-target="bis_project_banner_image" data-preview="banner">Убрать фото</button>
                    </div>
                    <p class="bis-field__hint">Если баннер не выбран, используется фото проекта или миниатюра записи.</p>
                </div>
            </div>

            <div class="bis-project-banner-builder">
                <div class="bis-project-banner-previews">
                    <div class="bis-banner-preview" data-banner-preview="desktop" style="background-image: url('<?php echo esc_url($banner_preview); ?>');">
                        <?php foreach ($banner_layers as $index => $layer) :
                            $text = isset($layer['text']) ? $layer['text'] : '';
                            $size = isset($layer['size']) ? $layer['size'] : 'md';
                            $align = isset($layer['align']) ? $layer['align'] : 'left';
                            $dx = isset($layer['desktop_x']) ? $layer['desktop_x'] : 50;
                            $dy = isset($layer['desktop_y']) ? $layer['desktop_y'] : 50;
                            $mx = isset($layer['mobile_x']) ? $layer['mobile_x'] : $dx;
                            $my = isset($layer['mobile_y']) ? $layer['mobile_y'] : $dy;
                            ?>
                            <div class="bis-banner-preview__layer is-<?php echo esc_attr($size); ?> is-align-<?php echo esc_attr($align); ?>" data-layer-index="<?php echo esc_attr($index); ?>" style="left: <?php echo esc_attr($dx); ?>%; top: <?php echo esc_attr($dy); ?>%;">
                                <?php echo esc_html($text); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="bis-banner-preview bis-banner-preview--mobile" data-banner-preview="mobile" style="background-image: url('<?php echo esc_url($banner_preview); ?>');">
                        <?php foreach ($banner_layers as $index => $layer) :
                            $text = isset($layer['text']) ? $layer['text'] : '';
                            $size = isset($layer['size']) ? $layer['size'] : 'md';
                            $align = isset($layer['align']) ? $layer['align'] : 'left';
                            $dx = isset($layer['desktop_x']) ? $layer['desktop_x'] : 50;
                            $dy = isset($layer['desktop_y']) ? $layer['desktop_y'] : 50;
                            $mx = isset($layer['mobile_x']) ? $layer['mobile_x'] : $dx;
                            $my = isset($layer['mobile_y']) ? $layer['mobile_y'] : $dy;
                            ?>
                            <div class="bis-banner-preview__layer is-<?php echo esc_attr($size); ?> is-align-<?php echo esc_attr($align); ?>" data-layer-index="<?php echo esc_attr($index); ?>" style="left: <?php echo esc_attr($mx); ?>%; top: <?php echo esc_attr($my); ?>%;">
                                <?php echo esc_html($text); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bis-project-banner-list">
                    <ul id="bis-project-banner-layers" class="bis-banner-layer-list">
                        <?php foreach ($banner_layers as $index => $layer) :
                            $text = isset($layer['text']) ? $layer['text'] : '';
                            $size = isset($layer['size']) ? $layer['size'] : 'md';
                            $align = isset($layer['align']) ? $layer['align'] : 'left';
                            $dx = isset($layer['desktop_x']) ? $layer['desktop_x'] : 50;
                            $dy = isset($layer['desktop_y']) ? $layer['desktop_y'] : 50;
                            $mx = isset($layer['mobile_x']) ? $layer['mobile_x'] : $dx;
                            $my = isset($layer['mobile_y']) ? $layer['mobile_y'] : $dy;
                            ?>
                            <li class="bis-banner-layer-item" data-index="<?php echo esc_attr($index); ?>">
                                <div class="bis-banner-layer-item__header">
                                    <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
                                    <strong>Текст <?php echo esc_html($index + 1); ?></strong>
                                    <button type="button" class="button-link-delete bis-banner-layer-remove">Удалить</button>
                                </div>
                                <div class="bis-banner-layer-fields">
                                    <label>Текст</label>
                                    <textarea rows="2" data-field="text" name="bis_project_banner_layers[<?php echo esc_attr($index); ?>][text]" placeholder="Введите текст"><?php echo esc_textarea($text); ?></textarea>
                                    <div class="bis-banner-layer-row">
                                        <div class="bis-banner-layer-field">
                                            <label>Размер</label>
                                            <select data-field="size" name="bis_project_banner_layers[<?php echo esc_attr($index); ?>][size]">
                                                <option value="xl" <?php selected($size, 'xl'); ?>>XL</option>
                                                <option value="lg" <?php selected($size, 'lg'); ?>>L</option>
                                                <option value="md" <?php selected($size, 'md'); ?>>M</option>
                                                <option value="sm" <?php selected($size, 'sm'); ?>>S</option>
                                            </select>
                                        </div>
                                        <div class="bis-banner-layer-field">
                                            <label>Выравнивание</label>
                                            <select data-field="align" name="bis_project_banner_layers[<?php echo esc_attr($index); ?>][align]">
                                                <option value="left" <?php selected($align, 'left'); ?>>Слева</option>
                                                <option value="center" <?php selected($align, 'center'); ?>>По центру</option>
                                                <option value="right" <?php selected($align, 'right'); ?>>Справа</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="bis-banner-layer-row">
                                        <div class="bis-banner-layer-field">
                                            <label>Desktop X (%)</label>
                                            <input type="number" step="0.1" min="0" max="100" data-field="desktop_x" name="bis_project_banner_layers[<?php echo esc_attr($index); ?>][desktop_x]" value="<?php echo esc_attr($dx); ?>">
                                        </div>
                                        <div class="bis-banner-layer-field">
                                            <label>Desktop Y (%)</label>
                                            <input type="number" step="0.1" min="0" max="100" data-field="desktop_y" name="bis_project_banner_layers[<?php echo esc_attr($index); ?>][desktop_y]" value="<?php echo esc_attr($dy); ?>">
                                        </div>
                                        <div class="bis-banner-layer-field">
                                            <label>Mobile X (%)</label>
                                            <input type="number" step="0.1" min="0" max="100" data-field="mobile_x" name="bis_project_banner_layers[<?php echo esc_attr($index); ?>][mobile_x]" value="<?php echo esc_attr($mx); ?>">
                                        </div>
                                        <div class="bis-banner-layer-field">
                                            <label>Mobile Y (%)</label>
                                            <input type="number" step="0.1" min="0" max="100" data-field="mobile_y" name="bis_project_banner_layers[<?php echo esc_attr($index); ?>][mobile_y]" value="<?php echo esc_attr($my); ?>">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="button" id="bis-add-banner-layer">Добавить текст</button>
                </div>
            </div>
        </div>

        <div class="bis-project-section">
            <div class="bis-project-section__header">
                <h4>Галерея проекта</h4>
                <p class="bis-field__hint">Фото для слайдера на странице проекта. Можно менять порядок перетаскиванием.</p>
            </div>
            <div class="bis-project-gallery-admin">
                <ul id="bis-project-gallery-list" class="bis-project-gallery-list">
                    <?php foreach ($gallery as $image) : ?>
                        <li class="bis-project-gallery-item">
                            <div class="bis-project-gallery-thumb" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                            <input type="hidden" name="bis_project_gallery[]" value="<?php echo esc_url($image); ?>">
                            <button type="button" class="button-link-delete bis-project-gallery-remove">Удалить</button>
                            <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="button" id="bis-project-gallery-add">Добавить фото</button>
            </div>
        </div>

        <script type="text/template" id="bis-project-banner-layer-template">
            <li class="bis-banner-layer-item" data-index="">
                <div class="bis-banner-layer-item__header">
                    <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
                    <strong>Текст</strong>
                    <button type="button" class="button-link-delete bis-banner-layer-remove">Удалить</button>
                </div>
                <div class="bis-banner-layer-fields">
                    <label>Текст</label>
                    <textarea rows="2" data-field="text" placeholder="Введите текст"></textarea>
                    <div class="bis-banner-layer-row">
                        <div class="bis-banner-layer-field">
                            <label>Размер</label>
                            <select data-field="size">
                                <option value="xl">XL</option>
                                <option value="lg">L</option>
                                <option value="md" selected>M</option>
                                <option value="sm">S</option>
                            </select>
                        </div>
                        <div class="bis-banner-layer-field">
                            <label>Выравнивание</label>
                            <select data-field="align">
                                <option value="left" selected>Слева</option>
                                <option value="center">По центру</option>
                                <option value="right">Справа</option>
                            </select>
                        </div>
                    </div>
                    <div class="bis-banner-layer-row">
                        <div class="bis-banner-layer-field">
                            <label>Desktop X (%)</label>
                            <input type="number" step="0.1" min="0" max="100" data-field="desktop_x" value="20">
                        </div>
                        <div class="bis-banner-layer-field">
                            <label>Desktop Y (%)</label>
                            <input type="number" step="0.1" min="0" max="100" data-field="desktop_y" value="30">
                        </div>
                        <div class="bis-banner-layer-field">
                            <label>Mobile X (%)</label>
                            <input type="number" step="0.1" min="0" max="100" data-field="mobile_x" value="20">
                        </div>
                        <div class="bis-banner-layer-field">
                            <label>Mobile Y (%)</label>
                            <input type="number" step="0.1" min="0" max="100" data-field="mobile_y" value="30">
                        </div>
                    </div>
                </div>
            </li>
        </script>
        <script type="text/template" id="bis-project-gallery-item-template">
            <li class="bis-project-gallery-item">
                <div class="bis-project-gallery-thumb"></div>
                <input type="hidden" value="">
                <button type="button" class="button-link-delete bis-project-gallery-remove">Удалить</button>
                <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
            </li>
        </script>

        <div class="bis-project-toggle">
            <label class="bis-switch">
                <input type="checkbox" name="bis_project_is_featured" value="1" <?php checked($is_key, '1'); ?> data-featured-toggle>
                <span class="bis-switch__slider"></span>
                <span class="bis-switch__label">Показать в блоке «Ключевые проекты»</span>
            </label>
            <p class="bis-field__hint">Ключевые проекты отображаются на главной в верхнем списке, остальные — в блоке «Все проекты».</p>
        </div>
    </div>
    <?php
}

function bis_project_details_metabox($post) {
    wp_nonce_field('bis_project_details_nonce', 'bis_project_details_nonce_field');

    $is_key = get_post_meta($post->ID, 'bis_project_is_featured', true);
    $banner_image = get_post_meta($post->ID, 'bis_project_banner_image', true);
    $banner_title = get_post_meta($post->ID, 'bis_project_banner_title', true);
    $banner_blocks = get_post_meta($post->ID, 'bis_project_banner_blocks', true);
    $gallery = get_post_meta($post->ID, 'bis_project_gallery', true);

    if (!is_array($banner_blocks)) {
        $banner_blocks = array();
    }

    if (!is_array($gallery)) {
        $gallery = array();
    }

    $default_blocks = array(
        'top_left' => array('label' => '', 'value' => ''),
        'bottom_left' => array('label' => '', 'value' => ''),
        'top_right' => array('label' => '', 'value' => ''),
        'bottom_right' => array('label' => '', 'value' => ''),
    );
    $banner_blocks = wp_parse_args($banner_blocks, $default_blocks);

    $legacy_year = get_post_meta($post->ID, 'bis_project_year', true);
    $legacy_area = get_post_meta($post->ID, 'bis_project_area', true);
    $legacy_address = get_post_meta($post->ID, 'bis_project_address', true);

    $has_blocks = false;
    foreach ($banner_blocks as $block) {
        if (!empty($block['label']) || !empty($block['value'])) {
            $has_blocks = true;
            break;
        }
    }

    if (!$has_blocks) {
        if ($legacy_year) {
            $banner_blocks['top_left'] = array('label' => 'Год реализации', 'value' => $legacy_year);
        }
        if ($legacy_address) {
            $banner_blocks['bottom_left'] = array('label' => 'Адрес', 'value' => $legacy_address);
        }
        if ($legacy_area) {
            $area_value = $legacy_area;
            if (!preg_match('/\b(м2|м²|m2|m²)\b/iu', $area_value)) {
                $area_value .= ' м²';
            }
            $banner_blocks['top_right'] = array('label' => 'Площадь', 'value' => $area_value);
        }
    }

    $legacy_image = get_post_meta($post->ID, 'bis_project_image', true);
    $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
    $banner_preview = $banner_image ? $banner_image : ($legacy_image ? $legacy_image : $thumbnail_url);
    ?>
    <div class="bis-project-box">
        <div class="bis-project-box__header">
            <div>
                <h3>Страница проекта</h3>
                <p>Настройте баннер и галерею проекта. Тексты блоков можно задавать вручную.</p>
            </div>
            <div class="bis-project-box__status <?php echo $is_key ? 'is-featured' : ''; ?>" data-featured-badge>
                <?php echo $is_key ? 'Ключевой проект' : 'Обычный проект'; ?>
            </div>
        </div>

        <div class="bis-project-media">
            <div class="bis-project-media__preview <?php echo $banner_preview ? '' : 'is-empty'; ?>" data-banner-image-preview style="background-image: url('<?php echo esc_url($banner_preview); ?>');">
                <?php if (!$banner_preview) : ?>
                    <span class="bis-project-media__placeholder">Нет изображения</span>
                <?php endif; ?>
            </div>
            <div class="bis-project-media__controls">
                <label for="bis_project_banner_image">Главное изображение (баннер)</label>
                <input type="text" id="bis_project_banner_image" name="bis_project_banner_image" value="<?php echo esc_url($banner_image); ?>" placeholder="https://">
                <div class="bis-project-media__buttons">
                    <button type="button" class="button button-primary bis-project-image-upload" data-target="bis_project_banner_image" data-preview="banner">Выбрать в медиабиблиотеке</button>
                    <button type="button" class="button bis-project-image-clear" data-target="bis_project_banner_image" data-preview="banner">Убрать фото</button>
                </div>
                <p class="bis-field__hint">Изображение отображается в баннере и в карточках на главной.</p>
            </div>
        </div>

        <div class="bis-project-section">
            <div class="bis-project-section__header">
                <h4>Текст на баннере</h4>
                <p class="bis-field__hint">Заполните подписи и значения для четырех блоков. Если подпись и значение пустые — блок не отображается.</p>
            </div>

            <div class="bis-project-grid">
                <div class="bis-field">
                    <label for="bis_project_banner_title">Заголовок баннера</label>
                    <input type="text" id="bis_project_banner_title" name="bis_project_banner_title" value="<?php echo esc_attr($banner_title); ?>" placeholder="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                    <p class="bis-field__hint">Если оставить пустым, будет использовано название записи.</p>
                </div>
            </div>

            <div class="bis-project-banner-blocks">
                <?php
                $positions = array(
                    'top_left' => 'Левый верхний блок',
                    'bottom_left' => 'Левый нижний блок',
                    'top_right' => 'Правый верхний блок',
                    'bottom_right' => 'Правый нижний блок',
                );
                foreach ($positions as $key => $label) :
                    $block = isset($banner_blocks[$key]) ? $banner_blocks[$key] : array('label' => '', 'value' => '');
                    $block_label = isset($block['label']) ? $block['label'] : '';
                    $block_value = isset($block['value']) ? $block['value'] : '';
                    ?>
                    <div class="bis-banner-block">
                        <h4><?php echo esc_html($label); ?></h4>
                        <div class="bis-field">
                            <label>Подпись</label>
                            <input type="text" name="bis_project_banner_blocks[<?php echo esc_attr($key); ?>][label]" value="<?php echo esc_attr($block_label); ?>" placeholder="Например: Год реализации">
                        </div>
                        <div class="bis-field">
                            <label>Значение</label>
                            <textarea rows="2" name="bis_project_banner_blocks[<?php echo esc_attr($key); ?>][value]" placeholder="Например: 2024"><?php echo esc_textarea($block_value); ?></textarea>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bis-project-section">
            <div class="bis-project-section__header">
                <h4>Галерея проекта</h4>
                <p class="bis-field__hint">Фото для слайдера на странице проекта. Можно менять порядок перетаскиванием.</p>
            </div>
            <div class="bis-project-gallery-admin">
                <ul id="bis-project-gallery-list" class="bis-project-gallery-list">
                    <?php foreach ($gallery as $image) : ?>
                        <li class="bis-project-gallery-item">
                            <div class="bis-project-gallery-thumb" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                            <input type="hidden" name="bis_project_gallery[]" value="<?php echo esc_url($image); ?>">
                            <button type="button" class="button-link-delete bis-project-gallery-remove">Удалить</button>
                            <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="button" id="bis-project-gallery-add">Добавить фото</button>
            </div>
        </div>

        <script type="text/template" id="bis-project-gallery-item-template">
            <li class="bis-project-gallery-item">
                <div class="bis-project-gallery-thumb"></div>
                <input type="hidden" value="">
                <button type="button" class="button-link-delete bis-project-gallery-remove">Удалить</button>
                <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
            </li>
        </script>

        <div class="bis-project-toggle">
            <label class="bis-switch">
                <input type="checkbox" name="bis_project_is_featured" value="1" <?php checked($is_key, '1'); ?> data-featured-toggle>
                <span class="bis-switch__slider"></span>
                <span class="bis-switch__label">Показать в блоке <Ключевые проекты></span>
            </label>
            <p class="bis-field__hint">Ключевые проекты отображаются на главной в верхнем списке, остальные - в блоке <Все проекты>.</p>
        </div>
    </div>
    <?php
}

function bis_save_project_details($post_id) {
    if (!isset($_POST['bis_project_details_nonce_field']) || !wp_verify_nonce($_POST['bis_project_details_nonce_field'], 'bis_project_details_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!isset($_POST['post_type']) || 'bis_project' !== $_POST['post_type'] || !current_user_can('edit_post', $post_id)) {
        return;
    }

    $banner_image = isset($_POST['bis_project_banner_image']) ? esc_url_raw(wp_unslash($_POST['bis_project_banner_image'])) : '';
    $banner_title = isset($_POST['bis_project_banner_title']) ? sanitize_text_field(wp_unslash($_POST['bis_project_banner_title'])) : '';
    $is_key = isset($_POST['bis_project_is_featured']) ? '1' : '0';

    $positions = array('top_left', 'bottom_left', 'top_right', 'bottom_right');
    $banner_blocks = array();
    if (isset($_POST['bis_project_banner_blocks']) && is_array($_POST['bis_project_banner_blocks'])) {
        $raw_blocks = $_POST['bis_project_banner_blocks'];
    } else {
        $raw_blocks = array();
    }

    foreach ($positions as $key) {
        $block = isset($raw_blocks[$key]) && is_array($raw_blocks[$key]) ? $raw_blocks[$key] : array();
        $label = isset($block['label']) ? sanitize_text_field(wp_unslash($block['label'])) : '';
        $value = isset($block['value']) ? sanitize_textarea_field(wp_unslash($block['value'])) : '';
        $banner_blocks[$key] = array(
            'label' => $label,
            'value' => $value,
        );
    }

    $gallery = array();
    if (isset($_POST['bis_project_gallery']) && is_array($_POST['bis_project_gallery'])) {
        foreach ($_POST['bis_project_gallery'] as $image) {
            $url = esc_url_raw(wp_unslash($image));
            if ($url) {
                $gallery[] = $url;
            }
        }
    }

    $legacy_year = isset($banner_blocks['top_left']['value']) ? $banner_blocks['top_left']['value'] : '';
    $legacy_address = isset($banner_blocks['bottom_left']['value']) ? $banner_blocks['bottom_left']['value'] : '';
    $legacy_area_raw = isset($banner_blocks['top_right']['value']) ? $banner_blocks['top_right']['value'] : '';
    $legacy_area = trim(preg_replace('/\\s*(м2|м²|m2|m²)\\s*/iu', '', $legacy_area_raw));

    update_post_meta($post_id, 'bis_project_address', $legacy_address);
    update_post_meta($post_id, 'bis_project_area', $legacy_area);
    update_post_meta($post_id, 'bis_project_year', $legacy_year);
    update_post_meta($post_id, 'bis_project_image', $banner_image);
    update_post_meta($post_id, 'bis_project_banner_image', $banner_image);
    update_post_meta($post_id, 'bis_project_banner_title', $banner_title);
    update_post_meta($post_id, 'bis_project_banner_blocks', $banner_blocks);
    update_post_meta($post_id, 'bis_project_gallery', $gallery);
    update_post_meta($post_id, 'bis_project_is_featured', $is_key);
}
add_action('save_post', 'bis_save_project_details');

/**
 * Custom columns for projects.
 */
function bis_project_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $label) {
        if ('title' === $key) {
            $new_columns['bis_project_address'] = 'Адрес';
            $new_columns['bis_project_year'] = 'Год';
            $new_columns['bis_project_featured'] = 'Ключевой';
        }
        $new_columns[$key] = $label;
    }
    return $new_columns;
}
add_filter('manage_bis_project_posts_columns', 'bis_project_columns');

function bis_project_custom_column($column, $post_id) {
    if ('bis_project_address' === $column) {
        echo esc_html(get_post_meta($post_id, 'bis_project_address', true));
    }

    if ('bis_project_year' === $column) {
        echo esc_html(get_post_meta($post_id, 'bis_project_year', true));
    }

    if ('bis_project_featured' === $column) {
        $is_key = get_post_meta($post_id, 'bis_project_is_featured', true);
        echo $is_key ? '✓' : '—';
    }
}
add_action('manage_bis_project_posts_custom_column', 'bis_project_custom_column', 10, 2);

/**
 * Seed initial projects from current layout once.
 */
function bis_seed_projects_from_layout() {
    if (get_option('bis_projects_seeded')) {
        return;
    }

    $base = get_template_directory_uri() . '/assets/img/';
    $projects = array(
        array(
            'title'    => 'Футбольный стадион «ЦСКА» (Москва)',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k1.jpg',
            'featured' => true,
        ),
        array(
            'title'    => 'Штаб-квартира «Ростелеком»',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k2.jpg',
            'featured' => true,
        ),
        array(
            'title'    => 'Офисы Яндекс (Москва)',
            'address'  => 'БЦ «Аврора», Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k1.png',
            'featured' => true,
        ),
        array(
            'title'    => 'Яндекс — БЦ «Аврора», Москва',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k2.png',
            'featured' => false,
        ),
        array(
            'title'    => 'Яндекс — БЦ «Красная Роза», Москва',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k1.png',
            'featured' => false,
        ),
        array(
            'title'    => 'Центральный офис Россельхозбанка (Москва-Сити)',
            'address'  => 'Москва-Сити',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k7.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'Штаб-квартира ПАО «Сбербанк»',
            'address'  => 'Кутузовский проспект, Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k3.png',
            'featured' => false,
        ),
        array(
            'title'    => 'Офис Avon (БЦ «Большевик», Москва)',
            'address'  => 'Москва, БЦ «Большевик»',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k4.png',
            'featured' => false,
        ),
        array(
            'title'    => 'Центральный офис Металлинвестбанка',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k8.png',
            'featured' => false,
        ),
        array(
            'title'    => 'ЗАО «Канонфарма Продакшн» (Щёлково)',
            'address'  => 'Щёлково',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k4.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'АО «Валента Фарм» (Щёлково)',
            'address'  => 'Щёлково',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k11.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'НПК «Генериум» (Владимирская область)',
            'address'  => 'Владимирская область',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k5.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'АО «Красногорсклекарства»',
            'address'  => 'Красногорск',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k11.png',
            'featured' => false,
        ),
        array(
            'title'    => 'ООО «ССТинвест»',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k13.png',
            'featured' => false,
        ),
        array(
            'title'    => 'Клинический госпиталь «Мать и Дитя» (Тюмень)',
            'address'  => 'Тюмень',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k6.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'Клинический госпиталь «Мать и Дитя» (Самара)',
            'address'  => 'Самара',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k13.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'Мать и Дитя (Рязань)',
            'address'  => 'Рязань',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k12.png',
            'featured' => false,
        ),
        array(
            'title'    => 'Онкоцентр, Балашиха',
            'address'  => 'Балашиха',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k9.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'Онкоцентр, Подольск',
            'address'  => 'Подольск',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k10.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'Онкоцентр, Кострома',
            'address'  => 'Кострома',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k12.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'Медицинский центр «МЕДСИ» (Москва)',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k6.png',
            'featured' => false,
        ),
        array(
            'title'    => 'Пассажирский терминал «Домодедово-2»',
            'address'  => 'Домодедово',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k8.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'СКА Арена (Санкт-Петербург)',
            'address'  => 'Санкт-Петербург',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k14.webp',
            'featured' => false,
        ),
        array(
            'title'    => 'Футбольный стадион «Открытие Арена» (Москва)',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k18.jpg',
            'featured' => false,
        ),
        array(
            'title'    => 'ЖК «Триколор» (Москва)',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k15.jpg',
            'featured' => false,
        ),
        array(
            'title'    => 'ЖК «LIFE Ботанический сад» (Москва)',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k23.png',
            'featured' => false,
        ),
        array(
            'title'    => 'ЖК «Маяк» (Химки)',
            'address'  => 'Химки',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k24.jpg',
            'featured' => false,
        ),
        array(
            'title'    => 'ПИК — Белая Дача парк, Одинцово-1',
            'address'  => 'Московская область',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k5.png',
            'featured' => false,
        ),
        array(
            'title'    => 'ТРЦ «Эльград»',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k9.png',
            'featured' => false,
        ),
        array(
            'title'    => 'ТРЦ «Мега»',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k10.png',
            'featured' => false,
        ),
        array(
            'title'    => 'ПАО «Гостиничный комплекс «Космос»',
            'address'  => 'Москва',
            'area'     => '',
            'year'     => '',
            'image'    => $base . 'k14.png',
            'featured' => false,
        ),
    );

    foreach ($projects as $project) {
        $existing = get_page_by_title($project['title'], OBJECT, 'bis_project');
        $post_id = $existing ? $existing->ID : 0;

        if (!$post_id) {
            $post_id = wp_insert_post(array(
                'post_title'  => $project['title'],
                'post_status' => 'publish',
                'post_type'   => 'bis_project',
            ));
        }

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, 'bis_project_address', $project['address']);
            update_post_meta($post_id, 'bis_project_area', $project['area']);
            update_post_meta($post_id, 'bis_project_year', $project['year']);
            update_post_meta($post_id, 'bis_project_image', $project['image']);
            update_post_meta($post_id, 'bis_project_banner_image', $project['image']);
            update_post_meta($post_id, 'bis_project_is_featured', $project['featured'] ? '1' : '0');
        }
    }

    update_option('bis_projects_seeded', 1);
}
add_action('init', 'bis_seed_projects_from_layout', 20);

/**
 * Helpers for projects.
 */
function bis_get_project_image_url($post_id) {
    $banner = get_post_meta($post_id, 'bis_project_banner_image', true);
    if ($banner) {
        return esc_url($banner);
    }

    $legacy = get_post_meta($post_id, 'bis_project_image', true);
    if ($legacy) {
        return esc_url($legacy);
    }

    $thumb = get_the_post_thumbnail_url($post_id, 'large');
    return $thumb ? $thumb : '';
}

function bis_get_project_details($post_id) {
    return array(
        'address' => get_post_meta($post_id, 'bis_project_address', true),
        'area'    => get_post_meta($post_id, 'bis_project_area', true),
        'year'    => get_post_meta($post_id, 'bis_project_year', true),
    );
}

function bis_get_project_banner_image($post_id) {
    $banner = get_post_meta($post_id, 'bis_project_banner_image', true);
    if ($banner) {
        return esc_url($banner);
    }

    $image = bis_get_project_image_url($post_id);
    return $image ? $image : '';
}

function bis_get_project_banner_title($post_id) {
    $title = get_post_meta($post_id, 'bis_project_banner_title', true);
    if ($title) {
        return $title;
    }

    return get_the_title($post_id);
}

function bis_get_project_banner_blocks($post_id) {
    $blocks = get_post_meta($post_id, 'bis_project_banner_blocks', true);
    return is_array($blocks) ? $blocks : array();
}

function bis_get_project_gallery($post_id) {
    $gallery = get_post_meta($post_id, 'bis_project_gallery', true);
    return is_array($gallery) ? $gallery : array();
}

function bis_get_team_members() {
    $members = get_option('bis_team_members', array());
    if (!is_array($members)) {
        return array();
    }

    $filtered = array();
    foreach ($members as $member) {
        if (!is_array($member)) {
            continue;
        }
        $filtered[] = $member;
    }

    return $filtered;
}

/**
 * Registers gratitude letters custom post type for thank-you scans.
 */
function bis_register_gratitude_cpt() {
    $labels = array(
        'name'                  => 'Благодарности',
        'singular_name'         => 'Благодарность',
        'menu_name'             => 'Благодарности',
        'name_admin_bar'        => 'Благодарность',
        'add_new'               => 'Добавить',
        'add_new_item'          => 'Добавить благодарность',
        'edit_item'             => 'Редактировать',
        'new_item'              => 'Новая благодарность',
        'view_item'             => 'Просмотр',
        'search_items'          => 'Искать благодарности',
        'not_found'             => 'Не найдено',
        'not_found_in_trash'    => 'В корзине пусто',
        'all_items'             => 'Все благодарности',
    );

    register_post_type('bis_gratitude', array(
        'labels'             => $labels,
        'public'             => true,
        'show_in_menu'       => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-format-image',
        'supports'           => array('title', 'thumbnail', 'page-attributes'),
        'hierarchical'       => true,
        'rewrite'            => array('slug' => 'gratitude'),
        'show_in_rest'       => true,
    ));
}
add_action('init', 'bis_register_gratitude_cpt');

/**
 * Sort gratitude posts by manual order in admin by default.
 */
function bis_gratitude_admin_order($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ('bis_gratitude' === $query->get('post_type') && !$query->get('orderby')) {
        $query->set('orderby', 'menu_order');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'bis_gratitude_admin_order');

/**
 * Adds thumbnail and order columns for gratitude admin list.
 */
function bis_gratitude_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $label) {
        if ('title' === $key) {
            $new_columns['thumbnail'] = 'Изображение';
        }
        $new_columns[$key] = $label;
    }
    $new_columns['menu_order'] = 'Порядок';

    return $new_columns;
}
add_filter('manage_bis_gratitude_posts_columns', 'bis_gratitude_columns');

function bis_gratitude_custom_column($column, $post_id) {
    if ('thumbnail' === $column) {
        $thumb = get_the_post_thumbnail($post_id, array(80, 80));
        echo $thumb ? $thumb : '—';
    }

    if ('menu_order' === $column) {
        echo intval(get_post_field('menu_order', $post_id));
    }
}
add_action('manage_bis_gratitude_posts_custom_column', 'bis_gratitude_custom_column', 10, 2);

// Hero Slider Settings
function bis_hero_slider_menu() {
    add_menu_page(
        'Слайдер Hero',
        'Слайдер Hero',
        'manage_options',
        'bis-hero-slider',
        'bis_hero_slider_page',
        'dashicons-images-alt2',
        20
    );
}
add_action('admin_menu', 'bis_hero_slider_menu');

function bis_team_menu() {
    add_menu_page(
        'Команда',
        'Команда',
        'manage_options',
        'bis-team',
        'bis_team_page',
        'dashicons-groups',
        22
    );
}
add_action('admin_menu', 'bis_team_menu');

function bis_admin_scripts($hook) {
    if ('toplevel_page_bis-hero-slider' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('bis-admin-script', get_template_directory_uri() . '/assets/js/admin-script.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
        wp_enqueue_style('bis-admin-style', get_template_directory_uri() . '/assets/css/admin-style.css', array(), '1.0');
        return;
    }

    if ('toplevel_page_bis-team' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('bis-team-admin', get_template_directory_uri() . '/assets/js/admin-team.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
        wp_enqueue_style('bis-team-admin', get_template_directory_uri() . '/assets/css/admin-team.css', array(), '1.0');
        return;
    }

    if ('toplevel_page_bis-floating-buttons' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script('bis-floating-buttons-admin', get_template_directory_uri() . '/assets/js/admin-floating-buttons.js', array('jquery'), '1.0', true);
        wp_enqueue_style('bis-floating-buttons-admin', get_template_directory_uri() . '/assets/css/admin-floating-buttons.css', array(), '1.0');
        return;
    }

    if (in_array($hook, array('post-new.php', 'post.php'), true)) {
        $screen = get_current_screen();
        if ($screen && 'bis_project' === $screen->post_type) {
            wp_enqueue_media();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('bis-projects-admin', get_template_directory_uri() . '/assets/js/admin-projects.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
            wp_enqueue_style('bis-projects-admin', get_template_directory_uri() . '/assets/css/admin-projects.css', array(), '1.0');
        }
    }
}
add_action('admin_enqueue_scripts', 'bis_admin_scripts');

function bis_hero_slider_page() {
    if (isset($_POST['bis_hero_slider_save']) && check_admin_referer('bis_hero_slider_nonce')) {
        $images = isset($_POST['hero_images']) ? array_map('esc_url_raw', $_POST['hero_images']) : array();
        update_option('bis_hero_slider_images', $images);
        echo '<div class="updated"><p>Настройки сохранены.</p></div>';
    }

    $images = get_option('bis_hero_slider_images', array());
    ?>
    <div class="wrap">
        <h1>Настройки слайдера Hero</h1>
        <form method="post">
            <?php wp_nonce_field('bis_hero_slider_nonce'); ?>
            <div id="hero-slider-images-container">
                <ul id="hero-slider-list" class="hero-slider-list">
                    <?php if (!empty($images)) : ?>
                        <?php foreach ($images as $image) : ?>
                            <li class="hero-slider-item">
                                <div class="image-preview" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                                <input type="hidden" name="hero_images[]" value="<?php echo esc_url($image); ?>">
                                <button type="button" class="button remove-image">Удалить</button>
                                <span class="dashicons dashicons-move handle"></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
            <p>
                <button type="button" class="button" id="add-hero-image">Добавить изображение</button>
            </p>
            <p class="submit">
                <input type="submit" name="bis_hero_slider_save" class="button button-primary" value="Сохранить изменения">
            </p>
        </form>
    </div>
    <?php
}

function bis_team_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['bis_team_save']) && check_admin_referer('bis_team_nonce')) {
        $members = array();
        $raw_members = isset($_POST['team_members']) && is_array($_POST['team_members']) ? $_POST['team_members'] : array();

        foreach ($raw_members as $member) {
            $name = isset($member['name']) ? sanitize_text_field($member['name']) : '';
            $role = isset($member['role']) ? sanitize_text_field($member['role']) : '';
            $short = isset($member['short']) ? wp_kses_post($member['short']) : '';
            $long = isset($member['long']) ? wp_kses_post($member['long']) : '';
            $photo = isset($member['photo']) ? esc_url_raw($member['photo']) : '';
            $modal_photo = isset($member['modal_photo']) ? esc_url_raw($member['modal_photo']) : '';

            if ($name === '' && $role === '' && $short === '' && $long === '' && $photo === '' && $modal_photo === '') {
                continue;
            }

            $members[] = array(
                'name' => $name,
                'role' => $role,
                'short' => $short,
                'long' => $long,
                'photo' => $photo,
                'modal_photo' => $modal_photo,
            );
        }

        update_option('bis_team_members', $members);
        echo '<div class="updated"><p>Команда сохранена.</p></div>';
    }

    $members = get_option('bis_team_members', array());
    if (!is_array($members)) {
        $members = array();
    }
    ?>
    <div class="wrap bis-team-admin">
        <h1>Команда</h1>
        <p class="description">Добавляйте сотрудников для слайдера блока "Команда". Изменения сразу отражаются на главной странице.</p>

        <form method="post">
            <?php wp_nonce_field('bis_team_nonce'); ?>

            <ul id="team-members-list" class="team-members-list">
                <?php foreach ($members as $index => $member) :
                    $name = isset($member['name']) ? $member['name'] : '';
                    $role = isset($member['role']) ? $member['role'] : '';
                    $short = isset($member['short']) ? $member['short'] : '';
                    $long = isset($member['long']) ? $member['long'] : '';
                    $photo = isset($member['photo']) ? $member['photo'] : '';
                    $modal_photo = isset($member['modal_photo']) ? $member['modal_photo'] : '';
                    ?>
                    <li class="team-member-item" data-index="<?php echo esc_attr($index); ?>">
                        <div class="team-member-card">
                            <div class="team-member-media">
                                <div class="team-member-preview <?php echo $photo ? '' : 'is-empty'; ?>" data-preview="photo" style="background-image: url('<?php echo esc_url($photo); ?>');">
                                    <?php if (!$photo) : ?>
                                        <span class="team-member-placeholder">Нет фото</span>
                                    <?php endif; ?>
                                </div>
                                <div class="team-member-controls">
                                    <label>Фото для слайда</label>
                                    <input type="text" value="<?php echo esc_url($photo); ?>" data-field="photo" name="team_members[<?php echo esc_attr($index); ?>][photo]" placeholder="https://">
                                    <div class="team-member-buttons">
                                        <button type="button" class="button team-photo-upload" data-photo-type="photo">Выбрать</button>
                                        <button type="button" class="button team-photo-clear" data-photo-type="photo">Убрать</button>
                                    </div>
                                </div>
                            </div>

                            <div class="team-member-media">
                                <div class="team-member-preview <?php echo $modal_photo ? '' : 'is-empty'; ?>" data-preview="modal_photo" style="background-image: url('<?php echo esc_url($modal_photo); ?>');">
                                    <?php if (!$modal_photo) : ?>
                                        <span class="team-member-placeholder">Нет фото</span>
                                    <?php endif; ?>
                                </div>
                                <div class="team-member-controls">
                                    <label>Фото для модального окна</label>
                                    <input type="text" value="<?php echo esc_url($modal_photo); ?>" data-field="modal_photo" name="team_members[<?php echo esc_attr($index); ?>][modal_photo]" placeholder="https://">
                                    <div class="team-member-buttons">
                                        <button type="button" class="button team-photo-upload" data-photo-type="modal_photo">Выбрать</button>
                                        <button type="button" class="button team-photo-clear" data-photo-type="modal_photo">Убрать</button>
                                    </div>
                                </div>
                            </div>

                            <div class="team-member-fields">
                                <div class="team-field">
                                    <label>ФИО</label>
                                    <input type="text" value="<?php echo esc_attr($name); ?>" data-field="name" name="team_members[<?php echo esc_attr($index); ?>][name]" placeholder="Иванов Иван Иванович">
                                </div>
                                <div class="team-field">
                                    <label>Должность</label>
                                    <input type="text" value="<?php echo esc_attr($role); ?>" data-field="role" name="team_members[<?php echo esc_attr($index); ?>][role]" placeholder="Директор">
                                </div>
                                <div class="team-field">
                                    <label>Короткое описание для слайда</label>
                                    <textarea rows="4" data-field="short" name="team_members[<?php echo esc_attr($index); ?>][short]" placeholder="Короткая история/резюме"><?php echo esc_textarea($short); ?></textarea>
                                </div>
                                <div class="team-field">
                                    <label>Подробное описание для модального окна</label>
                                    <textarea rows="6" data-field="long" name="team_members[<?php echo esc_attr($index); ?>][long]" placeholder="Подробный текст"><?php echo esc_textarea($long); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="team-member-actions">
                            <button type="button" class="button link-delete team-member-remove">Удалить сотрудника</button>
                            <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <p>
                <button type="button" class="button" id="add-team-member">Добавить сотрудника</button>
            </p>

            <p class="submit">
                <input type="submit" name="bis_team_save" class="button button-primary" value="Сохранить изменения">
            </p>
        </form>

        <script type="text/template" id="team-member-template">
            <li class="team-member-item" data-index="">
                <div class="team-member-card">
                    <div class="team-member-media">
                        <div class="team-member-preview is-empty" data-preview="photo">
                            <span class="team-member-placeholder">Нет фото</span>
                        </div>
                        <div class="team-member-controls">
                            <label>Фото для слайда</label>
                            <input type="text" value="" data-field="photo" placeholder="https://">
                            <div class="team-member-buttons">
                                <button type="button" class="button team-photo-upload" data-photo-type="photo">Выбрать</button>
                                <button type="button" class="button team-photo-clear" data-photo-type="photo">Убрать</button>
                            </div>
                        </div>
                    </div>

                    <div class="team-member-media">
                        <div class="team-member-preview is-empty" data-preview="modal_photo">
                            <span class="team-member-placeholder">Нет фото</span>
                        </div>
                        <div class="team-member-controls">
                            <label>Фото для модального окна</label>
                            <input type="text" value="" data-field="modal_photo" placeholder="https://">
                            <div class="team-member-buttons">
                                <button type="button" class="button team-photo-upload" data-photo-type="modal_photo">Выбрать</button>
                                <button type="button" class="button team-photo-clear" data-photo-type="modal_photo">Убрать</button>
                            </div>
                        </div>
                    </div>

                    <div class="team-member-fields">
                        <div class="team-field">
                            <label>ФИО</label>
                            <input type="text" value="" data-field="name" placeholder="Иванов Иван Иванович">
                        </div>
                        <div class="team-field">
                            <label>Должность</label>
                            <input type="text" value="" data-field="role" placeholder="Директор">
                        </div>
                        <div class="team-field">
                            <label>Короткое описание для слайда</label>
                            <textarea rows="4" data-field="short" placeholder="Короткая история/резюме"></textarea>
                        </div>
                        <div class="team-field">
                            <label>Подробное описание для модального окна</label>
                            <textarea rows="6" data-field="long" placeholder="Подробный текст"></textarea>
                        </div>
                    </div>
                </div>

                <div class="team-member-actions">
                    <button type="button" class="button link-delete team-member-remove">Удалить сотрудника</button>
                    <span class="dashicons dashicons-move handle" aria-hidden="true"></span>
                </div>
            </li>
        </script>
    </div>
    <?php
}

// Floating social buttons settings
function bis_social_buttons_menu() {
    add_menu_page(
        'Плавающие кнопки',
        'Плавающие кнопки',
        'manage_options',
        'bis-floating-buttons',
        'bis_social_buttons_page',
        'dashicons-share',
        21
    );
}
add_action('admin_menu', 'bis_social_buttons_menu');

function bis_social_buttons_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['bis_social_buttons_save']) && check_admin_referer('bis_social_buttons_nonce')) {
        $images = isset($_POST['bis_social_buttons_image']) ? (array) $_POST['bis_social_buttons_image'] : array();
        $links = isset($_POST['bis_social_buttons_link']) ? (array) $_POST['bis_social_buttons_link'] : array();

        $buttons = array();
        foreach ($images as $index => $image) {
            $image_url = esc_url_raw($image);
            $link_url = isset($links[$index]) ? esc_url_raw($links[$index]) : '';

            if (empty($image_url) || empty($link_url)) {
                continue;
            }

            $buttons[] = array(
                'image' => $image_url,
                'link'  => $link_url,
            );
        }

        update_option('bis_social_buttons', $buttons);
        echo '<div class="updated"><p>Настройки сохранены.</p></div>';
    }

    $buttons = get_option('bis_social_buttons', array());
    ?>
    <div class="wrap">
        <h1>Плавающие кнопки социальных сетей</h1>
        <p class="description bis-floating-buttons-description">
            Добавьте любое количество кнопок с изображениями и ссылками. Они будут закреплены поверх сайта и помогут посетителям быстро переходить в нужные социальные сети.
        </p>
        <form method="post">
            <?php wp_nonce_field('bis_social_buttons_nonce'); ?>
            <ul id="bis-floating-buttons-list" class="bis-floating-buttons-list">
                <?php if (!empty($buttons)) : ?>
                    <?php foreach ($buttons as $button) : ?>
                        <li class="bis-floating-buttons-item<?php echo !empty($button['image']) ? ' has-image' : ''; ?>">
                            <div class="bis-floating-buttons-preview" <?php echo !empty($button['image']) ? 'style="background-image: url(' . esc_url($button['image']) . ');"' : ''; ?>></div>
                            <div class="bis-floating-buttons-fields">
                                <input type="hidden" class="bis-floating-buttons-image" name="bis_social_buttons_image[]" value="<?php echo esc_url($button['image']); ?>">
                                <button type="button" class="button bis-select-floating-image">Выбрать изображение</button>
                                <label>
                                    <span>Ссылка</span>
                                    <input type="url" class="regular-text" name="bis_social_buttons_link[]" value="<?php echo esc_url($button['link']); ?>" placeholder="https://example.com" required>
                                </label>
                            </div>
                            <button type="button" class="button button-link-delete bis-remove-floating-button">Удалить</button>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <p class="bis-floating-buttons-empty<?php echo empty($buttons) ? '' : ' hidden'; ?>">Пока нет ни одной кнопки — добавьте первую.</p>
            <p>
                <button type="button" class="button" id="bis-add-floating-button">Добавить кнопку</button>
            </p>
            <p class="submit">
                <input type="submit" name="bis_social_buttons_save" class="button button-primary" value="Сохранить изменения">
            </p>
        </form>
    </div>
    <script type="text/template" id="bis-floating-button-template">
        <li class="bis-floating-buttons-item">
            <div class="bis-floating-buttons-preview"></div>
            <div class="bis-floating-buttons-fields">
                <input type="hidden" class="bis-floating-buttons-image" name="bis_social_buttons_image[]" value="">
                <button type="button" class="button bis-select-floating-image">Выбрать изображение</button>
                <label>
                    <span>Ссылка</span>
                    <input type="url" class="regular-text" name="bis_social_buttons_link[]" value="" placeholder="https://example.com" required>
                </label>
            </div>
            <button type="button" class="button button-link-delete bis-remove-floating-button">Удалить</button>
        </li>
    </script>
    <?php
}

function bis_render_floating_social_buttons() {
    $buttons = get_option('bis_social_buttons', array());

    if (empty($buttons) || !is_array($buttons)) {
        return;
    }

    $items = array();
    foreach ($buttons as $button) {
        $image = isset($button['image']) ? esc_url($button['image']) : '';
        $link = isset($button['link']) ? esc_url($button['link']) : '';

        if (!$image || !$link) {
            continue;
        }

        $items[] = sprintf(
            '<a class="floating-social-buttons__link" href="%1$s" target="_blank" rel="noopener noreferrer"><img src="%2$s" alt="%3$s" loading="lazy"></a>',
            $link,
            $image,
            esc_attr__('Социальная сеть', 'bis')
        );
    }

    if (empty($items)) {
        return;
    }

    echo '<div class="floating-social-buttons" aria-label="Социальные сети">';
    echo implode('', $items);
    echo '</div>';
}
add_action('wp_footer', 'bis_render_floating_social_buttons');

// Register Custom Post Type for Requests
function bis_register_requests_cpt() {
    register_post_type('bis_request', array(
        'labels' => array(
            'name' => 'Заявки',
            'singular_name' => 'Заявка',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => false, // Hidden from main menu, accessed via custom page
        'supports' => array('title', 'custom-fields'),
        'capabilities' => array(
            'create_posts' => 'do_not_allow', // Users can't create via admin
        ),
        'map_meta_cap' => true,
    ));
}
add_action('init', 'bis_register_requests_cpt');

// AJAX Handler for Estimate Submission
function bis_submit_estimate() {
    $name = sanitize_text_field($_POST['name']);
    $phone = sanitize_text_field($_POST['phone']);
    $email = sanitize_email($_POST['email']);
    $messenger = sanitize_text_field($_POST['messenger']);
    $comment = sanitize_textarea_field($_POST['comment']);

    if (empty($name) || empty($phone) || empty($email)) {
        wp_send_json_error(array('message' => 'Required fields missing'));
    }

    $post_id = wp_insert_post(array(
        'post_title' => $name . ' - ' . $phone,
        'post_type' => 'bis_request',
        'post_status' => 'publish',
        'meta_input' => array(
            'bis_name' => $name,
            'bis_phone' => $phone,
            'bis_email' => $email,
            'bis_messenger' => $messenger,
            'bis_comment' => $comment,
            'bis_request_type' => 'estimate',
            'bis_status' => 'new',
            'bis_date' => current_time('mysql'),
        ),
    ));

    if ($post_id) {
        $upload_error = '';
        // Handle File Upload
        if (!empty($_FILES['project_doc']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('project_doc', $post_id);

            if (is_wp_error($attachment_id)) {
                $upload_error = $attachment_id->get_error_message();
            } else {
                update_post_meta($post_id, 'bis_file_id', $attachment_id);
            }
        }

        wp_send_json_success(array('message' => 'Request saved', 'upload_error' => $upload_error));
    } else {
        wp_send_json_error(array('message' => 'Error saving request'));
    }
}
add_action('wp_ajax_bis_submit_estimate', 'bis_submit_estimate');
add_action('wp_ajax_nopriv_bis_submit_estimate', 'bis_submit_estimate');

// AJAX Handler for Project Consultation Form
function bis_submit_project_consultation() {
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $company = isset($_POST['company']) ? sanitize_text_field(wp_unslash($_POST['company'])) : '';
    $position = isset($_POST['position']) ? sanitize_text_field(wp_unslash($_POST['position'])) : '';
    $topic = isset($_POST['topic']) ? sanitize_text_field(wp_unslash($_POST['topic'])) : '';
    $details = isset($_POST['details']) ? sanitize_textarea_field(wp_unslash($_POST['details'])) : '';
    $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
    $privacy = isset($_POST['privacy']) ? '1' : '0';
    $marketing = isset($_POST['marketing']) ? '1' : '0';

    if (empty($name) || empty($phone) || empty($email)) {
        wp_send_json_error(array('message' => 'Required fields missing'));
    }

    $project_title = $project_id ? get_the_title($project_id) : '';
    $title_suffix = $project_title ? ' - ' . $project_title : '';

    $post_id = wp_insert_post(array(
        'post_title' => $name . $title_suffix,
        'post_type' => 'bis_request',
        'post_status' => 'publish',
        'meta_input' => array(
            'bis_name' => $name,
            'bis_phone' => $phone,
            'bis_email' => $email,
            'bis_company' => $company,
            'bis_position' => $position,
            'bis_topic' => $topic,
            'bis_details' => $details,
            'bis_project_id' => $project_id,
            'bis_project_title' => $project_title,
            'bis_request_type' => 'consultation',
            'bis_comment' => $details,
            'bis_privacy' => $privacy,
            'bis_marketing' => $marketing,
            'bis_status' => 'new',
            'bis_date' => current_time('mysql'),
        ),
    ));

    if ($post_id) {
        wp_send_json_success(array('message' => 'Request saved'));
    }

    wp_send_json_error(array('message' => 'Error saving request'));
}
add_action('wp_ajax_bis_submit_project_consultation', 'bis_submit_project_consultation');
add_action('wp_ajax_nopriv_bis_submit_project_consultation', 'bis_submit_project_consultation');

function bis_get_new_requests_count() {
    $args = array(
        'post_type' => 'bis_request',
        'post_status' => 'publish',
        'meta_key' => 'bis_status',
        'meta_value' => 'new',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    $query = new WP_Query($args);
    return $query->found_posts;
}

// Admin Page for Requests
function bis_requests_menu() {
    $count = bis_get_new_requests_count();
    $menu_title = 'Заявки';
    
    if ($count > 0) {
        $menu_title .= sprintf(
            ' <span class="awaiting-mod count-%1$d"><span class="pending-count" aria-hidden="true">%1$d</span></span>',
            $count
        );
    }

    add_menu_page(
        'Заявки',
        $menu_title,
        'manage_options',
        'bis-requests',
        'bis_requests_page',
        'dashicons-email',
        6
    );
}
add_action('admin_menu', 'bis_requests_menu');

function bis_requests_page() {
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Заявки</h1>
        <div id="bis-requests-app">
            <div class="bis-requests-list" id="bis-requests-list">
                <!-- Requests will be loaded here via JS -->
                <div class="bis-loading">Загрузка...</div>
            </div>
        </div>
    </div>
    <?php
}

// AJAX Handler to Get Requests
function bis_get_requests() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error();
    }

    $args = array(
        'post_type' => 'bis_request',
        'posts_per_page' => 50,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);
    $requests = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $file_id = get_post_meta(get_the_ID(), 'bis_file_id', true);
            $file_url = $file_id ? wp_get_attachment_url($file_id) : '';
            $file_name = $file_id ? basename(get_attached_file($file_id)) : '';

            $comment = get_post_meta(get_the_ID(), 'bis_comment', true);
            if (!$comment) {
                $comment = get_post_meta(get_the_ID(), 'bis_details', true);
            }

            $requests[] = array(
                'id' => get_the_ID(),
                'name' => get_post_meta(get_the_ID(), 'bis_name', true),
                'phone' => get_post_meta(get_the_ID(), 'bis_phone', true),
                'email' => get_post_meta(get_the_ID(), 'bis_email', true),
                'messenger' => get_post_meta(get_the_ID(), 'bis_messenger', true),
                'comment' => $comment,
                'company' => get_post_meta(get_the_ID(), 'bis_company', true),
                'position' => get_post_meta(get_the_ID(), 'bis_position', true),
                'topic' => get_post_meta(get_the_ID(), 'bis_topic', true),
                'details' => get_post_meta(get_the_ID(), 'bis_details', true),
                'project' => get_post_meta(get_the_ID(), 'bis_project_title', true),
                'type' => get_post_meta(get_the_ID(), 'bis_request_type', true),
                'file_url' => $file_url,
                'file_name' => $file_name,
                'status' => get_post_meta(get_the_ID(), 'bis_status', true),
                'date' => get_post_meta(get_the_ID(), 'bis_date', true),
                'time_ago' => human_time_diff(strtotime(get_post_meta(get_the_ID(), 'bis_date', true)), current_time('timestamp')) . ' назад',
            );
        }
        wp_reset_postdata();
    }

    wp_send_json_success($requests);
}
add_action('wp_ajax_bis_get_requests', 'bis_get_requests');

// AJAX Handler to Mark Request as Read
function bis_mark_read() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error();
    }

    $post_id = intval($_POST['id']);
    if (!$post_id) {
        wp_send_json_error();
    }

    update_post_meta($post_id, 'bis_status', 'read');
    wp_send_json_success(array('count' => bis_get_new_requests_count()));
}
add_action('wp_ajax_bis_mark_read', 'bis_mark_read');

// Enqueue Admin Scripts for Requests Page
function bis_requests_admin_scripts($hook) {
    if ('toplevel_page_bis-requests' !== $hook) {
        return;
    }
    wp_enqueue_script('bis-requests-script', get_template_directory_uri() . '/assets/js/admin-requests.js', array('jquery'), '1.0', true);
    wp_enqueue_style('bis-requests-style', get_template_directory_uri() . '/assets/css/admin-requests.css', array(), '1.0');
}
add_action('admin_enqueue_scripts', 'bis_requests_admin_scripts');

/**
 * Revenue chart settings
 */
function bis_get_revenue_settings() {
    $defaults = array(
        'title'          => 'Динамика выручки за 10 лет',
        'currency_label' => 'млрд ₽',
        'cta_label'      => 'Узнать больше',
        'cta_link'       => '#contact',
        'points'         => array(
            array('label' => '2014', 'value' => 1.1),
            array('label' => '2015', 'value' => 3.8),
            array('label' => '2016', 'value' => 5.2),
            array('label' => '2017', 'value' => 8.0),
            array('label' => '2018', 'value' => 10.2),
            array('label' => '2019', 'value' => 11.4),
            array('label' => '2020', 'value' => 18.0),
            array('label' => '2021', 'value' => 19.1),
            array('label' => '2022', 'value' => 54.5),
            array('label' => '2023', 'value' => 51.7),
        ),
    );

    $settings = get_option('bis_revenue_chart', array());
    if (empty($settings) || !is_array($settings)) {
        return $defaults;
    }

    return wp_parse_args($settings, $defaults);
}

function bis_revenue_menu() {
    add_menu_page(
        'Динамика выручки',
        'Динамика выручки',
        'manage_options',
        'bis-revenue',
        'bis_revenue_page',
        'dashicons-chart-line',
        22
    );
}
add_action('admin_menu', 'bis_revenue_menu');

function bis_revenue_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['bis_revenue_save']) && check_admin_referer('bis_revenue_nonce')) {
        $title          = isset($_POST['bis_revenue_title']) ? sanitize_text_field(wp_unslash($_POST['bis_revenue_title'])) : '';
        $currency_label = isset($_POST['bis_revenue_currency']) ? sanitize_text_field(wp_unslash($_POST['bis_revenue_currency'])) : '';
        $cta_label      = isset($_POST['bis_revenue_cta_label']) ? sanitize_text_field(wp_unslash($_POST['bis_revenue_cta_label'])) : '';
        $cta_link       = isset($_POST['bis_revenue_cta_link']) ? esc_url_raw(wp_unslash($_POST['bis_revenue_cta_link'])) : '';

        $years  = isset($_POST['bis_revenue_year']) ? (array) $_POST['bis_revenue_year'] : array();
        $values = isset($_POST['bis_revenue_value']) ? (array) $_POST['bis_revenue_value'] : array();

        $points = array();
        foreach ($years as $index => $year) {
            $year_label = sanitize_text_field(wp_unslash($year));
            $value_raw  = isset($values[$index]) ? wp_unslash($values[$index]) : '';
            if ($year_label === '' && $value_raw === '') {
                continue;
            }
            $value = floatval(str_replace(',', '.', $value_raw));
            $points[] = array(
                'label' => $year_label,
                'value' => $value,
            );
        }

        $settings = array(
            'title'          => $title !== '' ? $title : 'Динамика выручки за 10 лет',
            'currency_label' => $currency_label !== '' ? $currency_label : 'млрд ₽',
            'cta_label'      => $cta_label !== '' ? $cta_label : 'Узнать больше',
            'cta_link'       => $cta_link,
            'points'         => $points,
        );

        update_option('bis_revenue_chart', $settings);
        echo '<div class="updated"><p>Настройки сохранены.</p></div>';
    }

    $settings = bis_get_revenue_settings();
    $points   = !empty($settings['points']) && is_array($settings['points']) ? $settings['points'] : array();
    ?>
    <div class="wrap">
        <h1>Динамика выручки</h1>
        <p class="description">Управляйте данными для блока графика на главной странице. Добавьте значения по годам и ссылку для CTA.</p>

        <form method="post">
            <?php wp_nonce_field('bis_revenue_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="bis_revenue_title">Заголовок</label></th>
                    <td><input type="text" id="bis_revenue_title" name="bis_revenue_title" class="regular-text" value="<?php echo esc_attr($settings['title']); ?>" placeholder="Динамика выручки за 10 лет"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_revenue_currency">Единица (подпись)</label></th>
                    <td><input type="text" id="bis_revenue_currency" name="bis_revenue_currency" class="regular-text" value="<?php echo esc_attr($settings['currency_label']); ?>" placeholder="млрд ₽"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_revenue_cta_label">Текст кнопки</label></th>
                    <td><input type="text" id="bis_revenue_cta_label" name="bis_revenue_cta_label" class="regular-text" value="<?php echo esc_attr($settings['cta_label']); ?>" placeholder="Узнать больше"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_revenue_cta_link">Ссылка кнопки</label></th>
                    <td><input type="url" id="bis_revenue_cta_link" name="bis_revenue_cta_link" class="regular-text" value="<?php echo esc_url($settings['cta_link']); ?>" placeholder="#contact"></td>
                </tr>
            </table>

            <h2 style="margin-top:30px;">Точки графика</h2>
            <p class="description">Укажите подпись (обычно год) и значение. Для дробных значений можно использовать запятую или точку.</p>

            <table class="widefat fixed striped" id="bis-revenue-table" style="max-width:800px; margin-top:10px;">
                <thead>
                    <tr>
                        <th style="width:40%;">Подпись</th>
                        <th style="width:40%;">Значение</th>
                        <th style="width:20%;">Действие</th>
                    </tr>
                </thead>
                <tbody id="bis-revenue-rows">
                    <?php if (!empty($points)) : ?>
                        <?php foreach ($points as $point) : ?>
                            <tr>
                                <td><input type="text" name="bis_revenue_year[]" value="<?php echo esc_attr($point['label']); ?>" placeholder="2024" class="widefat"></td>
                                <td><input type="text" name="bis_revenue_value[]" value="<?php echo esc_attr($point['value']); ?>" placeholder="12.5" class="widefat"></td>
                                <td><button type="button" class="button bis-revenue-remove">Удалить</button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td><input type="text" name="bis_revenue_year[]" value="" placeholder="2024" class="widefat"></td>
                            <td><input type="text" name="bis_revenue_value[]" value="" placeholder="12.5" class="widefat"></td>
                            <td><button type="button" class="button bis-revenue-remove">Удалить</button></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <p style="margin-top:12px;">
                <button type="button" class="button" id="bis-revenue-add">Добавить точку</button>
            </p>

            <p class="submit" style="margin-top:20px;">
                <input type="submit" name="bis_revenue_save" class="button button-primary" value="Сохранить изменения">
            </p>
        </form>
    </div>
    <script type="text/html" id="bis-revenue-row-template">
        <tr>
            <td><input type="text" name="bis_revenue_year[]" value="" placeholder="2024" class="widefat"></td>
            <td><input type="text" name="bis_revenue_value[]" value="" placeholder="12.5" class="widefat"></td>
            <td><button type="button" class="button bis-revenue-remove">Удалить</button></td>
        </tr>
    </script>
    <script>
        (function() {
            const addBtn = document.getElementById('bis-revenue-add');
            const rows = document.getElementById('bis-revenue-rows');
            const template = document.getElementById('bis-revenue-row-template').textContent.trim();

            if (addBtn && rows) {
                addBtn.addEventListener('click', function() {
                    rows.insertAdjacentHTML('beforeend', template);
                });

                rows.addEventListener('click', function(e) {
                    if (e.target.classList.contains('bis-revenue-remove')) {
                        const tr = e.target.closest('tr');
                        if (tr && rows.children.length > 1) {
                            tr.remove();
                        } else if (tr) {
                            tr.querySelectorAll('input').forEach(input => input.value = '');
                        }
                    }
                });
            }
        })();
    </script>
    <?php
}

/**
 * Maintenance mode settings
 */
function bis_get_maintenance_settings() {
    $defaults = array(
        'enabled' => '0',
        'badge'   => 'Технические работы',
        'title'   => 'Сайт временно недоступен',
        'message' => 'Сейчас на сайте идут технические работы. Мы скоро вернёмся. Спасибо за понимание!',
        'phone'   => '+7 (926) 438-07-70',
        'email'   => 'office@bis-rf.ru',
    );

    $settings = get_option('bis_maintenance_settings', array());
    if (!is_array($settings)) {
        return $defaults;
    }

    return wp_parse_args($settings, $defaults);
}

function bis_maintenance_menu() {
    add_menu_page(
        'Технические работы',
        'Тех. работы',
        'manage_options',
        'bis-maintenance',
        'bis_maintenance_page',
        'dashicons-shield-alt',
        23
    );
}
add_action('admin_menu', 'bis_maintenance_menu');

function bis_maintenance_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['bis_maintenance_save']) && check_admin_referer('bis_maintenance_nonce')) {
        $settings = array(
            'enabled' => isset($_POST['bis_maintenance_enabled']) ? '1' : '0',
            'badge'   => isset($_POST['bis_maintenance_badge']) ? sanitize_text_field(wp_unslash($_POST['bis_maintenance_badge'])) : '',
            'title'   => isset($_POST['bis_maintenance_title']) ? sanitize_text_field(wp_unslash($_POST['bis_maintenance_title'])) : '',
            'message' => isset($_POST['bis_maintenance_message']) ? sanitize_textarea_field(wp_unslash($_POST['bis_maintenance_message'])) : '',
            'phone'   => isset($_POST['bis_maintenance_phone']) ? sanitize_text_field(wp_unslash($_POST['bis_maintenance_phone'])) : '',
            'email'   => isset($_POST['bis_maintenance_email']) ? sanitize_email(wp_unslash($_POST['bis_maintenance_email'])) : '',
        );

        update_option('bis_maintenance_settings', $settings);
        echo '<div class="updated"><p>Настройки сохранены.</p></div>';
    }

    $settings = bis_get_maintenance_settings();
    ?>
    <div class="wrap">
        <h1>Технические работы</h1>
        <p class="description">Включите заглушку, чтобы скрыть сайт для гостей. Администраторы продолжают видеть сайт.</p>
        <form method="post">
            <?php wp_nonce_field('bis_maintenance_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Статус</th>
                    <td>
                        <label>
                            <input type="checkbox" name="bis_maintenance_enabled" value="1" <?php checked($settings['enabled'], '1'); ?>>
                            Включить заглушку
                        </label>
                        <p class="description">Незалогиненные посетители увидят страницу технических работ.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_maintenance_badge">Бейдж</label></th>
                    <td><input type="text" id="bis_maintenance_badge" name="bis_maintenance_badge" class="regular-text" value="<?php echo esc_attr($settings['badge']); ?>" placeholder="Технические работы"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_maintenance_title">Заголовок</label></th>
                    <td><input type="text" id="bis_maintenance_title" name="bis_maintenance_title" class="regular-text" value="<?php echo esc_attr($settings['title']); ?>" placeholder="Сайт временно недоступен"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_maintenance_message">Сообщение</label></th>
                    <td><textarea id="bis_maintenance_message" name="bis_maintenance_message" rows="3" class="large-text" placeholder="Сейчас на сайте идут технические работы."><?php echo esc_textarea($settings['message']); ?></textarea></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_maintenance_phone">Телефон</label></th>
                    <td><input type="text" id="bis_maintenance_phone" name="bis_maintenance_phone" class="regular-text" value="<?php echo esc_attr($settings['phone']); ?>" placeholder="+7 (000) 000-00-00"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bis_maintenance_email">Email</label></th>
                    <td><input type="email" id="bis_maintenance_email" name="bis_maintenance_email" class="regular-text" value="<?php echo esc_attr($settings['email']); ?>" placeholder="office@example.com"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="bis_maintenance_save" class="button button-primary" value="Сохранить изменения">
            </p>
        </form>
    </div>
    <?php
}

function bis_handle_maintenance_mode() {
    $settings = bis_get_maintenance_settings();
    $enabled  = isset($settings['enabled']) && '1' === $settings['enabled'];

    if (!$enabled) {
        return;
    }

    if (is_user_logged_in() && current_user_can('manage_options')) {
        return;
    }

    if (is_admin() || wp_doing_ajax() || wp_doing_cron() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return;
    }

    status_header(503);
    nocache_headers();
    include get_template_directory() . '/maintenance.php';
    exit;
}
add_action('template_redirect', 'bis_handle_maintenance_mode');
?>
