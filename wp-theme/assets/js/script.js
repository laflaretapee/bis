// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
  // applyBisCondensedStyling();
  initTypingEffect();
  initMobileMenu();
  initCallbackModal(); // Добавьте эту строку
  initScrollEffects();
  initHeroParallax();
  //initStatsCounter();
  initFormValidation();
  initPopupForm();
  initSmoothScroll();
  initEquipmentSlider();
  initGratitudeSlider();
  initGratitudeModal();
  initExperienceModal();
  initCasesModal();
  initFAQ();
  initServicesSlider();
  initEstimateModal();
  initRevenueChart();
});

function applyBisCondensedStyling(root = document.body) {
  if (!root) return;

  const disallowedParents = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEXTAREA', 'OPTION']);
  const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
  const textNodes = [];

  while (walker.nextNode()) {
    textNodes.push(walker.currentNode);
  }

  textNodes.forEach(node => {
    const parent = node.parentNode;
    if (!parent || disallowedParents.has(parent.nodeName) || parent.closest('.bis-condensed')) {
      return;
    }

    const text = node.nodeValue;
    if (!text || !text.includes('БИС')) return;

    const parts = text.split(/(БИС)/);
    const fragment = document.createDocumentFragment();

    parts.forEach(part => {
      if (!part) return;
      if (part === 'БИС') {
        const span = document.createElement('span');
        span.className = 'bis-condensed';
        span.textContent = part;
        fragment.appendChild(span);
      } else {
        fragment.appendChild(document.createTextNode(part));
      }
    });

    parent.replaceChild(fragment, node);
  });
}

// Эффект печатающейся машинки
function initTypingEffect() {
  const typingText = document.querySelector('.typing-text');
  const cursor = document.querySelector('.cursor');

  if (!typingText) return;

  const textParts = [
    { text: 'Баланс ', isGradient: false },
    { text: 'Инженерных ', isGradient: true },
    { text: 'Систем»', isGradient: false }
  ];

  // Установим ширину контейнера до начала анимации, чтобы избежать смещения
  const fullText = '«БИС — ' + textParts.map(part => part.text).join('');
  const tempSpan = document.createElement('span');
  tempSpan.style.visibility = 'hidden';
  tempSpan.style.position = 'absolute';
  tempSpan.style.whiteSpace = 'pre-wrap';
  tempSpan.style.font = getComputedStyle(typingText).font;
  tempSpan.textContent = fullText;
  document.body.appendChild(tempSpan);

  // Учитываем размер экрана при установке ширины
  const textWidth = tempSpan.offsetWidth;
  const screenWidth = window.innerWidth;

  // Для мобильных устройств используем меньшую ширину
  if (screenWidth <= 768) {
    // На мобильных устройствах ограничиваем ширину для лучшего отображения
    typingText.style.minWidth = '100%';
    typingText.style.display = 'inline-block';
    typingText.style.width = '100%';
  } else {
    // На десктопе устанавливаем рассчитанную ширину
    typingText.style.minWidth = (textWidth + 20) + 'px'; // Добавляем немного места для курсора
  }

  // Также устанавливаем максимальную ширину для предотвращения переполнения
  typingText.style.maxWidth = '100%';

  // Убедимся, что элемент занимает всю доступную ширину для корректного позиционирования курсора
  typingText.style.flex = '1 1 auto';

  document.body.removeChild(tempSpan);

  let partIndex = 0;
  let charIndex = 0;
  const typingSpeed = 70; // Скорость печати в мс
  const pauseAfterWord = 150; // Пауза после слова
  cursor.style.display = 'inline-block';
  function type() {
    if (partIndex < textParts.length) {
      const currentPart = textParts[partIndex];

      if (charIndex < currentPart.text.length) {
        const char = currentPart.text[charIndex];
        let currentSpan = typingText.querySelector(`[data-part="${partIndex}"]`);
        if (!currentSpan) {
          currentSpan = document.createElement('span');
          currentSpan.setAttribute('data-part', partIndex);
          if (currentPart.isGradient) currentSpan.className = 'gradient-text';
          typingText.appendChild(currentSpan);
        }
        currentSpan.textContent += char;

        // Перемещаем курсор после добавленного текста
        if (cursor && currentSpan.parentNode) {
          // Перемещаем курсор после текущего span
          currentSpan.parentNode.insertBefore(cursor, currentSpan.nextSibling);
        }

        charIndex++;
        setTimeout(type, typingSpeed);
      } else {
        partIndex++;
        charIndex = 0;
        setTimeout(type, pauseAfterWord);
      }
    } else {
      setTimeout(() => {
        if (cursor) {
          // Останавливаем анимацию курсора и делаем его менее заметным
          cursor.style.animation = 'none';
          cursor.style.opacity = '0';
        }
      }, 1000);
    }
  }
  setTimeout(type, 500);
}

// Callback Modal Functionality
function initCallbackModal() {
  const callbackButtons = document.querySelectorAll('.callback-btn');
  const callbackBtnMobile = document.querySelector('.callback-btn-mobile');
  const callbackOverlay = document.getElementById('callbackOverlay');
  const callbackClose = document.getElementById('callbackClose');
  const callbackForm = document.getElementById('callbackForm');

  if ((callbackButtons.length === 0 && !callbackBtnMobile) || !callbackOverlay) return;

  // Обработчик для всех кнопок с обратным звонком
  if (callbackButtons.length) {
    callbackButtons.forEach(btn => btn.addEventListener('click', () => {
      callbackOverlay.classList.add('active');
      closeMenuDrawer();
    }));
  }

  // Обработчик для мобильной кнопки
  if (callbackBtnMobile) {
    callbackBtnMobile.addEventListener('click', () => {
      callbackOverlay.classList.add('active');
      closeMenuDrawer();
    });
  }

  if (callbackClose) {
    callbackClose.addEventListener('click', () => {
      closeCallbackModal();
    });
  }

  callbackOverlay.addEventListener('click', (e) => {
    if (e.target === callbackOverlay) {
      closeCallbackModal();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && callbackOverlay.classList.contains('active')) {
      closeCallbackModal();
    }
  });

  if (callbackForm) {
    callbackForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = {
        name: callbackForm.querySelector('#callbackName').value,
        phone: callbackForm.querySelector('#callbackPhone').value,
        message: callbackForm.querySelector('#callbackMessage').value,
        type: 'callback'
      };

      if (validateForm(formData)) {
        submitCallbackForm(formData, callbackForm);
      }
    });

    // Добавляем валидацию полей
    const inputs = callbackForm.querySelectorAll('input, textarea');
    inputs.forEach(input => {
      input.addEventListener('blur', () => validateField(input));
      input.addEventListener('input', () => {
        if (input.classList.contains('error')) validateField(input);
      });
    });
  }

  function closeCallbackModal() {
    callbackOverlay.classList.remove('active');
    if (callbackForm) {
      callbackForm.reset();
      const inputs = callbackForm.querySelectorAll('input, textarea');
      inputs.forEach(input => clearError(input));
    }
  }
}

