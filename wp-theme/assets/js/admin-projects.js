(function ($) {
  $(document).ready(function () {
    const updateBadge = (checkbox) => {
      const badge = $('[data-featured-badge]');
      if (!badge.length) return;
      if (checkbox.is(':checked')) {
        badge.addClass('is-featured').text('Ключевой проект');
      } else {
        badge.removeClass('is-featured').text('Обычный проект');
      }
    };

    const updatePreview = (url) => {
      const preview = $('[data-project-preview]');
      if (!preview.length) return;
      if (url) {
        preview.css('background-image', `url('${url}')`);
        preview.removeClass('is-empty').find('.bis-project-media__placeholder').remove();
      } else {
        preview.css('background-image', 'none').addClass('is-empty');
        if (!preview.find('.bis-project-media__placeholder').length) {
          preview.append('<span class="bis-project-media__placeholder">Нет изображения</span>');
        }
      }
    };

    $('.bis-project-image-upload').on('click', function (e) {
      e.preventDefault();
      const button = $(this);
      const targetId = button.data('target');
      const input = $('#' + targetId);

      const frame = wp.media({
        title: 'Выберите изображение проекта',
        multiple: false,
        library: { type: 'image' }
      });

      frame.on('select', function () {
        const attachment = frame.state().get('selection').first().toJSON();
        input.val(attachment.url);
        updatePreview(attachment.url);
      });

      frame.open();
    });

    $('.bis-project-image-clear').on('click', function (e) {
      e.preventDefault();
      $('#bis_project_image').val('');
      updatePreview('');
    });

    $('[data-featured-toggle]').on('change', function () {
      updateBadge($(this));
    });

    updateBadge($('[data-featured-toggle]'));
    updatePreview($('#bis_project_image').val());
  });
})(jQuery);
