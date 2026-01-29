(function ($) {
  $(document).ready(function () {
    const projectPreview = $('[data-project-preview]');
    const bannerImagePreview = $('[data-banner-image-preview]');
    const bannerPreviews = $('[data-banner-preview]');
    const bannerList = $('#bis-project-banner-layers');
    const bannerTemplate = $('#bis-project-banner-layer-template');
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

    const getBannerBackground = () => {
      const bannerUrl = $('#bis_project_banner_image').val();
      if (bannerUrl) return bannerUrl;
      const projectUrl = $('#bis_project_image').val();
      return projectUrl || '';
    };

    const updateBannerBackground = () => {
      const url = getBannerBackground();
      bannerPreviews.each(function () {
        const preview = $(this);
        preview.css('background-image', url ? `url('${url}')` : 'none');
      });
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
      const previewType = button.data('preview') || 'project';
      const input = $('#' + targetId);

      openMediaFrame('Выберите изображение', false, (url) => {
        input.val(url);
        if (previewType === 'banner') {
          updatePreview(bannerImagePreview, url);
          updateBannerBackground();
        } else {
          updatePreview(projectPreview, url);
          updateBannerBackground();
        }
      });
    });

    $('.bis-project-image-clear').on('click', function (e) {
      e.preventDefault();
      const button = $(this);
      const targetId = button.data('target');
      const previewType = button.data('preview') || 'project';
      const input = $('#' + targetId);
      input.val('');

      if (previewType === 'banner') {
        updatePreview(bannerImagePreview, '');
      } else {
        updatePreview(projectPreview, '');
      }
      updateBannerBackground();
    });

    $('#bis_project_image').on('input', function () {
      updatePreview(projectPreview, $(this).val());
      updateBannerBackground();
    });

    $('#bis_project_banner_image').on('input', function () {
      updatePreview(bannerImagePreview, $(this).val());
      updateBannerBackground();
    });

    const updateLayerIndices = () => {
      if (!bannerList.length) return;
      bannerList.children('.bis-banner-layer-item').each(function (index) {
        const item = $(this);
        item.attr('data-index', index);
        item.find('[data-field]').each(function () {
          const field = $(this).data('field');
          $(this).attr('name', `bis_project_banner_layers[${index}][${field}]`);
        });
      });
    };

    const getLayerData = (item) => {
      const getValue = (field, fallback = '') => {
        const input = item.find(`[data-field="${field}"]`);
        return input.length ? input.val() : fallback;
      };

      return {
        text: getValue('text', ''),
        size: getValue('size', 'md'),
        align: getValue('align', 'left'),
        desktopX: parseFloat(getValue('desktop_x', 50)) || 0,
        desktopY: parseFloat(getValue('desktop_y', 50)) || 0,
        mobileX: parseFloat(getValue('mobile_x', 50)) || 0,
        mobileY: parseFloat(getValue('mobile_y', 50)) || 0
      };
    };

    const renderBannerPreview = () => {
      if (!bannerList.length) return;
      bannerPreviews.find('.bis-banner-preview__layer').remove();

      bannerList.children('.bis-banner-layer-item').each(function (index) {
        const item = $(this);
        const data = getLayerData(item);
        if (!data.text) return;

        bannerPreviews.each(function () {
          const preview = $(this);
          const mode = preview.data('banner-preview') === 'mobile' ? 'mobile' : 'desktop';
          const layer = $('<div class="bis-banner-preview__layer"></div>');
          layer.attr('data-layer-index', index);
          layer.addClass(`is-${data.size}`);
          layer.addClass(`is-align-${data.align}`);
          layer.text(data.text);

          const x = mode === 'mobile' ? data.mobileX : data.desktopX;
          const y = mode === 'mobile' ? data.mobileY : data.desktopY;
          layer.css({ left: `${x}%`, top: `${y}%` });

          preview.append(layer);
        });
      });
    };

    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);
    let dragState = null;

    const onPointerMove = (event) => {
      if (!dragState) return;
      const { preview, mode, layer, index } = dragState;
      const rect = preview.getBoundingClientRect();
      const clientX = event.clientX !== undefined ? event.clientX : (event.touches && event.touches[0].clientX);
      const clientY = event.clientY !== undefined ? event.clientY : (event.touches && event.touches[0].clientY);
      if (clientX === undefined || clientY === undefined) return;

      const rawX = ((clientX - rect.left) / rect.width) * 100;
      const rawY = ((clientY - rect.top) / rect.height) * 100;
      const x = clamp(rawX, 0, 100);
      const y = clamp(rawY, 0, 100);

      layer.style.left = `${x}%`;
      layer.style.top = `${y}%`;

      const item = bannerList.children('.bis-banner-layer-item').eq(index);
      const fieldX = mode === 'mobile' ? 'mobile_x' : 'desktop_x';
      const fieldY = mode === 'mobile' ? 'mobile_y' : 'desktop_y';
      item.find(`[data-field="${fieldX}"]`).val(x.toFixed(1));
      item.find(`[data-field="${fieldY}"]`).val(y.toFixed(1));
    };

    const endDrag = () => {
      dragState = null;
      document.removeEventListener('pointermove', onPointerMove);
      document.removeEventListener('pointerup', endDrag);
    };

    $(document).on('pointerdown', '[data-banner-preview] .bis-banner-preview__layer', function (event) {
      const layerEl = event.currentTarget;
      const preview = layerEl.closest('[data-banner-preview]');
      if (!preview || !bannerList.length) return;

      dragState = {
        layer: layerEl,
        preview,
        mode: preview.getAttribute('data-banner-preview'),
        index: parseInt(layerEl.getAttribute('data-layer-index'), 10)
      };

      document.addEventListener('pointermove', onPointerMove);
      document.addEventListener('pointerup', endDrag);
    });

    if (bannerList.length && bannerList.sortable) {
      bannerList.sortable({
        handle: '.handle',
        update: function () {
          updateLayerIndices();
          renderBannerPreview();
        }
      });
    }

    if (bannerList.length) {
      bannerList.on('input change', '[data-field]', function () {
        renderBannerPreview();
      });

      bannerList.on('click', '.bis-banner-layer-remove', function () {
        $(this).closest('.bis-banner-layer-item').remove();
        updateLayerIndices();
        renderBannerPreview();
      });
    }

    $('#bis-add-banner-layer').on('click', function (e) {
      e.preventDefault();
      if (!bannerList.length || !bannerTemplate.length) return;
      const html = $(bannerTemplate.html());
      bannerList.append(html);
      updateLayerIndices();
      renderBannerPreview();
    });

    const addGalleryItem = (url) => {
      if (!galleryList.length || !galleryTemplate.length || !url) return;
      const item = $(galleryTemplate.html());
      item.find('.bis-project-gallery-thumb').css('background-image', `url('${url}')`);
      item.find('input[type="hidden"]').attr('name', 'bis_project_gallery[]').val(url);
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
    updatePreview(projectPreview, $('#bis_project_image').val());
    updatePreview(bannerImagePreview, $('#bis_project_banner_image').val());
    updateBannerBackground();
    updateLayerIndices();
    renderBannerPreview();
  });
})(jQuery);
