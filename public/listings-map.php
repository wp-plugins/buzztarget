<?php
namespace BuzzTargetLive;

if($propertyTypeFilter) {
    $listings = Listings::where(array('property_type' => $propertyTypeFilter));
} else {
    $listings = Listings::all();
}

$map_options = get_option('buzztarget_map_options');

foreach ($listings as $listing) {
    $listing->getMapIcon($map_options);
}

$vars = array(
    'listings' => $listings,
    'properties_url' => site_url() . '/properties',
    'map_options' => $map_options,
    'width' => $widgetWidth,
    'height' => $widgetHeight
);

// free some memory
unset($listings);
unset($map_options);
unset($widgetWidth);
unset($widgetHeight);

echo $this->twig->render('listings-map.twig', $vars);
unset($vars);
?>