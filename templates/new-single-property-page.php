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

<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/js/jcarousel.connected-carousels.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/js/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">
    function getBrowserGradient(gradient, ieColor) {
        var browser = $.browser;
        var browserGrad = browser.mozilla
            ? '-moz-' + gradient
            : browser.webkit
            ? '-webkit-' + gradient
            : browser.opera
            ? '-o-' + gradient
            : ieColor;
        return browserGrad;
    }

    stLight.options({
        publisher: '374f82ac-04f2-4ff8-8aca-2f1c3b0acc4a',
        shareButtonColor: getBrowserGradient('linear-gradient(top , #FF8D00 0%, #BB0000 100%)', 'rgb(187,0,0)'),
        footerColor: getBrowserGradient('linear-gradient(top, #6a6b74 0%, #62646c 35%, #34353b 72%, #242529 100%)', 'rgb(52,53,59)')
    });
</script>

<div class="container">
    <?php
        if ($listingDetailStyle == 'style1'){
    ?>
            <section class="image">
                <img class="main-logo-image" src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" />
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
                    <?php foreach($property['ListingAgents'] as $broker){ ?>
                        <div class="broker-item">
                            <h4>
                                <span class="name"><?php echo $broker['FirstName'] . ' ' . $broker['LastName']; ?></span>
                                <a href="mailto:<?php echo $broker['Email']; ?>?subject=<?php echo $property_name; ?>" class="icon-mail">
                                    <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png" />
                                </a>
                            </h4>
                            <span><?php echo $broker['Phone']; ?></span>
                        </div>
                    <?php } ?>
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
                        <?php if(isset($otherImages)){ ?>
                            <div class="connected-carousels image-slider small">
                                <div class="stage slider-main-image">
                                    <div class="carousel carousel-stage carousel-stage-small">
                                        <ul>
                                            <?php foreach ($otherImages as $src) {?>
                                            <li>
                                                <img src="<?php echo $src; ?>" width="334" height="350">
                                                <div class="image-overlay pop-up-btn">
                                                    <span class="zoom"></span>
                                                </div>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <a href="javascript:void(0)" class="prev prev-stage"><span>&lsaquo;</span></a>
                                    <a href="javascript:void(0)" class="next next-stage"><span>&rsaquo;</span></a>
                                </div>

                                <div class="navigation slider-pagination">
                                    <a href="javascript:void(0)" class="prev prev-navigation"><span>&lsaquo;</span></a>
                                    <a href="javascript:void(0)" class="next next-navigation"><span>&rsaquo;</span></a>
                                    <div class="carousel carousel-navigation carousel-navigation-small">
                                        <ul>
                                            <?php foreach ($otherImages as $src) {?>
                                            <li>
                                                <div class="pagination-item">
                                                    <img src="<?php echo $src; ?>" width="50" height="50">
                                                </div>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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

    <?php
        }
        else{
    ?>
        <section class="title">
            <h1><?php echo $property_name; ?></h1>
            <div class="share-buttons">
<!--                <button class="btn-share-this"><strong>SHARE</strong></button>-->
<!--                <span class="st_sharethis" st_title="--><?php //echo $property_name; ?><!-- &amp; Office Condos 16,650/SF | --><?php //echo $property['Property']['Address']['City'] . ' ' . $property['Property']['Address']['State'] . ', ' . $property['Property']['Address']['Zip']; ?><!-- | --><?php //echo '$' . number_format($property['PropertyPrice']); ?><!--" st_image="--><?php //echo $property['ListingImages'][0]['AttachmentPath']; ?><!--?width=400" st_summary="--><?php //echo $listingType; ?><!-- listing by --><?php //echo $property['ListingAgents'][0]['FirstName'] . ' ' . $property['ListingAgents'][0]['LastName']; ?><!-- at Cantrell &amp; Morgan, Inc on BuzzTarget.com" displaytext=""></span>-->
<!--                <button class="red-style btn-share-this"><strong>SHARE</strong></button>-->

<!--                <span class="st_sharethis" st_title="Marsh Point Medical &amp; Office Condos 16,650/SF | Neptune Beach, FL | $599,000" st_image="http://buzztarget.com/s3/buzztarget-images/ListingImages/13281/dd665e02-413e-4a71-84e0-9fd09611ecae.JPG.ashx?width=400" st_summary="For sale listing by Cantrell  Morgan at Cantrell &amp; Morgan, Inc on BuzzTarget.com" displaytext="ShareThis"></span>-->
                <script language="javascript" type="text/javascript">
                    jQuery(function () {

                        jQuery('button.btn-share-this').unbind('click').click(function () {
                            jQuery('span.st_sharethis .stButton').click();

                            var newTopPosition = Math.round((jQuery(window).height() - jQuery('#stwrapper').height()) / 2);
                            if (newTopPosition > 50) {
                                jQuery('#stwrapper').css('top', newTopPosition + 'px');
                            }
                            return false;
                        });
                        var contactMemberBtn = jQuery('.listing-actions .la-private-reply');
                        if (contactMemberBtn.length > 0) {
                            jQuery('.property-bar-content button.btn-contact-member').unbind('click').click(function () {
                                contactMemberBtn.click();
                                return false;
                            });
                        } else {
                            jQuery('.property-bar-content button.btn-contact-member').hide();
                        }
                    });
                </script>

                <a class="print-page" href="javascript:window.print();"><img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/print_16_gray.png"></a>
            </div>

        </section>
        <section class="content two-columns">
            <div class="clearfix">
                <div class="column half">
                    <?php if(isset($otherImages)){ ?>
                        <div class="connected-carousels image-slider">
                            <div class="stage slider-main-image">
                                <div class="carousel carousel-stage carousel-stage-small">
                                    <ul>
                                        <?php foreach ($otherImages as $src) {?>
                                        <li>
                                            <img src="<?php echo $src; ?>" width="423" height="350">
                                            <div class="image-overlay pop-up-btn">
                                                <span class="zoom"></span>
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <a href="javascript:void(0)" class="prev prev-stage"><span>&lsaquo;</span></a>
                                <a href="javascript:void(0)" class="next next-stage"><span>&rsaquo;</span></a>
                            </div>

                            <div class="navigation slider-pagination">
                                <a href="javascript:void(0)" class="prev prev-navigation"><span>&lsaquo;</span></a>
                                <a href="javascript:void(0)" class="next next-navigation"><span>&rsaquo;</span></a>
                                <div class="carousel carousel-navigation carousel-navigation-small">
                                    <ul>
                                        <?php foreach ($otherImages as $src) {?>
                                        <li>
                                            <div class="pagination-item">
                                                <img src="<?php echo $src; ?>" width="60" height="60">
                                            </div>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
                        <?php foreach($property['ListingAgents'] as $broker){ ?>
                        <li>
                            <div class="broker-item">
                                <h4>
                                    <span class="name"><?php echo $broker['FirstName'] . ' ' . $broker['LastName']; ?></span>
                                </h4>
                                <a href="mailto:<?php echo $broker['Email']; ?>?subject=<?php echo $property_name; ?>" target="_blank" class="icon-mail">
                                    <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png"><span><?php echo $broker['Email']; ?></span>
                                </a>
                                <span><?php echo $broker['Phone']; ?></span>
                            </div>
                        </li>
                        <?php } ?>
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
        <div class="connected-carousels image-slider">
            <div class="stage slider-main-image">
                <div class="carousel carousel-stage carousel-stage-popup">
                    <ul>
                        <?php foreach ($otherImages as $src) {?>
                        <li>
                            <img src="<?php echo $src; ?>"  width="750">
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <a href="javascript:void(0)" class="prev prev-stage"><span>&lsaquo;</span></a>
                <a href="javascript:void(0)" class="next next-stage"><span>&rsaquo;</span></a>
            </div>

            <div class="navigation slider-pagination">
                <a href="javascript:void(0)" class="prev prev-navigation"><span>&lsaquo;</span></a>
                <a href="javascript:void(0)" class="next next-navigation"><span>&rsaquo;</span></a>
                <div class="carousel carousel-navigation carousel-navigation-popup">
                    <ul>
                        <?php foreach ($otherImages as $src) {?>
                        <li>
                            <div class="pagination-item">
                                <img src="<?php echo $src; ?>" width="100" height="100">
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
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

    jQuery('.pop-up-close, .pop-up-bg').on('click', function () {
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