// Revenue chart rendering
function initRevenueChart() {
  const chart = document.querySelector('.revenue-chart');
  if (!chart) return;

  const svg = chart.querySelector('.revenue-chart__svg');
  const linePath = svg?.querySelector('.revenue-chart__line');
  const areaPath = svg?.querySelector('.revenue-chart__area');
  const lastBadge = chart.querySelector('[data-revenue-last] .revenue-chip');
  const tooltip = document.createElement('div');
  tooltip.className = 'revenue-tooltip';
  chart.appendChild(tooltip);

  let points = [];
  try {
    const dataAttr = chart.dataset.revenuePoints ? JSON.parse(chart.dataset.revenuePoints) : [];
    if (Array.isArray(dataAttr)) points = dataAttr;
  } catch (e) {
    points = [];
  }

  if (!points.length && typeof bisRevenueData !== 'undefined' && Array.isArray(bisRevenueData.points)) {
    points = bisRevenueData.points;
  }

  if (!points.length || !svg || !linePath || !areaPath) return;

  const cleanPoints = points
    .map(point => ({
      label: point.label || '',
      value: parseFloat(point.value) || 0,
    }))
    .filter(point => point.label !== '');

  if (cleanPoints.length < 2) return;

  const width = 100;
  const height = 60;
  const paddingTop = 6;
  const paddingBottom = 6;
  const maxValue = Math.max(...cleanPoints.map(p => p.value), 1);
  const lastValue = cleanPoints[cleanPoints.length - 1]?.value || 0;
  const currencyLabel = chart.dataset.currency || (bisRevenueData?.currency_label || '');

  const coords = cleanPoints.map((point, index) => {
    const x = (index / (cleanPoints.length - 1)) * width;
    const y = height - paddingBottom - (point.value / maxValue) * (height - paddingTop - paddingBottom);
    return { x, y, value: point.value, label: point.label };
  });

  const smoothing = 0.2;

  function controlPoint(current, previous, next, reverse) {
    const p = previous || current;
    const n = next || current;
    const o = {
      length: Math.hypot(n.x - p.x, n.y - p.y) * smoothing,
      angle: Math.atan2(n.y - p.y, n.x - p.x),
    };
    const angle = o.angle + (reverse ? Math.PI : 0);
    return {
      x: current.x + Math.cos(angle) * o.length,
      y: current.y + Math.sin(angle) * o.length,
    };
  }

  const lineD = coords.reduce((path, point, i, arr) => {
    if (i === 0) {
      return `M ${point.x.toFixed(2)} ${point.y.toFixed(2)}`;
    }
    const cp1 = controlPoint(arr[i - 1], arr[i - 2], point, false);
    const cp2 = controlPoint(point, arr[i - 1], arr[i + 1], true);
    return `${path} C ${cp1.x.toFixed(2)} ${cp1.y.toFixed(2)} ${cp2.x.toFixed(2)} ${cp2.y.toFixed(2)} ${point.x.toFixed(2)} ${point.y.toFixed(2)}`;
  }, '');

  const areaStart = `M ${coords[0].x.toFixed(2)} ${height - paddingBottom}`;
  const areaCurve = coords.reduce((path, point, i, arr) => {
    if (i === 0) {
      return `${path} L ${point.x.toFixed(2)} ${point.y.toFixed(2)}`;
    }
    const cp1 = controlPoint(arr[i - 1], arr[i - 2], point, false);
    const cp2 = controlPoint(point, arr[i - 1], arr[i + 1], true);
    return `${path} C ${cp1.x.toFixed(2)} ${cp1.y.toFixed(2)} ${cp2.x.toFixed(2)} ${cp2.y.toFixed(2)} ${point.x.toFixed(2)} ${point.y.toFixed(2)}`;
  }, areaStart);
  const areaD = `${areaCurve} L ${coords[coords.length - 1].x.toFixed(2)} ${height - paddingBottom} Z`;

  linePath.setAttribute('d', lineD);
  areaPath.setAttribute('d', areaD);

  // Animate stroke drawing
  const totalLength = linePath.getTotalLength();
  linePath.style.strokeDasharray = totalLength;
  linePath.style.strokeDashoffset = totalLength;
  areaPath.style.opacity = 0;

  requestAnimationFrame(() => {
    linePath.style.transition = 'stroke-dashoffset 1.2s ease, opacity 0.6s ease';
    areaPath.style.transition = 'opacity 1s ease';
    linePath.style.strokeDashoffset = '0';
    linePath.style.opacity = 1;
    areaPath.style.opacity = 1;
  });

  // Points with hover tooltip (SVG circles to stay on path)
  const pointsGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
  pointsGroup.setAttribute('class', 'revenue-points');
  svg.appendChild(pointsGroup);

  function showTooltip(xPercent, yPercent, text) {
    const clampedX = Math.min(92, Math.max(8, xPercent));
    const desiredTop = yPercent - 8;
    const placeBelow = desiredTop < 10;
    const finalY = placeBelow ? Math.min(90, yPercent + 10) : Math.max(10, desiredTop);
    tooltip.textContent = text;
    tooltip.style.left = `${clampedX}%`;
    tooltip.style.top = `${finalY}%`;
    tooltip.style.opacity = '1';
    tooltip.style.transform = placeBelow ? 'translate(-50%, 20%)' : 'translate(-50%, -130%)';
  }

  function hideTooltip() {
    tooltip.style.opacity = '0';
    tooltip.style.transform = 'translate(-50%, -90%)';
  }

  const chartRect = chart.getBoundingClientRect();
  const svgRect = svg.getBoundingClientRect();

  coords.forEach(coord => {
    const cx = coord.x;
    const cy = coord.y;
    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    circle.setAttribute('class', 'revenue-point');
    circle.setAttribute('cx', cx.toFixed(2));
    circle.setAttribute('cy', cy.toFixed(2));
    circle.setAttribute('r', '0.5');
    circle.setAttribute('stroke-width', '0.35');

    circle.addEventListener('mouseenter', () => {
      const xPercent = (cx / width) * 100;
      const yPercent = (cy / height) * 100;
      const text = `${coord.value}${currencyLabel ? ' ' + currencyLabel : ''}`;
      showTooltip(xPercent, yPercent, text);
    });
    circle.addEventListener('mouseleave', hideTooltip);
    pointsGroup.appendChild(circle);
  });

  if (lastBadge) {
    lastBadge.textContent = `${lastValue}${currencyLabel ? ' ' + currencyLabel : ''}`;
  }
}

