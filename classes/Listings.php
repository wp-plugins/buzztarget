<?php

namespace BuzzTargetLive;

class Listings implements \Iterator, \ArrayAccess
{
    private $position = 0;
    private $container = array();
    protected $config;

    public function __construct(Config $config)
    {
        $this->position = 0;
        $this->config = $config;
    }

    public function __debugInfo() {
        return $this->container;
    }

    public static function getSearchParameters() { // returns available parameter values for use in search
        $listings = get_option('repl_listings');
    }

    public static function all() { // returns all listings, that are in the database
        $object = new self(new Config);
        $object->container = array();
        foreach(get_option('repl_listings') as $listing) {
            $object->container[] = new Listing($listing);
        }
        return $object;
    }

    public static function where(array $filter) { // returns filtered listings by input parameters
        $object = new self(new Config);
        $object->container = array();
        foreach(get_option('repl_listings') as $listing) {
            $matched = true;

            $forLease = (strtolower($listing['ListingType']) == 'forlease') ? true : false;
            $forSale = (strtolower($listing['ListingType']) == 'forsale') ? true : false;

            if(!($forLease || $forSale)){
                $forLease = true;
                $forSale = true;
            }

            if(count($filter)){
                foreach($filter as $key => $value){
                    switch($key){
                        case 'address_line_1': // address
                            if (! isset($listing['Property']['Address']['Address'])
                                || strripos($listing['Property']['Address']['Address'], $value) === false){
                                $matched = false;
                            }
                            break;
                        case 'address_line_2': // city
                            if (!isset($listing['Property']['Address']['City'])
                                || strripos(str_replace(array(',',' '), array("",""), $value), str_replace(array(',',' '), array("",""), $listing['Property']['Address']['City'])) === false){
                                $matched = false;
                            }
                            break;
                        case 'county': // address
                            if (! isset($listing['County'])
                                || strripos($listing['County'], $value) === false){
                                $matched = false;
                            }
                            break;
                        case 'address_zip_state': // state & zip
                            if(!isset($listing['Property']['Address']['State']) && !isset($listing['Property']['Address']['Zip'])){
                                $matched = false;
                            }else{
                                $stateZip = "";
                                if(isset($listing['Property']['Address']['State'])){
                                    $stateZip = $listing['Property']['Address']['State'];
                                }
                                if(isset($listing['Property']['Address']['Zip'])){
                                    $stateZip .= $listing['Property']['Address']['Zip'];
                                }
                                $addrStateZip = str_replace(array(',',' '), array("",""), $value);

                                if (strripos($stateZip, $addrStateZip) === false){
                                    $matched = false;
                                }
                            }
                            break;
                        case 'property_type':
                            if(!isset($listing['PropertyTypes'])){
                                $matched = false;
                            }else{
                                if(!isset($listing['PropertyTypes']) || !in_array($value, $listing['PropertyTypes'])) {
                                    $matched = false;
                                }
                            }
                            break;
                        case 'broker':
                            if(!isset($listing['ListingAgents'])){
                                $matched = false;
                            }else{
                                for($i = 0; $i < count($listing['ListingAgents']); ++$i){
                                    if($listing['ListingAgents'][$i]['FirstName'] . ' ' . $listing['ListingAgents'][$i]['LastName'] == $value){
                                        break;
                                    }
                                }
                                if($i >= count($listing['ListingAgents'])){
                                    $matched = false;
                                }
                            }
                            break;
                        case 'keyword':
                            if(!in_multiarray($value, $listing)){
                                $matched = false;
                            }
                            break;
                        case 'size_from': //&& $listing['TotalLotSize'] <= $sizeTo |  && $availableSpaceSize[1] <= $sizeTo
                            if ($forSale){
                                if(!isset($listing['TotalLotSize'])
                                   || $listing['TotalLotSize'] < $value ){
                                    $matched = false;
                                }
                            }elseif($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $availableSpaceSize = $getAvailableSpaceSize($listing['SpacesToLease']);
                                    if ($availableSpaceSize[0] < $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'size_to':
                            if ($forSale){
                                if(!isset($listing['TotalLotSize'])
                                    || $listing['TotalLotSize'] > $value ){
                                    $matched = false;
                                }
                            }elseif($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $availableSpaceSize = $getAvailableSpaceSize($listing['SpacesToLease']);
                                    if ($availableSpaceSize[1] > $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'price_from':
                            if ($forSale){
                                if(!isset($listing['PropertyPrice'])
                                    || $listing['PropertyPrice'] < $value){
                                    $matched = false;
                                }
                            }elseif ($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $spacePrice = $getSpacePrice($listing['SpacesToLease']);
                                    if ($spacePrice[0] < $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'price_to':
                            if ($forSale){
                                if(!isset($listing['PropertyPrice'])
                                    || $listing['PropertyPrice'] > $value){
                                    $matched = false;
                                }
                            }elseif ($forLease){
                                if(! isset($listing['SpacesToLease'])){
                                    $matched = false;
                                }else{
                                    $spacePrice = $getSpacePrice($listing['SpacesToLease']);
                                    if ($spacePrice[1] > $value){
                                        $matched = false;
                                    }
                                }
                            }
                            break;
                        case 'listing_types':
                            if (is_array($value) && !empty($value)){
                                for($i = 0; $i < count($value); ++$i){
                                    if ($value[$i] == strtolower($listing['ListingType'])){
                                        break;
                                    }
                                }
                                if($i >= count($listing['ListingType'])){
                                    $matched = false;
                                }
                            }
                            break;
                    }

                }
                if($matched){
                    $object->container[] = new Listing($listing);;
                }
            }
        }
        return $object;
    }

    public static function get() {} // returns a listing by id

    public function getListings() // loads listings from API
    {

        // Get options
        $api_options = get_option('buzztarget_api_options');

        // Get API url, key & secret key
        $api_url = $this->config->getValue('api_url');
        $api_key = (isset($api_options['api_key'])) ? $api_options['api_key'] : $this->config->getValue('api_key');
        $api_secret_key = (isset($api_options['api_secret_key'])) ? $api_options['api_secret_key'] : $this->config->getValue('api_secret_key');
        if (($ch = curl_init($api_url)))
        {
            $headers = array(
                "Authorization-ApiKey: {$api_key}",
                "Authorization-ApiSecretKey: {$api_secret_key}",
                "Content-type: application/json"
            );

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $listings = curl_exec($ch);
            curl_close($ch);

            $listings = json_decode($listings, true);
            $listings = $listings['Result'];

            $_listings = array();

            $listingFilterOptions = get_option('buzztarget_listings_filter_options');

            $allowedListingTypes = (isset($listingFilterOptions['listing_types'])) ?
                $listingFilterOptions['listing_types'] :
                null;

            $allowedListingStatuses = (isset($listingFilterOptions['listing_statuses'])) ?
                $listingFilterOptions['listing_statuses'] :
                null;

            foreach ($listings as $listing)
            {
                // Include any types that are allowed
                if (isset($listing['ListingType']))
                {
                    if ($allowedListingTypes
                        && in_array(strtolower($listing['ListingType']), $allowedListingTypes))
                    {
                        //Include any statuses that are allowed
                        if (isset($listing['Status']))
                        {
                            if ($allowedListingStatuses
                                && in_array(strtolower($listing['Status']), $allowedListingStatuses))
                            {
                                $_listings[] = $listing;
                            }
                        }
                    }
                }
            }
            unset($listing);

            $listings = $_listings;

            global $wpdb;
            $sVersion = $wpdb->get_var("select @@version"); if ( $sVersion{0} == 5 ) $wpdb->query('set sql_mode="";');

            // Get listings page ID.
            $listingsPageID = $wpdb->get_var("SELECT `ID` FROM `$wpdb->posts` WHERE `post_content` LIKE '%[all-listings][/all-listings]%' AND `post_status` = 'publish';");
            $salePageID = $wpdb->get_var("SELECT `ID` FROM `$wpdb->posts` WHERE `post_content` LIKE '%[for-sale][/for-sale]%' AND `post_status` = 'publish';");
            $leasePageID = $wpdb->get_var("SELECT `ID` FROM `$wpdb->posts` WHERE `post_content` LIKE '%[for-lease][/for-lease]%' AND `post_status` = 'publish';");

            // Only create the page if it doesn't exist.
            if (!$listingsPageID)
            {
                // Create new all-listings page
                $wpdb->insert(
                    $wpdb->posts,
                    array(
                        'post_author' => 1,
                        'post_content' => '[all-listings][/all-listings]',
                        'post_title' => 'Listings',
                        'post_status' => 'publish',
                        'post_name' => 'listings',
                        'post_type' => 'page'
                    )
                );

                // The listings page ID
                $listingsPageID = $wpdb->insert_id;

                // Insert meta data for the listings page, specifically the page template to use (recommended)
                $wpdb->insert(
                    $wpdb->postmeta,
                    array(
                        'post_id' => $listingsPageID,
                        'meta_key' => '_wp_page_template',
                        'meta_value' => 'template-full.php'
                    )
                );
            }

            // Delete all property pages
//            if (get_option('repl_properties') !== false)
//            {
//                $wpdb->query("DELETE FROM `{$wpdb->posts}` WHERE `post_type` = 'properties';");
//                delete_option('repl_properties');
//            }

            // Create a new post for each property and save them
            $properties = array();

            $existingPosts = $wpdb->get_results("SELECT * FROM `{$wpdb->posts}` WHERE `post_type` = 'properties';", ARRAY_A);

            foreach ($listings as $key => $listing)
            {
                $parentId = $listingsPageID;

                if($leasePageID && $listing['ListingType'] == 'ForLease'){
                    $parentId = $leasePageID;
                }else if($salePageID && $listing['ListingType'] == 'ForSale'){
                    $parentId = $salePageID;
                }

                if (isset($listing['Property']['PropertyName']))
                {
                    $listing['Property']['PropertyName'] = trim($listing['Property']['PropertyName']);
                    $propertyName = $listing['Property']['PropertyName'];
                    $propertyID = $listing['ListingId'];
                    if (!array_key_exists($propertyID, $properties))
                    {
                        $properties[$propertyName] = $listing;
                        $properties[$propertyID] = $listing;
                        $postName = $propertyID . '-' . strtolower(str_replace(
                            array('/',' ','--','---'),
                            '-',
                            str_replace( array('.',',','&','\'', '%', '?', '@', '#', '(', ')'), '', $propertyName)));

                        $postStatus = 'publish';


                        for($i=0; $i < count($existingPosts); ++$i){
                            if($existingPosts[$i]['post_name'] == $postName){
                                $postStatus = $existingPosts[$i]['post_status'];
                                wp_delete_post( $existingPosts[$i]['ID'], true );
                                array_splice($existingPosts, $i, 1);
                                break;
                            }
                        }

                        $wpdb->insert(
                            $wpdb->posts,
                            array(
                                'post_title' => $propertyName,
                                'post_name' => $postName,
                                'post_status' => $postStatus,
                                'post_author' => 1,
                                'post_type' => 'properties',
                                'post_parent' => $parentId,
                            )
                        );

                        $listings[$key]['wp_status'] = $postStatus;
                        $listings[$key]['wp_page_id'] = $wpdb->insert_id;
                    }
                }
            }
            if(count($existingPosts)){
                foreach($existingPosts as $ex_post){
                    wp_delete_post( $ex_post['ID'], true );
                }
            }

            // Delete all listings
            if (get_option('repl_listings') !== false)
            {
                delete_option('repl_listings');
            }

            if (get_option('repl_properties') !== false)
            {
                delete_option('repl_properties');
            }

            add_option('repl_listings', $listings);

            add_option('repl_properties', $properties);

            unset($listing);

            flush_rewrite_rules();

            return true;
        }
        return false;
    }

    public function count() {
        return count($this->container);
    }

    ///////////////////////////////////////
    // below are interfaces implementation methods to make the class act as a usual array
    ///////////////////////////////////////

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->container[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->container[$this->position]);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

}