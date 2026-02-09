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
  initTeamSlider();
  initTeamModal();
  initServicesSlider();
  initEstimateModal();
  initRevenueChart();
  initProjectGallery();
  initProjectConsultationForm();
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
    { text: 'Систем', isGradient: false }
  ];

  // Установим ширину контейнера до начала анимации, чтобы избежать смещения
  const fullText = 'БИС — ' + textParts.map(part => part.text).join('');
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

  const svg = chart.querySelector('.revenue-svg');
  const linePath = svg?.querySelector('.revenue-line');
  const areaPath = svg?.querySelector('.revenue-area');
  const pointsGroup = svg?.querySelector('.revenue-points');
  const labelsContainer = chart.querySelector('[data-revenue-labels]');
  const axisContainer = chart.querySelector('[data-revenue-axis]');
  const gridContainer = chart.querySelector('[data-revenue-grid]');
  const xAxisContainer = chart.querySelector('[data-revenue-xaxis]');

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

  if (!svg || !linePath || !areaPath || !pointsGroup) return;

  const cleanPoints = points
    .map(point => ({
      label: point.label || '',
      value: parseFloat(point.value) || 0,
    }))
    .filter(point => point.label !== '');

  const width = 100;
  const height = 60;
  const paddingTop = 6;
  const paddingBottom = 6;
  const rawMax = cleanPoints.length ? Math.max(...cleanPoints.map(p => p.value), 0) : 0;

  const getNiceStep = (max) => {
    if (max <= 0) {
      return { step: 10, max: 60 };
    }
    const roughStep = max / 6;
    const pow = Math.pow(10, Math.floor(Math.log10(roughStep)));
    const fraction = roughStep / pow;
    let niceFraction = 1;
    if (fraction <= 1) {
      niceFraction = 1;
    } else if (fraction <= 2) {
      niceFraction = 2;
    } else if (fraction <= 5) {
      niceFraction = 5;
    } else {
      niceFraction = 10;
    }
    const step = niceFraction * pow;
    const niceMax = Math.ceil(max / step) * step;
    return { step, max: niceMax };
  };

  const nice = getNiceStep(rawMax || 60);
  const maxValue = nice.max;
  const axisStep = nice.step;

  const DECIMAL_PRECISION = 2;
  const roundValue = (value, decimals = DECIMAL_PRECISION) => {
    const factor = Math.pow(10, decimals);
    return Math.round((value + Number.EPSILON) * factor) / factor;
  };

  const formatValue = (value, decimals = DECIMAL_PRECISION) => {
    const safeValue = Number.isFinite(value) ? value : 0;
    const rounded = roundValue(safeValue, decimals);
    let text = rounded.toFixed(decimals);
    text = text.replace(/\.?0+$/, '');
    return text.replace('.', ',');
  };

  const denom = cleanPoints.length > 1 ? (cleanPoints.length - 1) : 1;
  const coords = cleanPoints.map((point, index) => {
    const x = (index / denom) * width;
    const y = height - paddingBottom - (point.value / maxValue) * (height - paddingTop - paddingBottom);
    return { x, y, value: point.value, label: point.label };
  });

  if (axisContainer) {
    axisContainer.innerHTML = '';
    const totalSteps = Math.floor(maxValue / axisStep);
    for (let i = 0; i <= totalSteps; i++) {
      const value = axisStep * i;
      const y = height - paddingBottom - (value / maxValue) * (height - paddingTop - paddingBottom);
      const yPercent = (y / height) * 100;
      const label = document.createElement('div');
      label.className = 'revenue-axis-label';
      label.textContent = formatValue(value);
      label.style.top = `${yPercent}%`;
      axisContainer.appendChild(label);
    }
  }

  if (gridContainer) {
    gridContainer.innerHTML = '';
    const totalSteps = Math.floor(maxValue / axisStep);
    for (let i = 0; i <= totalSteps; i++) {
      const value = axisStep * i;
      const y = height - paddingBottom - (value / maxValue) * (height - paddingTop - paddingBottom);
      const yPercent = (y / height) * 100;
      const line = document.createElement('div');
      line.className = 'revenue-grid-line';
      line.style.top = `${yPercent}%`;
      gridContainer.appendChild(line);
    }
  }

  if (labelsContainer) {
    labelsContainer.innerHTML = '';
    coords.forEach((coord) => {
      const label = document.createElement('div');
      label.className = 'revenue-label';
      label.textContent = formatValue(coord.value);
      const xPercent = (coord.x / width) * 100;
      const yPercent = (coord.y / height) * 100;
      const clampedX = Math.min(96, Math.max(4, xPercent));
      const clampedY = Math.min(96, Math.max(6, yPercent));
      label.style.left = `${clampedX}%`;
      label.style.top = `${clampedY}%`;
      labelsContainer.appendChild(label);
    });
  }

  if (xAxisContainer) {
    xAxisContainer.innerHTML = '';
    coords.forEach((coord) => {
      const label = document.createElement('div');
      label.className = 'revenue-xlabel';
      label.textContent = coord.label;
      const xPercent = (coord.x / width) * 100;
      const clampedX = Math.min(96, Math.max(4, xPercent));
      label.style.left = `${clampedX}%`;
      xAxisContainer.appendChild(label);
    });
  }

  pointsGroup.innerHTML = '';

  if (!cleanPoints.length) {
    linePath.setAttribute('d', '');
    areaPath.setAttribute('d', '');
    if (labelsContainer) labelsContainer.innerHTML = '';
    if (xAxisContainer) xAxisContainer.innerHTML = '';
    return;
  }

  coords.forEach((coord) => {
    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    circle.setAttribute('class', 'revenue-dot');
    circle.setAttribute('cx', coord.x.toFixed(2));
    circle.setAttribute('cy', coord.y.toFixed(2));
    circle.setAttribute('r', '0.9');
    pointsGroup.appendChild(circle);
  });

  if (cleanPoints.length < 2) {
    const point = coords[0];
    const lineD = `M ${point.x.toFixed(2)} ${point.y.toFixed(2)}`;
    linePath.setAttribute('d', lineD);
    areaPath.setAttribute('d', '');
    return;
  }

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
  pointsGroup.innerHTML = '';
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
  if (!servicesGrid) return;

  const serviceCards = servicesGrid.querySelectorAll('.service-card');
  const prevBtn = document.querySelector('.services-slider-nav .slider-prev');
  const nextBtn = document.querySelector('.services-slider-nav .slider-next');
  const dotsContainer = document.querySelector('.services-slider-nav .slider-dots');

  if (serviceCards.length === 0) return;

  const resetButton = (selector) => {
    const btn = document.querySelector(selector);
    if (btn) {
      const clone = btn.cloneNode(true);
      btn.parentNode.replaceChild(clone, btn);
    }
  };

  const isMobile = window.innerWidth <= 768;

  if (!isMobile) {
    if (servicesGrid.dataset.sliderInitialized === 'true') {
      if (servicesGrid._touchStartHandler) {
        servicesGrid.removeEventListener('touchstart', servicesGrid._touchStartHandler);
        servicesGrid._touchStartHandler = null;
      }
      if (servicesGrid._touchEndHandler) {
        servicesGrid.removeEventListener('touchend', servicesGrid._touchEndHandler);
        servicesGrid._touchEndHandler = null;
      }
      if (servicesGrid._scrollHandler) {
        servicesGrid.removeEventListener('scroll', servicesGrid._scrollHandler);
        servicesGrid._scrollHandler = null;
      }
      if (servicesGrid._resizeObserver) {
        servicesGrid._resizeObserver.disconnect();
        servicesGrid._resizeObserver = null;
      }
      if (servicesGrid._scrollEndTimer) {
        clearTimeout(servicesGrid._scrollEndTimer);
        servicesGrid._scrollEndTimer = null;
      }
    }

    servicesGrid.removeAttribute('data-slider-initialized');
    if (dotsContainer) dotsContainer.innerHTML = '';
    resetButton('.services-slider-nav .slider-prev');
    resetButton('.services-slider-nav .slider-next');
    return;
  }

  if (servicesGrid.dataset.sliderInitialized === 'true') return;

  servicesGrid.dataset.sliderInitialized = 'true';

  let currentSlide = 0;
  const totalSlides = serviceCards.length;

  if (dotsContainer) {
    dotsContainer.innerHTML = '';
    serviceCards.forEach((_, index) => {
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

  function getTargetLeft(index) {
    const targetCard = serviceCards[index];
    const maxScrollLeft = servicesGrid.scrollWidth - servicesGrid.clientWidth;
    const offset = targetCard.offsetLeft - (servicesGrid.clientWidth - targetCard.offsetWidth) / 2;
    return Math.max(0, Math.min(Math.round(offset), maxScrollLeft));
  }

  function goToSlide(slideIndex, behavior = 'smooth') {
    currentSlide = Math.max(0, Math.min(slideIndex, totalSlides - 1));
    if (servicesGrid.scrollTo) {
      servicesGrid.scrollTo({ left: getTargetLeft(currentSlide), behavior });
    } else {
      servicesGrid.scrollLeft = getTargetLeft(currentSlide);
    }
    updateNavigation();
  }

  const goPrev = () => {
    if (currentSlide > 0) {
      goToSlide(currentSlide - 1);
    }
  };

  const goNext = () => {
    if (currentSlide < totalSlides - 1) {
      goToSlide(currentSlide + 1);
    }
  };

  if (prevBtn) prevBtn.addEventListener('click', goPrev);
  if (nextBtn) nextBtn.addEventListener('click', goNext);

  const getClosestSlideIndex = () => {
    const center = servicesGrid.scrollLeft + servicesGrid.clientWidth / 2;
    let closestIndex = 0;
    let minDiff = Number.POSITIVE_INFINITY;

    serviceCards.forEach((card, index) => {
      const cardCenter = card.offsetLeft + card.offsetWidth / 2;
      const diff = Math.abs(cardCenter - center);
      if (diff < minDiff) {
        minDiff = diff;
        closestIndex = index;
      }
    });

    return closestIndex;
  };

  const handleScroll = () => {
    servicesGrid._scrollEndTimer = setTimeout(() => {
      const newIndex = getClosestSlideIndex();
      if (newIndex !== currentSlide) {
        currentSlide = newIndex;
        updateNavigation();
      }
    }, 120);
  };

  servicesGrid.addEventListener('scroll', handleScroll, { passive: true });

  servicesGrid._scrollHandler = handleScroll;

  if (window.ResizeObserver) {
    const resizeObserver = new ResizeObserver(() => {
      if (window.innerWidth <= 768) {
        goToSlide(currentSlide, 'auto');
      }
    });
    resizeObserver.observe(servicesGrid);
    servicesGrid._resizeObserver = resizeObserver;
  }

  goToSlide(0, 'auto');
}

// Slider for gratitude letters
function initGratitudeSlider() {
  const galleries = document.querySelectorAll('[data-gratitude-gallery]');
  if (!galleries.length) return;

  galleries.forEach((gallery) => {
    const track = gallery.querySelector('[data-gratitude-track]');
    const slides = Array.from(gallery.querySelectorAll('[data-gratitude-slide]'));
    const prevBtn = gallery.querySelector('[data-gratitude-prev]');
    const nextBtn = gallery.querySelector('[data-gratitude-next]');
    const dotsContainer = gallery.parentElement ? gallery.parentElement.querySelector('[data-gratitude-dots]') : null;

    if (!track || slides.length === 0) {
      if (prevBtn) prevBtn.disabled = true;
      if (nextBtn) nextBtn.disabled = true;
      return;
    }

    let activeIndex = 0;
    let slideStep = 0;

    const getGap = () => {
      const styles = window.getComputedStyle(track);
      const rawGap = styles.columnGap || styles.gap || '0';
      const parsedGap = parseFloat(rawGap);
      return Number.isNaN(parsedGap) ? 0 : parsedGap;
    };

    const computeSlideStep = () => {
      const first = slides[0];
      if (!first) return 0;
      const rect = first.getBoundingClientRect();
      return rect.width + getGap();
    };

    const normalizeIndex = (index) => {
      const total = slides.length;
      if (!total) return 0;
      return ((index % total) + total) % total;
    };

    const updateDots = () => {
      if (!dotsContainer) return;
      const dots = dotsContainer.querySelectorAll('.slider-dot');
      dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === activeIndex);
      });
    };

    const scrollToIndex = (index) => {
      if (!slideStep || Number.isNaN(slideStep)) {
        slideStep = computeSlideStep();
      }
      if (!slideStep || Number.isNaN(slideStep)) {
        return;
      }
      activeIndex = normalizeIndex(index);
      track.scrollTo({
        left: activeIndex * slideStep,
        behavior: 'smooth'
      });
      updateDots();
    };

    if (prevBtn) {
      prevBtn.addEventListener('click', () => scrollToIndex(activeIndex - 1));
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => scrollToIndex(activeIndex + 1));
    }

    if (dotsContainer) {
      dotsContainer.innerHTML = '';
      slides.forEach((_, index) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'slider-dot';
        dot.addEventListener('click', () => scrollToIndex(index));
        dotsContainer.appendChild(dot);
      });
    }

    track.addEventListener('scroll', () => {
      if (!slideStep || Number.isNaN(slideStep)) {
        slideStep = computeSlideStep();
      }
      if (!slideStep || Number.isNaN(slideStep)) {
        return;
      }
      const index = Math.round(track.scrollLeft / slideStep);
      if (index !== activeIndex) {
        activeIndex = normalizeIndex(index);
        updateDots();
      }
    });

    window.addEventListener('resize', () => {
      if (!track.isConnected) return;
      slideStep = computeSlideStep();
      scrollToIndex(activeIndex);
    });

    slideStep = computeSlideStep();
    updateDots();
  });
}

