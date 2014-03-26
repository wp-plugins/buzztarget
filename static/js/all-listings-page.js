jQuery(document).ready(function($)
{
    $('select[id=listCount]').change(function (e) {
        $.ajax( {
            url : window.location.pathname,
            data : { limit_per_page: $(this).val() },
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
        var str = window.location.search;
        str = replaceQueryParam('sort_by', $(this).val(), str);
        str = deleteQueryParam('current_page', str);
        window.location = window.location.pathname + str;
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

    function replaceQueryParam(param, newval, search) {
        var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
        var query = search.replace(regex, "$1").replace(/&$/, '');
        return (query.length > 2 ? query + "&" : "?") + param + "=" + newval;
    }
    function deleteQueryParam(param, search) {
        var regex = new RegExp("([?;&])" + param + "[^&;]*[;&]?");
        var query = search.replace(regex, "$1").replace(/&$/, '');
        return (query.length > 2 ? query + "&" : "?");
    }

});


