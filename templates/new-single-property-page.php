<?php
get_header();

$theme_options = get_option('buzztarget_theme_options');
$themeColor = (isset($theme_options['theme_color'])) ? $theme_options['theme_color'] : NULL;
$listingDetailStyle = $theme_options['listing_detail_style'];

$property_name = get_the_title();

$post_id = get_the_ID();
$post = get_post($post_id);
$post_name = $post->post_name;

// these two do not work within one line
$property_id = array_values(explode('-', $post_name));
$property_id = $property_id[0];

$property = BuzzTargetLive\Listings::getProperty($property_id);

if($property){
    $phoneImage = plugin_dir_url(dirname(__FILE__)) . 'static/images/phone.png';

    $otherImages = array();
    foreach ($property['ListingImages'] as $propertyThumbnail):
        if (isset($propertyThumbnail['AttachmentPath'])):
            $otherImages[] = $propertyThumbnail['AttachmentPath'];
        endif;
    endforeach;

    $propertyDocuments = $property['ListingDocuments'];
    $propertyLinks = array();
    if(isset($property['Link1Title']) && isset($property['Link1Url']))
        $propertyLinks[] = array('Url' => $property['Link1Url'], 'Title' => $property['Link1Title']);
    if(isset($property['Link2Title']) && isset($property['Link2Url']))
        $propertyLinks[] = array('Url' => $property['Link2Url'], 'Title' => $property['Link2Title']);
    if(isset($property['Link3Title']) && isset($property['Link3Url']))
        $propertyLinks[] = array('Url' => $property['Link3Url'], 'Title' => $property['Link3Title']);
    $spaces = $property['SpacesToLease'];

    $currentImage = 1;
    $imagesCount = count($otherImages);

    $siteUrl = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

?>
<?php
    if($theme_options['full_width_style'] == 'on'){
?>
<script>
    if(jQuery('body').hasClass('right_sidebar')){
        jQuery('body').removeClass('right_sidebar').addClass('full_width');
    }
    else{
        jQuery('body').removeClass('right-sidebar').addClass('full-width');
    }
</script>

<?php
    }
?>
<script>
    jQuery('body').addClass('<?php echo str_replace(' ', '-', get_current_theme());?>');
</script>
<div id="buzz-target-plugin">
<meta name="description" content="<?php echo $property_name . ' & '. $property['PropertyTypes'][0]; ?> <?php if (count($spaces) > 0){ echo '$'.$spaces[0]['RentalRate'] . '  / SF'; } ?> | <?php echo $property['Property']['Address']['City'] . ', ' . $property['Property']['Address']['State']; ?> <?php if($property['PropertyPrice']){ echo '| $' . number_format($property['PropertyPrice']); }?> on <?php echo $_SERVER['SERVER_NAME'];?>."/>
<meta property="og:title" content="<?php echo $property_name . ' & '. $property['PropertyTypes'][0]; ?> <?php if (count($spaces) > 0){ echo '$'.$spaces[0]['RentalRate'] . '  / SF'; } ?> | <?php echo $property['Property']['Address']['City'] . ', ' . $property['Property']['Address']['State']; ?> <?php if($property['PropertyPrice']){ echo '| $' . number_format($property['PropertyPrice']); }?> "/>
<meta property="og:type" content="<?php echo $property['ListingType'];?> Listing" />
<meta property="og:url" content="<?php echo $siteUrl;?>" />

<meta property="og:image" content="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>?width=400" />

<meta property="og:description" content="<?php echo $property['ListingType'];?> listing by <?php echo $property['ListingAgents'][0]['FirstName'] . ' ' . $property['ListingAgents'][0]['LastName']; ?> on <?php echo $_SERVER['SERVER_NAME'];?>" />
<meta property="og:site_name" content="<?php echo $_SERVER['SERVER_NAME'];?>" />


<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/js/jcarousel.connected-carousels.js"></script>
<script type="text/javascript" src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/js/jquery.jcarousel.min.js"></script>

<div class="container">
    <?php
    if ($listingDetailStyle == 'style1'){
        ?>
    <div class="section image">
        <img class="main-logo-image" src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" alt="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>"/>
        <div class="darken-bg"></div>
        <h1><?php echo $property_name; ?></h1>
                    <span>
                        <?php echo $property['Property']['Address']['City']; ?>,
                        <?php echo $property['Property']['Address']['State']; ?>
                        <?php echo $property['Property']['Address']['Zip']; ?>
                    </span>
    </div>
    <div class="section content">
        <h3 class="for-lease">
            <div>
                <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>
                <?php
                $listingType = ($property['ListingType'] === 'ForSale') ? 'For Sale' : 'For Lease';
                ?>
                <?= $listingType; ?>
            </div>
        </h3>
        <div class="brokers">
            <?php foreach($property['ListingAgents'] as $broker){ ?>
            <div class="broker-item">
                <h4>
                    <span class="name"><?php echo $broker['FirstName'] . ' ' . $broker['LastName']; ?></span>
                    <a href="mailto:<?php echo $broker['Email']; ?>?subject=<?php echo urlencode($property_name); ?>" class="icon-mail">
                        <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png" alt="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png"/>
                    </a>
                </h4>
                <span><?php echo $broker['Phone']; ?></span>
            </div>
            <?php } ?>
        </div>
        <div class="share-buttons one-column-style">
            <script type="text/javascript">var switchTo5x=true;</script>
            <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
            <script type="text/javascript">stLight.options({publisher: "3d7a2d4f-e80b-4e92-a0dc-3005fb9f74d7", doNotHash: true, doNotCopy: false, hashAddressBar: false, onhover: false});</script>
            <span class='st_sharethis' displayText=''></span>

            <a class="print-page" href="javascript:window.print();"><img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/print_16_gray.png" alt="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/print_16_gray.png"></a>

            <?php if (count($propertyDocuments) > 0) {?>
                <a class="attachment-clip" href="<?php echo $propertyDocuments[0]['AttachmentPath']?>" target='_blank'><img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/paperclip_16_black.png" alt="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/paperclip_16_black.png"></a>
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
    </div>
    <div class="section content">
        <div class="clearfix">
            <div class="column less-half">
                <?php if(isset($otherImages)){ ?>
                <div class="connected-carousels image-slider small">
                    <div class="stage slider-main-image">
                        <div class="carousel carousel-stage carousel-stage-small">
                            <ul>
                                <?php foreach ($otherImages as $src) {?>
                                <li>
                                    <img src="<?php echo $src; ?>" alt="<?php echo $src; ?>">
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
                        <div class="carousel carousel-navigation carousel-navigation-small <?php if (count($otherImages)<5){ echo 'centered-items';}?>">
                            <ul>
                                <?php foreach ($otherImages as $src) {?>
                                <li>
                                    <div class="pagination-item">
                                        <img src="<?php echo $src; ?>" alt="<?php echo $src; ?>" width="50" height="50">
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
                    <li>
                        <a href="<?php echo $doc['AttachmentPath']?>" target="_blank">
                            <?php if (isset($theme_options['show_document_icons_on_listing']) && $theme_options['show_document_icons_on_listing'] == 'on') { ?>
                                <?php preg_match('/\.(\w+)$/', $doc['AttachmentPath'], $matches); ?>
                                <img src='<?php echo plugin_dir_url(dirname(__FILE__)) . 'static/images/'.$matches[1].'-50.png';?>' class='property-attachment-icon' />
                            <?php } ?>
                            <?php echo $doc['AttachmentTitle']?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>


                <?php if (count($propertyLinks) > 0) {?>
                <h4 class="title info theme-color">Listing Links:</h4>
                <ul class="property-docs">
                    <?php foreach ($propertyLinks as $doc) {?>
                    <li>
                        <a href="<?php echo $doc['Url']?>" target="_blank">
                            <?php echo $doc['Title']?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
            <?php if ((isset($property['HouseholdIncome']) && ($property['HouseholdIncome']['RangeFrom'] != 0 || $property['HouseholdIncome']['RangeTo'] != 0))
                || (isset($property['PopulationRange']) && ($property['PopulationRange']['RangeFrom'] != 0 || $property['PopulationRange']['RangeTo'] != 0))
                || (isset($property['TrafficCounts']) && ($property['TrafficCounts']['RangeFrom'] != 0 || $property['TrafficCounts']['RangeTo'] != 0))
                || isset($property['County'])
                || isset($property['Zoning'])
                || isset($property['Utilities'])
                || isset($property['Divisible'])
                || ((isset($property['DivisibleMax']) && $property['DivisibleMax'] != 0) && (isset($property['DivisibleMin']) && $property['DivisibleMin'] != 0))
                || (isset($property['ParkingSpace']) && $property['ParkingSpace'] != 0 )
                || (isset($property['YearRenovated']) && $property['YearRenovated'] != 0 )
                || (isset($property['YearBuild']) && $property['YearBuild'] != 0 )
                || (isset($property['Occupancy']) && $property['Occupancy'] != 0)
                || (isset($property['TotalLotSize']) && $property['TotalLotSize'] != 0)
                || (isset($property['GrossLeasableArea']) && $property['GrossLeasableArea'] != 0)
            ){ ?>
            <div class="column half property-info">
                <h4 class="title info theme-color">Property Information</h4>
                <table>
                    <tbody>
                        <?php if (isset($property['GrossLeasableArea'])){ ?>
                    <tr>
                        <td>Total Building SF:</td>
                        <td><?php echo $property->getSize('GrossLeasableArea', true, 'SF'); ?></td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['TotalLotSize']) && $property['TotalLotSize'] != 0){ ?>
                    <tr>
                        <td>Total Lot Size <?php echo ($theme_options['show_size_in_acres'] == 'acres') ? 'Acres' : 'SF'; ?>:</td>
                        <td><?php echo $property->getSize('TotalLotSize', true, $theme_options['show_size_in_acres']); ?></td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['Occupancy']) && $property['Occupancy'] != 0){ ?>
                    <tr>
                        <td>Occupancy:</td>
                        <td><?php echo $property['Occupancy'] . '%'; ?></td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['YearBuild']) && ($property['YearBuild'] != 0 && strlen($property['YearBuild']) > 0 )){ ?>
                    <tr>
                        <td>Year Built:</td>
                        <td><?php echo $property['YearBuild']; ?></td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['YearRenovated']) && ($property['YearRenovated'] != 0 && strlen($property['YearRenovated']) > 0 )){ ?>
                    <tr>
                        <td>Year Renovated:</td>
                        <td><?php echo $property['YearRenovated']; ?></td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['ParkingSpace']) && ($property['ParkingSpace'] != 0 && strlen($property['ParkingSpace']) > 0 )){ ?>
                    <tr>
                        <td>Parking Space:</td>
                        <td><?php echo $property['ParkingSpace']; ?></td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['Zoning']) && ($property['Zoning'] != 0 && strlen($property['Zoning']) > 0 )){ ?>
                    <tr>
                        <td>Zoning:</td>
                        <td><?php echo $property['Zoning']; ?></td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['County']) && ($property['County'] != 0 && strlen($property['County']) > 0 )){ ?>
                    <tr>
                        <td>County:</td>
                        <td><?php echo $property['County']; ?></td>
                    </tr>
                        <?php } ?>

                        <?php if (isset($property['Utilities']) && ($property['Utilities'] != 0 && strlen($property['Utilities']) > 0 )){ ?>
                    <tr>
                        <td>Utilities:</td>
                        <td><?php echo ($property['Utilities']) ? 'Yes' : 'No'; ?></td>
                    </tr>
                        <?php } ?>

                        <?php if (isset($property['Divisible']) && ($property['Divisible'] != 0 && strlen($property['Divisible']) > 0 )){ ?>
                    <tr>
                        <td>Divisible:</td>
                        <td><?php echo ($property['Divisible']) ? 'Yes' : 'No';; ?></td>
                    </tr>
                        <?php } ?>

                        <?php if ((isset($property['DivisibleMax']) && $property['DivisibleMax'] != 0) && (isset($property['DivisibleMin']) && $property['DivisibleMin'] != 0)){ ?>
                    <tr>
                        <td>Divisible Max/Min:</td>
                        <td><?php echo $property['DivisibleMax']; ?>/<?php echo $property['DivisibleMin']; ?> Acres</td>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['TrafficCounts']) && ($property['TrafficCounts']['RangeFrom'] != 0 || $property['TrafficCounts']['RangeTo'] != 0)){ ?>
                    <tr>
                        <td>Traffic Count:</td>
                        <?php if ($property['TrafficCounts']['RangeFrom'] == $property['TrafficCounts']['RangeTo']){ ?>
                        <td><?php echo number_format($property['TrafficCounts']['RangeFrom']); ?></td>
                        <?php } else{ ?>
                        <td><?php echo number_format($property['TrafficCounts']['RangeFrom']) . ' - ' . number_format($property['TrafficCounts']['RangeTo']); ?></td>
                        <?php } ?>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['PopulationRange']) && ($property['PopulationRange']['RangeFrom'] != 0 || $property['PopulationRange']['RangeTo'] != 0)){ ?>
                    <tr>
                        <td>Population:</td>
                        <?php if ($property['PopulationRange']['RangeFrom'] == $property['PopulationRange']['RangeTo']){ ?>
                        <td><?php echo number_format($property['PopulationRange']['RangeFrom']); ?></td>
                        <?php } else{ ?>
                        <td><?php echo number_format($property['PopulationRange']['RangeFrom']) . ' - ' . number_format($property['PopulationRange']['RangeTo']); ?></td>
                        <?php } ?>
                    </tr>
                        <?php } ?>
                        <?php if (isset($property['HouseholdIncome']) && ($property['HouseholdIncome']['RangeFrom'] != 0 || $property['HouseholdIncome']['RangeTo'] != 0)){ ?>
                    <tr>
                        <td>Income:</td>
                        <?php if ($property['HouseholdIncome']['RangeFrom'] == $property['HouseholdIncome']['RangeTo']){ ?>
                        <td><?php echo number_format($property['HouseholdIncome']['RangeFrom']); ?></td>
                        <?php } else{ ?>
                        <td><?php echo '$' . number_format($property['HouseholdIncome']['RangeFrom']) . ' - ' . ' $' . number_format($property['HouseholdIncome']['RangeTo']); ?></td>
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
                        <?php if($space['RentalRate']>0) {
                            if ($space['RentalRateIsUndisclosed'] == false) { ?>
                        <td><?php echo '$'.$space['RentalRate'] . '  / PSF'; ?></td>
                        <?php }
                          } ?>
                        <td><?php echo number_format($space['Size']) . ' SF'; ?></td>
                        <td><?php echo $space['SpaceType'];?></td>
                    </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="section content">
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
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
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
                    zoom: 12,
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
    </div>
    <div class="section content">
        <a href="javascript:history.back()">Back to Search Results</a>
    </div>

        <?php
    }
    else{
        ?>
    <div class="section title">
        <h1><?php echo $property_name; ?></h1>
    </div>
    <div class="section content two-columns">
    <div class="clearfix">
    <div class="column less-half">
        <?php if(isset($otherImages)){ ?>
        <div class="connected-carousels image-slider">
            <div class="stage slider-main-image">
                <div class="carousel carousel-stage carousel-stage-small">
                    <ul>
                        <?php foreach ($otherImages as $src) {?>
                        <li>
                            <img src="<?php echo $src; ?>" alt="<?php echo $src; ?>">
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
                <div class="carousel carousel-navigation carousel-navigation-small <?php if (count($otherImages)<5){ echo 'centered-items';}?>">
                    <ul>
                        <?php foreach ($otherImages as $src) {?>
                        <li>
                            <div class="pagination-item">
                                <img src="<?php echo $src; ?>" width="60" height="60" alt="<?php echo $src; ?>">
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
        <div class="header-share-block">
            <div class="listings-type">
                <strong
                    <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                    <?php
                    $listingType = ($property['ListingType'] === 'ForSale') ? 'For Sale' : 'For Lease';
                    ?>
                    <?php echo $listingType; ?>
                </strong>
            </div>
            <div class="share-buttons">
                <script type="text/javascript">var switchTo5x=true;</script>
                <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
                <script type="text/javascript">stLight.options({publisher: "3d7a2d4f-e80b-4e92-a0dc-3005fb9f74d7", doNotHash: true, doNotCopy: false, hashAddressBar: false, onhover: false});</script>
                <?php if (count($propertyDocuments) > 0) {?>
                    <a class="attachment-clip" href="<?php echo $propertyDocuments[0]['AttachmentPath']?>" target='_blank'><img width='16' src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/paperclip_16_black.png" alt="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/paperclip_16_black.png" title="Listing Attachment"></a>
                <?php } ?>

				<span class='st_sharethis' displayText=''></span>

                <a class="print-page" href="javascript:window.print();"><img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/print_16_gray.png" alt="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/print_16_gray.png" title="Print"></a>


            </div>
        </div>
        <h4 class="title info theme-color"><?php echo $property_name; ?></h4>
        <div class="clearfix main-info">
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
                    <?php if (isset($property['County']) && ($property['County'] != 0 && strlen($property['County']) > 0 )){ ?>
                <tr>
                    <td>County: </td>
                    <td><?php echo $property['County'] ?></td>
                </tr>
                    <?php }?>
                </tbody>
            </table>

            <?php if ($property['PropertyPriceIsUndisclosed'] == false && (isset($property['PropertyPrice']) && $property['PropertyPrice'] != 0)){ ?>
                <div class="detail-price theme-color">Price: $<?php echo number_format($property['PropertyPrice']); ?></div>
            <? } ?>
            <?php if (isset($property['PricePerAcre']) && $property['PricePerAcre'] != 0){ ?>
                <div>Price Per Acre: $<?php echo number_format($property['PricePerAcre']); ?></div>
            <?php }?>
            <?php if (isset($property['PropertyTaxes']) && $property['PropertyTaxes'] != 0){ ?>
                <div>Property Taxes: $<?php echo number_format($property['PropertyTaxes']); ?></div>
            <?php }?>
            <?php if (isset($property['CapRate']) && $property['CapRate'] != 0){ ?>
                <div>CAP Rate: <?php echo round($property['CapRate'], 2) . '%'; ?></div>
            <?php }?>
            <?php if (isset($property['Noi']) && $property['Noi'] != 0){ ?>
                <div>NOI: <?php echo '$' . number_format($property['Noi']); ?></div>
            <?php }?>

        </div>
        <?php if (isset($property['PropertyDescription']) || isset($property['LocationDescription'])){ ?>
        <div class="overview">
            <h4 class="title info theme-color">Property Overview</h4>
            <?php
            if (isset($property['PropertyDescription'])){
                print '<p class="property-description">'.$property['PropertyDescription'].'</p>';
            }
            if (isset($property['LocationDescription'])){
                print '<p class="property-description">'.$property['LocationDescription'].'</p>';;
            }
            ?>
        </div>
        <?php } ?>
        <?php if ((isset($property['HouseholdIncome']) && ($property['HouseholdIncome']['RangeFrom'] != 0 || $property['HouseholdIncome']['RangeTo'] != 0))
            || (isset($property['PopulationRange']) && ($property['PopulationRange']['RangeFrom'] != 0 || $property['PopulationRange']['RangeTo'] != 0))
            || (isset($property['TrafficCounts']) && ($property['TrafficCounts']['RangeFrom'] != 0 || $property['TrafficCounts']['RangeTo'] != 0))
            || isset($property['County'])
            || isset($property['Zoning'])
            || isset($property['Utilities'])
            || isset($property['Divisible'])
            || ((isset($property['DivisibleMax']) && $property['DivisibleMax'] != 0) && (isset($property['DivisibleMin']) && $property['DivisibleMin'] != 0))
            || (isset($property['ParkingSpace']) && $property['ParkingSpace'] != 0 )
            || (isset($property['YearRenovated']) && $property['YearRenovated'] != 0 )
            || (isset($property['YearBuild']) && $property['YearBuild'] != 0 )
            || (isset($property['Occupancy']) && $property['Occupancy'] != 0)
            || (isset($property['TotalLotSize']) && $property['TotalLotSize'] != 0)
            || (isset($property['GrossLeasableArea']) && $property['GrossLeasableArea'] != 0)
        ){ ?>
        <h4 class="title info theme-color">Property Information</h4>
        <table>
            <tbody>
                <?php if (isset($property['GrossLeasableArea'])){ ?>
            <tr>
                <td>Total Building SF:</td>
                <td><?php echo $property->getSize('GrossLeasableArea', true, 'SF'); ?></td>
            </tr>
                <?php } ?>
                <?php  if (isset($property['TotalLotSize'])){ ?>
            <tr>
                <td>Total Lot Size <?php echo ($theme_options['show_size_in_acres'] == 'acres') ? 'Acres' : 'SF'; ?>:</td>
                <td><?php echo $property->getSize('TotalLotSize', true, $theme_options['show_size_in_acres']); ?></td>
            </tr>
                <?php } ?>
                <?php if (isset($property['Occupancy']) && ($property['Occupancy'] != 0 && strlen($property['Occupancy']) > 0 )){ ?>
            <tr>
                <td>Occupancy:</td>
                <td><?php echo $property['Occupancy'] . '%'; ?></td>
            </tr>
                <?php } ?>
                <?php if (isset($property['YearBuild']) && ($property['YearBuild'] != 0 && strlen($property['YearBuild']) > 0 )){ ?>
            <tr>
                <td>Year Built:</td>
                <td><?php echo $property['YearBuild']; ?></td>
            </tr>
                <?php } ?>
                <?php if (isset($property['YearRenovated']) && ($property['YearRenovated'] != 0 && strlen($property['YearRenovated']) > 0 )){ ?>
            <tr>
                <td>Year Renovated:</td>
                <td><?php echo $property['YearRenovated']; ?></td>
            </tr>
                <?php } ?>
                <?php if (isset($property['ParkingSpace']) && ($property['ParkingSpace'] != 0 && strlen($property['ParkingSpace']) > 0 )){ ?>
            <tr>
                <td>Parking Space:</td>
                <td><?php echo $property['ParkingSpace']; ?></td>
            </tr>
                <?php } ?>
                <?php if (isset($property['Zoning']) && ($property['Zoning'] != 0 && strlen($property['Zoning']) > 0 )){ ?>
            <tr>
                <td>Zoning:</td>
                <td><?php echo $property['Zoning']; ?></td>
            </tr>
                <?php } ?>
                <?php if (isset($property['County']) && ($property['County'] != 0 && strlen($property['County']) > 0 )){ ?>
            <tr>
                <td>County:</td>
                <td><?php echo $property['County']; ?></td>
            </tr>
                <?php } ?>

                <?php if (isset($property['Utilities']) && ($property['Utilities'] != 0 && strlen($property['Utilities']) > 0 )){ ?>
            <tr>
                <td>Utilities:</td>
                <td><?php echo ($property['Utilities']) ? 'Yes' : 'No'; ?></td>
            </tr>
                <?php } ?>

                <?php if (isset($property['Divisible']) && ($property['Divisible'] != 0 && strlen($property['Divisible']) > 0 )){ ?>
            <tr>
                <td>Divisible:</td>
                <td><?php echo ($property['Divisible']) ? 'Yes' : 'No'; ?></td>
            </tr>
                <?php } ?>

                <?php if ((isset($property['DivisibleMax']) && $property['DivisibleMax'] != 0) && (isset($property['DivisibleMin']) && $property['DivisibleMin'] != 0)){ ?>
            <tr>
                <td>Divisible Max/Min:</td>
                <td><?php echo $property['DivisibleMax']; ?>/<?php echo $property['DivisibleMin']; ?> Acres</td>
            </tr>
                <?php } ?>

                <?php if (isset($property['TrafficCounts']) && ($property['TrafficCounts']['RangeFrom']!=0 || $property['TrafficCounts']['RangeTo']!=0)){ ?>
            <tr>
                <td>Traffic Count:</td>
                <?php if ($property['TrafficCounts']['RangeFrom'] == $property['TrafficCounts']['RangeTo']){ ?>
                <td><?php echo number_format($property['TrafficCounts']['RangeFrom']); ?></td>
                <?php } else{ ?>
                <td><?php echo number_format($property['TrafficCounts']['RangeFrom']) . ' - ' . number_format($property['TrafficCounts']['RangeTo']); ?></td>
                <?php } ?>
            </tr>
                <?php } ?>
                <?php if (isset($property['PopulationRange']) && ($property['PopulationRange']['RangeFrom']!=0 || $property['PopulationRange']['RangeTo']!=0)){ ?>
            <tr>
                <td>Population:</td>
                <?php if ($property['PopulationRange']['RangeFrom'] == $property['PopulationRange']['RangeTo']){ ?>
                <td><?php echo number_format($property['PopulationRange']['RangeFrom']); ?></td>
                <?php } else{ ?>
                <td><?php echo number_format($property['PopulationRange']['RangeFrom']) . ' - ' . number_format($property['PopulationRange']['RangeTo']); ?></td>
                <?php } ?>
            </tr>
                <?php } ?>
                <?php if (isset($property['HouseholdIncome']) && ($property['HouseholdIncome']['RangeFrom']!=0 || $property['HouseholdIncome']['RangeTo']!=0)){ ?>
            <tr>
                <td>Income:</td>
                <?php if ($property['HouseholdIncome']['RangeFrom'] == $property['HouseholdIncome']['RangeTo']){ ?>
                <td><?php echo number_format($property['HouseholdIncome']['RangeFrom']); ?></td>
                <?php } else{ ?>
                <td><?php echo '$' . number_format($property['HouseholdIncome']['RangeFrom']) . ' - ' . ' $' . number_format($property['HouseholdIncome']['RangeTo']); ?></td>
                <?php } ?>
            </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
        <?php if (count($spaces) > 0){?>
        <h4 class="title info theme-color">Spaces</h4>
        <table class="theme-table">
            <tbody>
                <?php foreach($spaces as $space){ ?>
            <tr>
                <td><?php echo $space['Name'];?></td>
                <?php if($space['RentalRate']>0) {
                    if ($space['RentalRateIsUndisclosed'] == false) { ?>
                <td><?php echo '$'.$space['RentalRate'] . '  / PSF'; ?></td>
                <?php }
                  } ?>
                <td><?php echo number_format($space['Size']) . ' SF'; ?></td>
                <td><?php echo $space['SpaceType'];?></td>
            </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
    <div class="column less-half">
        <div class="section content">
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
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
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
                        zoom: 12,
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
        </div>
        <?php if (count($propertyDocuments) > 0) {?>
        <h4 class="title info theme-color">Attachments:</h4>
        <ul class="property-docs">
            <?php foreach ($propertyDocuments as $doc) {?>
            <li>
                <a href="<?php echo $doc['AttachmentPath']?>" target="_blank">
                    <?php if (isset($theme_options['show_document_icons_on_listing']) && $theme_options['show_document_icons_on_listing'] == 'on') { ?>
                        <?php preg_match('/\.(\w+)$/', $doc['AttachmentPath'], $matches); ?>
                        <img src='<?php echo plugin_dir_url(dirname(__FILE__)) . 'static/images/'.$matches[1].'-50.png';?>' class='property-attachment-icon' />
                    <?php } ?>
                    <?php echo $doc['AttachmentTitle']?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>


        <?php if (count($propertyLinks) > 0) {?>
        <h4 class="title info theme-color">Listing Links:</h4>
        <ul class="property-docs">
            <?php foreach ($propertyLinks as $doc) {?>
            <li>
                <a href="<?php echo $doc['Url']?>" target="_blank">
                    <?php echo $doc['Title']?>
                </a>
            </li>
            <?php } ?>
        </ul>
        <?php } ?>
        <h4 class="title info theme-color">Contact Listing Broker(s):</h4>
        <ul class="broker-list">
            <?php foreach($property['ListingAgents'] as $broker){ ?>
            <li>
                <div class="broker-item">
                    <h4>
                        <span class="name"><?php echo $broker['FirstName'] . ' ' . $broker['LastName']; ?></span>
                        <a href="mailto:<?php echo $broker['Email']; ?>?subject=<?php echo str_replace('+', '%20', urlencode($property_name)); ?>" target="_blank" class="icon-mail">
                            <img src="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png" alt="<?php echo plugin_dir_url(dirname(__FILE__))?>static/images/envelope.png">
                        </a>
                    </h4>
                    <span><?php echo $broker['Phone']; ?></span>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
    </div>
    </div>
    <div class="section content">
        <a href="javascript:history.back()">Back to Search Results</a>
    </div>
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
                            <img src="<?php echo $src; ?>" alt="<?php echo $src; ?>">
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
                <div class="carousel carousel-navigation carousel-navigation-popup <?php if (count($otherImages)<5){ echo 'centered-items';}?>">
                    <ul>
                        <?php foreach ($otherImages as $src) {?>
                        <li>
                            <div class="pagination-item">
                                <img src="<?php echo $src; ?>" alt="<?php echo $src; ?>">
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
}
get_footer();
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