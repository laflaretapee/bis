<?php
get_header();
$hero_images = get_option('bis_hero_slider_images', array());
$has_hero_slider = !empty($hero_images);
?>
  <!-- Hero Section -->
  <section class="hero <?php echo $has_hero_slider ? 'hero--with-slider' : 'hero--parallax'; ?>" id="home">
    <?php if ($has_hero_slider) : ?>
      <div class="hero-slider">
        <?php foreach ($hero_images as $index => $image) : ?>
          <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.75)), url('<?php echo esc_url($image); ?>') center/cover no-repeat;"></div>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="hero-parallax" aria-hidden="true">
        <div class="parallax-layer parallax-layer--back" data-speed="0.12" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/layers/layer2.png');"></div>
        <div class="parallax-layer parallax-layer--front" data-speed="0.2" style="background-image: url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/layers/layer1.png');"></div>
      </div>
    <?php endif; ?>
    <div class="grid-pattern"></div>
    <div class="hero-content">
      <h1 class="typing-title">
        <span class="typing-text">«БИС — </span><span class="cursor">|</span>
      </h1>
      <p class="hero-subtitle">
        Компания «БИС — Баланс Инженерных Систем» специализируется на комплексных пусконаладочных работах инженерных систем, техническом обслуживании и сопровождении
      </p>
      <div class="hero-cta">
        <button class="btn btn-primary open-estimate-modal">Рассчитать смету и сроки</button>
      </div>
      </div>
    </div>
    <div class="hero-nav-rail">
      <ul class="hero-nav">
        <li><a href="#services">Специализация</a></li>
        <li><a href="#equipment">Оборудование</a></li>
        <li><a href="#experience">Опыт</a></li>
        <li><a href="#why">О нас</a></li>
        <li><a href="#contact">Контакты</a></li>
        <li><a href="#faq">F.A.Q</a></li>
      </ul>
    </div>
  </section>
<!-- Tasks Section -->
<section class="tasks-section" id="tasks">
    <div class="tasks-content">
        <div class="section-header">
            <span class="section-badge">Наши задачи</span>
            <h2 class="section-title">Что мы решаем</h2>
            <p class="section-subtitle">Основные задачи, которые мы ставим перед собой при работе с каждым проектом</p>
        </div>

        <div class="tasks-grid">
            <div class="task-item">
                <div class="task-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/z1.webp');"></div>
                <p>Сбалансировать расчетные и фактические параметры систем микроклимата</p>
            </div>
            <div class="task-item">
                <div class="task-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/z2.webp');"></div>
                <p>Обеспечить оптимальную работу и эффективность работы оборудования</p>
            </div>
            <div class="task-item">
                <div class="task-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/z3.webp');"></div>
                <p>Понять и решить технические задачи, возникающие на объекте</p>
            </div>
        </div>
    </div>
</section>


<!-- Objects Section -->
<section class="objects-section" id="objects">
  <div class="objects-container">
    <div class="section-header">
      <span class="section-badge">Типы объектов</span>
      <h2 class="section-title">Объекты, которые мы налаживаем</h2>
      <p class="section-subtitle">Работаем с инженерными системами на разных типах площадок — от производственных комплексов до жилых объектов.</p>
    </div>

    <div class="objects-grid">
      <div class="object-card">
        <h3>Промышленные</h3>
        <p>Пусконаладка производственных зданий, складов и чистых помещений.</p>
      </div>
      <div class="object-card">
        <h3>Административные</h3>
        <p>Офисы, общественные пространства и коммерческие здания.</p>
      </div>
      <div class="object-card">
        <h3>Жилые</h3>
        <p>Многоквартирные дома, апартаменты и частные резиденции.</p>
      </div>
    </div>
  </div>
</section>



  <section class="services" id="services">
  <div class="section-header">
    <h2 class="section-title">Комплексные решения для ваших систем</h2>
    <p class="section-subtitle">Специализация инженерных систем</p>
  </div>

  <div class="services-grid">
    <div class="service-card">
      <div class="service-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/spec1.jpg');"></div>
      <h5>Комплексная наладка и испытания</h5>
      <ul>
        <li>Общеобменной вентиляции</li>
        <li>Противодымной вентиляции</li>
        <li>Гидравлическая балансировка холодоснабжения, теплоснабжения, отопления</li>
        <li>Автоматизация, диспетчеризация, программирование</li>
        <li>Комплексные испытания</li>
      </ul>
      <button class="btn btn-primary order-btn" data-service="Пусконаладочные работы">Заказать</button>
    </div>

    <div class="service-card">
      <div class="service-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/spec2.jpg');"></div>
      <h5>Обслуживание вентиляционных установок</h5>
      <ul>
        <li>Проверка
