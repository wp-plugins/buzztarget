<?php

namespace BuzzTargetLive;

class Listings implements \Iterator, \ArrayAccess
{
    private $position = 0;
    private $container = array();
    protected $config;

    // pagination
    protected $currentPage;
    protected $totalPages;
    protected $positionStart;
    protected $positionEnd;

    public function __construct(Config $config)
    {
        $this->position = 0;
        $this->config = $config;
    }

    public function __debugInfo() {
        return $this->container;
    }

    public static function getSearchParameters() { // returns available parameter values for use in search
        $params_list = array(
            'county' => array(),
            'property_type' => array(),
            'broker' => array()
            );
        $listings = get_option('repl_listings');
        foreach($listings as $listing){
            foreach($listing['ListingAgents'] as $agent){
                $broker = $agent['FirstName'] . ' ' . $agent['LastName'];
                if (!in_array($broker, $params_list['broker'])) {
                    $params_list['broker'][] = $broker;
                }
            }
            foreach($listing['PropertyTypes'] as $propertyType){
                if ($propertyType && !in_array($propertyType, $params_list['property_type'])) {
                    $params_list['property_type'][] = $propertyType;
                }
            }
            if ($listing['County'] && $listing['County'] != "" && !in_array($listing['County'], $params_list['county'])) {
                $params_list['county'][] = $listing['County'];
            }
        }

        return $params_list;
    }

    public static function all() { // returns all listings, that are in the database
        $object = new self(new Config);
        $object->container = array();
        $duplicates = array();
        foreach(get_option('repl_listings') as $listing) {
            if(in_array($listing['ListingId'], $duplicates)) continue;
            $duplicates[] = $listing['ListingId'];
            $object->container[] = new Listing($listing);
        }
        return $object;
    }

