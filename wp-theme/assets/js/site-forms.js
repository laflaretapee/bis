const bisAjaxUrl = window.bisSiteConfig?.ajaxUrl || '/wp-admin/admin-ajax.php';

function getHCaptchaContainers(scope = document) {
  return Array.from(scope.querySelectorAll('.h-captcha, h-captcha'));
}

function rerenderHCaptchaWidget(widget) {
  if (!widget) return;

  const form = widget.closest('form');
  const currentId = form?.dataset?.hCaptchaId || '';

  if (window.hCaptcha && Array.isArray(window.hCaptcha.foundForms) && currentId) {
    window.hCaptcha.foundForms = window.hCaptcha.foundForms.filter((foundForm) => foundForm.hCaptchaId !== currentId);
  }

  if (form) {
    form.removeAttribute('data-h-captcha-id');
  }

  widget.innerHTML = '';

  if (typeof window.hCaptchaBindEvents === 'function') {
    window.hCaptchaBindEvents(widget);
  }
}

function resetHCaptcha(form) {
  if (!form) return;

  form.querySelectorAll('textarea[name="h-captcha-response"], input[name="h-captcha-response"]').forEach((field) => {
    field.value = '';
  });

  const widgets = getHCaptchaContainers(form);
  if (!widgets.length) {
    return;
  }

  if (typeof window.hCaptchaBindEvents === 'function') {
    widgets.forEach((widget) => {
      try {
        rerenderHCaptchaWidget(widget);
      } catch (error) {
        console.warn('hCaptcha reset failed', error);
      }
    });
    return;
  }

  if (typeof window.hCaptchaReset !== 'function') {
    return;
  }

  widgets.forEach((widget) => {
    try {
      window.hCaptchaReset(widget);
    } catch (error) {
      console.warn('hCaptcha reset failed', error);
    }
  });
}

function formatRussianPhone(value) {
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
}

function isValidRussianPhone(value) {
  const digits = value.replace(/\D/g, '');
  return digits.length === 11 && digits.startsWith('7');
}

function attachPhoneMask(input) {
  if (!input || input.dataset.phoneMaskBound === 'true') return;

  input.dataset.phoneMaskBound = 'true';

  input.addEventListener('focus', () => {
    if (!input.value.trim()) {
      input.value = '+7 ';
    }
  });

  input.addEventListener('input', (event) => {
    event.target.value = formatRussianPhone(event.target.value);
  });

  input.addEventListener('blur', () => {
    const digits = input.value.replace(/\D/g, '');
    if (digits.length <= 1) {
      input.value = '';
    }
  });
}

function initPhoneMasks(root = document) {
  root.querySelectorAll('input[type="tel"]').forEach(attachPhoneMask);
}

function resetFormState(form, { clearErrors = false } = {}) {
  if (!form) return;

  form.reset();
  resetHCaptcha(form);

  if (!clearErrors) return;

  const inputs = form.querySelectorAll('input, textarea, select');
  inputs.forEach((input) => clearError(input));
}

function syncUniformCardHeights() {
  const containers = document.querySelectorAll('.equipment-grid, .experience-grid, .projects-grid');

  containers.forEach((container) => {
    const cards = Array.from(container.children).filter((card) => (
      card.classList.contains('equipment-card') ||
      card.classList.contains('experience-card')
    ));

    cards.forEach((card) => {
      card.style.height = '';
    });

    if (cards.length < 2) return;

    let maxHeight = 0;
    cards.forEach((card) => {
      maxHeight = Math.max(maxHeight, card.offsetHeight);
    });

    cards.forEach((card) => {
      card.style.height = `${maxHeight}px`;
    });
  });
}

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

      if (validateFormFields(callbackForm) && validateForm(formData)) {
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
      resetFormState(callbackForm, { clearErrors: true });
    }
  }
}

function submitAjaxForm(form, action, extraData = {}, options = {}) {
  const submitBtn = form.querySelector('button[type="submit"]');
  const originalText = submitBtn ? submitBtn.textContent : '';
  const formData = new FormData(form);

  formData.append('action', action);
  Object.entries(extraData).forEach(([key, value]) => {
    formData.append(key, value);
  });

  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.textContent = 'Отправка...';
    submitBtn.style.opacity = '0.6';
  }

  fetch(bisAjaxUrl, {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then((data) => {
      if (!data.success) {
        throw new Error(data.data?.message || 'Ошибка отправки. Попробуйте позже.');
      }

      if (submitBtn) {
        submitBtn.textContent = '✓ Отправлено!';
        submitBtn.style.background = '#10b981';
      }

      if (typeof options.onSuccess === 'function') {
        options.onSuccess(data);
      } else {
        resetFormState(form);
      }

      showNotification(options.successMessage || 'Спасибо! Ваша заявка отправлена.', 'success');
    })
    .catch((error) => {
      showNotification(error.message || 'Ошибка отправки. Попробуйте позже.', 'error');
      resetHCaptcha(form);
    })
    .finally(() => {
      if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
        submitBtn.style.background = '';
        submitBtn.style.opacity = '';
      }
    });
}

