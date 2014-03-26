<?php

/**
 * Manipulates HTTP super global variables 
 * (currently: $_GET, $_POST, $_REQUEST)
 *
 * @since 1.1.0
 *
 * @package BuzzTargetLive
 */

namespace BuzzTargetLive;

class Request
{
    /**
     * Checks whether the request method used to access the page was $_POST
     *
     * @access public
     *
     * @since 1.1.0
     *
     * @return bool True otherwise false
     */
    public function isPost()
    {
        return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST');
    }

    /**
     * Checks whether the request method used to access the page was $_GET
     *
     * @access public
     *
     * @since 1.1.0
     *
     * @return bool True otherwise false
     */
    public function isQuery()
    {
        return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET');
    }
    
    /**
     * Returns $_GET value(s) specified by key(s) filtered by optional
     * filter(s).
     *
     * @access public
     *
     * @since 1.1.0
     *
     * @param string|int|array    $keys    Array of keys as values or provided
     *                                     as a string or int.
     * @param array|callable|null $filters Array of callback functions as
     *                                     values, a callback function or null
     *                                     to return the value(s) unfiltered.
     *
     * @return mixed False if a key doesn't exist, is an invalid type or a
     *               provided filter isn't callable otherwise the value(s) at
     *               the specified key(s).
     */
    public function getQueryValues($keys, $filters = null)
    {
        return $this->getSuperGlobalValues($_GET, $keys, $filters);
    }

    /**
     * Returns $_POST value(s) specified by key(s) filtered by optional
     * filter(s).
     *
     * @access public
     *
     * @since 1.1.0
     *
     * @param string|int|array    $keys    Array of keys as values or provided
     *                                     as a string or int.
     * @param array|callable|null $filters Array of callback functions as
     *                                     values, a callback function or null
     *                                     to return the value(s) unfiltered.
     *
     * @return mixed False if a key doesn't exist, is an invalid type or a
     *               provided filter isn't callable otherwise the value(s) at
     *               the specified key(s).
     */
    public function getPostValues($keys, $filters = null)
    {
        return $this->getSuperGlobalValues($_POST, $keys, $filters);
    }

    /**
     * Returns $_REQUEST value(s) specified by key(s) filtered by optional
     * filter(s).
     *
     * @access public
     *
     * @since 1.1.0
     *
     * @param string|int|array    $keys    Array of keys as values or provided
     *                                     as a string or int.
     * @param array|callable|null $filters Array of callback functions as
     *                                     values, a callback function or null
     *                                     to return the value(s) unfiltered.
     *
     * @return mixed False if a key doesn't exist, is an invalid type or a
     *               provided filter isn't callable otherwise the value(s) at
     *               the specified key(s).
     */
    public function getRequestValues($keys, $filters = null)
    {
        return $this->getSuperGlobalValues($_REQUEST, $keys, $filters);
    }

    /**
     * Returns value(s) at specified HTTP super global var key(s) (un)filtered.
     *
     * @access protected
     *
     * @since 1.1.0
     *
     * @param array               $superGlobal  The HTTP super global variable
     * @param array|string|int    $keys         Array of keys as values or
     *                                          provided as a string or int.
     * @param array|callable|null $filters      Array of callback functions as
     *                                          values, a callback function or
     *                                          null to return the value(s)
     *                                          unfiltered.
     *
     * @return mixed False if a key doesn't exist, is an invalid type or a
     *               provided filter isn't callable otherwise the value(s) at
     *               the specified key(s).
     */
    protected function getSuperGlobalValues($superGlobal, $keys, $filters)
    {
        // Returning multiple values
        if (is_array($keys))
        {
            $values = array();

            $keysCount = count($keys);

            foreach ($keys as $index => $key)
            {
                if (!array_key_exists($key, $superGlobal))
                {
                    $values[$index] = false;
                }
                else
                {
                    $value = $superGlobal[$key];

                    // If a filter was provided, use it
                    $filter = $this->getFilter($filters, $index);

                    // Apply the filter (just returns value if no filter
                    // exists)
                    $values[$index] = $this->applyFilter($value, $filter);
                }
            }
            unset($key);
            return $values;
        }
        // Returning a single value
        elseif (is_string($keys) || is_int($keys))
        {
            if (!array_key_exists($keys, $superGlobal))
                return false;

            $value = $superGlobal[$keys];

            // If a filter was provided, use it
            $filter = $this->getFilter($filters);

            // Apply the filter (just returns value if no filter
            // exists)
            return $this->applyFilter($value, $filter);
        }
        else // Invalid type for keys
        {
            return false;
        }
    }

    /**
     * Returns a filter after checking it exists and is callable
     *
     * @access protected
     *
     * @since 1.1.0
     *
     * @param array|callable|null $filters Array of callback functions as
     *                                     values, a callback function or null
     *                                     to return the value(s) unfiltered.
     * @param int|null            $index   (Optional) Index for filters if
     *                                     filters is an array.
     *
     * @return mixed False if the filter is not callable, null if no filter
     *               was provided, otherwise the filter
     */
    protected function getFilter($filters, $index = null)
    {
        // No filter provided
        if (is_null($filters)) return null;

        // Array of callback functions
        if (is_array($filters))
        {
            // No filter provided for this element
            if (!isset($filters[$index]))
                return null;

            // Filter provided but not callable
            if (!is_callable($filters[$index]))
                return false;

            return $filters[$index];
        }
        
        return (is_callable($filters)) ? $filters : false;
    }

    /**
     * Returns value(s) after being optionally filtered
     *
     * @access protected
     *
     * @since 1.1.0
     *
     * @param mixed         $value  The value(s)
     * @param callable|null $filter (Optional) The callback function to filter
     *                              the value(s)
     *
     * @return mixed False if a filter was provided but not callable otherwise
     *               the value (un)filtered
     */
    protected function applyFilter($value, $filter = null)
    {
        // No filter provided, return unfiltered
        if (is_null($filter))
            return $value;

        // Filter provided but not callable
        if (false === $filter)
            return $filter;

        // Apply filter to all value's elements
        if (is_array($value))
            return call_user_func_array($filter, $value);

        // Apply filter to value
        return call_user_func($filter, $value);
    }
}