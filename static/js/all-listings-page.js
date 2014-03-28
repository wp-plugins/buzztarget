jQuery(document).ready(function($)
{
    $('select[id=listCount]').change(function (e) {
        $.ajax( {
            url : window.location.pathname,
            data : { sort_by: $('#sortBy').val(), limit_per_page: $(this).val() },
            dataType : 'html'}
        ).success(function( html ) {
            htm = $(html).find("#buzz-target-plugin .grid-view").parent().html();
            $("#buzz-target-plugin .grid-view").parent().html(htm);
            htm = $(html).find("#buzz-target-plugin .list-view").parent().html();
            $("#buzz-target-plugin .list-view").parent().html(htm);

            htm = $(html).find("#buzz-target-plugin .content.pagination").html();
            if(htm != undefined){
                $("#buzz-target-plugin .content.pagination").remove();
                $("#buzz-target-plugin").append('<section class="content pagination">' + htm + '</section>');
            }
            else{
                $("#buzz-target-plugin .content.pagination").remove();
            }

            var filterHeight = $('.search-filter').innerHeight();
            if($('.search-filter').length == 0){
                $('.search-map').css("right", "0");
            }
            else{
                $('.search').height(filterHeight + "px");
            }

        });
    });

    $('select[id=sortBy]').change(function (e) {
        $.ajax( {
                url : window.location.pathname,
                data : { sort_by: $(this).val(), limit_per_page: $('#listCount').val() },
                dataType : 'html'}
        ).success(function( html ) {
                htm = $(html).find("#buzz-target-plugin .grid-view").parent().html();
                $("#buzz-target-plugin .grid-view").parent().html(htm);
                htm = $(html).find("#buzz-target-plugin .list-view").parent().html();
                $("#buzz-target-plugin .list-view").parent().html(htm);

                htm = $(html).find("#buzz-target-plugin .content.pagination").html();
                if(htm != undefined){
                    $("#buzz-target-plugin .content.pagination").remove();
                    $("#buzz-target-plugin").append('<section class="content pagination">' + htm + '</section>');
                }
                else{
                    $("#buzz-target-plugin .content.pagination").remove();
                }

                var filterHeight = $('.search-filter').innerHeight();
                if($('.search-filter').length == 0){
                    $('.search-map').css("right", "0");
                }
                else{
                    $('.search').height(filterHeight + "px");
                }

            });
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
    console.log(url);
    jQuery.ajax( {
            url : url,
            data : { sort_by: jQuery('#sortBy').val(), limit_per_page: jQuery('#listCount').val() },
            dataType : 'html'}
    ).success(function( html ) {
            htm = jQuery(html).find("#buzz-target-plugin .grid-view").parent().html();
            console.log(html);
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