// Функция отправки формы обратного звонка
function submitCallbackForm(data, form) {
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalText = submitBtn.textContent;

  submitBtn.disabled = true;
  submitBtn.textContent = 'Отправка...';
  submitBtn.style.opacity = '0.6';

  setTimeout(() => {
    submitBtn.textContent = '✓ Отправлено!';
    submitBtn.style.background = '#10b981';

    form.reset();
    closeCallbackModal();

    showNotification('Спасибо! Мы перезвоним вам в течение 15 минут.', 'success');

    setTimeout(() => {
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
      submitBtn.style.background = '';
      submitBtn.style.opacity = '';
    }, 3000);
  }, 1500);
}

function openMenuDrawer() {
  const navDrawer = document.getElementById('navDrawer');
  const menuToggle = document.getElementById('menuToggle');

  if (navDrawer) {
    navDrawer.classList.add('active');
    navDrawer.setAttribute('aria-hidden', 'false');
  }

  if (menuToggle) {
    menuToggle.classList.add('active');
  }

  document.body.classList.add('nav-open');
}

function closeMenuDrawer() {
  const navDrawer = document.getElementById('navDrawer');
  const menuToggle = document.getElementById('menuToggle');

  if (navDrawer) {
    navDrawer.classList.remove('active');
    navDrawer.setAttribute('aria-hidden', 'true');
  }

  if (menuToggle) {
    menuToggle.classList.remove('active');
  }

  document.body.classList.remove('nav-open');
}


// Мобильное меню
function initMobileMenu() {
  const menuToggle = document.getElementById('menuToggle');
  const navDrawer = document.getElementById('navDrawer');
  const navBackdrop = document.getElementById('navBackdrop');
  const drawerClose = document.getElementById('drawerClose');
  const drawerLinks = document.querySelectorAll('.drawer-nav a');
  const primaryLinks = document.querySelectorAll('.nav a');

  if (!menuToggle || !navDrawer) return;

  menuToggle.addEventListener('click', () => {
    const isOpen = navDrawer.classList.contains('active');
    if (isOpen) {
      closeMenuDrawer();
    } else {
      openMenuDrawer();
    }
  });

  [navBackdrop, drawerClose].forEach(el => {
    if (el) el.addEventListener('click', closeMenuDrawer);
  });

  [...drawerLinks, ...primaryLinks].forEach(link => {
    link.addEventListener('click', () => {
      closeMenuDrawer();
    });
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) {
      closeMenuDrawer();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMenuDrawer();
  });
}

// Эффекты при скролле
function initScrollEffects() {
  const header = document.getElementById('header');
  let lastScroll = 0;

  window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    if (currentScroll > 50) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
    lastScroll = currentScroll;
  });

  const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('fade-in');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Все элементы для анимации при скролле
  document.querySelectorAll(
    '.service-card, .case-card, .why-card, .task-item, .pnr-content, .pnr-why-content, .equipment-card, .brand-card'
  ).forEach(el => observer.observe(el));
}

// Параллакс в hero по умолчанию
function initHeroParallax() {
  const parallax = document.querySelector('.hero-parallax');
  if (!parallax) return;

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReducedMotion) return;

  const hero = document.querySelector('.hero');
  const layers = Array.from(parallax.querySelectorAll('[data-speed]'));
  if (!layers.length) return;

  let ticking = false;
  let heroOffset = 0;

  const recalc = () => {
    heroOffset = hero ? hero.offsetTop : 0;
    update();
  };

  const update = () => {
    const relativeScroll = Math.max(window.scrollY - heroOffset, 0);
    layers.forEach(layer => {
      const speed = parseFloat(layer.dataset.speed) || 0;
      layer.style.transform = `translate3d(0, ${relativeScroll * speed}px, 0)`;
    });
    ticking = false;
  };

  const onScroll = () => {
    if (!ticking) {
      window.requestAnimationFrame(update);
      ticking = true;
    }
  };

  recalc();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', recalc);
}

// Анимация счетчиков статистики
/*function initStatsCounter() {
  const statValues = document.querySelectorAll('.stat-value');
  let animated = false;

  const animateStats = () => {
    if (animated) return;

    const statsSection = document.querySelector('.stats-container');
    if (!statsSection) return;

    const rect = statsSection.getBoundingClientRect();
    const isVisible = rect.top < window.innerHeight && rect.bottom >= 0;

    if (isVisible) {
      animated = true;
      statValues.forEach(stat => {
        const target = parseInt(stat.getAttribute('data-target'));
        const duration = 2000; // 2 секунды
        const increment = target / (duration / 16); // 60 FPS
        let current = 0;

        const updateCounter = () => {
          current += increment;
          if (current < target) {
            stat.textContent = Math.floor(current);
            requestAnimationFrame(updateCounter);
          } else {
            stat.textContent = target;
          }
        };

        requestAnimationFrame(updateCounter);
      });
    }
  };

  window.addEventListener('scroll', animateStats);
  animateStats(); // Проверка при загрузке
}*/

// Валидация формы
function initFormValidation() {
  const forms = document.querySelectorAll('#contactForm, #orderForm');

  forms.forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = {
        name: form.querySelector('[id$="name"]').value,
        phone: form.querySelector('[id$="phone"]').value,
        message: form.querySelector('[id$="message"]').value,
        service: form.querySelector('#orderService')?.value || '',
        isOrder: form.id === 'orderForm'
      };

      if (validateForm(formData)) {
        submitForm(formData, form);
      }
    });

    const inputs = form.querySelectorAll('input:not([readonly]), textarea');
    inputs.forEach(input => {
      input.addEventListener('blur', () => validateField(input));
      input.addEventListener('input', () => {
        if (input.classList.contains('error')) validateField(input);
      });
    });
  });
}

// Валидация отдельного поля
function validateField(field) {
  const value = field.value.trim();
  let isValid = true;

  if (field.hasAttribute('required') && !value) {
    isValid = false;
    showError(field, 'Это поле обязательно для заполнения');
  } else if (field.type === 'tel' && value) {
    const phoneRegex = /^[\d\s\+\-\(\)]+$/;
    if (!phoneRegex.test(value) || value.length < 10) {
      isValid = false;
      showError(field, 'Введите корректный номер телефона');
    } else {
      clearError(field);
    }
  } else {
    clearError(field);
  }

  return isValid;
}

