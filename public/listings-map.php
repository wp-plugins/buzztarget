<?php
namespace BuzzTargetLive;

$listings = get_option('repl_listings');
$map_options = get_option('buzztarget_map_options');
$filtered_listings = array();

foreach ($listings as $listing) {
    $matched = true;

    if($propertyTypeFilter) {
        if(!isset($listing['PropertyTypes']) || !in_array($propertyTypeFilter, $listing['PropertyTypes'])) {
            $matched = false;
        }
    }

    if (!$matched)
        continue; // it did not match... nothin' to do with it

    $listing['PropertyMapIcon'] = (isset($map_options['markers'][$listing['PropertyTypes'][0]])
        && strlen($map_options['markers'][$listing['PropertyTypes'][0]]) > 0) ? $map_options['markers'][$listing['PropertyTypes'][0]] : $map_options['markers']['default'];

    $filtered_listings[] = $listing;
}


$vars = array(
    'listings' => $filtered_listings,
    'map_options' => $map_options,
    'width' => $widgetWidth,
    'height' => $widgetHeight
);
// free some memory
unset($filtered_listings);
unset($listings);
unset($map_options);
unset($widgetWidth);
unset($widgetHeight);

echo $this->twig->render('listings-map.twig', $vars);
unset($vars);
?>