import $ from 'jquery';
import prestashop from 'prestashop';
import ProductSelect from './components/product-select';
import updateSources from './components/update-sources';


$(document).ready(() => {
  function coverImage() {
    let $productCover = $(prestashop.themeSelectors.product.cover);
  
    $(prestashop.themeSelectors.product.thumb).on('click', (event) => {
      event.preventDefault();
  
      $(prestashop.themeSelectors.product.thumb).removeClass('selected');
      const $target = $(event.currentTarget);
      $target.addClass('selected');
  
      const $thumbContainer = $target.closest('.thumb-container');
      const isVideo = $thumbContainer.data('is-video') === 1;

      $productCover = $(prestashop.themeSelectors.product.cover);
  
      if (isVideo) {
        const videoUrl = $target.attr('src');
        if ($productCover.is('img')) {
          const $video = $('<video class="js-qv-product-cover product-cover-video" autoplay playsinline muted loop>');
          $video.attr('src', videoUrl);
  
          $productCover.replaceWith($video);
          $productCover = $video;
        } else {
          $productCover.attr('src', videoUrl);
          }
        } else {
        const mediumSrc = $target.data('image-medium-src');
        if ($productCover.is('video')) {
          const $img = $('<img class="js-qv-product-cover img-fluid"/>');
          $img.attr('src', mediumSrc);
          $img.attr('alt', $target.attr('alt') || '');
          $img.attr('title', $target.attr('title') || '');

          $productCover.replaceWith($img);
          $productCover = $img;
        } else {
          $productCover.attr('src', mediumSrc);
          $productCover.attr('alt', $target.attr('alt'));
          $productCover.attr('title', $target.attr('title'));
        }
        updateSources($productCover, $target.data('image-medium-sources'));
      }
    });
  }

  function imageScrollBox() {
    if ($('#main .js-qv-product-images li').length > 2) {
      $('#main .js-qv-mask').addClass('scroll');
      $('.scroll-box-arrows').addClass('scroll');
      $('#main .js-qv-mask').scrollbox({
        direction: 'h',
        distance: 113,
        autoPlay: false,
      });
      $('.scroll-box-arrows .left').click(() => {
        $('#main .js-qv-mask').trigger('backward');
      });
      $('.scroll-box-arrows .right').click(() => {
        $('#main .js-qv-mask').trigger('forward');
      });
    } else {
      $('#main .js-qv-mask').removeClass('scroll');
      $('.scroll-box-arrows').removeClass('scroll');
    }
  }

  function createInputFile() {
    $(prestashop.themeSelectors.fileInput).on('change', (event) => {
      const target = $(event.currentTarget)[0];
      const file = (target) ? target.files[0] : null;

      if (target && file) {
        $(target).prev().text(file.name);
      }
    });
  }

  function createProductSpin() {
    const $quantityInput = $(prestashop.selectors.quantityWanted);

    $quantityInput.TouchSpin({
      verticalbuttons: true,
      verticalupclass: 'material-icons touchspin-up',
      verticaldownclass: 'material-icons touchspin-down',
      buttondown_class: 'btn btn-touchspin js-touchspin',
      buttonup_class: 'btn btn-touchspin js-touchspin',
      min: parseInt($quantityInput.attr('min'), 10),
      max: 1000000,
    });

    $(prestashop.themeSelectors.touchspin).off('touchstart.touchspin');

    $quantityInput.on('focusout', () => {
      if ($quantityInput.val() === '' || $quantityInput.val() < $quantityInput.attr('min')) {
        $quantityInput.val($quantityInput.attr('min'));
        $quantityInput.trigger('change');
      }
    });

    $('body').on('change keyup', prestashop.selectors.quantityWanted, (e) => {
      if ($quantityInput.val() !== '') {
        $(e.currentTarget).trigger('touchspin.stopspin');
        prestashop.emit('updateProduct', {
          eventType: 'updatedProductQuantity',
          event: e,
        });
      }
    });
  }

  function addJsProductTabActiveSelector() {
    const nav = $(prestashop.themeSelectors.product.tabs);
    nav.on('show.bs.tab', (e) => {
      const target = $(e.target);
      target.addClass(prestashop.themeSelectors.product.activeNavClass);
      $(target.attr('href')).addClass(prestashop.themeSelectors.product.activeTabClass);
    });
    nav.on('hide.bs.tab', (e) => {
      const target = $(e.target);
      target.removeClass(prestashop.themeSelectors.product.activeNavClass);
      $(target.attr('href')).removeClass(prestashop.themeSelectors.product.activeTabClass);
    });
  }

  createProductSpin();
  createInputFile();
  coverImage();
  imageScrollBox();
  addJsProductTabActiveSelector();

  prestashop.on('updatedProduct', (event) => {
    createInputFile();
    coverImage();
    if (event && event.product_minimal_quantity) {
      const minimalProductQuantity = parseInt(event.product_minimal_quantity, 10);
      const quantityInputSelector = prestashop.selectors.quantityWanted;
      const quantityInput = $(quantityInputSelector);

      // @see http://www.virtuosoft.eu/code/bootstrap-touchspin/ about Bootstrap TouchSpin
      quantityInput.trigger('touchspin.updatesettings', {
        min: minimalProductQuantity,
      });
    }
    imageScrollBox();
    $($(prestashop.themeSelectors.product.activeTabs).attr('href')).addClass('active').removeClass('fade');
    $(prestashop.themeSelectors.product.imagesModal).replaceWith(event.product_images_modal);

    const productSelect = new ProductSelect();
    productSelect.init();
  });
});
