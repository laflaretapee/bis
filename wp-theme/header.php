<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="БИС: комплексные пусконаладочные работы, техническое обслуживание и сопровождение инженерных систем">
  <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/LOGOLOGO11.ico">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <!-- Header -->
  <header class="header" id="header">
    <div class="header-content">
      <div class="brand-block">
        <a href="<?php echo esc_url( home_url( '/#home' ) ); ?>" class="logo-link" aria-label="На главную">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/LOGOLOGO11.png" alt="БИС — Баланс Инженерных Систем" class="brand-mark">
        </a>
        <div class="brand-text">
          <span class="brand-title">«БИС» — Баланс</span>
          <span class="brand-subtitle">Инженерные системы</span>
        </div>
      </div>
      <div class="header-actions">
        <button class="menu-toggle" id="menuToggle" aria-label="Меню">
          <span class="line line-top"></span>
          <span class="line line-middle"></span>
          <span class="line line-bottom"></span>
        </button>
      </div>
    </div>
  </header>

  <div class="nav-drawer" id="navDrawer" aria-hidden="true">
    <div class="nav-drawer__backdrop" id="navBackdrop"></div>
    <aside class="nav-drawer__panel">
      <div class="drawer-header">
        <div class="drawer-brand">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/LOGOLOGO11.png" alt="БИС — Баланс Инженерных Систем" class="drawer-mark">
          <div class="drawer-brand-text">
            <span class="drawer-title">Баланс Инженерных Систем</span>
            <span class="drawer-subtitle">технологически и эстетически совершенные</span>
          </div>
        </div>
        <button class="drawer-close" id="drawerClose" aria-label="Закрыть меню">
          <span></span>
          <span></span>
        </button>
      </div>
      <ul class="drawer-nav">
        <li><a href="<?php echo esc_url(home_url('/about/')); ?>">О компании</a></li>
        <li><a href="#services">Специализация</a></li>
        <li><a href="#equipment">Оборудование</a></li>
        <li><a href="#experience">Опыт</a></li>
        <li><a href="#why">О нас</a></li>
        <li><a href="#contact">Контакты</a></li>
        <li><a href="#faq">F.A.Q</a></li>
      </ul>
      <div class="drawer-footer">
        <p class="drawer-note">Инжиниринговая команда полного цикла — проектируем, запускаем, сопровождаем.</p>
        <div class="drawer-actions">
          <button class="btn btn-primary callback-btn">Обратный звонок</button>
        </div>
      </div>
    </aside>
  </div>
