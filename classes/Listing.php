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

    public function getMapIcon(array $map_options) {
        $this->attributes['PropertyMapIcon'] = (isset($map_options['markers'][$this->attributes['PropertyTypes'][0]])
            && strlen($map_options['markers'][$this->attributes['PropertyTypes'][0]]) > 0) ? $map_options['markers'][$this->attributes['PropertyTypes'][0]] : $map_options['markers']['default'];

        return $this->attributes['PropertyMapIcon'];
    }

    private function sortImages() {
        if(isset($this->attributes['ListingImages']) && count($this->attributes['ListingImages'])) {
            $new_images = array();
            foreach($this->attributes['ListingImages'] as $image) {
                $new_images[$image["OrdinalNumber"]] = $image;
            }
            $this->attributes['ListingImages'] = $new_images;
            ksort($this->attributes['ListingImages']);
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