состояния электродвигателей, вентиляторов, теплообменных
агрегатов, увлажнителей, натяжение ремней и замена фильтрующих
элементов, диагностика электрических соединений</li>
      </ul>
      <button class="btn btn-primary order-btn" data-service="Техническое сопровождение">Заказать</button>
    </div>

    <div class="service-card">
      <div class="service-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/spec3.jpg');"></div>
      <h5>Комплексная чистка и дезинфекция системы вентиляции,
удаление жировых отложений</h5>
      <ul>
        <li>чистка вентиляционных сетей
механическим способом, с применением специальных средств для
расщепления жировых отложений и аппаратов высокого давления </li>
      </ul>
      <button class="btn btn-primary order-btn" data-service="Техническое сопровождение">Заказать</button>
    </div>

    <div class="service-card">
      <div class="service-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/spec4.jpg');"></div>
      <h5>Замеры параметров микроклимата</h5>
      <p>На соответствие СанПин, ГОСТ:</p>
      <ul>
        <li>Скорость воздуха</li>
        <li>Температура</li>
        <li>Влажность</li>
        <li>Уровень освещенности и шума</li>
      </ul>
      <button class="btn btn-primary order-btn" data-service="Замеры микроклимата">Заказать</button>
    </div>

    <div class="service-card">
      <div class="service-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/spec5.jpg');"></div>
      <h5>Проведение испытаний воздуховодов на плотность и
видеоинспекция вентиляционных каналов и трубопроводов</h5>
      <button class="btn btn-primary order-btn" data-service="Испытания воздуховодов">Заказать</button>
    </div>

    <div class="service-card">
      <div class="service-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/spec6.jpg');"></div>
      <h5>Испытания чистых помещений</h5>
      <p>На соответствие требованиям ГОСТ:</p>
      <ul>
        <li>Классы чистоты</li>
        <li>Кратность воздухообмена</li>
        <li>Скорость однонаправленного потока воздуха</li>
        <li>Относительная влажность воздуха</li>
      </ul>
      <button class="btn btn-primary order-btn" data-service="Испытания чистых помещений">Заказать</button>
    </div>

    <div class="service-card">
      <div class="service-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/spec7.jpg');"></div>
      <h5>Проведение предпроектного и технического
обследования</h5>
      <ul>
        <li>Обследование систем на дефекты монтажа,
проверка соответствия параметром микроклимата и
воздухообмена требованиям нормативных документов.</li>
      </ul>
      <button class="btn btn-primary order-btn" data-service="Документация">Заказать</button>
    </div>



  </div>

  <div class="services-slider-nav">
    <button class="slider-prev" aria-label="Предыдущая услуга">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <div class="slider-dots"></div>
    <button class="slider-next" aria-label="Следующая услуга">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </div>

  <div class="popup-overlay" id="popupOverlay">
    <div class="popup-form">
      <button class="popup-close" id="popupClose" aria-label="Закрыть форму">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <h2>Заявка на услугу</h2>
      <p>Заполните контакты, и команда БИС — Баланс Инженерных Систем свяжется с вами для уточнения деталей.</p>
      <form class="contact-form" id="orderForm">
        <input type="hidden" id="orderService" name="service" value="">
        <div class="form-group">
          <label for="orderName">Имя</label>
          <input type="text" id="orderName" name="name" required placeholder="Ваше имя">
        </div>
        <div class="form-group">
          <label for="orderPhone">Телефон</label>
          <input type="tel" id="orderPhone" name="phone" required placeholder="+7 (___) ___-__-__">
        </div>
        <div class="form-group">
          <label for="orderMessage">Комментарий</label>
          <textarea id="orderMessage" name="message" placeholder="Опишите задачу или оставьте комментарий"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Отправить заявку</button>
      </form>
    </div>
  </div>
</section>

