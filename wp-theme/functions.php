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
        'supports'      => array('title', 'thumbnail'),
    ));
}
add_action('init', 'bis_register_projects_cpt');

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

function bis_project_details_metabox($post) {
    wp_nonce_field('bis_project_details_nonce', 'bis_project_details_nonce_field');

    $address   = get_post_meta($post->ID, 'bis_project_address', true);
    $area      = get_post_meta($post->ID, 'bis_project_area', true);
    $year      = get_post_meta($post->ID, 'bis_project_year', true);
    $is_key    = get_post_meta($post->ID, 'bis_project_is_featured', true);
    $image_url = get_post_meta($post->ID, 'bis_project_image', true);
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
                    <button type="button" class="button button-primary bis-project-image-upload" data-target="bis_project_image">Выбрать в медиабиблиотеке</button>
                    <button type="button" class="button bis-project-image-clear">Убрать фото</button>
                </div>
                <p class="bis-field__hint">Лучше использовать горизонтальные изображения 1200px+ для четкой обложки.</p>
            </div>
        </div>

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

    $address = isset($_POST['bis_project_address']) ? sanitize_text_field($_POST['bis_project_address']) : '';
    $area = isset($_POST['bis_project_area']) ? sanitize_text_field($_POST['bis_project_area']) : '';
    $year = isset($_POST['bis_project_year']) ? sanitize_text_field($_POST['bis_project_year']) : '';
    $image_url = isset($_POST['bis_project_image']) ? esc_url_raw($_POST['bis_project_image']) : '';
    $is_key = isset($_POST['bis_project_is_featured']) ? '1' : '0';

    update_post_meta($post_id, 'bis_project_address', $address);
    update_post_meta($post_id, 'bis_project_area', $area);
    update_post_meta($post_id, 'bis_project_year', $year);
    update_post_meta($post_id, 'bis_project_image', $image_url);
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
    $image = get_post_meta($post_id, 'bis_project_image', true);
    if ($image) {
        return esc_url($image);
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

function bis_admin_scripts($hook) {
    if ('toplevel_page_bis-hero-slider' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('bis-admin-script', get_template_directory_uri() . '/assets/js/admin-script.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
        wp_enqueue_style('bis-admin-style', get_template_directory_uri() . '/assets/css/admin-style.css', array(), '1.0');
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
            wp_enqueue_script('bis-projects-admin', get_template_directory_uri() . '/assets/js/admin-projects.js', array('jquery'), '1.0', true);
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

            $requests[] = array(
                'id' => get_the_ID(),
                'name' => get_post_meta(get_the_ID(), 'bis_name', true),
                'phone' => get_post_meta(get_the_ID(), 'bis_phone', true),
                'email' => get_post_meta(get_the_ID(), 'bis_email', true),
                'messenger' => get_post_meta(get_the_ID(), 'bis_messenger', true),
                'comment' => get_post_meta(get_the_ID(), 'bis_comment', true),
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
?>
