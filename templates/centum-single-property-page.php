<?php

get_header();

$theme_options = get_option('buzztarget_theme_options');

$themeColor = $theme_options['theme_color'];

$properties = get_option('repl_properties');
$property_name = get_the_title();
$property = $properties[$property_name];

$phoneImage = plugin_dir_url(dirname(__FILE__)) . 'static/images/phone.png';

/*
echo '<pre>';
var_dump($property);
echo '</pre>';
*/

$otherImages = array();
foreach ($property['ListingImages'] as $propertyThumbnail):
    if (isset($propertyThumbnail['AttachmentPath'])):
        $otherImages[] = $propertyThumbnail['AttachmentPath'];
    endif;
endforeach;

$currentImage = 1;
$imagesCount = count($otherImages);

?>

<!-- 960 Container Start -->
<div class="container">
    <div class="sixteen columns">
        <div id="page-title">
            <?php $breadcrumbs = ot_get_option('centum_breadcrumbs'); ?>
            <h1 <?php if ($breadcrumbs === 'yes') echo 'class="has-breadcrumbs"';?>>
                <?php the_title(); ?>
                <?php $subtitle  = get_post_meta($post->ID, 'incr_subtitle', true);
                if ( $subtitle) {
                    echo ' <span>/ '.$subtitle.'</span>';
                } ?>
            </h1>
            <?php if(ot_get_option('centum_breadcrumbs') == 'yes') echo dimox_breadcrumbs() ;?>
            <div id="bolded-line"></div>
        </div>
    </div>
</div>
<!-- 960 Container End -->