    public static function where(array $filter) { // returns filtered listings by input parameters
        $getAvailableSpaceSize = function ($spacesToLease) {
            $sizes = array();
            foreach ($spacesToLease as $space)
            {
                if (isset($space['Size']))
                {
                    $sizes[] = $space['Size'];
                }
            }
            unset($space);
            return array(min($sizes), max($sizes));
        };

        $getSpacePrice = function ($spacesToLease) {
            $rates = array();
            foreach ($spacesToLease as $space)
            {
                if (isset($space['RentalRate']))
                {
                    $rates[] = $space['RentalRate'];
                }
            }
            unset($space);
            return array(min($rates), max($rates));
        };

        $object = new self(new Config);
        $object->container = array();
        $duplicates = array();
        foreach(get_option('repl_listings') as $listing) {
            if(in_array($listing['ListingId'], $duplicates)) continue;

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
                                || strripos(strtolower(str_replace(array(',',' '), array("",""), $listing['Property']['Address']['City'])), strtolower(str_replace(array(',',' '), array("",""), $value))) === false){
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
                        case 'total_size_from':
                            $value = ($filter['total_size_by'] == 'acres') ? $value * 43560 : $value;
                            if(!isset($listing['TotalLotSize'])
                               || $listing['TotalLotSize'] < $value ){
                                $matched = false;
                            }
                            break;
                        case 'total_size_to':
                            $value = ($filter['total_size_by'] == 'acres') ? $value * 43560 : $value;
                            if(!isset($listing['TotalLotSize'])
                               || $listing['TotalLotSize'] > $value ){
                                $matched = false;
                            }
                            break;
                        case 'size_from': //&& $listing['TotalLotSize'] <= $sizeTo |  && $availableSpaceSize[1] <= $sizeTo
                            if ($forSale){
                                if(!isset($listing['GrossLeasableArea'])
                                   || $listing['GrossLeasableArea'] < $value ){
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
                                if(!isset($listing['GrossLeasableArea'])
                                    || $listing['GrossLeasableArea'] > $value ){
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
                    $duplicates[] = $listing['ListingId'];
                    $object->container[] = new Listing($listing);
                }
            }
        }
        return $object;
    }

    public static function getListing($id) {
        $listings = get_option('repl_listings');
        $listing = $listings[$id];
        return new Listing($listings[$id]);
    } // returns a listing by id

    public static function getProperty($id) {
        $listings = get_option('repl_properties');
        $listing = $listings[$id];
        return new Listing($listings[$id]);
    } // returns a property by id

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

    public function remove($key) {
        unset($this->container[$key]);
        return $this;
    }

    public function count() {
        return count($this->container);
    }

    ///////////////////////////////////////
    // Listings sorting implementation
    ///////////////////////////////////////

    public function order($sort_by_field)
    {
        $list = false;
        switch ($sort_by_field) {
            case "name_a_z":
                $field = 'Property';
                $subfield = 'PropertyName';
                $list = true;
                $reverse = false;
                break;
            case "name_z_a":
                $field = 'Property';
                $subfield = 'PropertyName';
                $list = true;
                $reverse = true;
                break;
            case "price_a_z":
                $field = 'PropertyPrice';
                $reverse = false;
                break;
            case "price_z_a":
                $field = 'PropertyPrice';
                $reverse = true;
                break;
            case "date_a_z":
                $field = 'Updated';
                $reverse = false;
                break;
            case "date_z_a":
                $field = 'Updated';
                $reverse = true;
                break;
            case "size_a_z":
                $field = 'TotalLotSize';
                $reverse = false;
                break;
            case "size_z_a":
                $field = 'TotalLotSize';
                $reverse = true;
                break;
            case "broker_a_z":
                $field = 'ListingAgents';
                $subfield = 0;
                $reverse = false;
                $list = true;
                break;
            case "broker_z_a":
                $field = 'ListingAgents';
                $subfield = 0;
                $reverse = true;
                $list = true;
                break;
            case "county_a_z":
                $field = 'County';
                $reverse = false;
                break;
            case "county_z_a":
                $field = 'County';
                $reverse = true;
                break;
        }

        $arr_has_no_property = array();
        $arr_has_property = array();
        for ($i=0; $i<count($this->container); $i++) {
            if(isset($this->container[$i][$field])){
                $arr_has_property[] = $this->container[$i];
            }
            else{
                $arr_has_no_property[] = $this->container[$i];
            }
        }
        $size =count($arr_has_property);
        for ($i=0; $i<$size; $i++) {
            for ($j=0; $j<$size-1-$i; $j++) {
                if ($list == true){
                    if ($this->getVal($arr_has_property[$j+1], $field, $subfield) < $this->getVal($arr_has_property[$j], $field, $subfield)) {
                        $this->swap($arr_has_property, $j, $j+1);
                    }
                }
                else{
                    if ($this->getVal($arr_has_property[$j+1],$field) < $this->getVal($arr_has_property[$j],$field)) {
                        $this->swap($arr_has_property, $j, $j+1);
                    }
                }
            }
        }

        if($reverse == true){
            $arr_has_property = array_reverse($arr_has_property);
        }
        $this->container = array_merge($arr_has_property, $arr_has_no_property);

        return $this;
    }

    private function swap(&$arr, $a, $b) {
        $tmp = $arr[$a];
        $arr[$a] = $arr[$b];
        $arr[$b] = $tmp;
    }

    private function getVal($obj, $field, $subfiled=null){
        if(isset($obj[$field])){
            if(isset($subfiled)){
                return $obj[$field][$subfiled];
            }else{
                return $obj[$field];
            }
        }else{
            return null;
        }

    }

    ///////////////////////////////////////
    // Listings pagination implementation
    ///////////////////////////////////////

    public function paginate($page = 1, $limit = 9)
    {

        $listingsCount = count($this->container);

        $totalPages = ($listingsCount > $limit) ? $listingsCount / $limit : 1;

        if (is_float($totalPages))
            $totalPages = ceil($totalPages);

        $currentPage = absint($page);

        $end = $limit * $currentPage;

        $start = absint($end - $limit);

        $currentPageListings = array();

        for ($i = $start; $i < $end; $i++)
        {
            if (isset($this->container[$i]))
            {
                $currentPageListings[] = $this->container[$i];
            }
        }

        $this->container = $currentPageListings;

        $this->currentPage   = $currentPage;
        $this->totalPages    = $totalPages;
        $this->positionStart = $start;
        $this->positionEnd   = $end;

        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getTotalPages()
    {
        return $this->totalPages;
    }

    public function getStart()
    {
        return $this->positionStart;
    }

    public function getEnd()
    {
        return $this->positionEnd;
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

function _normaliseString($string){
    return strtolower(preg_replace('/\s+/', ' ', preg_replace('/[^a-zA-Z0-9\s]/', '', $string)));
}

function in_multiarray($elem, $array) {
    foreach ($array as $key => $value) {
        if(is_array($value)){
            if(in_multiarray($elem, $value))
                return true;
        }
        else{
            if(strripos(_normaliseString($value), _normaliseString($elem))!==false) return true;
        }
    }
    return false;
}

?>