// Показать ошибку
function showError(field, message) {
  field.classList.add('error');
  field.style.borderColor = '#ef4444';

  let errorElement = field.parentElement.querySelector('.error-message');
  if (!errorElement) {
    errorElement = document.createElement('span');
    errorElement.className = 'error-message';
    errorElement.style.color = '#ef4444';
    errorElement.style.fontSize = '13px';
    errorElement.style.marginTop = '4px';
    errorElement.style.display = 'block';
    field.parentElement.appendChild(errorElement);
  }
  errorElement.textContent = message;
}

// Очистить ошибку
function clearError(field) {
  field.classList.remove('error');
  field.style.borderColor = '';

  const errorElement = field.parentElement.querySelector('.error-message');
  if (errorElement) errorElement.remove();
}

// Валидация всей формы
function validateForm(data) {
  let isValid = true;

  if (!data.name) {
    isValid = false;
  }

  if (!data.phone) {
    isValid = false;
  }

  if (!data.message && !data.isOrder) {
    isValid = false;
  }

  return isValid;
}

// Отправка формы
function submitForm(data, form) {
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalText = submitBtn.textContent;

  submitBtn.disabled = true;
  submitBtn.textContent = 'Отправка...';
  submitBtn.style.opacity = '0.6';

  setTimeout(() => {
    submitBtn.textContent = '✓ Отправлено!';
    submitBtn.style.background = '#10b981';

    form.reset();

    if (form.id === 'orderForm') {
      closePopup();
    }

    showNotification('Спасибо! Ваша заявка отправлена. Мы свяжемся с вами в ближайшее время.', 'success');

    setTimeout(() => {
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
      submitBtn.style.background = '';
      submitBtn.style.opacity = '';
    }, 3000);
  }, 1500);
}

// Уведомления
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = 'notification';
  notification.textContent = message;

  Object.assign(notification.style, {
    position: 'fixed',
    bottom: '32px',
    right: '32px',
    padding: '16px 24px',
    background: type === 'success' ? '#10b981' : '#2563eb',
    color: 'white',
    borderRadius: '12px',
    boxShadow: '0 8px 24px rgba(0, 0, 0, 0.15)',
    zIndex: '10000',
    maxWidth: '400px',
    animation: 'slideIn 0.3s ease-out',
    fontWeight: '500'
  });

  document.body.appendChild(notification);

  setTimeout(() => {
    notification.style.animation = 'slideOut 0.3s ease-out';
    setTimeout(() => notification.remove(), 300);
  }, 5000);
}

// Плавная прокрутка
function initSmoothScroll() {
  // Прокрутка для навигационных ссылок
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const targetId = this.getAttribute('href');

      if (targetId === '#') return;

      const targetElement = document.querySelector(targetId);

      if (targetElement) {
        const headerHeight = document.getElementById('header').offsetHeight;
        const targetPosition = targetElement.offsetTop - headerHeight;

      window.scrollTo({
        top: targetPosition,
        behavior: 'smooth'
      });

        // Закрываем меню если оно открыто
        closeMenuDrawer();
      }
    });
  });

  // Особый обработчик для ссылки на главную
  const homeLink = document.querySelector('a[href="#home"]');
  if (homeLink) {
    homeLink.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });

      // Закрываем меню если оно открыто
      closeMenuDrawer();
    });
  }
}

// Slider for Services
function initServicesSlider() {
  const servicesGrid = document.querySelector('.services-grid');
  const serviceCards = document.querySelectorAll('.service-card');
  const prevBtn = document.querySelector('.services-slider-nav .slider-prev');
  const nextBtn = document.querySelector('.services-slider-nav .slider-next');
  const dotsContainer = document.querySelector('.services-slider-nav .slider-dots');

  if (!servicesGrid || serviceCards.length === 0) {
    return;
  }

  const isMobile = window.innerWidth <= 768;

  if (!isMobile) {
    servicesGrid.removeAttribute('data-slider-initialized');
    if (dotsContainer) dotsContainer.innerHTML = '';
    if (prevBtn) {
      const clone = prevBtn.cloneNode(true);
      prevBtn.parentNode.replaceChild(clone, prevBtn);
    }
    if (nextBtn) {
      const clone = nextBtn.cloneNode(true);
      nextBtn.parentNode.replaceChild(clone, nextBtn);
    }
    return;
  }

  if (servicesGrid.dataset.sliderInitialized === 'true') {
    return;
  }
  servicesGrid.dataset.sliderInitialized = 'true';

  let currentSlide = 0;
  const totalSlides = serviceCards.length;

  // Очищаем контейнер точек
  if (dotsContainer) {
    dotsContainer.innerHTML = '';
  }

  // Создаем точки навигации
  serviceCards.forEach((_, index) => {
    if (dotsContainer) {
      const dot = document.createElement('div');
      dot.className = 'slider-dot';
      if (index === 0) dot.classList.add('active');
      dot.addEventListener('click', () => goToSlide(index));
      dotsContainer.appendChild(dot);
    }
  });

  const dots = document.querySelectorAll('.services-slider-nav .slider-dot');

  function updateNavigation() {
    // Обновляем состояние кнопок
    if (prevBtn) prevBtn.disabled = currentSlide === 0;
    if (nextBtn) nextBtn.disabled = currentSlide === totalSlides - 1;

    // Обновляем точки
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentSlide);
    });
  }

  function goToSlide(slideIndex) {
    currentSlide = slideIndex;

    // Прокрутка к активной карточке
    if (servicesGrid.scrollTo) {
      const targetCard = serviceCards[currentSlide];
      const targetLeft = targetCard.offsetLeft - 10; // частично компенсируем margin
      servicesGrid.scrollTo({ left: targetLeft, behavior: 'instant' }); // используем instant для более плавной прокрутки

      // После прокрутки с небольшой задержкой обновляем навигацию
      setTimeout(() => {
        updateNavigation();
      }, 100);
    } else {
      // Альтернативный метод прокрутки
      servicesGrid.scrollLeft = targetCard.offsetLeft - 10;
      updateNavigation();
    }
  }

  // Кнопки навигации
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      if (currentSlide > 0) {
        goToSlide(currentSlide - 1);
      }
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      if (currentSlide < totalSlides - 1) {
        goToSlide(currentSlide + 1);
      }
    });
  }

  // Swipe для мобильных
  let startX = 0;
  let endX = 0;
  let isScrolling = false;

  servicesGrid.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isScrolling = false;
  });

  servicesGrid.addEventListener('touchmove', () => {
    isScrolling = true;
  });

  servicesGrid.addEventListener('touchend', (e) => {
    if (!isScrolling) return;

    endX = e.changedTouches[0].clientX;
    handleSwipe();
  });

  function handleSwipe() {
    const swipeThreshold = 50;
    const diff = startX - endX;

    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0 && currentSlide < totalSlides - 1) {
        // Swipe left - next
        goToSlide(currentSlide + 1);
      } else if (diff < 0 && currentSlide > 0) {
        // Swipe right - prev
        goToSlide(currentSlide - 1);
      }
    }
  }

  // Определяем текущий слайд при скролле
  servicesGrid.addEventListener('scroll', () => {
    // Добавляем небольшую задержку для предотвращения тряски
    clearTimeout(this.scrollTimer);
    this.scrollTimer = setTimeout(() => {
      const scrollLeft = servicesGrid.scrollLeft;
      const cardWidth = serviceCards[0].offsetWidth;
      const newSlide = Math.round(scrollLeft / cardWidth);

      if (newSlide !== currentSlide && newSlide >= 0 && newSlide < totalSlides) {
        currentSlide = newSlide;
        updateNavigation();
      }
    }, 50); // Задержка в 50мс для предотвращения тряски
  });

  // Инициализация
  goToSlide(0);
}