<section class="pnr-why-section">
  <div class="grid-pattern"></div>
  <div class="pnr-why-content">
    <h3>Почему «БИС — Баланс Инженерных Систем»?</h3>
    <ul class="pnr-why">
      <li>Комплексный подход</li>
      <li>Честность и ответственность</li>
      <li>Мобильность и нацеленность на качественный результат</li>
      <li>Внимание и быстрое реагирование на требования заказчика</li>
      <li>Постоянная квалифицированная команда инженеров</li>
      <li>Возможность работы с НДС и без</li>
      <li>Наличие всех необходимых лицензий и разрешений</li>
    </ul>
    <div class="pnr-stats">
      <h4 class="pnr-stats-title">Наш опыт в цифрах</h4>
      <div class="stats">
        <div class="stat-item">
          <span class="stat-value">10</span>
          <span class="stat-label">лет на рынке</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">200</span>
          <span class="stat-label">реализованных проектов</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">20 тыс</span>
          <span class="stat-label">общеобменных систем обследовано и налажено</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">10 тыс</span>
          <span class="stat-label">противодымных систем обследовано и налажено</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">20 тыс</span>
          <span class="stat-label">регулирующих устройств</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">40 тыс</span>
          <span class="stat-label">индивидуальных испытаний</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">200</span>
          <span class="stat-label">ИТП и хладоцентров</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">100 тыс</span>
          <span class="stat-label">м.п. воздуховодов проверено на герметичность</span>
        </div>
        <div class="stat-item">
          <span class="stat-value">80%</span>
          <span class="stat-label">клиентов возвращаются повторно</span>
        </div>
      </div>
      <p class="pnr-stats-note">Комплексный подход: мы команда высококлассных инженеров с уникальным опытом наладки инженерных систем.</p>
    </div>
    <a href="#contact" class="order-btn" data-service="Общая заявка">Отправить заявку</a>
  </div>
</section>

<section class="equipment-section" id="equipment">
  <div class="section-header">
    <!-- <span class="section-badge">Оборудование</span> -->
    <h2 class="section-title">Оборудование «БИС — Баланс Инженерных Систем»</h2>
    <p class="section-subtitle">Собственные решения для очистки вентиляции и полный парк измерительных приборов для ПНР, сервиса и метрологического контроля</p>
  </div>

  <div class="equipment-park">
    <div class="equipment-grid">

    
    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob3.jpg');"></div>
      <div class="equipment-content">
        <h5>Комплект для вентиляции Testo 440</h5>
        <p>Профессиональный набор с Bluetooth крыльчаткой 100 мм и зондом с обогреваемой струной для точных измерений воздушных потоков.</p>
      </div>
    </div>

    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob2.jpg');"></div>
      <div class="equipment-content">
        <h5>Электронный балометр Testo 420</h5>
        <p>Замер объемного расхода воздуха с решёток размером до 600×600 мм и 1200×300 мм для систем вентиляции и кондиционирования.</p>
      </div>
    </div>

    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob7.jpg');"></div>
      <div class="equipment-content">
        <h5>Набор воронок Testo</h5>
        <p>Специализированные воронки с выпрямителем потока для диффузоров ДУ200 и решёток 350×350 мм.</p>
      </div>
    </div>

    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob4.jpg');"></div>
      <div class="equipment-content">
        <h5>Комплект смарт-зондов Testo</h5>
        <p>Универсальный набор для диагностики систем вентиляции: анемометр с обогреваемой струйной, зонд-крыльчатка ду15, пирометр лазерный, зонд замера качества воздуха,
термогигрометр.</p>
      </div>
    </div>

    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob5.jpg');"></div>
      <div class="equipment-content">
        <h5>Октавный шумомер Октава 110А</h5>
        <p>Точные измерения уровня шума и вибраций оборудования для соответствия санитарным нормам и стандартам.</p>
      </div>
    </div>

    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob6.jpg');"></div>
      <div class="equipment-content">
        <h5>СУВ-1</h5>
        <p>Прибор для проведения испытаний на герметичность вентиляционной сети. Данный прибор наша собственная разработка, позволяющая быстро и качественно проводить испытания и определять величину утечек.</p>
      </div>
    </div>

    <!-- <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob6.webp');"></div>
      <div class="equipment-content">
        <h3>Анемометр Testo 416</h3>
        <p>Компактный анемометр с крыльчаткой ДУ16 для измерения скорости воздушных потоков в системах вентиляции.</p>
      </div>
    </div> -->

    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob8.jpg');"></div>
      <div class="equipment-content">
        <h5>Измерительный прибор Danfoss PFM 1000</h5>
        <p>Многофункциональный прибор для диагностики и наладки систем отопления, вентиляции и кондиционирования.</p>
      </div>
    </div>

    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob9.jpg');"></div>
      <div class="equipment-content">
        <h5>Измерительный прибор TA Scope</h5>
        <p>Современный диагностический комплекс для комплексного анализа параметров инженерных систем.</p>
      </div>
    </div>



    <div class="equipment-card">
      <div class="equipment-image" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/ob1.jpg');"></div>
      <div class="equipment-content">
        <h5>Комплекс для очистки и герметичности</h5>
        <p>Собственный комплекс «БИС — Баланс Инженерных Систем»: механическая очистка, химическая обработка и проверка герметичности воздуховодов с фото- и видеофиксацией каждого этапа.</p>
      </div>
    </div>


    </div>
    <div class="equipment-slider-nav">
      <button class="slider-prev" aria-label="Предыдущий прибор">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
      <div class="slider-dots"></div>
      <button class="slider-next" aria-label="Следующий прибор">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
  </div>
