jQuery(document).ready(function($)
{
    $('.pagination-item img, .carousel.carousel-stage.carousel-stage-small li img, .carousel.carousel-stage.carousel-stage-popup img, .carousel.carousel-navigation.carousel-navigation-popup .pagination-item img').each(function(){
        if ( $(this).height() > $(this).width()) {
            $(this).addClass( "heigherImg" );
        }
        else{
            $(this).addClass( "widtherImg" );
        }
    });
    $("#buzz-target-plugin .column.half:nth-child(even)").addClass("even");
    $("#buzz-target-plugin .column.half:nth-child(odd)").addClass("odd");
    $("#buzz-target-plugin .theme-table tr:nth-child(odd)").addClass("odd");
    $("#buzz-target-plugin .main-info table:last-child").addClass("last");
    if($("#buzz-target-plugin .main-info table").length == 1){
        $("#buzz-target-plugin .main-info table").css('width', '54%');
    }
    if($('.carousel-navigation.carousel-navigation-popup ul li').size()<=5){
        $('.carousel-navigation-popup').parent().find($('a')).addClass('hidden');
    };

    if($('body').hasClass('inFocus')){
        var footer = $('#footer');
        var subfooter = $('#sub_footer');
        $('body').remove("#footer");
        $('body').remove("#sub_footer");
        footer.insertAfter("#content");
        subfooter.insertAfter("#footer");
        if($('body').hasClass('full_width')){
            $('#sidebar').remove();
        }
        else if($('body').hasClass('right_sidebar')){
            var sidebar = $('#sidebar');
            $('#sidebar').remove();
            sidebar.insertAfter($('#main'));
        }
    }


//    var propertyImages = otherImages;
//
//    var imageNo = 0;
//
//    var propertyImagesLength = propertyImages.length;
//
//    // Next image
//    $('a[id=bt-next-property-image]').click(function (e)
//    {
//        e.preventDefault();
//
//        // Get the image
//        var image = getImage('next');
//
//        $('div[id=bt-single-property-image] > img').attr('src', image);
//    });
//
//    // Previous image
//    $('a[id=bt-previous-property-image]').click(function (e)
//    {
//        e.preventDefault();
//
//        // Get the image
//        var image = getImage('previous');
//
//        $('div[id=bt-single-property-image] > img').attr('src', image);
//    });
//
//    // Return image depending on request type (next or prev)
//    function getImage(type)
//    {
//        var image;
//
//        if (type === 'next')
//        {
//            var nextImageNo = imageNo + 1;
//
//            if (nextImageNo >= propertyImagesLength)
//            {
//                nextImageNo = propertyImagesLength - 1;
//            }
//            else
//            {
//                nextImageNo = nextImageNo;
//            }
//
//            imageNo = nextImageNo;
//
//            image = propertyImages[nextImageNo];
//        }
//        else if (type === 'previous')
//        {
//            var prevImageNo = imageNo - 1;
//
//            if (prevImageNo <= 0)
//            {
//                prevImageNo = 0;
//            }
//            else
//            {
//                prevImageNo = prevImageNo;
//            }
//
//            imageNo = prevImageNo;
//
//            image = propertyImages[prevImageNo];
//        }
//
//        var displayImgNo = imageNo + 1;
//
//        if (displayImgNo > propertyImagesLength)
//            displayImgNo--;
//
//        $('div[id=bt-single-property-image-top-bar] > p').text('Images ' + displayImgNo + '/' + propertyImagesLength);
//
//        return image;
//    }

    /* Display the the image with larger dimensions when clicked */

    $('div[id=bt-single-property-image] > img').click(function ()
    {
        var $dialog = $(this).clone();
        $dialog.dialog({
            resizeable: true,
            modal: true,
            width: 'auto',
            closeOnEscape: true,
            appendTo: 'body',
            dialogClass: 'bt-property-image-dialog',
            open: function( event, ui )
            {
                $('div.ui-dialog-titlebar').remove();
                $('<div id="bt-property-close-dialog">&nbsp;<a href="#"></a></div>')
                    .insertBefore('div.bt-property-image-dialog > img');
                $('div[id=bt-property-close-dialog] > a').click(function ()
                {
                    var isOpen = ($dialog).dialog('isOpen');
                    if (isOpen)
                    {
                        $($dialog).dialog('close');
                    }
                });
            }
        });
    });
});