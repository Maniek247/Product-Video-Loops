/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */
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
      //.on('click', '.js-modal-thumb', (event) => {
        // Swap active classes on thumbnail
        //if ($('.js-modal-thumb').hasClass('selected')) {
        //  $('.js-modal-thumb').removeClass('selected');
      //  }
      //  $(event.currentTarget).addClass('selected');

        // Get data from thumbnail and update cover src, alt and title
      //  $(prestashop.themeSelectors.product.modalProductCover).attr('src', $(event.target).data('image-large-src'));
      //  $(prestashop.themeSelectors.product.modalProductCover).attr('title', $(event.target).attr('title'));
      //  $(prestashop.themeSelectors.product.modalProductCover).attr('alt', $(event.target).attr('alt'));

        // Get data from thumbnail and update cover sources
      //  updateSources(
      //    $(prestashop.themeSelectors.product.modalProductCover),
      //    $(event.target).data('image-large-sources'),
      //  );
      //})
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
      // a) usuń .selected z innych miniaturek
      $('.js-modal-thumb').removeClass('selected');
      // b) dodaj .selected do aktualnie klikniętej
      $(event.currentTarget).addClass('selected');
  
      // pobieramy info, czy w <li> jest data-is-video
      const $thumbContainer = $(event.currentTarget).closest('.thumb-container');
      const isVideo = $thumbContainer.data('is-video') === 1;
  
      // znajdź "cover" w modalu
      const $modalCover = $(prestashop.themeSelectors.product.modalProductCover);
  
      // c) jeśli to wideo, podmień <img> na <video> (lub odwrotnie)
      if (isVideo) {
        // Tworzymy nowy <video>:
        const videoUrl = $(event.currentTarget).attr('src'); 
        // UWAGA: w tym przykładzie bierzemy `src` z miniaturki wideo,
        // ewentualnie możesz przechowywać "duży" film w data-video-large="..." itp.
  
        // Stwórz nowy element <video>:
        const $video = $('<video class="js-modal-product-cover product-cover-modal" autoplay muted loop>');
        $video.attr('src', videoUrl);
  
        // Zamień oryginalny element (może to być <img> lub stare <video>):
        $modalCover.replaceWith($video);
      } else {
        // standardowy obrazek
        const largeSrc = $(event.target).data('image-large-src');
        // Zamiast replace, możemy sprawdzić, czy $modalCover jest <video> i wtedy go zamienić na <img>:
        if ($modalCover.is('video')) {
          // Utwórzmy <img> od zera
          const $img = $('<img class="js-modal-product-cover product-cover-modal"/>');
          // Wstaw atrybuty:
          $img.attr('src', largeSrc);
          $img.attr('title', $(event.target).attr('title') || '');
          $img.attr('alt', $(event.target).attr('alt') || '');
  
          // Podmień <video> na <img>
          $modalCover.replaceWith($img);
  
          // Wywołaj "updateSources", jeśli masz webp/avif w data
          updateSources($img, $(event.target).data('image-large-sources'));
        } else {
          // $modalCover jest obrazkiem? Ustaw mu po prostu nowe src:
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