// Slider for Equipment
function initEquipmentSlider() {
  const equipmentGrid = document.querySelector('.equipment-grid');
  const equipmentCards = document.querySelectorAll('.equipment-card');
  const prevBtn = document.querySelector('.equipment-slider-nav .slider-prev');
  const nextBtn = document.querySelector('.equipment-slider-nav .slider-next');
  const dotsContainer = document.querySelector('.equipment-slider-nav .slider-dots');

  if (!equipmentGrid || equipmentCards.length === 0) {
    return;
  }

  const isMobile = window.innerWidth <= 768;

  if (!isMobile) {
    equipmentGrid.removeAttribute('data-slider-initialized');
    if (dotsContainer) dotsContainer.innerHTML = '';
    if (prevBtn) {
      const clone = prevBtn.cloneNode(true);
      prevBtn.parentNode.replaceChild(clone, prevBtn);
    }
    if (nextBtn) {
      const clone = nextBtn.cloneNode(true);
      nextBtn.parentNode.replaceChild(clone, nextBtn);
    }
    return;
  }

  if (equipmentGrid.dataset.sliderInitialized === 'true') {
    return;
  }
  equipmentGrid.dataset.sliderInitialized = 'true';

  let currentSlide = 0;
  const totalSlides = equipmentCards.length;

  // Очищаем контейнер точек
  if (dotsContainer) {
    dotsContainer.innerHTML = '';
  }

  // Создаем точки навигации
  equipmentCards.forEach((_, index) => {
    if (dotsContainer) {
      const dot = document.createElement('div');
      dot.className = 'slider-dot';
      if (index === 0) dot.classList.add('active');
      dot.addEventListener('click', () => goToSlide(index));
      dotsContainer.appendChild(dot);
    }
  });

  const dots = document.querySelectorAll('.equipment-slider-nav .slider-dot');

  function updateNavigation() {
    // Обновляем состояние кнопок
    if (prevBtn) prevBtn.disabled = currentSlide === 0;
    if (nextBtn) nextBtn.disabled = currentSlide === totalSlides - 1;

    // Обновляем точки
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === currentSlide);
    });
  }

  function goToSlide(slideIndex) {
    currentSlide = slideIndex;

    // Прокрутка к активной карточке
    if (equipmentGrid.scrollTo) {
      const targetCard = equipmentCards[currentSlide];
      const targetLeft = targetCard.offsetLeft - 10; // частично компенсируем margin
      equipmentGrid.scrollTo({ left: targetLeft, behavior: 'instant' }); // используем instant для более плавной прокрутки

      // После прокрутки с небольшой задержкой обновляем навигацию
      setTimeout(() => {
        updateNavigation();
      }, 100);
    } else {
      // Альтернативный метод прокрутки
      equipmentGrid.scrollLeft = targetCard.offsetLeft - 10;
      updateNavigation();
    }
  }

  // Кнопки навигации
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      if (currentSlide > 0) {
        goToSlide(currentSlide - 1);
      }
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      if (currentSlide < totalSlides - 1) {
        goToSlide(currentSlide + 1);
      }
    });
  }

  // Swipe для мобильных
  let startX = 0;
  let endX = 0;
  let isScrolling = false;

  equipmentGrid.addEventListener('touchstart', (e) => {
    startX = e.touches[0].clientX;
    isScrolling = false;
  });

  equipmentGrid.addEventListener('touchmove', () => {
    isScrolling = true;
  });

  equipmentGrid.addEventListener('touchend', (e) => {
    if (!isScrolling) return;

    endX = e.changedTouches[0].clientX;
    handleSwipe();
  });

  function handleSwipe() {
    const swipeThreshold = 50;
    const diff = startX - endX;

    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0 && currentSlide < totalSlides - 1) {
        // Swipe left - next
        goToSlide(currentSlide + 1);
      } else if (diff < 0 && currentSlide > 0) {
        // Swipe right - prev
        goToSlide(currentSlide - 1);
      }
    }
  }

  // Определяем текущий слайд при скролле
  equipmentGrid.addEventListener('scroll', () => {
    // Добавляем небольшую задержку для предотвращения тряски
    clearTimeout(this.scrollTimer);
    this.scrollTimer = setTimeout(() => {
      const scrollLeft = equipmentGrid.scrollLeft;
      const cardWidth = equipmentCards[0].offsetWidth;
      const newSlide = Math.round(scrollLeft / cardWidth);

      if (newSlide !== currentSlide && newSlide >= 0 && newSlide < totalSlides) {
        currentSlide = newSlide;
        updateNavigation();
      }
    }, 50); // Задержка в 50мс для предотвращения тряски
  });

  // Инициализация
  goToSlide(0);
}

