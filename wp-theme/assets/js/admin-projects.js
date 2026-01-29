(function ($) {
  $(document).ready(function () {
    const bannerImagePreview = $('[data-banner-image-preview]');
    const galleryList = $('#bis-project-gallery-list');
    const galleryTemplate = $('#bis-project-gallery-item-template');

    const updateBadge = (checkbox) => {
      const badge = $('[data-featured-badge]');
      if (!badge.length) return;
      if (checkbox.is(':checked')) {
        badge.addClass('is-featured').text('Ключевой проект');
      } else {
        badge.removeClass('is-featured').text('Обычный проект');
      }
    };

    const updatePreview = (preview, url) => {
      if (!preview || !preview.length) return;
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

    const openMediaFrame = (title, multiple, callback) => {
      const frame = wp.media({
        title,
        multiple,
        library: { type: 'image' }
      });

      frame.on('select', function () {
        const selection = frame.state().get('selection');
        if (multiple) {
          selection.each(function (attachment) {
            const data = attachment.toJSON();
            callback(data.url);
          });
          return;
        }
        const attachment = selection.first().toJSON();
        callback(attachment.url);
      });

      frame.open();
    };

    $('.bis-project-image-upload').on('click', function (e) {
      e.preventDefault();
      const button = $(this);
      const targetId = button.data('target');
      const input = $('#' + targetId);

      openMediaFrame('Выберите изображение', false, (url) => {
        input.val(url);
        updatePreview(bannerImagePreview, url);
      });
    });

    $('.bis-project-image-clear').on('click', function (e) {
      e.preventDefault();
      const button = $(this);
      const targetId = button.data('target');
      const input = $('#' + targetId);
      input.val('');
      updatePreview(bannerImagePreview, '');
    });

    $('#bis_project_banner_image').on('input', function () {
      updatePreview(bannerImagePreview, $(this).val());
    });

    const addGalleryItem = (url) => {
      if (!galleryList.length || !galleryTemplate.length || !url) return;
      const item = $(galleryTemplate.html());
      item.find('.bis-project-gallery-thumb').css('background-image', `url('${url}')`);
      item.find('input[type=\"hidden\"]').attr('name', 'bis_project_gallery[]').val(url);
      galleryList.append(item);
    };

    $('#bis-project-gallery-add').on('click', function (e) {
      e.preventDefault();
      openMediaFrame('Выберите изображения галереи', true, (url) => {
        addGalleryItem(url);
      });
    });

    if (galleryList.length) {
      galleryList.on('click', '.bis-project-gallery-remove', function () {
        $(this).closest('.bis-project-gallery-item').remove();
      });

      if (galleryList.sortable) {
        galleryList.sortable({
          handle: '.handle'
        });
      }
    }

    $('[data-featured-toggle]').on('change', function () {
      updateBadge($(this));
    });

    updateBadge($('[data-featured-toggle]'));
    updatePreview(bannerImagePreview, $('#bis_project_banner_image').val());
  });
})(jQuery);
