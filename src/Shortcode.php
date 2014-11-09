<?php namespace Nerweb\Shortcode;

use Closure;

/**
 * Class that parses strings from short codes to real value
 *
 * Class Shortcode
 * @package Nerweb\Shortcode
 */
class Shortcode {

    /**
     * Items of registered short codes
     *
     * @var array
     */
    protected $shortCodes = array();

    /**
     * Register short code in shortCodes collection
     *
     * @param mixed             $shortCode  - if array, use key value as short code data
     * @param string|closure    $Closure    - Optional if shortCode param is array
     * @return void
     */
    public function register($shortCode, $Closure = NULL)
    {
        if (is_array($shortCode))
        {
            foreach ($shortCode as $key => $value)
            {
                $this->shortCodes[$key] = $value;
            }
        }
        else
        {
            $this->shortCodes[$shortCode] = $Closure;
        }
    }

    /**
     * Return the short code value
     *
     * @param string    $shortCode
     * @param mixed     $default
     * @return mixed
     */
    public function get($shortCode, $default = NULL)
    {
        return $this->has($shortCode) ? $this->shortCodes[$shortCode] : $default;
    }

    /**
     * Remove item from the short codes
     *
     * @param string    $shortCode
     * @param mixed     $Closure
     * @return void
     */
    public function remove($shortCode, $Closure)
    {
        unset($this->shortCodes[$shortCode]);
    }

    /**
     * Check if shortcode is registered
     *
     * @param string    $shortCode
     * @param mixed     $default
     * @return bool
     */
    public function has($shortCode, $default = false)
    {
        return isset($this->shortCodes[$shortCode]) ? $this->shortCodes[$shortCode] : $default;
    }

    /**
     * Run a shortcode and its value
     *
     * @param string    $shortCode
     * @param array     $parameters
     * @return mixed
     */
    public function run($shortCode, array $parameters = array())
    {
        if ($this->has($shortCode))
        {
            return $this->runClosure($this->get($shortCode), $parameters);
        }

        return false;
    }

    /**
     * Decode a string that has a short code init
     *
     * @param string    $strings
     * @param array     $shortCodes
     * @return string
     */
    public function decode($strings, array $shortCodes = array())
    {
        // Add aditional shortcodes
        $this->register($shortCodes);

        // start decoding
        $Shortcode = $this;
        $matchPattern = '/\[(\w{1,})([\S\s]*?)\]/';
        $resultString = preg_replace_callback($matchPattern, function($matches) use ($Shortcode) {
            $matchedGeneral     = $matches[0];
            $matchedShortCode   = $matches[1];
            $matchedParameters  = $matches[2];

            if ($Shortcode->has($matchedShortCode))
            {
                $parameters = $Shortcode->decodeParameter($matchedParameters);
                if ($result = $Shortcode->run($matchedShortCode, $parameters))
                {
                    return $result;
                }
            }

            return $matchedGeneral;
        }, $strings);
        return $resultString;
    }

    /**
     * Decode a string of parameters
     *
     * @param string    $parameterString
     * @param array     $default
     * @return array
     */
    public function decodeParameter($parameterString, array $default = array())
    {
        $parameterString = trim($parameterString);

        $matchPattern = '/([\w]+)?(?:\s+)?=(?:\s+)?"([^"]+)?/';
        preg_match_all($matchPattern, $parameterString, $matches, PREG_SET_ORDER);

        $ret_results = array();
        foreach ($matches as $item)
        {
            array_shift($item);
            list($parameterName, $parameterValue) = $item;

            $ret_results[$parameterName] = $parameterValue;
        }

        return $ret_results;
    }

    /**
     * Run closure and add the parameter if closure, else return its value
     *
     * @param mixed     $Closure - might be a closured instance
     * @param array     $parameters - 1 parameter to pass if the $Closure is an instance
     * @return mixed
     */
    public function runClosure($Closure, array $parameters = array())
    {
        if ($Closure instanceof Closure)
        {
            return $Closure($parameters, $this);
        }

        return $Closure;
    }
}