// Slider for gratitude letters
function initGratitudeSlider() {
  const slider = document.querySelector('.gratitude-slider');
  const track = slider ? slider.querySelector('.gratitude-track') : null;
  const cards = track ? Array.from(track.children) : [];
  const prevBtn = document.querySelector('.gratitude-prev');
  const nextBtn = document.querySelector('.gratitude-next');

  if (!slider || !track || cards.length === 0) {
    if (prevBtn) prevBtn.disabled = true;
    if (nextBtn) nextBtn.disabled = true;
    return;
  }

  let currentIndex = 0;
  let slidesPerView = getSlidesPerView();

  const getGap = () => {
    const styles = window.getComputedStyle(track);
    const rawGap = styles.columnGap || styles.gap || '0';
    const parsedGap = parseFloat(rawGap);
    return Number.isNaN(parsedGap) ? 0 : parsedGap;
  };

  function getSlidesPerView() {
    if (window.innerWidth >= 1400) return 4;
    if (window.innerWidth >= 1024) return 3;
    if (window.innerWidth >= 640) return 2;
    return 1;
  }

  function setCardWidth() {
    const sliderWidth = slider.clientWidth;
    const gap = getGap();
    const width = (sliderWidth - gap * (slidesPerView - 1)) / slidesPerView;
    slider.style.setProperty('--gratitude-card-width', `${width}px`);
  }

  function getCardWidth() {
    const raw = parseFloat(getComputedStyle(slider).getPropertyValue('--gratitude-card-width'));
    if (Number.isNaN(raw) || raw <= 0) {
      return cards[0].offsetWidth || slider.clientWidth;
    }
    return raw;
  }

  function getMaxIndex() {
    return Math.max(0, cards.length - slidesPerView);
  }

  function updateButtons() {
    if (prevBtn) prevBtn.disabled = currentIndex === 0;
    if (nextBtn) nextBtn.disabled = currentIndex >= getMaxIndex();
  }

  function updatePosition() {
    const offset = currentIndex * (getCardWidth() + getGap());
    track.style.transform = `translateX(-${offset}px)`;
  }

  function goTo(index) {
    const maxIndex = getMaxIndex();
    currentIndex = Math.max(0, Math.min(index, maxIndex));
    updateButtons();
    updatePosition();
  }

  setCardWidth();
  goTo(0);

  if (prevBtn) {
    prevBtn.addEventListener('click', () => goTo(currentIndex - 1));
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => goTo(currentIndex + 1));
  }

  let resizeTimeout;
  window.addEventListener('resize', () => {
    if (!slider.isConnected) return;
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      const updatedSlides = getSlidesPerView();
      if (updatedSlides !== slidesPerView) {
        slidesPerView = updatedSlides;
      }
      setCardWidth();
      goTo(currentIndex);
    }, 150);
  });
}

// Modal for gratitude cards
function initGratitudeModal() {
  const modal = document.getElementById('gratitudeModal');
  const cards = document.querySelectorAll('.gratitude-card.has-image');

  if (!modal || cards.length === 0) {
    return;
  }

  const modalImage = modal.querySelector('.gratitude-modal-image img');
  const modalTitle = modal.querySelector('.gratitude-modal-title');
  const closeButtons = modal.querySelectorAll('[data-close-gratitude]');
  const modalClose = modal.querySelector('.gratitude-modal-close');
  let previouslyFocused = null;

  const openModal = (card) => {
    const image = card.dataset.image;
    if (!image || !modalImage) return;

    previouslyFocused = document.activeElement;
    modalImage.src = image;
    modalImage.alt = card.dataset.title || 'Благодарственное письмо';
    if (modalTitle) {
      modalTitle.textContent = card.dataset.title || '';
    }

    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    if (modalClose) {
      modalClose.focus();
    }
  };

  const closeModal = () => {
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
    if (modalImage) {
      modalImage.src = '';
    }
    document.body.style.overflow = '';
    if (previouslyFocused) {
      previouslyFocused.focus();
    }
  };

  cards.forEach((card) => {
    card.addEventListener('click', () => openModal(card));
    card.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        openModal(card);
      }
    });
  });

  closeButtons.forEach((btn) => {
    btn.addEventListener('click', closeModal);
  });

  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('active')) {
      closeModal();
    }
  });
}

// Обработчик ресайза для переинициализации слайдеров
window.addEventListener('resize', () => {
  initEquipmentSlider();
  initServicesSlider();
});
// Popup Form Functionality
function initPopupForm() {
  const orderButtons = document.querySelectorAll('.order-btn');
  const popupOverlay = document.getElementById('popupOverlay');
  const popupClose = document.getElementById('popupClose');
  const orderServiceInput = document.getElementById('orderService');

  if (!popupOverlay || !orderServiceInput) return;

  orderButtons.forEach(button => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      const serviceName = button.getAttribute('data-service') || button.textContent.trim();
      orderServiceInput.value = serviceName;
      popupOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    });
  });

  if (popupClose) popupClose.addEventListener('click', closePopup);

  popupOverlay.addEventListener('click', (e) => {
    if (e.target === popupOverlay) closePopup();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && popupOverlay.classList.contains('active')) {
      closePopup();
    }
  });
}

function closePopup() {
  const popupOverlay = document.getElementById('popupOverlay');
  if (popupOverlay) {
    popupOverlay.classList.remove('active');
    document.body.style.overflow = '';
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
      orderForm.reset();
      const inputs = orderForm.querySelectorAll('input, textarea');
      inputs.forEach(input => clearError(input));
    }
  }
}