</section>



<?php
$gratitude_letters = new WP_Query(array(
  'post_type'      => 'bis_gratitude',
  'posts_per_page' => -1,
  'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'),
));
if ($gratitude_letters->have_posts()) :
?>
<section class="gratitude-section" id="gratitude">
  <div class="section-header">
    <!-- <span class="section-badge">Отзывы</span> -->
    <h2 class="section-title">Отзывы наших клиентов</h2>
    <p class="section-subtitle">Благодарственные письма от партнёров и заказчиков подтверждают качество и результат нашей работы</p>
  </div>

  <div class="gratitude-slider-wrapper">
    <button class="gratitude-nav gratitude-prev" type="button" aria-label="Предыдущий отзыв">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>

    <div class="gratitude-slider">
      <div class="gratitude-track">
        <?php while ($gratitude_letters->have_posts()) : $gratitude_letters->the_post(); ?>
          <?php
          $image_url = has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : '';
          $title_attr = the_title_attribute(array('echo' => false));
          ?>
          <article class="gratitude-card<?php echo $image_url ? ' has-image' : ''; ?>"<?php if ($image_url) : ?> data-image="<?php echo esc_url($image_url); ?>" data-title="<?php echo esc_attr($title_attr); ?>" tabindex="0"<?php endif; ?>>
            <div class="gratitude-letter">
              <?php if ($image_url) : ?>
                <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
              <?php else : ?>
                <div class="gratitude-letter__placeholder">Изображение письма появится здесь</div>
              <?php endif; ?>
            </div>
            <h3 class="gratitude-company"><?php the_title(); ?></h3>
          </article>
        <?php endwhile; ?>
      </div>
    </div>

    <button class="gratitude-nav gratitude-next" type="button" aria-label="Следующий отзыв">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
  </div>
</section>
<?php
wp_reset_postdata();
endif;
?>

<div class="gratitude-modal" id="gratitudeModal" aria-hidden="true" role="dialog">
  <div class="gratitude-modal-backdrop" data-close-gratitude></div>
  <div class="gratitude-modal-content">
    <button class="gratitude-modal-close" type="button" aria-label="Закрыть увеличенное письмо" data-close-gratitude>
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <div class="gratitude-modal-image">
      <img src="" alt="Благодарственное письмо" loading="lazy">
    </div>
    <p class="gratitude-modal-title"></p>
  </div>
</div>



  <!-- Experience Section -->
<section class="experience" id="experience">
  <div class="section-header">
    <!-- <span class="section-badge">Опыт</span> -->
    <h2 class="section-title">Наши ключевые проекты</h2>
    <p class="section-subtitle">Реализованные решения для ведущих компаний</p>
  </div>
  
  <?php
  $featured_projects = new WP_Query(array(
    'post_type'      => 'bis_project',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_key'       => 'bis_project_is_featured',
    'meta_value'     => '1',
  ));
  ?>

  <?php if ($featured_projects->have_posts()) : ?>
  <div class="experience-grid">
    <?php while ($featured_projects->have_posts()) : $featured_projects->the_post(); ?>
      <?php
      $project_id = get_the_ID();
      $image_url = bis_get_project_image_url($project_id);
      $details = bis_get_project_details($project_id);
      ?>
      <div class="experience-card"
           data-image="<?php echo esc_url($image_url); ?>"
           data-address="<?php echo esc_attr($details['address']); ?>"
           data-area="<?php echo esc_attr($details['area']); ?>"
           data-year="<?php echo esc_attr($details['year']); ?>"
           data-featured="1">
        <div class="experience-image" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
        <div class="experience-content">
          <h3><?php the_title(); ?></h3>
          <div class="experience-meta">
            <?php if (!empty($details['address'])) : ?>
              <span class="experience-meta__item">Адрес: <?php echo esc_html($details['address']); ?></span>
            <?php endif; ?>
            <?php if (!empty($details['area'])) : ?>
              <span class="experience-meta__item">Площадь: <?php echo esc_html($details['area']); ?> м²</span>
            <?php endif; ?>
            <?php if (!empty($details['year'])) : ?>
              <span class="experience-meta__item">Год: <?php echo esc_html($details['year']); ?></span>
            <?php endif; ?>
          </div>
          <button type="button" class="experience-more">Подробнее<span aria-hidden="true">→</span></button>
        </div>
      </div>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
  </div>
  <?php else : ?>
    <p class="section-subtitle">Добавьте проекты в админке, чтобы показать их здесь.</p>
  <?php endif; ?>
  
  <div class="experience-cta">
    <button class="btn btn-primary show-all-cases">Смотреть все проекты</button>
  </div>
