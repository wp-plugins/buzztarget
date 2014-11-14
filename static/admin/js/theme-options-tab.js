jQuery(document).ready(function($)
{
    // Initially check the value of the advanced search
    // and send off to be checked to see if the css colors table
    // needs to be hidden or displayed.
    var status = $('input[name="advanced_search[status]"]:checked').val();
    handleFieldsForStatus(status);

    /*
        When the advanced search status changes
    */
    $('input[name="advanced_search[status]"]').change(function()
    {
        var status = this.value;

        handleFieldsForStatus(status);
    });

    /*
        Check status of advanced search css colors table 
        and send off to be hidden or shown if required.
    */
    function handleFieldsForStatus(status)
    {
        var advancedSearchButtonCSSTable = 'table.advanced-search-fields';
        if (status === 'on')
        {
            toggleElement(advancedSearchButtonCSSTable, 'show');
        }
        else if (status === 'off')
        {
            toggleElement(advancedSearchButtonCSSTable, 'hide');
        }
    }

    /*
        Show or hide the element if it isn't already in that state.
    */
    function toggleElement(element, state)
    {
        if (state === 'show')
        {
            if ($(element).is(':hidden'))
            {
                $(element).show();
            }
        }
        else if (state === 'hide')
        {
            if ($(element).is(':visible'))
            {
                $(element).hide();
            }
        }
    }
});