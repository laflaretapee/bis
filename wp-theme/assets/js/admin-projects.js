(function ($) {
  $(document).ready(function () {
    const galleryList = $('#bis-project-gallery-list');
    const galleryTemplate = $('#bis-project-gallery-item-template');
    const hasBlockEditorStore = Boolean(window.wp && wp.data && wp.data.dispatch && wp.data.select);

    const getPreview = (targetId) => {
      if (!targetId) return $();
      return $(`[data-image-preview="${targetId}"]`);
    };

    const syncBlockEditorMeta = (targetId, value) => {
      if (!hasBlockEditorStore || targetId !== 'bis_news_image') return;

      const currentMeta = wp.data.select('core/editor').getEditedPostAttribute('meta') || {};
      wp.data.dispatch('core/editor').editPost({
        meta: {
          ...currentMeta,
          bis_news_image: value || ''
        }
      });
    };

    const syncFeaturedMedia = (targetId, attachmentId) => {
      if (!hasBlockEditorStore || targetId !== 'bis_news_image') return;

      wp.data.dispatch('core/editor').editPost({
        featured_media: attachmentId ? Number(attachmentId) : 0
      });
    };

    const updateBadge = (checkbox) => {
      const badge = $('[data-featured-badge]');
      if (!badge.length) return;
      if (checkbox.is(':checked')) {
        badge.addClass('is-featured').text('РљР»СЋС‡РµРІРѕР№ РїСЂРѕРµРєС‚');
      } else {
        badge.removeClass('is-featured').text('РћР±С‹С‡РЅС‹Р№ РїСЂРѕРµРєС‚');
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
          preview.append('<span class="bis-project-media__placeholder">РќРµС‚ РёР·РѕР±СЂР°Р¶РµРЅРёСЏ</span>');
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
            callback(attachment.toJSON().url);
          });
          return;
        }

        callback(selection.first().toJSON());
      });

      frame.open();
    };

    $('.bis-project-image-upload').on('click', function (e) {
      e.preventDefault();
      const button = $(this);
      const targetId = button.data('target');
      const input = $('#' + targetId);
      const preview = getPreview(targetId);

      openMediaFrame('Р’С‹Р±РµСЂРёС‚Рµ РёР·РѕР±СЂР°Р¶РµРЅРёРµ', false, (attachment) => {
        const url = attachment && attachment.url ? attachment.url : '';
        input.val(url).trigger('input').trigger('change');
        syncBlockEditorMeta(targetId, url);
        syncFeaturedMedia(targetId, attachment && attachment.id ? attachment.id : 0);
        updatePreview(preview, url);
      });
    });

    $('.bis-project-image-clear').on('click', function (e) {
      e.preventDefault();
      const button = $(this);
      const targetId = button.data('target');
      const input = $('#' + targetId);
      const preview = getPreview(targetId);

      input.val('').trigger('input').trigger('change');
      syncBlockEditorMeta(targetId, '');
      syncFeaturedMedia(targetId, 0);
      updatePreview(preview, '');
    });

    $('[data-image-input]').on('input', function () {
      const input = $(this);
      const targetId = input.data('preview-target') || input.attr('id');
      syncBlockEditorMeta(targetId, input.val());
      updatePreview(getPreview(targetId), input.val());
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
      openMediaFrame('Р’С‹Р±РµСЂРёС‚Рµ РёР·РѕР±СЂР°Р¶РµРЅРёСЏ РіР°Р»РµСЂРµРё', true, (url) => {
        addGalleryItem(url);
      });
    });

    $('#bis-project-gallery-add-url').on('click', function (e) {
      e.preventDefault();
      const urlInput = $('#bis-project-gallery-url');
      if (!urlInput.length) return;
      const url = urlInput.val().trim();
      if (!url) return;
      addGalleryItem(url);
      urlInput.val('');
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
    $('[data-image-input]').each(function () {
      const input = $(this);
      const targetId = input.data('preview-target') || input.attr('id');
      updatePreview(getPreview(targetId), input.val());
    });
  });
})(jQuery);