</section>

<div class="experience-modal-overlay" id="experienceModal">
  <div class="experience-modal">
    <button class="modal-close" id="experienceModalClose" aria-label="Закрыть описание проекта">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <div class="experience-modal-image"></div>
    <div class="experience-modal-content">
      <h2 class="experience-modal-title"></h2>
      <div class="experience-modal-meta"></div>
      <a href="#contact" class="btn btn-primary experience-modal-cta">Обсудить проект</a>
    </div>
  </div>
</div>

<div class="cases-modal-overlay" id="casesModal">
  <div class="cases-modal">
    <button class="modal-close" id="modalClose">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    
    <div class="modal-header">
      <h2>Все наши проекты</h2>
    </div>
    
    <div class="all-cases-grid">
      <?php
      $all_projects = new WP_Query(array(
        'post_type'      => 'bis_project',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
      ));
      ?>

      <?php if ($all_projects->have_posts()) : ?>
        <?php while ($all_projects->have_posts()) : $all_projects->the_post(); ?>
          <?php
          $project_id = get_the_ID();
          $image_url = bis_get_project_image_url($project_id);
          $details = bis_get_project_details($project_id);
          $is_featured = get_post_meta($project_id, 'bis_project_is_featured', true) === '1';
          ?>
          <div class="all-case-card"
               data-image="<?php echo esc_url($image_url); ?>"
               data-address="<?php echo esc_attr($details['address']); ?>"
               data-area="<?php echo esc_attr($details['area']); ?>"
               data-year="<?php echo esc_attr($details['year']); ?>"
               data-featured="<?php echo $is_featured ? '1' : '0'; ?>">
            <div class="all-case-image" style="background-image: url('<?php echo esc_url($image_url); ?>');"></div>
            <h4><?php the_title(); ?></h4>
            <div class="experience-meta experience-meta--compact">
              <?php if (!empty($details['address'])) : ?>
                <span class="experience-meta__item">Адрес: <?php echo esc_html($details['address']); ?></span>
              <?php endif; ?>
              <?php if (!empty($details['area'])) : ?>
                <span class="experience-meta__item">Площадь: <?php echo esc_html($details['area']); ?> м²</span>
              <?php endif; ?>
              <?php if (!empty($details['year'])) : ?>
                <span class="experience-meta__item">Год: <?php echo esc_html($details['year']); ?></span>
              <?php endif; ?>
              <?php if ($is_featured) : ?>
                <span class="experience-meta__item experience-meta__item--featured">Ключевой проект</span>
              <?php endif; ?>
            </div>
            <button type="button" class="case-more">Подробнее<span aria-hidden="true">→</span></button>
          </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
      <?php else : ?>
        <p class="section-subtitle">Добавьте проекты, чтобы показать их здесь.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Callback Modal -->
<div class="popup-overlay" id="callbackOverlay">
  <div class="popup-form">
    <button class="popup-close" id="callbackClose">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    
    <h2>Обратный звонок</h2>
    <p>Оставьте свои контакты и мы перезвоним вам в течение 15 минут</p>
    
    <form class="contact-form" id="callbackForm">
      <div class="form-group">
        <label for="callbackName">Имя</label>
        <input type="text" id="callbackName" name="name" required placeholder="Ваше имя">
      </div>
      
      <div class="form-group">
        <label for="callbackPhone">Телефон</label>
        <input type="tel" id="callbackPhone" name="phone" required placeholder="+7 (___) ___-__-__">
      </div>
      
      <div class="form-group">
        <label for="callbackMessage">Сообщение (необязательно)</label>
        <textarea id="callbackMessage" name="message" placeholder="Кратко опишите ваш вопрос"></textarea>
      </div>
      
      <button type="submit" class="btn btn-primary">Позвоните мне</button>
    </form>
  </div>
</div>