<div id="bt-single-property">
    <!-- Images, Attachments, Map -->
    <div id="bt-single-property-left-side"> 
        <!-- Images -->
        <div id="bt-single-property-image-top-bar" class="bt-single-property-image-bar" <?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
            <p>
                Images <?php echo $currentImage . '/' . $imagesCount; ?>
            </p>
        </div>
        <!-- Images Container -->
        <div id="bt-single-property-image">
            <img src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" />
            <script type="text/javascript">
                var otherImages = <?php echo json_encode($otherImages); ?>;
            </script>
        </div>
        <div id="bt-single-property-image-bottom-bar" class="bt-single-property-image-bar" <?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
            <p>
                <a href="#" id="bt-previous-property-image"><</a>
            </p>
            <p>
                <a href="#" id="bt-next-property-image">></a>
            </p>
        </div>

        <div class="bt-single-property-clear">&nbsp;</div>

        <!-- Attachments (display only if any exist)-->
        <?php if (isset($property['ListingDocuments']) && !empty($property['ListingDocuments'])): ?>
            <div id="bt-single-property-attachments">
                <strong class="bt-single-property-strong-head" <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>Attachments</strong>
                <hr class="bt-single-property-hr" <?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                <?php
                foreach ($property['ListingDocuments'] as $propertyThumbnail):
                    if (isset($propertyThumbnail['AttachmentPath'], $propertyThumbnail['AttachmentTitle'])):
                        ?>
                        <p>
                            <a href="<?php echo $propertyThumbnail['AttachmentPath']; ?>"<?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                            <?php echo $propertyThumbnail['AttachmentTitle']; ?>
                            </a>
                        </p>
                        <?php
                    endif;
                endforeach;
                ?>
            </div>
            <!-- Clear -->
            <div class="bt-single-property-clear">&nbsp;</div>
        <?php endif; ?>

        <!-- Google Map -->
        <div id="bt-single-property-googlemap">
            <?php 
            $lat = $property['Lat'];
            $lon = $property['Lon'];
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
        </div>

        <?php if (isset($property['BrokerPhone'])): ?>
            <!-- Clear -->
            <div class="bt-single-property-clear">&nbsp;</div>
            <!-- Request a call -->
            <div id="bt-single-property-request-call">
                <div>
                    <img src="<?php echo $phoneImage; ?>" />
                </div>
                <div>
                    <strong <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>Request A Call</strong>
                    <p><?php echo $property['BrokerPhone']; ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Details, Spaces (if lease), Contact Info etc -->
    <div id="bt-single-property-right-side">
        <!-- Overview/Details -->
        <div id="bt-single-property-details">
            <!-- Vertical bar -->
            <div id="bt-single-property-vertical-bar"<?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>&nbsp;</div>
            <!-- Type -->
            <p>
                <strong <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                    <?php
                    $listingType = ($property['ListingType'] === 'ForSale') ? 'For Sale' : 'For Lease';
                    ?>
                    <?php echo $listingType; ?>
                </strong>
            </p>
            <!-- Name, address -->
            <div id="bt-single-property-address">
                <p>
                    <strong <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                        <?php echo $property_name; ?>
                    </strong>
                </p>
                <p>
                    <?php echo $property['Property']['Address']['Address']; ?>
                </p>
                <p>
                    <?php echo $property['Property']['Address']['City']; ?>,
                    <?php echo $property['Property']['Address']['State']; ?>
                    <?php echo $property['Property']['Address']['Zip']; ?>
                </p>
            </div>
            <!-- Details Labels -->
            <div id="bt-single-property-type-details">
                <?php if ($listingType === 'For Sale'): ?>
                    <p>Price:</p>
                    <p>Cap Rate:</p>
                    <p>NOI:</p>
                <?php elseif ($listingType === 'For Lease'): ?>
                    <p>Available:</p>
                    <p>Rental Rate:</p>
                    <p>Total Lot Size:</p>
                <?php endif; ?>
            </div>
            <!-- Details values -->
            <div id="bt-single-property-type-details-values">
                <?php if ($listingType === 'For Sale'): ?>
                    <p>
                        <?php echo '$' . number_format($property['PropertyPrice']); ?>
                    </p>
                    <p>
                        <?php echo round($property['CapRate'], 2) . '%'; ?>
                    </p>
                    <p>
                        <?php echo '$' . number_format($property['Noi']); ?>
                    </p>
                <?php elseif ($listingType === 'For Lease'): ?>
                    <p>
                        <?php $available = bt_get_available_space_size($property['SpacesToLease']); ?>
                        <?php echo number_format($available[0]) . ' - ' . number_format($available[1]) . ' sq. ft.'; ?>
                    </p>
                    <p>
                        <?php $available = bt_get_rental_rate($property['SpacesToLease']); ?>
                        <?php echo '$'.$available[0] . ' - $'.round($available[1]) . ' PSF'; ?>
                    </p>
                    <p>
                        <?php echo number_format($property['TotalLotSize']) . ' sq. ft.'; ?>
                    </p>
                <?php endif; ?>
            </div>
        </div> <!-- #bt-single-property-details -->

        <!-- Clear -->
        <div class="bt-single-property-clear">&nbsp;</div>

        <!-- Information -->
        <div id="bt-single-property-information">
            <strong class="bt-single-property-strong-head" <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>Property Information</strong>
            <hr class="bt-single-property-hr" <?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
            <table id="bt-single-property-information-table">
                <?php if ($listingType === 'For Lease'): ?>
                    <tr>
                        <td><b><?php echo 'Gross Leasable Area (SF):'; ?></b></td>
                        <td><?php echo number_format($property['GrossLeasableArea']) . ' sq. ft.'; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Term Range (Years):'; ?></b></td>
                        <td><?php echo $property['TermRange']['RangeFrom'] . '-' . $property['TermRange']['RangeTo']; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Total Lot Size:'; ?></b></td>
                        <td><?php echo number_format($property['TotalLotSize']) . ' sq. ft.'; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Available:'; ?></b></td>
                        <td>
                            <?php $available = bt_get_available_space_size($property['SpacesToLease']); ?>
                            <?php echo number_format($available[0]) . ' - ' . number_format($available[1]) . ' sq. ft.'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Rental Rate:'; ?></b></td>
                        <td>
                            <?php $available = bt_get_rental_rate($property['SpacesToLease']); ?>
                            <?php echo '$'.$available[0] . ' - $'.round($available[1]) . ' PSF'; ?>
                        </td>
                    </tr>
                <?php elseif ($listingType === 'For Sale'): ?>
                    <tr>
                        <td><b><?php echo 'Price: '; ?></b></td>
                        <td><?php echo '$' . number_format($property['PropertyPrice']); ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Cap Rate: ';?></b></td>
                        <td><?php echo round($property['CapRate'], 2) . '%'; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'NOI: '; ?></b></td>
                        <td><?php echo '$' . number_format($property['Noi']); ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Building Size: '; ?></b></td>
                        <td><?php echo number_format($property['BuildingSize']) . ' sq. ft.'; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Lot Size: '; ?></b></td>
                        <td><?php echo number_format($property['TotalLotSize']) . ' sq. ft'; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Year Built: '; ?></b></td>
                        <td><?php echo $yearBuilt = ($property['YearBuild']) ? $property['YearBuild'] : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo 'Status: ';?></b></td>
                        <td><?php echo $property['Status']; ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div> <!-- #bt-single-property-information -->

        <!-- Additional Information -->
        <div id="bt-single-property-additional-info">
            <strong class="bt-single-property-strong-head" <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>Additional Information</strong>
            <hr class="bt-single-property-hr" <?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
            <p>
                <?php 
                    print (isset($property['PropertyDescription']) ? 
                        $property['PropertyDescription'] :
                        '');
                ?>
                <?php 
                    print (isset($property['LocationDescription']) ? 
                        $property['LocationDescription'] :
                        '');
                ?>
            </p>
        </div>

        <!-- Spaces to Lease (if property type is For Lease) -->
        <?php if ($listingType === 'For Lease' && isset($property['SpacesToLease'])): ?>
            <div id="bt-single-property-spaces">
                <strong class="bt-single-property-strong-head" <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>Spaces</strong>
                <hr class="bt-single-property-hr" <?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                <table>
                    <?php foreach ($property['SpacesToLease'] as $key => $space): ?>
                        <tr <?php if ($key % 2 !== 0): ?> style="background-color:<?php echo $themeColor; ?>!important;" <?php endif; ?>>
                            <?php if (isset($space['Name'])): ?>
                                <td>
                                    <?php echo $space['Name']; ?>
                                </td>
                            <?php endif; ?>
                            <td><?php echo '$'.$space['RentalRate'] . ' PSF / Year'; ?></td>
                            <td><?php echo number_format($space['Size']) . ' sq. ft.'; ?></td>
                            <td><?php echo $space['SpaceType']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <!-- Clear -->
            <div class="bt-single-property-clear">&nbsp;</div>
        <?php endif; ?>

        <?php if (isset($property['BrokerFirstName'], $property['BrokerLastName'], $property['BrokerEmail'], $property['BrokerPhone'])): ?>
            <!-- Contact -->
            <div id="bt-single-property-contact">
                <strong class="bt-single-property-strong-head" <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>For More Information Contact:</strong>
                <hr class="bt-single-property-hr" <?php if ($themeColor): ?> style="background-color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                <address>
                    <?php echo $property['BrokerFirstName'] . ' ' . $property['BrokerLastName']; ?>
                    <br />
                    <a href="mailto:<?php echo $property['BrokerEmail']; ?>" <?php if ($themeColor): ?> style="color: <?php echo $themeColor; ?> !important;" <?php endif; ?>>
                        <?php echo $property['BrokerEmail']; ?>
                    </a> 
                    <br />
                    <?php echo $property['BrokerPhone']; ?>
                </address>
            </div>
        <?php endif; ?>

    </div> <!-- #bt-single-property-right-side -->
    <p style="clear: both; padding: 30px 0 0 0;">
        <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back to search results</a>
    </p>
</div> <!-- #bt-single-property -->

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


get_footer();