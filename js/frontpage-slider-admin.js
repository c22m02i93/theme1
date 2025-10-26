(function ($) {
  'use strict';

  $(function () {
    var settings = window.FrontpageSliderAdmin || {};
    var gallery = $('#frontpage-slider-gallery');
    var hiddenInput = $('#frontpage-slider-ids');
    var emptyState = $('#frontpage-slider-empty');
    var addButton = $('#frontpage-slider-add');
    var mediaFrame;

    if (!gallery.length || !hiddenInput.length || !addButton.length) {
      return;
    }

    function updateEmptyState() {
      if (gallery.children('.frontpage-slider-item').length) {
        emptyState.addClass('is-hidden');
      } else {
        emptyState.removeClass('is-hidden');
      }
    }

    function updateInputValue() {
      var ids = [];

      gallery.children('.frontpage-slider-item').each(function () {
        var id = $(this).data('id');
        if (id) {
          ids.push(id);
        }
      });

      hiddenInput.val(ids.join(','));
      updateEmptyState();
    }

    function createItem(data) {
      var item = $('<div/>', {
        'class': 'frontpage-slider-item',
        'data-id': data.id
      });

      var imageSrc = data.url;
      if (data.sizes && data.sizes.thumbnail) {
        imageSrc = data.sizes.thumbnail.url;
      }

      var preview = $('<span/>', { 'class': 'frontpage-slider-item__preview' }).append(
        $('<img/>', {
          src: imageSrc,
          alt: data.alt || data.title || data.filename || ''
        })
      );

      var removeButton = $('<button/>', {
        'type': 'button',
        'class': 'frontpage-slider-remove button-link-delete',
        'aria-label': settings.removeLabel || settings.frameButton || 'Удалить'
      }).html('&times;');

      removeButton.on('click', function (event) {
        event.preventDefault();
        item.remove();
        updateInputValue();
      });

      item.append(preview, removeButton);
      return item;
    }

    addButton.on('click', function (event) {
      event.preventDefault();

      if (mediaFrame) {
        mediaFrame.open();
        return;
      }

      mediaFrame = wp.media({
        title: addButton.data('frame-title') || settings.frameTitle || '',
        button: {
          text: addButton.data('frame-button') || settings.frameButton || ''
        },
        library: {
          type: ['image']
        },
        multiple: true
      });

      mediaFrame.on('open', function () {
        var selection = mediaFrame.state().get('selection');
        var currentIds = hiddenInput.val();

        if (!currentIds) {
          return;
        }

        currentIds.split(',').forEach(function (id) {
          id = parseInt(id, 10);
          if (!id) {
            return;
          }
          var attachment = wp.media.attachment(id);
          if (attachment) {
            attachment.fetch();
            selection.add(attachment);
          }
        });
      });

      mediaFrame.on('select', function () {
        var selection = mediaFrame.state().get('selection');

        selection.each(function (attachment) {
          var data = attachment.toJSON();
          if (!data || !data.id) {
            return;
          }

          if (gallery.children('[data-id="' + data.id + '"]').length) {
            return;
          }

          var item = createItem(data);
          gallery.append(item);
        });

        updateInputValue();
      });

      mediaFrame.open();
    });

    gallery.sortable({
      placeholder: 'frontpage-slider-placeholder',
      items: '.frontpage-slider-item',
      forcePlaceholderSize: true,
      tolerance: 'pointer',
      start: function (event, ui) {
        ui.item.addClass('is-dragging');
      },
      stop: function (event, ui) {
        ui.item.removeClass('is-dragging');
        updateInputValue();
      }
    });

    updateEmptyState();
  });
})(jQuery);