<?php $team_members = bis_get_team_members(); ?>
<section class="structure-section team-section" id="structure">
  <?php if (!empty($team_members)) : ?>
    <div class="team-slider" data-team-slider>
      <div class="team-track-wrap">
        <div class="team-track">
          <?php foreach ($team_members as $member) :
            $name = isset($member['name']) ? $member['name'] : '';
            $role = isset($member['role']) ? $member['role'] : '';
            $short = isset($member['short']) ? $member['short'] : '';
            $long = isset($member['long']) ? $member['long'] : '';
            $photo = isset($member['photo']) ? $member['photo'] : '';
            $modal_photo = isset($member['modal_photo']) ? $member['modal_photo'] : '';
            $modal_photo = $modal_photo ? $modal_photo : $photo;
            ?>
            <article class="team-slide" data-team-slide data-name="<?php echo esc_attr($name); ?>" data-role="<?php echo esc_attr($role); ?>" data-photo="<?php echo esc_url($photo); ?>" data-modal-photo="<?php echo esc_url($modal_photo); ?>">
              <div class="team-slide__content">
                <span class="team-label">Команда</span>
                <div class="team-story"><?php echo wp_kses_post(wpautop($short)); ?></div>
                <div class="team-meta">
                  <span class="team-name"><?php echo esc_html($name); ?></span>
                  <span class="team-role"><?php echo esc_html($role); ?></span>
                </div>
                <div class="team-controls-wrap" style="
                  display: flex;
                  width: 100%;
                  justify-content: space-between;
                  height: fit-content;">
                  <button class="btn btn-primary team-more" type="button" data-team-more>Подробнее</button>
                  <div class="team-controls">
                  <button class="team-nav team-prev" aria-label="Предыдущий сотрудник">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                      <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                  <button class="team-nav team-next" aria-label="Следующий сотрудник">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                      <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </button>
                </div>
                </div>
              </div>
              <div class="team-slide__photo" style="background-image: url('<?php echo esc_url($photo); ?>');"></div>
              <div class="team-slide__long" hidden>
                <?php echo wp_kses_post(wpautop($long)); ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php else : ?>
    <div class="team-empty">
      <span class="team-empty__label">Команда</span>
      <p>Мы готовим презентацию ключевых специалистов.</p>
    </div>
  <?php endif; ?>
</section>


<div class="team-modal" id="teamModal" aria-hidden="true" role="dialog">
  <div class="team-modal__backdrop" data-team-close></div>
  <div class="team-modal__dialog" aria-modal="true" aria-labelledby="teamModalTitle">
    <button class="team-modal__close" type="button" aria-label="Закрыть" data-team-close>
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    <div class="team-modal__image">
      <img src="" alt="" loading="lazy" data-team-modal-image>
    </div>
    <div class="team-modal__body">
      <h3 class="team-modal__name" id="teamModalTitle" data-team-modal-name></h3>
      <p class="team-modal__role" data-team-modal-role></p>
      <div class="team-modal__text" data-team-modal-text></div>
    </div>
  </div>
</div>

<!-- Why Us Section -->
<section class="why-us" id="why">
  <div class="section-header">
    <span class="section-badge">Почему выбирают нас</span>
    <p class="section-subtitle">«БИС — Баланс Инженерных Систем» — это команда молодых и трудолюбивых специалистов. Для нас нет неразрешимых задач, поэтому если в Вашей деятельности возник вопрос по пусконаладке или замерам, то мы обязательно постараемся помочь.</p>
  </div>
  

  <div class="why-grid">
    <div class="why-card">
      <div class="why-number">01</div>
      <h3>Экспертиза</h3>
      <p>Команда сертифицированных специалистов в сфере инженерных систем.</p>
    </div>
    <div class="why-card">
      <div class="why-number">02</div>
      <h3>Надежность</h3>
      <p>Используем только проверенное оборудование и технологии. Гарантия на все виды работ.</p>
    </div>
    <div class="why-card">
      <div class="why-number">03</div>
      <h3>Индивидуальный подход</h3>
      <p>Разрабатываем решения под конкретные задачи и особенности вашего объекта.</p>
    </div>
    <div class="why-card">
      <div class="why-number">04</div>
      <h3>Поддержка 24/7</h3>
      <p>Круглосуточная техническая поддержка и оперативное реагирование на любые запросы.</p>
    </div>
  </div>
</section>

<?php
$news_query = new WP_Query(array(
  'post_type' => 'bis_news',
  'posts_per_page' => 4,
  'post_status' => 'publish',
));
?>

