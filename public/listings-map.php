<?php
namespace BuzzTargetLive;

$listings = get_option('repl_listings');
$map_options = get_option('buzztarget_map_options');

foreach ($listings as $key => $listing) {
    $matched = true;

    if($propertyTypeFilter) {
        if(!isset($listing['PropertyTypes']) || !in_array($propertyTypeFilter, $listing['PropertyTypes'])) {
            $matched = false;
        }
    }

    $listings[$key]['PropertyMapIcon'] = (isset($map_options['markers'][$listing['PropertyTypes'][0]])
        && strlen($map_options['markers'][$listing['PropertyTypes'][0]]) > 0) ? $map_options['markers'][$listing['PropertyTypes'][0]] : $map_options['markers']['default'];
}


$vars = array(
    'listings' => $listings,
    'map_options' => $map_options,
    'width' => $widgetWidth,
    'height' => $widgetHeight
);

echo $this->twig->render('listings-map.twig', $vars);
?>