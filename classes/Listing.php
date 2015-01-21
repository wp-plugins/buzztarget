<?php

namespace BuzzTargetLive;

class Listing implements \ArrayAccess {

    private $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
        $this->sortImages();
    }

    public function __debugInfo() {
        return $this->attributes;
    }

    public function getMapIcon($map_options) {
        if(!is_array($map_options))
            return '';
        $this->attributes['PropertyMapIcon'] = (isset($map_options['markers'][$this->attributes['PropertyTypes'][0]])
            && strlen($map_options['markers'][$this->attributes['PropertyTypes'][0]]) > 0) ? $map_options['markers'][$this->attributes['PropertyTypes'][0]] : $map_options['markers']['default'];

        return trim($this->attributes['PropertyMapIcon']);
    }

    public function getSize($type, $format = false, $units = "SF") {
        $size = 0;
        if($type == "building" || $type == "GrossLeasableArea")
            $size = $this->attributes["GrossLeasableArea"];
        else if ($type == "lot" || $type == "TotalLotSize")
            $size = $this->attributes["TotalLotSize"];
        if($units == "Acres" || $units == "acres")
            $size = $size / 43560;
        if($format)
            return number_format($size, ($units == "Acres" || $units == "acres") ? 2 : 0, ".", ",");
        return $size;
    }

    private function sortImages() {
        if(isset($this->attributes['ListingImages']) && count($this->attributes['ListingImages'])) {
            $new_images = array();
            foreach($this->attributes['ListingImages'] as $image) {
                $new_images[$image["OrdinalNumber"]] = $image;
            }
            ksort($new_images);
            $this->attributes['ListingImages'] = array();
            foreach($new_images as $image) {
                $this->attributes['ListingImages'][] = $image;
            }
        }

        return $this;
    }

    ///////////////////////////////////////
    // below are interfaces implementation methods to make the class act as a usual array
    ///////////////////////////////////////

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->attributes[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->attributes[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null;
    }
}

?>