<?php if ($news_query->have_posts()) : ?>
<section class="homepage-news" id="news">
  <div class="homepage-news__container">
    <div class="homepage-news__header">
      <!-- <span class="section-badge">Новости</span> -->
      <h2 class="section-title">Свежие новости компании</h2>
      <p class="section-subtitle">Рассказываем о ключевых событиях, проектах и экспертизе нашей команды.</p>
    </div>

    <div class="homepage-news__grid">
      <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
        <article class="news-card news-card--home">
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
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('d.m.Y')); ?></time>
            </div>
            <h3 class="news-card__title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h3>
            <p class="news-card__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
            <a class="news-card__link" href="<?php the_permalink(); ?>">Читать</a>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <div class="homepage-news__cta">
      <a class="btn btn-primary" href="<?php echo esc_url(get_post_type_archive_link('bis_news')); ?>">Все новости</a>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); ?>
<?php endif; ?>

  <!-- Contact Section -->
  <section class="contact" id="contact">
    <div class="contact-wrapper">
      <div class="contact-info">
        <h2>Свяжитесь с нами</h2>
        <p>Для нас нет неразрешимых задач, мы не боимся трудностей, решение неординарных технических задач - наша работа поэтому если в Вашей деятельности возникли технические задачи мы обязательно постараемся помочь!</p>
        <div class="contact-details">
          <div class="contact-item">
            <div class="contact-icon">📞</div>
            <div class="contact-item-content">
              <h4>Телефон</h4>
              <a href="tel:+79264380770">+7 (926) 438-07-70</a><br>
              <a href="tel:+79169861187">+7 (916) 986-11-87</a>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon">✉️</div>
            <div class="contact-item-content">
              <h4>Email</h4>
              <a href="mailto:office@bis-rf.ru">office@bis-rf.ru</a>
            </div>
          </div>
          <div class="contact-item">
            <div class="contact-icon">📍</div>
            <div class="contact-item-content">
              <h4>Адрес</h4>
              <p>г. Москва, проезд Таможенный д.6, стр.9</p>
            </div>
          </div>
        </div>
      </div>
      <div class="contact-form-wrapper">
        <form class="contact-form" id="contactForm">
          <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" id="name" name="name" required>
          </div>
          <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="tel" id="phone" name="phone" required>
          </div>
          <div class="form-group">
            <label for="message">Сообщение</label>
            <textarea id="message" name="message" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Отправить заявку</button>
        </form>
      </div>
    </div>
    
    <!-- Яндекс.Карта -->
    <div class="map-container">
      <div id="yandex-map">
        <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A7972908992b0111eaafc38990b26b0c1dbbd437ee1e3b769e14322fe175cdfff&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
      </div>
    </div>
  </section>