// Modal for gratitude cards
function initGratitudeModal() {
  const modal = document.getElementById('gratitudeModal');
  const cards = Array.from(document.querySelectorAll('.gratitude-card.has-image'));

  if (!modal || cards.length === 0) {
    return;
  }

  const modalImage = modal.querySelector('[data-gratitude-lightbox-image]');
  const modalImageWrap = modal.querySelector('.gratitude-modal-image');
  const modalCaption = modal.querySelector('[data-gratitude-lightbox-caption]');
  const prevButton = modal.querySelector('[data-gratitude-lightbox-prev]');
  const nextButton = modal.querySelector('[data-gratitude-lightbox-next]');
  const closeButtons = modal.querySelectorAll('[data-close-gratitude]');
  const modalClose = modal.querySelector('.gratitude-modal-close');
  let previouslyFocused = null;
  let currentIndex = 0;

  const normalizeIndex = (index) => {
    const total = cards.length;
    if (!total) return 0;
    return ((index % total) + total) % total;
  };

  const updateModal = () => {
    const card = cards[currentIndex];
    const image = card ? card.dataset.image : '';
    if (modalImage) {
      modalImage.src = image || '';
      modalImage.alt = card?.dataset.title || 'Благодарственное письмо';
    }
    if (modalCaption) {
      modalCaption.textContent = `${currentIndex + 1} / ${cards.length}`;
    }
  };

  const openModal = (card) => {
    const index = cards.indexOf(card);
    if (index === -1 || !modalImage) return;

    previouslyFocused = document.activeElement;
    currentIndex = normalizeIndex(index);
    updateModal();

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

  if (prevButton) {
    prevButton.addEventListener('click', () => {
      currentIndex = normalizeIndex(currentIndex - 1);
      updateModal();
    });
  }

  if (nextButton) {
    nextButton.addEventListener('click', () => {
      currentIndex = normalizeIndex(currentIndex + 1);
      updateModal();
    });
  }

  closeButtons.forEach((btn) => {
    btn.addEventListener('click', closeModal);
  });

  modal.addEventListener('click', (event) => {
    if (event.target === modal) {
      closeModal();
    }
  });

  if (modalImageWrap) {
    modalImageWrap.addEventListener('click', (event) => {
      if (event.target === modalImageWrap) {
        closeModal();
      }
    });
  }

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
      if (equipmentGrid._scrollEndTimer) {
        clearTimeout(equipmentGrid._scrollEndTimer);
        equipmentGrid._scrollEndTimer = null;
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

  function getTargetLeft(index) {
    const targetCard = equipmentCards[index];
    const maxScrollLeft = equipmentGrid.scrollWidth - equipmentGrid.clientWidth;
    const offset = targetCard.offsetLeft - (equipmentGrid.clientWidth - targetCard.offsetWidth) / 2;
    return Math.max(0, Math.min(Math.round(offset), maxScrollLeft));
  }

  function goToSlide(slideIndex, behavior = 'smooth') {
    currentSlide = Math.max(0, Math.min(slideIndex, totalSlides - 1));
    if (equipmentGrid.scrollTo) {
      equipmentGrid.scrollTo({
        left: getTargetLeft(currentSlide),
        behavior
      });
    } else {
      equipmentGrid.scrollLeft = getTargetLeft(currentSlide);
    }
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

  const handleScroll = () => {
    equipmentGrid._scrollEndTimer = setTimeout(() => {
      const center = equipmentGrid.scrollLeft + equipmentGrid.clientWidth / 2;
      let closestIndex = 0;
      let minDiff = Number.POSITIVE_INFINITY;

      equipmentCards.forEach((card, index) => {
        const cardCenter = card.offsetLeft + card.offsetWidth / 2;
        const diff = Math.abs(cardCenter - center);
        if (diff < minDiff) {
          minDiff = diff;
          closestIndex = index;
        }
      });

      if (closestIndex !== currentSlide) {
        currentSlide = closestIndex;
        updateNavigation();
      }
    }, 120);
  };

  equipmentGrid.addEventListener('scroll', handleScroll, { passive: true });

  equipmentGrid._scrollHandler = handleScroll;

  if (window.ResizeObserver) {
    const resizeObserver = new ResizeObserver(() => {
      if (window.innerWidth <= 768) {
        goToSlide(currentSlide, 'auto');
      }
    });
    resizeObserver.observe(equipmentGrid);
    equipmentGrid._resizeObserver = resizeObserver;
  }

  goToSlide(0, 'auto');
}

// Team Slider
function initTeamSlider() {
  const slider = document.querySelector('[data-team-slider]');
  if (!slider) return;

  const track = slider.querySelector('.team-track');
  const wrap = slider.querySelector('.team-track-wrap');
  const prevBtn = slider.querySelector('.team-prev');
  const nextBtn = slider.querySelector('.team-next');

  if (!track || !wrap) return;

  if (slider.dataset.teamSliderInitialized === 'true') {
    return;
  }

  const getSlides = () => Array.from(track.querySelectorAll('.team-slide:not(.is-clone)'));
  let originalSlides = getSlides();

  if (originalSlides.length === 0) return;

  // Создаем по одному клону для бесконечного цикла
  if (originalSlides.length > 1) {
    const firstClone = originalSlides[0].cloneNode(true);
    firstClone.classList.add('is-clone');
    const lastClone = originalSlides[originalSlides.length - 1].cloneNode(true);
    lastClone.classList.add('is-clone');
    track.appendChild(firstClone);
    track.insertBefore(lastClone, track.firstChild);
  }

  slider.dataset.teamSliderInitialized = 'true';
  const allSlides = Array.from(track.querySelectorAll('.team-slide'));
  const originalCount = originalSlides.length;

  let currentIndex = originalSlides.length > 1 ? 1 : 0;
  let slideWidth = 0;
  let isAnimating = false;
  let isDragging = false;
  let dragStartX = 0;
  let dragStartY = 0;
  let dragAxis = null;
  let dragStartTranslate = 0;
  let dragRaf = null;
  let dragPendingX = 0;

  const moveTo = (index, animate = true) => {
    currentIndex = index;
    track.style.transition = animate ? 'transform 0.55s cubic-bezier(0.22, 0.61, 0.36, 1)' : 'none';
    track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
    isAnimating = animate;
  };

  const setSizes = () => {
    slideWidth = wrap.getBoundingClientRect().width;
    allSlides.forEach((slide) => {
      slide.style.width = `${slideWidth}px`;
    });
    track.style.width = `${slideWidth * allSlides.length}px`;
    moveTo(currentIndex, false);
  };

  const goNext = () => {
    if (originalSlides.length <= 1 || isAnimating) return;
    moveTo(currentIndex + 1, true);
  };

  const goPrev = () => {
    if (originalSlides.length <= 1 || isAnimating) return;
    moveTo(currentIndex - 1, true);
  };

  if (prevBtn) prevBtn.addEventListener('click', goPrev);
  if (nextBtn) nextBtn.addEventListener('click', goNext);

  slider.addEventListener('click', (event) => {
    const prev = event.target.closest('.team-prev');
    const next = event.target.closest('.team-next');
    if (prev) {
      event.preventDefault();
      goPrev();
      return;
    }
    if (next) {
      event.preventDefault();
      goNext();
    }
  });

  track.addEventListener('transitionend', (event) => {
    if (event.target !== track || event.propertyName !== 'transform') return;
    if (!isAnimating || originalSlides.length <= 1) return;
    isAnimating = false;

    // Если мы закончили анимацию на клонированном слайде, мгновенно перейдем к соответствующему оригинальному
    if (currentIndex === 0) {
      // Находимся на первом клоне, переходим к последнему оригинальному
      currentIndex = originalCount;
      track.style.transition = 'none';
      track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
    } else if (currentIndex === allSlides.length - 1) {
      // Находимся на последнем клоне, переходим к первому оригинальному
      currentIndex = 1;
      track.style.transition = 'none';
      track.style.transform = `translateX(-${slideWidth * currentIndex}px)`;
    }
  });

  const handlePointerDown = (event) => {
    if (originalSlides.length <= 1 || isAnimating) return;
    if (event.target.closest('.team-more') || event.target.closest('.team-nav')) return;
    isDragging = true;
    dragStartX = event.clientX;
    dragStartY = event.clientY;
    dragAxis = null;
    dragStartTranslate = -slideWidth * currentIndex;
    track.style.transition = 'none';
    wrap.setPointerCapture(event.pointerId);
  };

  const handlePointerMove = (event) => {
    if (!isDragging) return;
    const deltaX = event.clientX - dragStartX;
    const deltaY = event.clientY - dragStartY;

    if (!dragAxis) {
      if (Math.abs(deltaX) < 6 && Math.abs(deltaY) < 6) return;
      dragAxis = Math.abs(deltaX) > Math.abs(deltaY) ? 'x' : 'y';
    }

    if (dragAxis !== 'x') {
      isDragging = false;
      dragAxis = null;
      wrap.releasePointerCapture(event.pointerId);
      return;
    }

    dragPendingX = dragStartTranslate + deltaX;
    if (dragRaf) return;

    dragRaf = requestAnimationFrame(() => {
      track.style.transform = `translateX(${dragPendingX}px)`;
      dragRaf = null;
    });
  };

  const handlePointerUp = (event) => {
    if (!isDragging) return;
    isDragging = false;
    wrap.releasePointerCapture(event.pointerId);
    const delta = event.clientX - dragStartX;
    const threshold = slideWidth * 0.15;

    if (dragRaf) {
      cancelAnimationFrame(dragRaf);
      dragRaf = null;
    }

    if (dragAxis === 'x') {
      if (Math.abs(delta) > threshold) {
        delta < 0 ? goNext() : goPrev();
      } else {
        moveTo(currentIndex, true);
      }
    }

    dragAxis = null;
  };

  wrap.addEventListener('pointerdown', handlePointerDown);
  wrap.addEventListener('pointermove', handlePointerMove);
  wrap.addEventListener('pointerup', handlePointerUp);
  wrap.addEventListener('pointercancel', handlePointerUp);
  wrap.addEventListener('dragstart', (event) => event.preventDefault());

  window.addEventListener('resize', () => {
    setSizes();
  });

  setSizes();
  moveTo(currentIndex, false);
}

function initTeamModal() {
  const modal = document.getElementById('teamModal');
  if (!modal) return;

  const nameEl = modal.querySelector('[data-team-modal-name]');
  const roleEl = modal.querySelector('[data-team-modal-role]');
  const textEl = modal.querySelector('[data-team-modal-text]');
  const imageEl = modal.querySelector('[data-team-modal-image]');

  const openModal = (slide) => {
    const name = slide.dataset.name || '';
    const role = slide.dataset.role || '';
    const modalPhoto = slide.dataset.modalPhoto || slide.dataset.photo || '';
    const detail = slide.querySelector('.team-slide__long');
    const summary = slide.querySelector('.team-story');
    const detailHtml = detail && detail.innerHTML.trim() ? detail.innerHTML : (summary ? summary.innerHTML : '');

    if (nameEl) nameEl.textContent = name;
    if (roleEl) roleEl.textContent = role;
    if (textEl) textEl.innerHTML = detailHtml;
    if (imageEl) {
      const imageWrap = imageEl.closest('.team-modal__image');
      if (modalPhoto) {
        imageEl.src = modalPhoto;
        imageEl.alt = name ? name : 'Фото сотрудника';
        if (imageWrap) imageWrap.style.display = '';
      } else {
        imageEl.removeAttribute('src');
        imageEl.alt = '';
        if (imageWrap) imageWrap.style.display = 'none';
      }
    }

    modal.classList.add('active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  };

  const closeModal = () => {
    modal.classList.remove('active');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  };

  document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-team-more]');
    if (trigger) {
      const slide = trigger.closest('.team-slide');
      if (slide) {
        openModal(slide);
      }
    }

    if (event.target.closest('[data-team-close]')) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modal.classList.contains('active')) {
      closeModal();
    }
  });
}

function initExperienceModal() {
  const cardSelector = '.experience-card[data-modal="1"], .all-case-card[data-modal="1"]';
  const cards = document.querySelectorAll(cardSelector);
  const modal = document.getElementById('experienceModal');
  if (!cards.length || !modal) return;

  const modalTitle = modal.querySelector('.experience-modal-title');
  const modalImage = modal.querySelector('.experience-modal-image');
  const modalMeta = modal.querySelector('.experience-modal-meta');
  const modalClose = document.getElementById('experienceModalClose');
  const discussButton = modal.querySelector('.experience-modal-cta');
  const pageButton = modal.querySelector('.experience-modal-link');
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
    const link = card.dataset.link || '';

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

    if (pageButton) {
      if (link) {
        pageButton.href = link;
        pageButton.style.display = '';
      } else {
        pageButton.style.display = 'none';
      }
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

function initProjectGallery() {
  const gallery = document.querySelector('[data-project-gallery]');
  if (!gallery) return;

  const slides = Array.from(gallery.querySelectorAll('[data-gallery-slide]'));
  if (slides.length === 0) return;

  const lightbox = document.getElementById('projectLightbox');
  if (!lightbox) return;

  const lightboxImage = lightbox.querySelector('[data-lightbox-image]');
  const lightboxCaption = lightbox.querySelector('[data-lightbox-caption]');
  const lightboxPrev = lightbox.querySelector('[data-lightbox-prev]');
  const lightboxNext = lightbox.querySelector('[data-lightbox-next]');
  const closeButtons = lightbox.querySelectorAll('[data-lightbox-close]');

  let lightboxIndex = 0;

  const normalizeIndex = (index) => {
    const total = slides.length;
    if (!total) return 0;
    return ((index % total) + total) % total;
  };

  const updateLightbox = () => {
    const slide = slides[lightboxIndex];
    const src = slide ? slide.dataset.full || slide.querySelector('img')?.src : '';
    if (lightboxImage) {
      lightboxImage.src = src || '';
      lightboxImage.alt = slide ? slide.getAttribute('aria-label') || '' : '';
    }
    if (lightboxCaption) {
      lightboxCaption.textContent = `${lightboxIndex + 1} / ${slides.length}`;
    }
  };

  const openLightbox = (index) => {
    lightboxIndex = normalizeIndex(index);
    updateLightbox();
    lightbox.classList.add('active');
    lightbox.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  };

  const closeLightbox = () => {
    lightbox.classList.remove('active');
    lightbox.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    if (lightboxImage) {
      lightboxImage.src = '';
    }
  };

  slides.forEach((slide, index) => {
    slide.addEventListener('click', () => openLightbox(index));
  });

  if (lightboxPrev) {
    lightboxPrev.addEventListener('click', () => {
      lightboxIndex = normalizeIndex(lightboxIndex - 1);
      updateLightbox();
    });
  }

  if (lightboxNext) {
    lightboxNext.addEventListener('click', () => {
      lightboxIndex = normalizeIndex(lightboxIndex + 1);
      updateLightbox();
    });
  }

  closeButtons.forEach((button) => {
    button.addEventListener('click', closeLightbox);
  });

  lightbox.addEventListener('click', (event) => {
    if (event.target === lightbox) {
      closeLightbox();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && lightbox.classList.contains('active')) {
      closeLightbox();
    }
  });
}

function initProjectConsultationForm() {
  const form = document.getElementById('projectConsultationForm');
  if (!form) return;

  form.addEventListener('submit', (event) => {
    event.preventDefault();
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn ? submitBtn.textContent : '';

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = 'Отправка...';
    }

    const formData = new FormData(form);
    formData.append('action', 'bis_submit_project_consultation');

    fetch('/wp-admin/admin-ajax.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showNotification('Спасибо! Мы свяжемся с вами в ближайшее время.', 'success');
          form.reset();
        } else {
          showNotification('Ошибка отправки. Попробуйте позже.', 'error');
        }
      })
      .catch(() => {
        showNotification('Ошибка отправки. Попробуйте позже.', 'error');
      })
      .finally(() => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = originalText;
        }
      });
  });
}
