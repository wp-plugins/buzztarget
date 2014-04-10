<?php

namespace BuzzTargetLive;

use Twig_Autoloader;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

function load_twig(Config $config)
{
	// Get paths
	$vendorPath = $config->getValue('vendor_path');
	$templatesPath = $config->getValue('templates_path');
	$adminTemplatesPath = $config->getValue('admin_templates_path');

	// Autoloads Twig's classes.
	require_once "{$vendorPath}twig/twig/lib/Twig/Autoloader.php";
	Twig_Autoloader::register();

	// Register our paths with Twig.
	$loader = new Twig_Loader_Filesystem($templatesPath);
	$loader->addPath($adminTemplatesPath, 'admin');
	$twig = new Twig_Environment($loader, array(
		'debug' => true,
	));
	$twig->addExtension(new Twig_Extension_Debug());

	// Twig custom functions.
	$displayRentalRateMinMax = new Twig_SimpleFunction('display_rental_rate_min_max', function ($spacesToLease) {
		$rates = array();
		foreach ($spacesToLease as $space) {
			if (isset($space['RentalRate'])) $rates[] = $space['RentalRate'];
		}

		if (count($rates) > 1)
			echo min($rates) . ' - ' . max($rates);
	});
	$twig->addFunction($displayRentalRateMinMax);

	$displayAvailableMinMax = new Twig_SimpleFunction('display_available_min_max', function ($spacesToLease) {
		$sizes = array();
		foreach ($spacesToLease as $space) {
			if (isset($space['Size'])) $sizes[] = $space['Size'];
		}
		echo number_format(min($sizes)) . ' - ' . number_format(max($sizes));
	});
	$twig->addFunction($displayAvailableMinMax);

	$filter = new Twig_SimpleFilter('str_replace_spaces', function ($string) {
		return str_replace(' ', '', $string);
	});
	$twig->addFilter($filter);

	return $twig;
}