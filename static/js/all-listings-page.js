jQuery(document).ready(function($)
{

    $('.number-per-row-3 .list-item:nth-child(3n)').addClass('nth-child-3np');
    $('.number-per-row-4 .list-item:nth-child(4n)').addClass('nth-child-4np');
    $('.number-per-row-5 .list-item:nth-child(5n)').addClass('nth-child-5np');
    $('.number-per-row-6 .list-item:nth-child(6n)').addClass('nth-child-6np');
    $('#buzz-target-plugin .grid-view .list-item:nth-child(3n)').not('#buzz-target-plugin .content.broker-listings .grid-view .list-item').not('#buzz-target-plugin .content.featured .grid-view .list-item').addClass('nth-child-3np');
    $('#buzz-target-plugin .grid-view .list-item:nth-child(odd)').addClass('odd');


    if ($.browser.msie) {

        $('input[placeholder]').each(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.val(input.attr('placeholder'));
            }
            $(input).focus(function() {
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            });
            $(input).blur(function() {
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.val(input.attr('placeholder'));
                }
            });
        });

        $('input[name=advanced_search_submit]').click(function(e){
            $('#adv_search_form').find('[placeholder]').each(function() {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                }
            })
        });
    }

    $('select[id=listCount]').change(function (e) {
        get_content(window.location.pathname);
    });

    $('select[id=sortBy]').change(function (e) {
        get_content(window.location.pathname);
    });
    /* Hide advanced search by default */
    if ($('div[id=bt-advanced-search]').is(':visible'))
    {
        $('div[id=bt-advanced-search]').hide();
        $('img[id=bt-toggle-advanced-search]').attr('src', showAdvancedSearchImg);
    }
    /* When the advanced search bar arrows are clicked */
    $('img[id=bt-toggle-advanced-search]').click(function ()
    {
        if ($('div[id=bt-advanced-search]').is(':visible'))
        {
            $('div[id=bt-advanced-search]').hide();
            $('img[id=bt-toggle-advanced-search]').attr('src', showAdvancedSearchImg);
        }
        else if ($('div[id=bt-advanced-search]').is(':hidden'))
        {
            $('div[id=bt-advanced-search]').show();
            $('img[id=bt-toggle-advanced-search]').attr('src', hideAdvancedSearchImg);
        }
    });
    setTimeout(function() {
        var filterHeight = $('.search-filter').innerHeight();
        var filterWidth = $('.search-filter').innerWidth();
        if($('.search-filter').length == 0){
            $('.search-map').css("right", "0");
        }
        else{
            $('.search').height(filterHeight + "px");
        }

    }, 100);
    $('.list-item .image img').each(function(){
        if ( $(this).height() > $(this).width()) {
            $(this).addClass( "heigherImg" );
        }
        else{
            $(this).addClass( "widtherImg" );
        }
    });

});

function get_content(url){
    var sendData = new Object();
    sendData.sort_by = jQuery('#sortBy').val();
    sendData.limit_per_page  = jQuery('#listCount').val();
    if(getQueryParam("search") == 'true'){
        sendData.search = getQueryParam("search");
    }

    jQuery.ajax( {
            url : url,
            data : sendData,
            dataType : 'html'}
    ).success(function( html ) {
            if(jQuery(html).find("#buzz-target-plugin .content").hasClass('broker-listings')){
                htm = jQuery(html).find("#buzz-target-plugin .content.broker-listings .grid-view").parent().html();
                jQuery('#buzz-target-plugin .content.broker-listings .grid-view .list-item:nth-child(3n)').addClass('nth-child-3np');
                jQuery('#buzz-target-plugin .grid-view .list-item:nth-child(odd)').addClass('odd');

                jQuery("#buzz-target-plugin .content.broker-listings .grid-view").parent().html(htm);

                htm = jQuery(html).find("#buzz-target-plugin .content.broker-listings").next(".content.pagination").html();

                if(htm != undefined){
                    jQuery("#buzz-target-plugin .content.broker-listings").next(".content.pagination").remove();
                    jQuery('<div class="section content pagination">' + htm + '</div>').insertAfter(jQuery("#buzz-target-plugin .content.broker-listings"));
                }
                else{
                    jQuery("#buzz-target-plugin .content.broker-listings").next(".content.pagination").remove();
                }
            }
            else{
                htm = jQuery(html).find("#buzz-target-plugin .grid-view").parent().html();
                jQuery("#buzz-target-plugin .grid-view").parent().html(htm);
                jQuery('#buzz-target-plugin .grid-view .list-item:nth-child(3n)').addClass('nth-child-3np');
                jQuery('#buzz-target-plugin .grid-view .list-item:nth-child(odd)').addClass('odd');
                htm =jQuery(html).find("#buzz-target-plugin .list-view").parent().html();
                jQuery("#buzz-target-plugin .list-view").parent().html(htm);

                htm = jQuery(html).find("#buzz-target-plugin .content.pagination").html();
                if(htm != undefined){
                    jQuery("#buzz-target-plugin .content.pagination").remove();
                    jQuery("#buzz-target-plugin").append('<div class="section content pagination">' + htm + '</div>');
                }
                else{
                    jQuery("#buzz-target-plugin .content.pagination").remove();
                }
            }

            var filterHeight = jQuery('.search-filter').innerHeight();
            if(jQuery('.search-filter').length == 0){
                jQuery('.search-map').css("right", "0");
            }
            else{
                jQuery('.search').height(filterHeight + "px");
            }
            jQuery('.list-item .image img').each(function(){
                if ( jQuery(this).height() > jQuery(this).width()) {
                    jQuery(this).addClass( "heigherImg" );
                }
                else{
                    jQuery(this).addClass( "widtherImg" );
                }
            });

        });
}
function getQueryParam(param) {
    var result =  window.location.search.match(
        new RegExp("(\\?|&)" + param + "(\\[\\])?=([^&]*)")
    );
    return result ? result[3] : false;
}