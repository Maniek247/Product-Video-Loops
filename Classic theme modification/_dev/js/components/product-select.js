
import $ from 'jquery';
// eslint-disable-next-line
import 'velocity-animate';
import updateSources from 'update-sources';

export default class ProductSelect {
  init() {
    const MAX_THUMBS = 5;
    const $arrows = $('.js-modal-arrows');
    const $thumbnails = $('.js-modal-product-images');

    $('body')
      .on('click', 'aside#thumbnails', (event) => {
        if (event.target.id === 'thumbnails') {
          $('#product-modal').modal('hide');
        }
      });

    if ($('.js-modal-product-images li').length <= MAX_THUMBS) {
      $arrows.css('opacity', '.2');
    } else {
      $arrows.on('click', (event) => {
        if ($(event.target).hasClass('arrow-up') && $thumbnails.position().top < 0) {
          this.move('up');
          $('.js-modal-arrow-down').css('opacity', '1');
        } else if (
          $(event.target).hasClass('arrow-down')
          && $thumbnails.position().top + $thumbnails.height() > $('.js-modal-mask').height()
        ) {
          this.move('down');
          $('.js-modal-arrow-up').css('opacity', '1');
        }
      });
    }

    this.onClickModalThumb();
  }
  onClickModalThumb() {
    $('body').on('click', '.js-modal-thumb', (event) => {
      $('.js-modal-thumb').removeClass('selected');
      $(event.currentTarget).addClass('selected');
  
      const $thumbContainer = $(event.currentTarget).closest('.thumb-container');
      const isVideo = $thumbContainer.data('is-video') === 1;

      const $modalCover = $(prestashop.themeSelectors.product.modalProductCover);
  
      if (isVideo) {
        const videoUrl = $(event.currentTarget).attr('src'); 

        const $video = $('<video class="js-modal-product-cover product-cover-modal" autoplay muted loop>');
        $video.attr('src', videoUrl);

        $modalCover.replaceWith($video);
      } else {
        const largeSrc = $(event.target).data('image-large-src');
        if ($modalCover.is('video')) {
          const $img = $('<img class="js-modal-product-cover product-cover-modal"/>');
          $img.attr('src', largeSrc);
          $img.attr('title', $(event.target).attr('title') || '');
          $img.attr('alt', $(event.target).attr('alt') || '');

          $modalCover.replaceWith($img);

          updateSources($img, $(event.target).data('image-large-sources'));
        } else {
          $modalCover.attr('src', largeSrc);
          $modalCover.attr('title', $(event.target).attr('title'));
          $modalCover.attr('alt', $(event.target).attr('alt'));
          updateSources($modalCover, $(event.target).data('image-large-sources'));
        }
      }
    });
  }    

  move(direction) {
    const THUMB_MARGIN = 10;
    const $thumbnails = $('.js-modal-product-images');
    const thumbHeight = $('.js-modal-product-images li img').height() + THUMB_MARGIN;
    const currentPosition = $thumbnails.position().top;
    $thumbnails.velocity(
      {
        translateY: direction === 'up' ? currentPosition + thumbHeight : currentPosition - thumbHeight,
      },
      () => {
        if ($thumbnails.position().top >= 0) {
          $('.js-modal-arrow-up').css('opacity', '.2');
        } else if ($thumbnails.position().top + $thumbnails.height() <= $('.js-modal-mask').height()) {
          $('.js-modal-arrow-down').css('opacity', '.2');
        }
      },
    );
  }
}
