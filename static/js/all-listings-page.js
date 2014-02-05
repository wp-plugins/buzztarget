jQuery(document).ready(function($)
{
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
});