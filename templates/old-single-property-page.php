<?php

// Header
get_header();

if (have_posts()):
    the_post();

    $page_slider = get_post_meta($post->ID, 'office_page_slider', true);

    if ($page_slider == 'enable')
    {
        get_template_part( 'includes/page-slides');
    }

    ?>


    <div id="page-heading">
        <h1>
            <?php the_title(); ?>
        </h1>

        <?php 
        if (function_exists('office_breadcrumbs')):
            if($data['disable_breadcrumbs'] !='disable'):
                office_breadcrumbs();
            endif;
        endif;
        ?> 

    </div>

    <div class="post full-width clearfix">
        <?php 
        $properties = get_option('repl_properties');
        $propertyName = get_the_title();
        $property = $properties[$propertyName];
        $listingType = $property['ListingType'];
        $forSale = ($listingType === 'ForSale') ? true : false;
        $forLease = ($listingType === 'ForLease') ? true : false;
        ?>
        <div id="bt-listing-container">
            <div id="bt-listing-images-details-container">
                <div id="bt-listing-details-container">
                    <table class="bt-listing-detail">
                        <tr>
                            <td colspan="2" class="bt-no-border">
                                <p class="bt-no-margin">
                                    <b><?php echo $property['Property']['Address']['Address']; ?></b>
                                </p>
                                <p>
                                    <b><?php echo $property['Property']['Address']['City']; ?>,
                                    <?php echo $property['Property']['Address']['State']; ?>
                                    <?php echo $property['Property']['Address']['Zip']; ?></b>
                                </p>
                            </td>
                        </tr>
                        <?php if ($forLease): ?>
                            <?php
                                function bt_get_available_space_size($spacesToLease) {
                                    $sizes = array();
                                    foreach ($spacesToLease as $space) {
                                        if ($space['Size']) $sizes[] = $space['Size'];
                                    }
                                    return array(min($sizes), max($sizes));
                                }
                                function bt_get_rental_rate($spacesToLease) {
                                    $rates = array();
                                    foreach ($spacesToLease as $space) {
                                        if ($space['RentalRate']) $rates[] = $space['RentalRate'];
                                    }
                                    return array(min($rates), max($rates));
                                }
                            ?>
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
                                    <?php echo '$'.$available[0] . ' - $'.$available[1] . ' PSF'; ?>
                                </td>
                            </tr>
                        <?php elseif ($forSale): ?>
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
                        <tr>
                            <td><b><?php echo 'Listing Links: '; ?></b></td>
                            <td>
                                <?php
                                $documents = '';
                                foreach ($property['ListingDocuments'] as $document)
                                {
                                    if (isset($document['AttachmentTitle']) && isset($document['AttachmentPath']))
                                    {
                                        $documents .= '<a href="'.wp_strip_all_tags($document['AttachmentPath']).'">';
                                        $documents .= wp_strip_all_tags($document["AttachmentTitle"]) . '</a>, ';
                                    }
                                }
                                $documents = rtrim($documents, ', ');
                                echo $documents;
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="bt-listing-images-container">
                    <div id="bt-listing-image-container">
                        <div id="bt-listing-image">
                            <img src="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>" />
                            <div id="bt-listing-image-enlarge-photo">
                                <a target="_blank" href="<?php echo $property['ListingImages'][0]['AttachmentPath']; ?>">
                                    Enlarge Photo
                                </a>
                            </div>
                        </div>
                    </div>
                    <div id="bt-listing-thumbnails-container">
                        <?php
                        $i = 0;
                        foreach ($property['ListingImages'] as $propertyThumbnail)
                        {
                            if (isset($propertyThumbnail['AttachmentPath']))
                            {
                                ?>
                                <a href="#">
                                    <img src="<?php echo $propertyThumbnail['AttachmentPath']; ?>" />
                                </a>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div id="bt-listing-main-details-container">
                <?php if ($forLease): ?>
                    <h2>Spaces For Lease</h2>
                    <table class="bt-spaces-to-lease">
                        <?php foreach ($property['SpacesToLease'] as $space): ?>
                            <?php if ($space['Name']): ?>
                                <tr>
                                    <td><?php echo $space['Name']; ?></td>
                                    <td><?php echo '$'.$space['RentalRate'] . ' PSF / Year'; ?></td>
                                    <td><?php echo number_format($space['Size']) . ' sq. ft.'; ?></td>
                                    <td><?php echo $space['SpaceType']; ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>    
                <?php endif; ?>
                <h2>Description</h2>
                <p>
                    <?php echo $property['PropertyDescription']; ?>
                </p>
                <h2>Location Description</h2>
                <p>
                    <?php echo $property['LocationDescription']; ?>
                </p>
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
                        zoom: 8,
                        center: new google.maps.LatLng(lat, lon),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    map = new google.maps.Map(document.getElementById('bt-map-canvas'), mapOptions);
                    var marker = new google.maps.Marker({
                      position: new google.maps.LatLng(lat, lon),
                      map: map,
                      title: address + '\n' + city + ', ' + state + ' ' + zip
                        });
                }
                google.maps.event.addDomListener(window, 'load', initialize);
                </script>
                <h2>Google Map</h2>
                <div id="bt-map-canvas" style="width: 900px; height: 600px; margin:0px; padding: 0px;">
                </div>
            </div>
        </div>
    </div>
    <?php
endif;

// Footer
get_footer();