// Функция отправки формы обратного звонка
function submitCallbackForm(data, form) {
  submitAjaxForm(form, 'bis_submit_general_request', {
    request_type: data.type || 'callback'
  }, {
    successMessage: 'Спасибо! Мы перезвоним вам в течение 15 минут.',
    onSuccess: () => {
      resetFormState(form);
      const overlay = document.getElementById('callbackOverlay');
      if (overlay) {
        overlay.classList.remove('active');
      }
    }
  });
}

// Валидация формы
function initFormValidation() {
  const forms = document.querySelectorAll('#contactForm, #orderForm');

  forms.forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();

      const formData = {
        name: form.querySelector('[name="name"]').value,
        phone: form.querySelector('[name="phone"]').value,
        message: form.querySelector('[name="message"]').value,
        service: form.querySelector('#orderService')?.value || '',
        isOrder: form.id === 'orderForm'
      };

      if (validateFormFields(form) && validateForm(formData)) {
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
  const value = typeof field.value === 'string' ? field.value.trim() : '';
  let isValid = true;

  if (field.type === 'checkbox' && field.hasAttribute('required') && !field.checked) {
    isValid = false;
    showError(field, 'Необходимо подтвердить согласие');
  } else if (field.hasAttribute('required') && !value) {
    isValid = false;
    showError(field, 'Это поле обязательно для заполнения');
  } else if (field.type === 'tel' && value) {
    if (!isValidRussianPhone(value)) {
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
function validateFormFields(form) {
  if (!form) return true;

  let isValid = true;

  form.querySelectorAll('input:not([readonly]), textarea, select').forEach((field) => {
    if (!validateField(field)) {
      isValid = false;
    }
  });

  return isValid;
}

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
  } else if (!isValidRussianPhone(data.phone)) {
    isValid = false;
  }

  if (!data.message && !data.isOrder && data.type !== 'callback') {
    isValid = false;
  }

  return isValid;
}

// Отправка формы
function submitForm(data, form) {
  submitAjaxForm(form, 'bis_submit_general_request', {
    request_type: data.isOrder ? 'order' : 'contact'
  }, {
    successMessage: 'Спасибо! Ваша заявка отправлена. Мы свяжемся с вами в ближайшее время.',
    onSuccess: () => {
      resetFormState(form);

      if (form.id === 'orderForm') {
        closePopup();
      }
    }
  });
}

// Уведомления
function showNotification(message, type = 'info') {
  const notification = document.createElement('div');
  notification.className = 'notification';
  notification.textContent = message;

  Object.assign(notification.style, {
    position: 'fixed',
    top: '32px',
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
      resetFormState(orderForm, { clearErrors: true });
    }
  }
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
    attachPhoneMask(estimatePhone);
  }

  if (estimateForm) {
    estimateForm.addEventListener('submit', (e) => {
      e.preventDefault();

      if (!validateFormFields(estimateForm)) {
        return;
      }

      const formData = new FormData(estimateForm);
      formData.append('action', 'bis_submit_estimate');

      const submitBtn = estimateForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;

      submitBtn.disabled = true;
      submitBtn.textContent = 'Отправка...';

      fetch(bisAjaxUrl, {
        method: 'POST',
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            submitBtn.textContent = '✓ Отправлено!';
            submitBtn.style.background = '#10b981';

            setTimeout(() => {
              closeEstimateModal({ resetForm: true });
              submitBtn.disabled = false;
              submitBtn.textContent = originalText;
              submitBtn.style.background = '';
              showNotification('Спасибо! Мы свяжемся с вами в течение 2 дней.', 'success');
            }, 1500);
          } else {
            showNotification(data.data?.message || 'Ошибка отправки. Попробуйте позже.', 'error');
            resetHCaptcha(estimateForm);
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
          }
        })
        .catch(error => {
          console.error('Error:', error);
          resetHCaptcha(estimateForm);
          showNotification(error.message || 'Ошибка отправки. Попробуйте позже.', 'error');
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

  function closeEstimateModal({ resetForm = true } = {}) {
    if (!estimateOverlay.classList.contains('active')) return;
    estimateOverlay.classList.add('closing');
    clearTimeout(closeTimeout);
    closeTimeout = setTimeout(() => {
      estimateOverlay.classList.remove('active', 'closing');
      document.body.style.overflow = '';
      if (estimateForm && resetForm) {
        resetFormState(estimateForm);
      }
    }, ANIMATION_DURATION);
  }
}
