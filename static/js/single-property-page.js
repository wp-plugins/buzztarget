jQuery(document).ready(function($)
{
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