<?php

get_header();

$theme_options = get_option('buzztarget_theme_options');
$themeColor = $theme_options['theme_color'];
$listingDetailStyle = $theme_options['listing_detail_style'];

$properties = get_option('repl_properties');
$property_name = get_the_title();

$post_id = get_the_ID();
$post = get_post($post_id);
$post_name = $post->post_name;
$property_id = array_values(explode('-', $post_name))[0];

$property = $properties[$property_id];

if($property){
    $phoneImage = plugin_dir_url(dirname(__FILE__)) . 'static/images/phone.png';

    $otherImages = array();
    foreach ($property['ListingImages'] as $propertyThumbnail):
        if (isset($propertyThumbnail['AttachmentPath'])):
            $otherImages[] = $propertyThumbnail['AttachmentPath'];
        endif;
    endforeach;

    $propertyDocuments = $property['ListingDocuments'];
    $spaces = $property['SpacesToLease'];

    $currentImage = 1;
    $imagesCount = count($otherImages);

?>

<div id="buzz-target-plugin">
<div class="container">
    <?php
        if ($listingDetailStyle == 'style1'){
    ?>
            <section class="image">
                <img src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" />
                <div class="darken-bg"></div>
                <h1><?php echo $property_name; ?></h1>
                <span>
                    <?php echo $property['Property']['Address']['City']; ?>,
                    <?php echo $property['Property']['Address']['State']; ?>
                    <?php echo $property['Property']['Address']['Zip']; ?>
                </span>
            </section>
            <section class="content">
                <h3 class="for-lease">
                    <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>
                    <?php
                        $listingType = ($property['ListingType'] === 'ForSale') ? 'For Sale' : 'For Lease';
                    ?>
                    <?= $listingType; ?>
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
                <?php if (isset($property['PropertyDescription']) || isset($property['LocationDescription'])){ ?>
                <div class="overview">
                    <h4 class="title theme-color">Property Overview</h4>

                    <p>
                        <?php
                        if (isset($property['PropertyDescription'])){
                            print $property['PropertyDescription'];
                        }
                        if (isset($property['LocationDescription'])){
                            print $property['LocationDescription'];
                        }
                        ?>
                    </p>
                </div>
                <?php } ?>
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
                                    <img src="<?php echo $src; ?>">
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if (count($propertyDocuments) > 0) {?>
                        <h4 class="title info theme-color">Attachments:</h4>
                        <ul class="property-docs">
                            <?php foreach ($propertyDocuments as $doc) {?>
                            <li><a href="<?php echo $doc['AttachmentPath']?>"><?php echo $doc['AttachmentTitle']?></a></li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </div>
                    <div class="column sixty">
                        <h4 class="title info theme-color">Property Information</h4>
                        <table>
                            <tbody>
                            <?php if (isset($property['GrossLeasableArea'])){ ?>
                            <tr>
                                <td>Total Building SF:</td>
                                <td><?php echo number_format($property['GrossLeasableArea']); ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['TotalLotSize'])){ ?>
                            <tr>
                                <td>Total Lot Size SF:</td>
                                <td><?php echo number_format($property['TotalLotSize']); ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['Occupancy'])){ ?>
                            <tr>
                                <td>Occupancy:</td>
                                <td><?php echo $property['Occupancy'] . '%'; ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['YearBuild'])){ ?>
                            <tr>
                                <td>Year Built:</td>
                                <td><?php echo $property['YearBuild']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['YearRenovated'])){ ?>
                            <tr>
                                <td>Year Renovated:</td>
                                <td><?php echo $property['YearRenovated']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['ParkingSpace'])){ ?>
                            <tr>
                                <td>Parking Space:</td>
                                <td><?php echo $property['ParkingSpace']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['Zoning'])){ ?>
                                <tr>
                                    <td>Zoning:</td>
                                    <td><?php echo $property['Zoning']; ?></td>
                                </tr>
                            <?php } ?>
                            <?php if (isset($property['County'])){ ?>
                            <tr>
                                <td>County:</td>
                                <td><?php echo $property['County']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['TrafficCounts'])){ ?>
                            <tr>
                                <td>Traffic Count:</td>
                                <?php if ($property['TrafficCounts']['RangeFrom'] == $property['TrafficCounts']['RangeTo']){ ?>
                                    <td><?php echo number_format($property['TrafficCounts']['RangeFrom']); ?></td>
                                <?php } else{ ?>
                                    <td><?php echo number_format($property['TrafficCounts']['RangeFrom']) . ' - ' . number_format($property['TrafficCounts']['RangeTo']); ?></td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['PopulationRange'])){ ?>
                            <tr>
                                <td>Population:</td>
                                <?php if ($property['PopulationRange']['RangeFrom'] == $property['PopulationRange']['RangeTo']){ ?>
                                <td><?php echo number_format($property['PopulationRange']['RangeFrom']); ?></td>
                                <?php } else{ ?>
                                <td><?php echo number_format($property['PopulationRange']['RangeFrom']) . ' - ' . number_format($property['PopulationRange']['RangeTo']); ?></td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                            <?php if (isset($property['HouseholdIncome'])){ ?>
                            <tr>
                                <td>Income:</td>
                                <?php if ($property['HouseholdIncome']['RangeFrom'] == $property['HouseholdIncome']['RangeTo']){ ?>
                                <td><?php echo number_format($property['HouseholdIncome']['RangeFrom']); ?></td>
                                <?php } else{ ?>
                                <td><?php echo '$' . number_format($property['HouseholdIncome']['RangeFrom']) . ' - ' . ' $' . number_format($property['PopulationRange']['RangeTo']); ?></td>
                                <?php } ?>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php if (count($spaces) > 0){?>
                        <h4 class="title info theme-color">Spaces</h4>
                        <table class="theme-table">
                            <tbody>
                                <?php foreach($spaces as $space){ ?>
                            <tr>
                                <td><?php echo $space['Name'];?></td>
                                <td><?php echo '$'.$space['RentalRate'] . '  / PSF'; ?></td>
                                <td><?php echo number_format($space['Size']) . ' SF'; ?></td>
                                <td><?php echo $space['SpaceType'];?></td>
                            </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </section>
            <section class="content">
                <?php
                $lat = $property['Lat'];
                $lon = $property['Lon'];
                if(!isset($lat) or !isset($lon)){
                    $address = $property['Property']['Address']['City'] . ', ' . $property['Property']['Address']['State'] . ' ' . $property['Property']['Address']['Zip'];
                    $address = urlencode($address);
                    $link = "http://maps.google.com/maps/api/geocode/xml?address=".$address."&sensor=false";
                    $file = file_get_contents($link);
                    if(!$file)  {
                        echo "Err: No access to Google service: ".$a."<br/>\n";
                    }else {
                        $get = simplexml_load_string($file);

                        if ($get->status == "OK") {
                            $lat = (float) $get->result->geometry->location->lat;
                            $lon = (float) $get->result->geometry->location->lng;
                        }else{
                            echo "Err: address not found: ".$a."<br/>\n";
                        }
                    }
                }
                ?>
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
                <script>
                    var map,
                        lat = <?php echo json_encode($lat); ?>,
                        lon = <?php echo json_encode($lon); ?>,
                        address = <?php echo json_encode($property['Property']['Address']['Address']); ?>,
                        city = <?php echo json_encode($property['Property']['Address']['City']); ?>,
                        state = <?php echo json_encode($property['Property']['Address']['State']); ?>,
                        zip = <?php echo json_encode($property['Property']['Address']['Zip']); ?>;
                    function initialize() {
                        var mapOptions = {
                            zoom: 10,
                            center: new google.maps.LatLng(lat, lon),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        };
                        map = new google.maps.Map(document.getElementById('bt-single-property-map-canvas'), mapOptions);
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(lat, lon),
                            map: map,
                            title: address + "\n" + city + ', ' + state + ' ' + zip
                        });
                    }
                    google.maps.event.addDomListener(window, 'load', initialize);
                </script>
                <div id="bt-single-property-map-canvas" style="margin:0px; padding: 0px;"></div>
            </section>
            <section class="content">
                <a href="javascript:history.back()">Back to Search Results</a>
            </section>
<!--        <div class="modal" id="pop-up1">-->
<!--            <div class="pop-up-bg"></div>-->
<!--            <div class="pop-up-wrap">-->
<!--                <div class="pop-up-close"></div>-->
<!--                <div class="image-slider">-->
<!--                    <div class="slider-main-image">-->
<!--                        <img src="http://placehold.it/750x350">-->
<!--                        <div class="slider-arrows">-->
<!--                            <div class="arrow left"><span><</span></div>-->
<!--                            <div class="arrow right"><span>></span></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="slider-pagination">-->
<!--                        --><?php //foreach ($otherImages as $src) {?>
<!--                        <div class="pagination-item">-->
<!--                            <img src="--><?php //echo $src?><!--">-->
<!--                        </div>-->
<!--                        --><?php// } ?>
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        </div>-->

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
                            <?php foreach ($otherImages as $src) { ?>
                            <div class="pagination-item">
                                <img src="<?php echo $src?>">
                            </div>
                            <?php } ?>
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
                                        <td colspan="2"><?php echo $property['Property']['Address']['Address']; ?></td>
                                    </tr>
                                <?php }?>
                                <tr>
                                    <td colspan="2">
                                        <?php echo $property['Property']['Address']['City']; ?>,
                                        <?php echo $property['Property']['Address']['State']; ?>
                                        <?php echo $property['Property']['Address']['Zip']; ?>
                                    </td>
                                </tr>
                                <?php if (isset($property['County'])){ ?>
                                    <tr>
                                        <td>County: </td>
                                        <td><?php echo $property['County'] ?></td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                        <table class="half">
                            <tbody>
                            <?php if (isset($property['PropertyPrice'])){ ?>
                                <tr>
                                    <td>Price:</td>
                                    <td> <?php echo '$' . number_format($property['PropertyPrice']); ?></td>
                                </tr>
                            <?php }?>
                            <?php if (isset($property['CapRate'])){ ?>
                                <tr>
                                    <td>CAP Rate:</td>
                                    <td><?php echo round($property['CapRate'], 2) . '%'; ?></td>
                                </tr>
                            <?php }?>
                            <?php if (isset($property['Noi'])){ ?>
                                <tr>
                                    <td>NOI: </td>
                                    <td><?php echo '$' . number_format($property['Noi']); ?></td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (isset($property['PropertyDescription']) || isset($property['LocationDescription'])){ ?>
                        <div class="overview">
                            <h4 class="title info theme-color">Property Overview</h4>

                            <p class="property-description">
                                <?php
                                if (isset($property['PropertyDescription'])){
                                    print $property['PropertyDescription'];
                                }
                                if (isset($property['LocationDescription'])){
                                    print $property['LocationDescription'];
                                }
                                ?>
                            </p>
                        </div>
                    <?php } ?>

                    <h4 class="title info theme-color">Property Information</h4>
                    <table>
                        <tbody>
                        <?php if (isset($property['GrossLeasableArea'])){ ?>
                            <tr>
                                <td>Total Building SF:</td>
                                <td><?php echo number_format($property['GrossLeasableArea']); ?></td>
                            </tr>
                        <?php } ?>
                        <?php  if (isset($property['TotalLotSize'])){ ?>
                            <tr>
                                <td>Total Lot Size SF:</td>
                                <td><?php echo number_format($property['TotalLotSize']); ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (isset($property['Occupancy'])){ ?>
                            <tr>
                                <td>Occupancy:</td>
                                <td><?php echo $property['Occupancy'] . '%'; ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (isset($property['YearBuild'])){ ?>
                            <tr>
                                <td>Year Built:</td>
                                <td><?php echo $property['YearBuild']; ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (isset($property['YearRenovated'])){ ?>
                            <tr>
                                <td>Year Renovated:</td>
                                <td><?php echo $property['YearRenovated']; ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (isset($property['ParkingSpace'])){ ?>
                        <tr>
                            <td>Parking Space:</td>
                            <td><?php echo $property['ParkingSpace']; ?></td>
                        </tr>
                        <?php } ?>
                        <?php if (isset($property['Zoning'])){ ?>
                            <tr>
                                <td>Zoning:</td>
                                <td><?php echo $property['Zoning']; ?></td>
                            </tr>
                        <?php } ?>
                        <?php if (isset($property['County'])){ ?>
                            <tr>
                                <td>County:</td>
                                <td><?php echo $property['County']; ?></td>
                            </tr>
                        <?php } ?>
                            <?php if (isset($property['TrafficCounts'])){ ?>
                        <tr>
                            <td>Traffic Count:</td>
                            <?php if ($property['TrafficCounts']['RangeFrom'] == $property['TrafficCounts']['RangeTo']){ ?>
                            <td><?php echo number_format($property['TrafficCounts']['RangeFrom']); ?></td>
                            <?php } else{ ?>
                            <td><?php echo number_format($property['TrafficCounts']['RangeFrom']) . ' - ' . number_format($property['TrafficCounts']['RangeTo']); ?></td>
                            <?php } ?>
                        </tr>
                            <?php } ?>
                            <?php if (isset($property['PopulationRange'])){ ?>
                        <tr>
                            <td>Population:</td>
                            <?php if ($property['PopulationRange']['RangeFrom'] == $property['PopulationRange']['RangeTo']){ ?>
                            <td><?php echo number_format($property['PopulationRange']['RangeFrom']); ?></td>
                            <?php } else{ ?>
                            <td><?php echo number_format($property['PopulationRange']['RangeFrom']) . ' - ' . number_format($property['PopulationRange']['RangeTo']); ?></td>
                            <?php } ?>
                        </tr>
                            <?php } ?>
                            <?php if (isset($property['HouseholdIncome'])){ ?>
                        <tr>
                            <td>Income:</td>
                            <?php if ($property['HouseholdIncome']['RangeFrom'] == $property['HouseholdIncome']['RangeTo']){ ?>
                            <td><?php echo number_format($property['HouseholdIncome']['RangeFrom']); ?></td>
                            <?php } else{ ?>
                            <td><?php echo '$' . number_format($property['HouseholdIncome']['RangeFrom']) . ' - ' . ' $' . number_format($property['PopulationRange']['RangeTo']); ?></td>
                            <?php } ?>
                        </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php if (count($spaces) > 0){?>
                        <h4 class="title info theme-color">Spaces</h4>
                        <table class="theme-table">
                            <tbody>
                            <?php foreach($spaces as $space){ ?>
                                <tr>
                                    <td><?php echo $space['Name'];?></td>
                                    <td><?php echo '$'.$space['RentalRate'] . '  / PSF'; ?></td>
                                    <td><?php echo number_format($space['Size']) . ' SF'; ?></td>
                                    <td><?php echo $space['SpaceType'];?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
                <div class="column half">
                    <section class="content">
                        <?php
                        $lat = $property['Lat'];
                        $lon = $property['Lon'];
                        if(!isset($lat) or !isset($lon)){
                            $address = $property['Property']['Address']['City'] . ', ' . $property['Property']['Address']['State'] . ' ' . $property['Property']['Address']['Zip'];
                            $address = urlencode($address);
                            $link = "http://maps.google.com/maps/api/geocode/xml?address=".$address."&sensor=false";
                            $file = file_get_contents($link);
                            if(!$file)  {
                                echo "Err: No access to Google service: ".$a."<br/>\n";
                            }else {
                                $get = simplexml_load_string($file);

                                if ($get->status == "OK") {
                                    $lat = (float) $get->result->geometry->location->lat;
                                    $lon = (float) $get->result->geometry->location->lng;
                                }else{
                                    echo "Err: address not found: ".$a."<br/>\n";
                                }
                            }
                        }
                        ?>
                        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
                        <script>
                            var map,
                                lat = <?php echo json_encode($lat); ?>,
                                lon = <?php echo json_encode($lon); ?>,
                                address = <?php echo json_encode($property['Property']['Address']['Address']); ?>,
                                city = <?php echo json_encode($property['Property']['Address']['City']); ?>,
                                state = <?php echo json_encode($property['Property']['Address']['State']); ?>,
                                zip = <?php echo json_encode($property['Property']['Address']['Zip']); ?>;
                            function initialize() {
                                var mapOptions = {
                                    zoom: 10,
                                    center: new google.maps.LatLng(lat, lon),
                                    mapTypeId: google.maps.MapTypeId.ROADMAP
                                };
                                map = new google.maps.Map(document.getElementById('bt-single-property-map-canvas'), mapOptions);
                                var marker = new google.maps.Marker({
                                    position: new google.maps.LatLng(lat, lon),
                                    map: map,
                                    title: address + "\n" + city + ', ' + state + ' ' + zip
                                });
                            }
                            google.maps.event.addDomListener(window, 'load', initialize);
                        </script>
                        <div id="bt-single-property-map-canvas" style="margin:0px; padding: 0px;"></div>
                    </section>
                    <?php if (count($propertyDocuments) > 0) {?>
                        <h4 class="title info theme-color">Attachments:</h4>
                        <ul class="property-docs">
                            <?php foreach ($propertyDocuments as $doc) {?>
                                <li><a href="<?php echo $doc['AttachmentPath']?>"><?php echo $doc['AttachmentTitle']?></a></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
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
        <section class="content">
            <a href="javascript:history.back()">Back to Search Results</a>
        </section>
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

}
?>