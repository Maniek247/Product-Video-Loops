{** themes/classic/templates/catalog/_partials/product-images-modal.tpl **}
<div class="modal fade js-product-images-modal" id="product-modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        {assign var=imagesCount value=$product.images|count}
        <figure>
          {if $product.default_image}
            {if isset($product.default_image.is_video) && $product.default_image.is_video == true}
                <video
                  class="js-modal-product-cover product-cover-modal"
                  src="{$product.default_image.video_url}"
                  autoplay
                  muted
                  loop
                >
                </video>
              {else}
                <picture>
                {if !empty($product.default_image.bySize.large_default.sources.avif)}
                  <source srcset="{$product.default_image.bySize.large_default.sources.avif}" type="image/avif">
                {/if}
                {if !empty($product.default_image.bySize.large_default.sources.webp)}
                  <source srcset="{$product.default_image.bySize.large_default.sources.webp}" type="image/webp">
                {/if}
                <img
                  class="js-modal-product-cover product-cover-modal"
                  width="{$product.default_image.bySize.large_default.width}"
                  src="{$product.default_image.bySize.large_default.url}"
                  {if !empty($product.default_image.legend)}
                    alt="{$product.default_image.legend}"
                    title="{$product.default_image.legend}"
                  {else}
                    alt="{$product.name}"
                  {/if}
                  height="{$product.default_image.bySize.large_default.height}"
                >
              </picture>
            {/if}
          {else}
            <picture>
              {if !empty($urls.no_picture_image.bySize.large_default.sources.avif)}
                <source srcset="{$urls.no_picture_image.bySize.large_default.sources.avif}" type="image/avif">
              {/if}
              {if !empty($urls.no_picture_image.bySize.large_default.sources.webp)}
                <source srcset="{$urls.no_picture_image.bySize.large_default.sources.webp}" type="image/webp">
              {/if}
              <img src="{$urls.no_picture_image.bySize.large_default.url}" loading="lazy" width="{$urls.no_picture_image.bySize.large_default.width}" height="{$urls.no_picture_image.bySize.large_default.height}" />
            </picture>
          {/if}
          <figcaption class="image-caption">
            {block name='product_description_short'}
              <div id="product-description-short">{$product.description_short nofilter}</div>
            {/block}
          </figcaption>
        </figure>
        <aside id="thumbnails" class="thumbnails js-thumbnails text-sm-center">
          {block name='product_images'}
            <div class="js-modal-mask mask {if $imagesCount <= 5} nomargin {/if}">
              <ul class="product-images js-modal-product-images">
                {foreach from=$product.images item=image}
                  <li
                    class="thumb-container js-thumb-container"
                    {if isset($image.is_video) && $image.is_video == true} data-is-video="1"{/if}
                  >
                    {if isset($image.is_video) && $image.is_video == true}
                      <video
                        class="thumb js-modal-thumb"
                        src="{$image.video_url}"
                        autoplay
                        muted
                        loop
                      >
                      </video>
                  {else}
                      <picture>
                        {if !empty($image.medium.sources.avif)}
                          <source srcset="{$image.medium.sources.avif}" type="image/avif">
                        {/if}
                        {if !empty($image.medium.sources.webp)}
                          <source srcset="{$image.medium.sources.webp}" type="image/webp">
                        {/if}
                        <img
                          data-image-large-src="{$image.large.url}"
                          {if !empty($image.large.sources)}
                            data-image-large-sources="{$image.large.sources|@json_encode}"
                          {/if}
                          class="thumb js-modal-thumb"
                          src="{$image.medium.url}"
                          {if !empty($image.legend)}
                            alt="{$image.legend}"
                            title="{$image.legend}"
                          {else}
                            alt="{$product.name}"
                          {/if}
                          width="{$image.medium.width}"
                          height="148"
                        >
                      </picture>
                    {/if}
                  </li>
                {/foreach}
              </ul>
            </div>
          {/block}
          {if $imagesCount > 5}
            <div class="arrows js-modal-arrows">
              <i class="material-icons arrow-up js-modal-arrow-up">&#xE5C7;</i>
              <i class="material-icons arrow-down js-modal-arrow-down">&#xE5C5;</i>
            </div>
          {/if}
        </aside>
      </div>
    </div>
  </div>
</div>
