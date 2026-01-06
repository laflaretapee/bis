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
      <div class="logo">
        <a href="<?php echo esc_url( home_url( '/#home' ) ); ?>" class="logo-link" aria-label="На главную">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/LOGOLOGO11.png" alt="БИС — Баланс Инженерных Систем">
        </a>
      </div>
      <nav>
        <ul class="nav" id="nav">
          <li><a href="#specialization">Специализация</a></li>
          <li><a href="#equipment">Оборудование</a></li>
          <li><a href="#experience">Опыт</a></li>
          <li><a href="#why">О нас</a></li>
          <li><a href="#contact">Контакты</a></li>
          <li><a href="#faq">F.A.Q</a></li>
          <li class="mobile-callback">
            <button class="btn btn-primary callback-btn-mobile">Обратный звонок</button>
          </li>
        </ul>
      </nav>
      <div class="header-cta">
        <button class="btn btn-primary callback-btn">Обратный звонок</button>
      </div>
      <button class="menu-toggle" id="menuToggle" aria-label="Меню">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </header>