<?php $revenue = bis_get_revenue_settings(); ?>
<section class="revenue-section" id="revenue">
  <div class="revenue-container">
    <div class="revenue-heading">
      <div>
        <span class="section-badge section-badge--ghost">Динамика</span>
        <h2 class="section-title"><?php echo esc_html($revenue['title']); ?></h2>
      </div>
      <div class="revenue-meta-chip">
        <span class="revenue-meta-label">Единицы</span>
        <span class="revenue-meta-value"><?php echo esc_html($revenue['currency_label']); ?></span>
      </div>
    </div>

    <div class="revenue-card">
      <div class="revenue-card__gradient"></div>
      <div class="revenue-card__header">
        <div class="revenue-card__title">Рост компании в динамике</div>
      </div>

      <div class="revenue-chart" data-currency="<?php echo esc_attr($revenue['currency_label']); ?>" data-revenue-points="<?php echo esc_attr(wp_json_encode($revenue['points'])); ?>">
        <svg class="revenue-chart__svg" viewBox="0 0 100 60" preserveAspectRatio="none" aria-hidden="true">
          <defs>
            <linearGradient id="revenueGradient" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stop-color="rgba(132, 139, 153, 0.25)"/>
              <stop offset="100%" stop-color="rgba(132, 139, 153, 0.08)"/>
            </linearGradient>
            <linearGradient id="revenueLine" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#848b99"/>
              <stop offset="100%" stop-color="#848b99"/>
            </linearGradient>
            <filter id="revenueGlow" x="-50%" y="-50%" width="200%" height="200%">
              <feGaussianBlur stdDeviation="2.8" result="coloredBlur"/>
              <feMerge>
                <feMergeNode in="coloredBlur"/>
                <feMergeNode in="SourceGraphic"/>
              </feMerge>
            </filter>
          </defs>
          <path class="revenue-chart__area" fill="url(#revenueGradient)" d=""></path>
          <path class="revenue-chart__line" stroke="url(#revenueLine)" stroke-width="1.8" fill="none" filter="url(#revenueGlow)" d=""></path>
        </svg>
        <div class="revenue-chart__grid"></div>
        <div class="revenue-chart__labels" data-revenue-labels></div>
        <div class="revenue-chart__last" data-revenue-last>
          <div class="revenue-chip">—</div>
          <span class="revenue-chip__caption">текущий показатель</span>
        </div>
      </div>

      <div class="revenue-footer">
        <?php if (!empty($revenue['cta_link'])) : ?>
          <a class="btn btn-outline btn-outline--bold" href="<?php echo esc_url($revenue['cta_link']); ?>">
            <?php echo esc_html($revenue['cta_label']); ?>
          </a>
        <?php else : ?>
          <span class="btn btn-outline btn-outline--bold btn-disabled"><?php echo esc_html($revenue['cta_label']); ?></span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="faq-section" id="faq">
  <div class="section-header">
    <span class="section-badge">FAQ</span>
    <h2 class="section-title">Часто задаваемые вопросы</h2>
    <p class="section-subtitle">Ответы на самые популярные вопросы о пусконаладочных работах</p>
  </div>

  <div class="faq-container">
    <div class="faq-item">
      <div class="faq-question">
        <h3>Как расшифровывается аббревиатура ПНР?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <p><strong>Пусконаладочные работы</strong></p>
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">
        <h3>Пусконаладочные работы - что это?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <p>Это комплекс мероприятий по регулировке инженерных систем с целью фактического достижения проектных показателей</p>
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">
        <h3>Зачем нужны пусконаладочные работы?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <p>Для поддержания комфортного пребывания человека в помещениях с искусственным микроклиматом</p>
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">
        <h3>Сколько стоят пусконаладочные работы?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <p><strong>7 – 10% от стоимости СМР</strong></p>
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">
        <h3>Что включено в стоимость пусконаладочных работ?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <ul>
          <li>Составление и согласование программы наладки</li>
          <li>Проверка фактического исполнения систем проектной документации</li>
          <li>Оформление ведомости соответствия с фотоотчетом</li>
          <li>Проведение индивидуальных испытаний оборудования</li>
          <li>Выполнение комплекса наладочных работ</li>
          <li>Разработка и оформление паспортов и протоколов</li>
        </ul>
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">
        <h3>Зачем делать испытания воздуховодов на герметичность?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <p>Мы рекомендуем обязательно делать испытания воздуховодов на герметичность до возведения строительных конструкций. Это существенно сэкономит ваши нервы, деньги и время при сдаче объекта и дальнейшей эксплуатации, проверено опытом.</p>
      </div>
    </div>

    <div class="faq-item">
      <div class="faq-question">
        <h3>Какую информацию необходимо предоставить для расчета стоимости?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <p><strong>При наличии необходимо предоставить:</strong></p>
        <ul>
          <li>Аксонометрические схемы</li>
          <li>Поэтажные планы с разводкой систем</li>
          <li>Высоту помещений</li>
          <li>Разрешенное время проведения работ (день или ночь)</li>
          <li>Срок производства работ (начало-окончание)</li>
        </ul>
        <p>По вашему желанию данную информацию мы можем собрать самостоятельно при осмотре объекта.</p>
        <p>Тогда от вас нам будет достаточно получить только адрес объекта и контакт ответственного инженера.</p>
      </div>
    </div>
    <div class="faq-item">
      <div class="faq-question">
        <h3>В соответствии с какими нормативными документами выполняются работы?</h3>
        <span class="faq-toggle">+</span>
      </div>
      <div class="faq-answer">
        <ul class="pnr-standards">
          <li>ГОСТ 34060-2017 (испытание и наладка систем вентиляции и кондиционирования воздуха)</li>
          <li>СП 60.13330.2016 «Отопление, вентиляция и кондиционирование»</li>
          <li>СП 73.13330.2016 «Внутренние санитарно-технические системы»</li>
          <li>СП 7.13130.2016 «Требования пожарной безопасности»</li>
          <li>ГОСТ Р 53300-2009 «Противодымная защита зданий»</li>
          <li>ГОСТ 12.3.018-79 ССБТ</li>
        </ul>
      </div>
    </div>
  </div>

  <div class="faq-cta">
    <p>Остались вопросы?</p>
    <a href="#contact" class="btn btn-primary">Получить консультацию</a>
  </div>
</section>


<?php get_template_part('estimate-modal'); ?>
<?php get_footer(); ?>
