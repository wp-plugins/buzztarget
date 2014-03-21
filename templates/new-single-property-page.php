<?php

get_header();

$theme_options = get_option('buzztarget_theme_options');
$themeColor = $theme_options['theme_color'];
$listingDetailStyle = $theme_options['listing_detail_style'];

$properties = get_option('repl_properties');
$property_name = get_the_title();
$property_id = end(explode('/', $_SERVER['REQUEST_URI']));
$property = $properties[$property_id];

$phoneImage = plugin_dir_url(dirname(__FILE__)) . 'static/images/phone.png';

$otherImages = array();
foreach ($property['ListingImages'] as $propertyThumbnail):
    if (isset($propertyThumbnail['AttachmentPath'])):
        $otherImages[] = $propertyThumbnail['AttachmentPath'];
    endif;
endforeach;

$currentImage = 1;
$imagesCount = count($otherImages);
?>

<div class="container">
    <?php
        if ($listingDetailStyle == 'style1'){
    ?>
        <div class="container">
            <section class="image">
                <img src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" />
                <div class="darken-bg"></div>
                <h1><?php echo $property_name; ?></h1><br/>
<!--                <p>-->
<!--                    <span>-->
<!--                        --><?//= $property['Property']['Address']['City']; ?><!--,-->
<!--                        --><?//= $property['Property']['Address']['State']; ?>
<!--                        --><?//= $property['Property']['Address']['Zip']; ?>
<!--                    </span>-->
<!--                </p>-->
            </section>
            <section class="content">
                <h3 class="for-lease">
                    <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>
                    <?php
                        $listingType = ($property['ListingType'] === 'ForSale') ? 'For Sale' : 'For Lease';
                    ?>
                    <?php echo $listingType; ?>
                </h3>
                <div class="brokers">
                    <div class="broker-item">
                        <h4>
                            <span class="name">John Smith</span>
                            <a href="#" class="icon-mail">
                                <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png" />
                            </a>
                        </h4>
                        <span>248-658-8999</span>
                    </div>
                    <div class="broker-item">
                        <h4>
                            <span class="name">Steven Conrad</span>
                            <a href="#" class="icon-mail">
                                <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png" />
                            </a>
                        </h4>
                        <span>248-658-8999</span>
                    </div>
                </div>
                <div class="overview clear">
                    <h4 class="title theme-color">Property Overview</h4>

                    <p><?= $property['PropertyDescription'];?></p>
                </div>
            </section>
            <section class="content">
                <div class="clearfix">
                    <div class="column fourty">
                        <div class="image-slider small">
                            <div class="slider-main-image">
                                <img src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" />
                                <div class="image-overlay pop-up-btn">
                                    <span class="zoom"></span>
                                </div>
                                <div class="slider-arrows">
                                    <div class="arrow left"><span>&lt;</span></div>
                                    <div class="arrow right"><span>&gt;</span></div>
                                </div>
                            </div>
                            <div class="slider-pagination">
                                <?php foreach ($otherImages as $src) {?>
                                <div class="pagination-item">
                                    <img src="http://placehold.it/100x100">
                                </div>
                                <? } ?>
                            </div>
                        </div>
                        <h4 class="title info theme-color">Attachments:</h4>
                        <ul>
                            <li><a href="#">Marketing Flyer</a></li>
                            <li><a href="#">Floor Plan</a></li>
                        </ul>
                    </div>
                    <div class="column sixty">
                        <h4 class="title info theme-color">Property Information</h4>
                        <table>
                            <tbody>
                            <tr>
                                <td>Property Type:</td>
                                <td>Retail</td>
                            </tr>
                            <tr>
                                <td>Total Building SF:</td>
                                <td>18,000</td>
                            </tr>
                            <tr>
                                <td>Occupancy:</td>
                                <td>90%</td>
                            </tr>
                            <tr>
                                <td>Year Built:</td>
                                <td>2006</td>
                            </tr>
                            <tr>
                                <td>County:</td>
                                <td>Oakland</td>
                            </tr>
                            <tr>
                                <td>Zoning:</td>
                                <td>C4-3</td>
                            </tr>
                            </tbody>
                        </table>
                        <h4 class="title info theme-color">Spaces</h4>
                        <table class="theme-table">
                            <tbody>
                            <tr>
                                <td>Space 1</td>
                                <td>$35 / PSF</td>
                                <td>2,700 SF</td>
                                <td>Retail</td>
                            </tr>
                            <tr>
                                <td>Space 2</td>
                                <td>$25 / PSF</td>
                                <td>9,700 SF</td>
                                <td>Retail</td>
                            </tr>
                            <tr>
                                <td>Space 3</td>
                                <td>$35 / PSF</td>
                                <td>1,700 SF</td>
                                <td>Retail</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            <section class="content">
                <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6304.893228812689!2d-122.30514182013415!3d37.80300668544161!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80857d8b28aaed03%3A0x71b415d535759367!2sOakland%2C+CA!5e0!3m2!1sen!2s!4v1395074246707" frameborder="0"></iframe>
            </section>
<!--            <section class="content">-->
<!--                <a href="search-list.html">Back to Search Results</a>-->
<!--            </section>-->
        </div>

        <!-- Pop-up -->
        <div class="modal" id="pop-up1">
            <div class="pop-up-bg"></div>
            <div class="pop-up-wrap">
                <div class="pop-up-close"></div>
                <div class="image-slider">
                    <div class="slider-main-image">
                        <img src="http://placehold.it/750x350">
                        <div class="slider-arrows">
                            <div class="arrow left"><span><</span></div>
                            <div class="arrow right"><span>></span></div>
                        </div>
                    </div>
                    <div class="slider-pagination">
                        <?php foreach ($otherImages as $src) {?>
                        <div class="pagination-item">
                            <img src="<?= $src?>">
                        </div>
                        <? } ?>
                    </div>
                </div>

            </div>
        </div>


        <!-- JS -->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>
            $('.pop-up-btn').on('click', function () {
                $('#pop-up1')
                    .show()
                    .addClass('visible');

            });

            $('.pop-up-close').on('click', function () {
                $('#pop-up1, #new_request')
                    .hide()
                    .removeClass('visible');
            });
        </script>
    <?php
        }
        else{
    ?>
        <section class="title">
            <h1><?php echo $property_name; ?></h1>
        </section>
        <section class="content two-columns">
            <div class="clearfix">
                <div class="column half">
                    <div class="image-slider">
                        <div class="slider-main-image">
                            <img src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" />
                            <div class="slider-arrows">
                                <div class="arrow left"><span>◄</span></div>
                                <div class="arrow right"><span>►</span></div>
                            </div>
                        </div>
                        <div class="slider-pagination">
                            <?php foreach ($otherImages as $src) {?>
                            <div class="pagination-item">
                                <img src="<?= $src?>">
                            </div>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <div class="column half">
                    <h3>
                        <strong
                            <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                            <?php
                            $listingType = ($property['ListingType'] === 'ForSale') ? 'For Sale' : 'For Lease';
                            ?>
                            <?php echo $listingType; ?>
                        </strong>
                    </h3>
                    <h4 class="title info theme-color"><?php echo $property_name; ?></h4>
                    <div class="clearfix">
                        <table class="half">
                            <tbody>
                                <?php if (isset($property['Property']['Address']['Address'])){ ?>
                                    <tr>
                                        <td colspan="2"><?= $property['Property']['Address']['Address']; ?></td>
                                    </tr>
                                <?}?>
                                <tr>
                                    <td colspan="2">
                                        <?= $property['Property']['Address']['City']; ?>,
                                        <?= $property['Property']['Address']['State']; ?>
                                        <?= $property['Property']['Address']['Zip']; ?>
                                    </td>
                                </tr>
                                <?php if (isset($property['County'])){ ?>
                                    <tr>
                                        <td>County: </td>
                                        <td><?= $property['County'] ?></td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                        <table class="half">
                            <tbody>
                            <? if (isset($property['PropertyPrice'])){ ?>
                                <tr>
                                    <td>Price:</td>
                                    <td> <?php echo '$' . number_format($property['PropertyPrice']); ?></td>
                                </tr>
                            <?}?>
                            <? if (isset($property['CapRate'])){ ?>
                                <tr>
                                    <td>CAP Rate:</td>
                                    <td><?php echo round($property['CapRate'], 2) . '%'; ?></td>
                                </tr>
                            <?}?>
                            <? if (isset($property['Noi'])){ ?>
                                <tr>
                                    <td>NOI: </td>
                                    <td><?php echo '$' . number_format($property['Noi']); ?></td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                    <? if (isset($property['PropertyDescription'])){ ?>
                        <div class="overview clear">
                            <h4 class="title info theme-color">Property Overview</h4>

                            <p class="property-description"><?=$property['PropertyDescription'];?></p>
                        </div>
                    <?}?>

                    <h4 class="title info theme-color">Property Information</h4>
                    <table>
                        <tbody>
                        <tr>
                            <td>Total Building SF:</td>
                            <td>18,000</td>
                        </tr>
                        <tr>
                            <td>Total Lat Size SF:</td>
                            <td>40,000</td>
                        </tr>
                        <tr>
                            <td>Occupancy:</td>
                            <td>90%</td>
                        </tr>
                        <tr>
                            <td>Year Built:</td>
                            <td>2006</td>
                        </tr>
                        <tr>
                            <td>Year Renovated:</td>
                            <td>2013</td>
                        </tr>
                        <tr>
                            <td>Parking Space:</td>
                            <td>100</td>
                        </tr>
                        <tr>
                            <td>Zoning:</td>
                            <td>C4-3</td>
                        </tr>
                        <tr>
                            <td>County:</td>
                            <td>Oakland</td>
                        </tr>
                        <tr>
                            <td>Traffic Count:</td>
                            <td>10,000</td>
                        </tr>
                        <tr>
                            <td>Population:</td>
                            <td>50,000</td>
                        </tr>
                        <tr>
                            <td>Income:</td>
                            <td>$40,000</td>
                        </tr>
                        </tbody>
                    </table>

                    <h4 class="title info theme-color">Spaces</h4>
                    <table class="theme-table">
                        <tbody>
                        <tr>
                            <td>Space 1</td>
                            <td>$35 / PSF</td>
                            <td>2,700 SF</td>
                            <td>Retail</td>
                        </tr>
                        <tr>
                            <td>Space 2</td>
                            <td>$25 / PSF</td>
                            <td>9,700 SF</td>
                            <td>Retail</td>
                        </tr>
                        <tr>
                            <td>Space 3</td>
                            <td>$35 / PSF</td>
                            <td>1,700 SF</td>
                            <td>Retail</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column half">
                    <section class="content">
                        <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6304.893228812689!2d-122.30514182013415!3d37.80300668544161!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80857d8b28aaed03%3A0x71b415d535759367!2sOakland%2C+CA!5e0!3m2!1sen!2s!4v1395074246707" frameborder="0"></iframe>
<!--                        <section class="content">-->
<!--                            --><?php
//                            $lat = $property['Lat'];
//                            $lon = $property['Lon'];
//                            ?>
<!--                            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>-->
<!--                            <script>-->
<!--                                var map,-->
<!--                                    lat = --><?php //echo json_encode($lat); ?><!--,-->
<!--                                    lon = --><?php //echo json_encode($lon); ?><!--,-->
<!--                                    address = --><?php //echo json_encode($property['Property']['Address']['Address']); ?><!--,-->
<!--                                    city = --><?php //echo json_encode($property['Property']['Address']['City']); ?><!--,-->
<!--                                    state = --><?php //echo json_encode($property['Property']['Address']['State']); ?><!--,-->
<!--                                    zip = --><?php //echo json_encode($property['Property']['Address']['Zip']); ?><!--;-->
<!--                                function initialize() {-->
<!--                                    var mapOptions = {-->
<!--                                        zoom: 10,-->
<!--                                        center: new google.maps.LatLng(lat, lon),-->
<!--                                        mapTypeId: google.maps.MapTypeId.ROADMAP-->
<!--                                    };-->
<!--                                    map = new google.maps.Map(document.getElementById('bt-single-property-map-canvas'), mapOptions);-->
<!--                                    var marker = new google.maps.Marker({-->
<!--                                        position: new google.maps.LatLng(lat, lon),-->
<!--                                        map: map,-->
<!--                                        title: address + "\n" + city + ', ' + state + ' ' + zip-->
<!--                                    });-->
<!--                                }-->
<!--                                google.maps.event.addDomListener(window, 'load', initialize);-->
<!--                            </script>-->
<!--                            <div id="bt-single-property-map-canvas" style="margin:0px; padding: 0px;"></div>-->
                    </section>

                    <h4 class="title info theme-color">Attachments:</h4>
                    <ul>
                        <li><a href="#">Marketing Flyer</a></li>
                        <li><a href="#">Floor Plan</a></li>
                    </ul>
                    <h4 class="title info theme-color">For More Information Contact:</h4>
                    <ul class="broker-list">
                        <li>
                            <div class="broker-item">
                                <h4>
                                    <span class="name">John Smith</span>
                                </h4>
                                <a href="#" class="icon-mail">
                                    <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png"><span>john.smith@keystonecres.com</span>
                                </a>
                                <span>248-658-8999</span>
                            </div>
                        </li>
                        <li>
                            <div class="broker-item">
                                <h4>
                                    <span class="name">Steven Conrad</span>
                                </h4>
                                <a href="#" class="icon-mail">
                                    <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png"><span>john.smith@keystonecres.com</span>
                                </a>
                                <span>248-658-8999</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
<!--        <section class="content">-->
<!--            <a href="#">Back to Search Results</a>-->
<!--        </section>-->
    <?php
    }
    ?>
</div>

<!-- Pop-up -->
<div class="modal" id="pop-up1">
    <div class="pop-up-bg"></div>
    <div class="pop-up-wrap">
        <div class="pop-up-close"></div>
        <div class="image-slider">
            <div class="slider-main-image">
                <img src="http://placehold.it/750x350">
                <div class="slider-arrows">
                    <div class="arrow left"><span><</span></div>
                    <div class="arrow right"><span>></span></div>
                </div>
            </div>
            <div class="slider-pagination">
                <div class="pagination-item">
                    <img src="http://placehold.it/100x100">
                </div>
                <div class="pagination-item">
                    <img src="http://placehold.it/100x100">
                </div>
                <div class="pagination-item">
                    <img src="http://placehold.it/100x100">
                </div>
                <div class="pagination-item">
                    <img src="http://placehold.it/100x100">
                </div>
                <div class="pagination-item">
                    <img src="http://placehold.it/100x100">
                </div>
            </div>
        </div>

    </div>
</div>



<script>
    jQuery('.pop-up-btn').on('click', function () {
        jQuery('#pop-up1')
            .show()
            .addClass('visible');

    });

    jQuery('.pop-up-close').on('click', function () {
        jQuery('#pop-up1, #new_request')
            .hide()
            .removeClass('visible');
    });
</script>
<?php

// Returns minimum and maximum space size
function bt_get_available_space_size($spacesToLease)
{
    $sizes = array();
    foreach ($spacesToLease as $space)
    {
        if (isset($space['Size']))
        {
            $sizes[] = (float) $space['Size'];
        }
    }
    unset($space);
    return array(min($sizes), max($sizes));
}

// Returns minimum and maximum rental rates
function bt_get_rental_rate($spacesToLease)
{
    $rates = array();
    foreach ($spacesToLease as $space)
    {
        if (isset($space['RentalRate']))
        {
            $rates[] = (float) $space['RentalRate'];
        }
    }
    unset($space);
    return array(min($rates), max($rates));
}