// Слайдер для оборудования
function initEquipmentSlider() {
  const equipmentSection = document.querySelector('.equipment-section');
  const equipmentGrid = document.querySelector('.equipment-grid');
  const dotsContainer = document.querySelector('.equipment-slider-nav .slider-dots');

  if (!equipmentGrid) {
    return;
  }

  const equipmentCards = equipmentGrid.querySelectorAll('.equipment-card');
  if (equipmentCards.length === 0) return;

  const resetButton = (selector) => {
    const btn = document.querySelector(selector);
    if (btn) {
      const clone = btn.cloneNode(true);
      btn.parentNode.replaceChild(clone, btn);
    }
  };

  const isMobile = window.innerWidth <= 768;

  if (!isMobile) {
    if (equipmentGrid.dataset.sliderInitialized === 'true') {
      if (equipmentGrid._touchStartHandler) {
        equipmentGrid.removeEventListener('touchstart', equipmentGrid._touchStartHandler);
        equipmentGrid._touchStartHandler = null;
      }
      if (equipmentGrid._touchEndHandler) {
        equipmentGrid.removeEventListener('touchend', equipmentGrid._touchEndHandler);
        equipmentGrid._touchEndHandler = null;
      }
      if (equipmentGrid._scrollHandler) {
        equipmentGrid.removeEventListener('scroll', equipmentGrid._scrollHandler);
        equipmentGrid._scrollHandler = null;
      }
      if (equipmentGrid._resizeObserver) {
        equipmentGrid._resizeObserver.disconnect();
        equipmentGrid._resizeObserver = null;
      }
    }

    equipmentGrid.removeAttribute('data-slider-initialized');
    equipmentSection?.classList.remove('slider-enabled');
    if (dotsContainer) dotsContainer.innerHTML = '';
    resetButton('.equipment-slider-nav .slider-prev');
    resetButton('.equipment-slider-nav .slider-next');
    return;
  }

  if (equipmentGrid.dataset.sliderInitialized === 'true') return;

  equipmentGrid.dataset.sliderInitialized = 'true';
  equipmentSection?.classList.add('slider-enabled');

  const prevBtn = document.querySelector('.equipment-slider-nav .slider-prev');
  const nextBtn = document.querySelector('.equipment-slider-nav .slider-next');

  let currentSlide = 0;
  const totalSlides = equipmentCards.length;

  if (dotsContainer) {
    dotsContainer.innerHTML = '';
    equipmentCards.forEach((_, index) => {
      const dot = document.createElement('div');
      dot.className = 'slider-dot';
      if (index === 0) dot.classList.add('active');
      dot.addEventListener('click', () => goToSlide(index));
      dotsContainer.appendChild(dot);
    });
  }

  const dots = dotsContainer ? dotsContainer.querySelectorAll('.slider-dot') : [];

  function updateNavigation() {
    if (prevBtn) prevBtn.disabled = currentSlide === 0;
    if (nextBtn) nextBtn.disabled = currentSlide === totalSlides - 1;
    dots.forEach((dot, index) => dot.classList.toggle('active', index === currentSlide));
  }

  function goToSlide(slideIndex) {
    currentSlide = Math.max(0, Math.min(slideIndex, totalSlides - 1));
    const targetCard = equipmentCards[currentSlide];
    equipmentGrid.scrollTo({
      left: targetCard.offsetLeft,
      behavior: 'smooth'
    });
    updateNavigation();
  }

  // Кнопки навигации
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      if (currentSlide > 0) {
        goToSlide(currentSlide - 1);
      }
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      if (currentSlide < totalSlides - 1) {
        goToSlide(currentSlide + 1);
      }
    });
  }

  // Swipe для мобильных
  let startX = 0;
  let endX = 0;

  const handleTouchStart = (e) => {
    startX = e.touches[0].clientX;
  };

  const handleTouchEnd = (e) => {
    endX = e.changedTouches[0].clientX;
    handleSwipe();
  };

  function handleSwipe() {
    const swipeThreshold = 50;
    const diff = startX - endX;

    if (Math.abs(diff) > swipeThreshold) {
      if (diff > 0 && currentSlide < totalSlides - 1) {
        // Swipe left - next
        goToSlide(currentSlide + 1);
      } else if (diff < 0 && currentSlide > 0) {
        // Swipe right - prev
        goToSlide(currentSlide - 1);
      }
    }
  }

  const handleScroll = () => {
    const scrollLeft = equipmentGrid.scrollLeft;
    let closestIndex = currentSlide;
    let minDiff = Math.abs(equipmentCards[currentSlide].offsetLeft - scrollLeft);

    equipmentCards.forEach((card, index) => {
      const diff = Math.abs(card.offsetLeft - scrollLeft);
      if (diff < minDiff) {
        minDiff = diff;
        closestIndex = index;
      }
    });

    if (closestIndex !== currentSlide) {
      currentSlide = closestIndex;
      updateNavigation();
    }
  };

  equipmentGrid.addEventListener('touchstart', handleTouchStart, { passive: true });
  equipmentGrid.addEventListener('touchend', handleTouchEnd, { passive: true });
  equipmentGrid.addEventListener('scroll', handleScroll, { passive: true });

  equipmentGrid._touchStartHandler = handleTouchStart;
  equipmentGrid._touchEndHandler = handleTouchEnd;
  equipmentGrid._scrollHandler = handleScroll;

  const resizeObserver = new ResizeObserver(() => {
    if (window.innerWidth <= 768) {
      goToSlide(currentSlide);
    }
  });
  resizeObserver.observe(equipmentGrid);
  equipmentGrid._resizeObserver = resizeObserver;

  goToSlide(0);
}

function initExperienceModal() {
  const cardSelector = '.experience-card, .all-case-card';
  const cards = document.querySelectorAll(cardSelector);
  const modal = document.getElementById('experienceModal');
  if (!cards.length || !modal) return;

  const modalTitle = modal.querySelector('.experience-modal-title');
  const modalImage = modal.querySelector('.experience-modal-image');
  const modalMeta = modal.querySelector('.experience-modal-meta');
  const modalClose = document.getElementById('experienceModalClose');
  const discussButton = modal.querySelector('.experience-modal-cta');
  const casesModal = document.getElementById('casesModal');

  const openModal = (card) => {
    let title = (card.querySelector('h3') || card.querySelector('h4'))?.innerHTML.trim() || 'Проект БИС — Баланс Инженерных Систем';

    // Ensure BIZ is styled in description if it comes from data attribute as plain text
    title = title.replace(/БИС/g, 'БИС');

    // Clean up nested spans if any
    title = title.replace(/<span class="bis-condensed"><span class="bis-condensed">БИС<\/span><\/span>/g, 'БИС');

    const image = card.dataset.image || '';
    const address = card.dataset.address || '';
    const area = card.dataset.area || '';
    const year = card.dataset.year || '';
    const featured = card.dataset.featured === '1';

    modalTitle.innerHTML = title;
    modalMeta.innerHTML = '';

    const metaList = document.createElement('ul');
    metaList.className = 'experience-modal-meta__list';

    const addMetaRow = (label, value) => {
      if (!value) return;
      const row = document.createElement('li');
      const labelEl = document.createElement('span');
      labelEl.textContent = label;
      const valueEl = document.createElement('strong');
      valueEl.textContent = value;
      row.appendChild(labelEl);
      row.appendChild(valueEl);
      metaList.appendChild(row);
    };

    addMetaRow('Адрес', address);
    addMetaRow('Площадь', area ? `${area} м²` : '');
    addMetaRow('Год', year);

    if (featured) {
      const badge = document.createElement('div');
      badge.className = 'experience-modal-meta__badge';
      badge.textContent = 'Ключевой проект';
      modalMeta.appendChild(badge);
    }

    if (metaList.childElementCount) {
      modalMeta.appendChild(metaList);
    } else {
      const placeholder = document.createElement('p');
      placeholder.className = 'experience-modal-meta__placeholder';
      placeholder.textContent = 'Детали проекта уточняются';
      modalMeta.appendChild(placeholder);
    }

    if (image) {
      modalImage.style.backgroundImage = `url('${image}')`;
    } else {
      modalImage.style.backgroundImage = '';
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  const closeModal = () => {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  };

  cards.forEach(card => {
    card.addEventListener('click', () => openModal(card));
  });

  document.querySelectorAll('.experience-more, .case-more').forEach(button => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      const parentCard = button.closest(cardSelector);
      if (parentCard) {
        openModal(parentCard);
      }
    });
  });

  if (modalClose) {
    modalClose.addEventListener('click', closeModal);
  }

  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('active')) {
      closeModal();
    }
  });

  if (discussButton) {
    discussButton.addEventListener('click', (event) => {
      event.preventDefault();
      closeModal();

      if (casesModal && casesModal.classList.contains('active')) {
        casesModal.classList.remove('active');
      }

      document.body.style.overflow = '';

      const contactSection = document.getElementById('contact');
      if (contactSection) {
        contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        const nameInput = contactSection.querySelector('#contactForm input[name="name"]');
        if (nameInput) {
          try {
            nameInput.focus({ preventScroll: true });
          } catch (error) {
            nameInput.focus();
          }
        }
      }
    });
  }
}

