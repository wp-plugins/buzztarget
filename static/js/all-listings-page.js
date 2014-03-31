jQuery(document).ready(function($)
{
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
            htm = jQuery(html).find("#buzz-target-plugin .grid-view").parent().html();
            jQuery("#buzz-target-plugin .grid-view").parent().html(htm);
            htm =jQuery(html).find("#buzz-target-plugin .list-view").parent().html();
            jQuery("#buzz-target-plugin .list-view").parent().html(htm);

            htm = jQuery(html).find("#buzz-target-plugin .content.pagination").html();
            if(htm != undefined){
                jQuery("#buzz-target-plugin .content.pagination").remove();
                jQuery("#buzz-target-plugin").append('<section class="content pagination">' + htm + '</section>');
            }
            else{
                jQuery("#buzz-target-plugin .content.pagination").remove();
            }

            var filterHeight = jQuery('.search-filter').innerHeight();
            if(jQuery('.search-filter').length == 0){
                jQuery('.search-map').css("right", "0");
            }
            else{
                jQuery('.search').height(filterHeight + "px");
            }

        });
}
function getQueryParam(param) {
    var result =  window.location.search.match(
        new RegExp("(\\?|&)" + param + "(\\[\\])?=([^&]*)")
    );
    return result ? result[3] : false;
}