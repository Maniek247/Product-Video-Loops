{** themes/classic/templates/catalog/_partials/product-cover-thumbnails.tpl **}
<div class="images-container js-images-container">
  {block name='product_cover'}
    <div class="product-cover">
      {if $product.default_image}
        {if isset($product.default_image.is_video) && $product.default_image.is_video == true}
          <video
            class="js-qv-product-cover product-cover-video img-fluid"
            src="{$product.default_image.video_url}"
            data-video-url="{$product.default_image.video_url}"
            autoplay
            playsinline
            muted
            loop
            data-is-video="1"
          >
            Your browser does not support the video tag.
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
              class="js-qv-product-cover img-fluid"
              src="{$product.default_image.bySize.large_default.url}"
              {if !empty($product.default_image.legend)}
                alt="{$product.default_image.legend}"
                title="{$product.default_image.legend}"
              {else}
                alt="{$product.name}"
              {/if}
              loading="lazy"
              width="{$product.default_image.bySize.large_default.width}"
              height="{$product.default_image.bySize.large_default.height}"
            >
          </picture>
        {/if}
        <div class="layer hidden-sm-down" data-toggle="modal" data-target="#product-modal">
          <i class="material-icons zoom-in">search</i>
        </div>
      {else}
        <picture>
          {if !empty($urls.no_picture_image.bySize.large_default.sources.avif)}
            <source srcset="{$urls.no_picture_image.bySize.large_default.sources.avif}" type="image/avif">
          {/if}
          {if !empty($urls.no_picture_image.bySize.large_default.sources.webp)}
            <source srcset="{$urls.no_picture_image.bySize.large_default.sources.webp}" type="image/webp">
          {/if}
          <img
            class="img-fluid"
            src="{$urls.no_picture_image.bySize.large_default.url}"
            loading="lazy"
            width="{$urls.no_picture_image.bySize.large_default.width}"
            height="{$urls.no_picture_image.bySize.large_default.height}"
          >
        </picture>
      {/if}
    </div>
  {/block}

  {block name='product_images'}
    <div class="js-qv-mask mask">
      <ul class="product-images js-qv-product-images">
        {foreach from=$product.images item=image}
          <li
            class="thumb-container js-thumb-container"
            {if isset($image.is_video) && $image.is_video == true} data-is-video="1"{/if}
          >
            {if isset($image.is_video) && $image.is_video == true}
              <video
                class="thumb js-thumb"
                src="{$image.video_url}"
                data-video-url="{$image.video_url}"
                autoplay
                playsinline
                muted
                loop
                width="{$image.bySize.small_default.width}"
                height="{$image.bySize.small_default.height}"
              >
                Your browser does not support the video tag.
              </video>
            {else}
              <picture>
                {if !empty($image.bySize.small_default.sources.avif)}
                  <source srcset="{$image.bySize.small_default.sources.avif}" type="image/avif">
                {/if}
                {if !empty($image.bySize.small_default.sources.webp)}
                  <source srcset="{$image.bySize.small_default.sources.webp}" type="image/webp">
                {/if}
                <img
                  class="thumb js-thumb {if $image.id_image == $product.default_image.id_image} selected js-thumb-selected {/if}"
                  data-image-medium-src="{$image.bySize.medium_default.url}"
                  {if !empty($image.bySize.medium_default.sources)}
                    data-image-medium-sources="{$image.bySize.medium_default.sources|@json_encode}"
                  {/if}
                  data-image-large-src="{$image.bySize.large_default.url}"
                  {if !empty($image.bySize.large_default.sources)}
                    data-image-large-sources="{$image.bySize.large_default.sources|@json_encode}"
                  {/if}
                  src="{$image.bySize.small_default.url}"
                  {if !empty($image.legend)}
                    alt="{$image.legend}"
                    title="{$image.legend}"
                  {else}
                    alt="{$product.name}"
                  {/if}
                  loading="lazy"
                  width="{$image.bySize.small_default.width}"
                  height="{$image.bySize.small_default.height}"
                >
              </picture>
            {/if}
          </li>
        {/foreach}
      </ul>
    </div>
  {/block}
  {hook h='displayAfterProductThumbs' product=$product}
</div>
