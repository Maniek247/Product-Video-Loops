import $ from 'jquery';
import prestashop from 'prestashop';
// eslint-disable-next-line
import "velocity-animate";
import updateSources from './components/update-sources';
import ProductMinitature from './components/product-miniature';

const move = (direction) => {
  const THUMB_MARGIN = 20;
  const $thumbnails = $('.js-qv-product-images');
  const thumbHeight = $('.js-qv-product-images li img').height() + THUMB_MARGIN;
  const currentPosition = $thumbnails.position().top;
  $thumbnails.velocity(
    {
      translateY:
        direction === 'up'
          ? currentPosition + thumbHeight
          : currentPosition - thumbHeight,
    },
    () => {
      if ($thumbnails.position().top >= 0) {
        $('.arrow-up').css('opacity', '.2');
      } else if (
        $thumbnails.position().top + $thumbnails.height()
        <= $('.js-qv-mask').height()
      ) {
        $('.arrow-down').css('opacity', '.2');
      }
    },
  );
};

const productConfig = (qv) => {
  const MAX_THUMBS = 4;
  const $arrows = $(prestashop.themeSelectors.product.arrows);
  const $thumbnails = qv.find('.js-qv-product-images');

  $(prestashop.themeSelectors.product.thumb).on('click', (event) => {
    event.preventDefault();

    $(prestashop.themeSelectors.product.thumb).removeClass('selected');
    $(event.currentTarget).addClass('selected');

    const $thumbContainer = $(event.currentTarget).closest('.thumb-container');
    const isVideo = $thumbContainer.data('is-video') === 1;
    const videoUrl = $(event.currentTarget).data('video-url');
    const largeSrc = $(event.target).data('image-large-src');
    const altText = $(event.target).attr('alt');
    const titleText = $(event.target).attr('title');
    const sources = $(event.target).data('image-large-sources');

    let $cover = $(prestashop.themeSelectors.product.cover);

    if (isVideo) {
      if ($cover.is('img')) {
        const $video = $('<video>', {
          class: 'js-qv-product-cover product-cover-modal img-fluid',
          autoplay: true,
          playsinline: true,
          muted: true,
          loop: true,
        });
        $video.attr('src', videoUrl);
        $cover.replaceWith($video);
        $cover = $video;
      } else {
        $cover.attr('src', videoUrl);
      }
    } else {
      if ($cover.is('video')) {
        const $img = $('<img>', {
          class: 'js-qv-product-cover product-cover-modal img-fluid',
        });
        $img.attr('src', largeSrc);
        $img.attr('alt', altText);
        $img.attr('title', titleText);
        $cover.replaceWith($img);
        $cover = $img;
      } else {
        $cover.attr('src', largeSrc);
        $cover.attr('alt', altText);
        $cover.attr('title', titleText);
      }
    }
    updateSources($cover, sources);
  });

  if ($thumbnails.find('li').length <= MAX_THUMBS) {
    $arrows.hide();
  } else {
    $arrows.on('click', (event) => {
      if (
        $(event.target).hasClass('arrow-up')
        && $('.js-qv-product-images').position().top < 0
      ) {
        move('up');
        $(prestashop.themeSelectors.arrowDown).css('opacity', '1');
      } else if (
        $(event.target).hasClass('arrow-down')
        && $thumbnails.position().top + $thumbnails.height() > $('.js-qv-mask').height()
      ) {
        move('down');
        $(prestashop.themeSelectors.arrowUp).css('opacity', '1');
      }
    });
  }

  qv.find(prestashop.selectors.quantityWanted).TouchSpin({
    verticalbuttons: true,
    verticalupclass: 'material-icons touchspin-up',
    verticaldownclass: 'material-icons touchspin-down',
    buttondown_class: 'btn btn-touchspin js-touchspin',
    buttonup_class: 'btn btn-touchspin js-touchspin',
    min: 1,
    max: 1000000,
  });

  $(prestashop.themeSelectors.touchspin).off('touchstart.touchspin');
};

const parseSearchUrl = function (event) {
  if (event.target.dataset.searchUrl !== undefined) {
    return event.target.dataset.searchUrl;
  }
  if ($(event.target).parent()[0].dataset.searchUrl === undefined) {
    throw new Error('Can not parse search URL');
  }
  return $(event.target).parent()[0].dataset.searchUrl;
};

function updateProductListDOM(data) {
  $(prestashop.themeSelectors.listing.searchFilters).replaceWith(
    data.rendered_facets,
  );
  $(prestashop.themeSelectors.listing.activeSearchFilters).replaceWith(
    data.rendered_active_filters,
  );
  $(prestashop.themeSelectors.listing.listTop).replaceWith(
    data.rendered_products_top,
  );

  const renderedProducts = $(data.rendered_products);
  const productSelectors = $(prestashop.themeSelectors.listing.product);

  if (productSelectors.length > 0) {
    productSelectors.removeClass().addClass(productSelectors.first().attr('class'));
  } else {
    productSelectors.removeClass().addClass(renderedProducts.first().attr('class'));
  }

  $(prestashop.themeSelectors.listing.list).replaceWith(renderedProducts);

  $(prestashop.themeSelectors.listing.listBottom).replaceWith(
    data.rendered_products_bottom,
  );
  if (data.rendered_products_header) {
    $(prestashop.themeSelectors.listing.listHeader).replaceWith(
      data.rendered_products_header,
    );
  }

  const productMinitature = new ProductMinitature();
  productMinitature.init();
}