// Modal for Cases
function initCasesModal() {
  const showAllBtn = document.querySelector('.show-all-cases');
  const casesModal = document.getElementById('casesModal');
  const modalClose = document.getElementById('modalClose');

  if (!showAllBtn || !casesModal) return;

  showAllBtn.addEventListener('click', () => {
    casesModal.classList.add('active');
    document.body.style.overflow = 'hidden'; // Блокируем скролл страницы
  });

  modalClose.addEventListener('click', () => {
    closeCasesModal();
  });

  casesModal.addEventListener('click', (e) => {
    if (e.target === casesModal) {
      closeCasesModal();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && casesModal.classList.contains('active')) {
      closeCasesModal();
    }
  });

  function closeCasesModal() {
    casesModal.classList.remove('active');
    document.body.style.overflow = ''; // Восстанавливаем скролл
  }
};


// FAQ Functionality
function initFAQ() {
  const faqItems = document.querySelectorAll('.faq-item');

  faqItems.forEach(item => {
    const question = item.querySelector('.faq-question');

    question.addEventListener('click', () => {
      // Закрываем все остальные элементы
      faqItems.forEach(otherItem => {
        if (otherItem !== item && otherItem.classList.contains('active')) {
          otherItem.classList.remove('active');
        }
      });

      // Переключаем текущий элемент
      item.classList.toggle('active');
    });
  });
}



// Добавление CSS анимаций через JavaScript
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from {
      transform: translateX(400px);
      opacity: 0;
    }
    to {
      transform: translateX(0);
      opacity: 1;
    }
  }

  @keyframes slideOut {
    from {
      transform: translateX(0);
      opacity: 1;
    }
    to {
      transform: translateX(400px);
      opacity: 0;
    }
  }
`;
document.head.appendChild(style);

// Estimate Modal Functionality
function initEstimateModal() {
  const estimateBtns = document.querySelectorAll('.open-estimate-modal');
  const estimateOverlay = document.getElementById('estimateOverlay');
  const estimateClose = document.getElementById('estimateClose');
  const estimateForm = document.getElementById('estimateForm');
  const estimatePhone = document.getElementById('estimatePhone');
  const ANIMATION_DURATION = 450;
  let closeTimeout;

  if (!estimateOverlay) return;

  estimateBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      openEstimateModal();
    });
  });

  if (estimateClose) {
    estimateClose.addEventListener('click', (event) => {
      event.preventDefault();
      closeEstimateModal();
    });
  }

  estimateOverlay.addEventListener('click', (e) => {
    if (e.target === estimateOverlay || e.target.closest('#estimateClose')) {
      closeEstimateModal();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && estimateOverlay.classList.contains('active')) {
      closeEstimateModal();
    }
  });

  if (estimatePhone) {
    const formatPhone = (value) => {
      let digits = value.replace(/\D/g, '');
      if (digits.startsWith('7') || digits.startsWith('8')) {
        digits = digits.slice(1);
      }
      digits = digits.substring(0, 10);

      const parts = {
        area: digits.substring(0, 3),
        central: digits.substring(3, 6),
        line1: digits.substring(6, 8),
        line2: digits.substring(8, 10)
      };

      let formatted = '+7';
      if (parts.area) {
        formatted += ` (${parts.area}`;
        if (parts.area.length === 3) {
          formatted += ')';
        }
      }

      if (parts.central) {
        formatted += ` ${parts.central}`;
      }

      if (parts.line1) {
        formatted += `-${parts.line1}`;
      }

      if (parts.line2) {
        formatted += `-${parts.line2}`;
      }

      if (!parts.area) {
        formatted += ' ';
      }

      return formatted.trimEnd();
    };

    const handlePhoneInput = (event) => {
      event.target.value = formatPhone(event.target.value);
    };

    estimatePhone.addEventListener('focus', () => {
      if (!estimatePhone.value.trim()) {
        estimatePhone.value = '+7 ';
      }
    });

    estimatePhone.addEventListener('input', handlePhoneInput);

    estimatePhone.addEventListener('blur', () => {
      const digits = estimatePhone.value.replace(/\D/g, '');
      if (digits.length <= 1) {
        estimatePhone.value = '';
      }
    });
  }

  if (estimateForm) {
    estimateForm.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = new FormData(estimateForm);
      formData.append('action', 'bis_submit_estimate');

      const submitBtn = estimateForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;

      submitBtn.disabled = true;
      submitBtn.textContent = 'Отправка...';

      fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            submitBtn.textContent = '✓ Отправлено!';
            submitBtn.style.background = '#10b981';

            setTimeout(() => {
              closeEstimateModal();
              estimateForm.reset();
              submitBtn.disabled = false;
              submitBtn.textContent = originalText;
              submitBtn.style.background = '';
              showNotification('Спасибо! Мы свяжемся с вами в течение 2 дней.', 'success');
            }, 1500);
          } else {
            showNotification('Ошибка отправки. Попробуйте позже.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('Ошибка отправки. Попробуйте позже.', 'error');
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        });
    });
  }

  function openEstimateModal() {
    clearTimeout(closeTimeout);
    estimateOverlay.classList.remove('closing');
    estimateOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeEstimateModal() {
    if (!estimateOverlay.classList.contains('active')) return;
    estimateOverlay.classList.add('closing');
    clearTimeout(closeTimeout);
    closeTimeout = setTimeout(() => {
      estimateOverlay.classList.remove('active', 'closing');
      document.body.style.overflow = '';
    }, ANIMATION_DURATION);
  }
}
