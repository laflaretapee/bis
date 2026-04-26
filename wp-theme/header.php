<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="title" content="<?php echo bloginfo('title'); ?>">
  <meta name="description" content="<?php echo bloginfo('description');?>">
  <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/LOGOLOGO11.ico">
  <title><?php echo get_the_title();?></title>
  <?php wp_head(); ?>
  <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
  
  <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=108008534', 'ym');

    ym(108008534, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/108008534" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</head>
<body <?php body_class('site-loading'); ?>>
  <?php wp_body_open(); ?>
  <div class="site-loader" id="siteLoader" role="status" aria-live="polite" aria-label="Loading page">
    <div class="site-loader__inner">
      <div class="site-loader__mark" aria-hidden="true">
        <span class="site-loader__ring"></span>
        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/LOGOLOGO11.png" alt="" class="site-loader__logo">
      </div>
      <div class="site-loader__progress" aria-hidden="true">
        <span class="site-loader__line"></span>
        <span class="site-loader__percent" data-loader-percent>0%</span>
      </div>
    </div>
  </div>
  <noscript><style>.site-loader{display:none!important}body.site-loading{overflow:auto}</style></noscript>
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
        </div>
        <button class="drawer-close" id="drawerClose" aria-label="Закрыть меню">
          <span></span>
          <span></span>
        </button>
      </div>
      <ul class="drawer-nav">
        <li><a href="<?php echo esc_url(home_url()); ?>">На главную</a></li>
        <li><a href="<?php echo esc_url(home_url('/about/')); ?>">О нас</a></li>
        <li><a href="<?php echo esc_url(home_url('/projects/')); ?>">Наши проекты</a></li>
        <li><a href="<?php echo esc_url(home_url('/#services'));?>">Специализация</a></li>
        <li><a href="<?php echo esc_url(home_url('/#equipment'));?>">Оборудование</a></li>
        <li><a href="<?php echo esc_url(home_url('/#contact'));?>">Контакты</a></li>
        <li><a href="<?php echo esc_url(home_url('/#faq'));?>">F.A.Q</a></li>
      </ul>
      <div class="drawer-footer">
        <p class="drawer-note">Инжиниринговая команда полного цикла — проектируем, запускаем, сопровождаем.</p>
        <div class="drawer-actions">
          <button class="btn btn-primary callback-btn">Обратный звонок</button>
        </div>
      </div>
    </aside>
  </div>
