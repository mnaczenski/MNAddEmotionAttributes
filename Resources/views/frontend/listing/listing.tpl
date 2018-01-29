{extends file="parent:frontend/listing/listing.tpl"}

{block name="frontend_listing_emotions"}
    <div class="content--emotions">

        {foreach $emotions as $emotion}
            {if $emotion.attributes.mnposition != 2}
                {if $emotion.fullscreen == 1}
                    {$fullscreen = true}
                {/if}

                <div class="emotion--wrapper"
                     data-controllerUrl="{url module=widgets controller=emotion action=index emotionId=$emotion.id controllerName=$Controller}"
                     data-availableDevices="{$emotion.devices}">
                </div>
            {/if}
        {/foreach}

        {block name="frontend_listing_list_promotion_link_show_listing"}
            {$showListingCls = "emotion--show-listing"}

            {foreach $showListingDevices as $device}
                {$showListingCls = "{$showListingCls} hidden--{$emotionViewports[$device]}"}
            {/foreach}

            {if $showListingButton}
                <div class="{$showListingCls}{if $fullscreen} is--align-center{/if}">
                    <a href="{url controller='cat' sPage=1 sCategory=$sCategoryContent.id}" title="{$sCategoryContent.name|escape}" class="link--show-listing{if $fullscreen} btn is--primary{/if}">
                        {s name="ListingActionsOffersLink"}Weitere Artikel in dieser Kategorie &raquo;{/s}
                    </a>
                </div>
            {/if}
        {/block}
    </div>
{/block}

{block name="frontend_listing_listing_wrapper"}
    {$smarty.block.parent}
    {foreach $emotions as $emotion}
        {if $emotion.attributes.mnposition == 2}
            {if $emotion.fullscreen == 1}
                {$fullscreen = true}
            {/if}

            <div class="emotion--wrapper"
                 data-controllerUrl="{url module=widgets controller=emotion action=index emotionId=$emotion.id controllerName=$Controller}"
                 data-availableDevices="{$emotion.devices}">
            </div>
        {/if}
    {/foreach}
{/block}