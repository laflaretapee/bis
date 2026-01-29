(function ($) {
  $(document).ready(function () {
    const list = $('#team-members-list');
    const template = $('#team-member-template');

    if (!list.length) {
      return;
    }

    const updatePreview = (item, field, url) => {
      const preview = item.find(`[data-preview="${field}"]`);
      if (!preview.length) return;

      if (url) {
        preview.css('background-image', `url('${url}')`).removeClass('is-empty');
        preview.find('.team-member-placeholder').remove();
      } else {
        preview.css('background-image', 'none').addClass('is-empty');
        if (!preview.find('.team-member-placeholder').length) {
          preview.append('<span class="team-member-placeholder">Нет фото</span>');
        }
      }
    };

    const updateIndices = () => {
      list.children('.team-member-item').each(function (index) {
        const item = $(this);
        item.attr('data-index', index);
        item.find('[data-field]').each(function () {
          const field = $(this).data('field');
          $(this).attr('name', `team_members[${index}][${field}]`);
        });
      });
    };

    list.sortable({
      handle: '.handle',
      placeholder: 'team-member-placeholder-item',
      update: updateIndices
    });

    const openMediaFrame = (callback) => {
      const frame = wp.media({
        title: 'Выберите изображение',
        multiple: false,
        library: { type: 'image' }
      });

      frame.on('select', function () {
        const attachment = frame.state().get('selection').first().toJSON();
        callback(attachment.url);
      });

      frame.open();
    };

    list.on('click', '.team-photo-upload', function (e) {
      e.preventDefault();
      const button = $(this);
      const field = button.data('photoType');
      const item = button.closest('.team-member-item');
      const input = item.find(`[data-field="${field}"]`);

      openMediaFrame((url) => {
        input.val(url);
        updatePreview(item, field, url);
      });
    });

    list.on('click', '.team-photo-clear', function (e) {
      e.preventDefault();
      const button = $(this);
      const field = button.data('photoType');
      const item = button.closest('.team-member-item');
      const input = item.find(`[data-field="${field}"]`);
      input.val('');
      updatePreview(item, field, '');
    });

    list.on('click', '.team-member-remove', function () {
      $(this).closest('.team-member-item').remove();
      updateIndices();
    });

    $('#add-team-member').on('click', function (e) {
      e.preventDefault();
      if (!template.length) return;
      const html = $(template.html());
      list.append(html);
      updateIndices();
    });

    updateIndices();
  });
})(jQuery);