$(document).ready(() => {
  $('body').on(
    'change',
    `${prestashop.themeSelectors.listing.searchFilters} input[data-search-url]`,
    (event) => {
      prestashop.emit('updateFacets', parseSearchUrl(event));
    },
  );

  $('body').on(
    'click',
    prestashop.themeSelectors.listing.searchFiltersClearAll,
    (event) => {
      prestashop.emit('updateFacets', parseSearchUrl(event));
    },
  );

  $('body').on('click', prestashop.themeSelectors.listing.searchLink, (event) => {
    event.preventDefault();
    prestashop.emit(
      'updateFacets',
      $(event.target)
        .closest('a')
        .get(0).href,
    );
  });

  window.addEventListener('popstate', (e) => {
    if (e.state && e.state.current_url) {
      window.location.href = e.state.current_url;
    }
  });

  $('body').on(
    'change',
    `${prestashop.themeSelectors.listing.searchFilters} select`,
    (event) => {
      const form = $(event.target).closest('form');
      prestashop.emit('updateFacets', `?${form.serialize()}`);
    },
  );

  prestashop.on('updateProductList', (data) => {
    updateProductListDOM(data);
    window.scrollTo(0, 0);
  });

  prestashop.on('clickQuickView', (elm) => {
    const data = {
      action: 'quickview',
      id_product: elm.dataset.idProduct,
      id_product_attribute: elm.dataset.idProductAttribute,
    };
    $.post(prestashop.urls.pages.product, data, null, 'json')
      .then((resp) => {
        $('body').append(resp.quickview_html);
        const productModal = $(
          `#quickview-modal-${resp.product.id}-${resp.product.id_product_attribute}`,
        );
        productModal.modal('show');
        productConfig(productModal);
        productModal.on('hidden.bs.modal', () => {
          productModal.remove();
        });
      })
      .fail((resp) => {
        prestashop.emit('handleError', {
          eventType: 'clickQuickView',
          resp,
        });
      });
  });

  $('body').on(
    'click',
    prestashop.themeSelectors.listing.searchFilterToggler,
    () => {
      $(prestashop.themeSelectors.listing.searchFiltersWrapper).removeClass(
        'hidden-sm-down',
      );
      $(prestashop.themeSelectors.contentWrapper).addClass('hidden-sm-down');
      $(prestashop.themeSelectors.footer).addClass('hidden-sm-down');
    },
  );
  $(`${prestashop.themeSelectors.listing.searchFilterControls} ${prestashop.themeSelectors.clear}`)
    .on('click', () => {
      $(prestashop.themeSelectors.listing.searchFiltersWrapper).addClass(
        'hidden-sm-down',
      );
      $(prestashop.themeSelectors.contentWrapper).removeClass('hidden-sm-down');
      $(prestashop.themeSelectors.footer).removeClass('hidden-sm-down');
    });
  $(`${prestashop.themeSelectors.listing.searchFilterControls} .ok`).on(
    'click',
    () => {
      $(prestashop.themeSelectors.listing.searchFiltersWrapper).addClass(
        'hidden-sm-down',
      );
      $(prestashop.themeSelectors.contentWrapper).removeClass('hidden-sm-down');
      $(prestashop.themeSelectors.footer).removeClass('hidden-sm-down');
    },
  );

  const parseSearchUrl = function (event) {
    if (event.target.dataset.searchUrl !== undefined) {
      return event.target.dataset.searchUrl;
    }
    if ($(event.target).parent()[0].dataset.searchUrl === undefined) {
      throw new Error('Can not parse search URL');
    }
    return $(event.target).parent()[0].dataset.searchUrl;
  };

  function updateProductListDOM(data) {
    $(prestashop.themeSelectors.listing.searchFilters).replaceWith(
      data.rendered_facets,
    );
    $(prestashop.themeSelectors.listing.activeSearchFilters).replaceWith(
      data.rendered_active_filters,
    );
    $(prestashop.themeSelectors.listing.listTop).replaceWith(
      data.rendered_products_top,
    );

    const renderedProducts = $(data.rendered_products);
    const productSelectors = $(prestashop.themeSelectors.listing.product);

    if (productSelectors.length > 0) {
      productSelectors.removeClass().addClass(productSelectors.first().attr('class'));
    } else {
      productSelectors.removeClass().addClass(renderedProducts.first().attr('class'));
    }

    $(prestashop.themeSelectors.listing.list).replaceWith(renderedProducts);

    $(prestashop.themeSelectors.listing.listBottom).replaceWith(
      data.rendered_products_bottom,
    );
    if (data.rendered_products_header) {
      $(prestashop.themeSelectors.listing.listHeader).replaceWith(
        data.rendered_products_header,
      );
    }

    const productMinitature = new ProductMinitature();
    productMinitature.init();
  }

  $('body').on(
    'change',
    `${prestashop.themeSelectors.listing.searchFilters} input[data-search-url]`,
    (event) => {
      prestashop.emit('updateFacets', parseSearchUrl(event));
    },
  );

  $('body').on(
    'click',
    prestashop.themeSelectors.listing.searchFiltersClearAll,
    (event) => {
      prestashop.emit('updateFacets', parseSearchUrl(event));
    },
  );

  $('body').on('click', prestashop.themeSelectors.listing.searchLink, (event) => {
    event.preventDefault();
    prestashop.emit(
      'updateFacets',
      $(event.target)
        .closest('a')
        .get(0).href,
    );
  });

  window.addEventListener('popstate', (e) => {
    if (e.state && e.state.current_url) {
      window.location.href = e.state.current_url;
    }
  });

  $('body').on(
    'change',
    `${prestashop.themeSelectors.listing.searchFilters} select`,
    (event) => {
      const form = $(event.target).closest('form');
      prestashop.emit('updateFacets', `?${form.serialize()}`);
    },
  );

  prestashop.on('updateProductList', (data) => {
    updateProductListDOM(data);
    window.scrollTo(0, 0);
  });
});
