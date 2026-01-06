(function($) {
  'use strict';

  const list = $('#bis-floating-buttons-list');
  const template = $('#bis-floating-button-template');
  const emptyState = $('.bis-floating-buttons-empty');

  function updateEmptyState() {
    if (!list.find('> li').length) {
      emptyState.removeClass('hidden');
    } else {
      emptyState.addClass('hidden');
    }
  }

  function updatePreview($item, url) {
    const $preview = $item.find('.bis-floating-buttons-preview');

    if (url) {
      $preview.css('background-image', 'url(' + url + ')');
      $item.addClass('has-image');
    } else {
      $preview.css('background-image', '');
      $item.removeClass('has-image');
    }
  }

  $('#bis-add-floating-button').on('click', function(e) {
    e.preventDefault();

    if (!template.length) {
      return;
    }

    const $item = $(template.html());
    list.append($item);
    updateEmptyState();
  });

  list.on('click', '.bis-remove-floating-button', function(e) {
    e.preventDefault();
    $(this).closest('.bis-floating-buttons-item').remove();
    updateEmptyState();
  });

  list.on('click', '.bis-select-floating-image', function(e) {
    e.preventDefault();

    const $item = $(this).closest('.bis-floating-buttons-item');
    const frame = wp.media({
      title: 'Выберите изображение',
      multiple: false,
      button: {
        text: 'Использовать'
      }
    });

    frame.on('select', function() {
      const attachment = frame.state().get('selection').first().toJSON();
      $item.find('.bis-floating-buttons-image').val(attachment.url).trigger('change');
    });

    frame.open();
  });

  list.on('change', '.bis-floating-buttons-image', function() {
    const $item = $(this).closest('.bis-floating-buttons-item');
    updatePreview($item, $(this).val());
  });

  list.find('.bis-floating-buttons-image').each(function() {
    const $item = $(this).closest('.bis-floating-buttons-item');
    updatePreview($item, $(this).val());
  });

  updateEmptyState();
})(jQuery);
