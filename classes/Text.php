<?php

namespace BuzzTargetLive;

class Text
{
	/**
     * @type array $strings All plugin strings.
     * @access protected
 	 */
	protected $strings = array();

	/**
     * @var Domain to retrieve the translated text
 	 */
	const domain = 'repl';

	public function __construct(array $strings) 
	{
		$this->strings = $strings;

		// Other plugins can modify strings
		$this->strings = apply_filters('repl_strings', $this->strings);

		// Actions
		add_action('plugins_loaded', array($this, 'pluginsLoaded'));
	}

	public function pluginsLoaded()
	{
		load_plugin_textdomain(self::domain, false, dirname(plugin_basename(dirname(__FILE__))) . '/languages/');
	}

	/**
	 * Prints a translated string formatted
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @param string $stringIdentifier The key where the string belongs.
	 * @param mixed  $args             The arguments the string requires.
	 */
	public function printf($stringIdentifier, $args)
	{
		if ($this->stringExists($stringIdentifier))
		{
			$args = func_get_arg(1);
			printf(__($this->strings[$stringIdentifier], self::domain), $args);
		}
	}

	/**
	 * Returns a translated string formatted
	 *
	 * @access public
	 *
	 * @since 1.0.0
	*/
	public function vsprintf($stringIdentifier, $args)
	{
		if ($this->stringExists($stringIdentifier))
		{
			$args = func_get_arg(1);
			return vsprintf(__($this->strings[$stringIdentifier], self::domain), $args);
		}
	}

	/**
	 * Prints a translated string
	 *
	 * @access public
	 *
	 * @since 1.0.0
	*/
	public function _e($stringIdentifier)
	{
		if ($this->stringExists($stringIdentifier))
			_e($this->strings[$stringIdentifier], self::domain);
		else
			error_log($stringIdentifier . ' not found');
	}

	/**
	 * Returns a translated string
	 *
	 * @access public
	 *
	 * @since 1.0.0
	*/
	public function __($stringIdentifier)
	{
		if ($this->stringExists($stringIdentifier))
			return __($this->strings[$stringIdentifier], self::domain);
	}

	/**
	 * Checks if a key exists in strings.
	 *
	 * @access protected
	 *
	 * @since 1.0.0
	 *
	 * @param string $stringIdentifier The key to search for in the strings.
	 *
	 * @return bool True if the key exists, otherwise false
	*/
	protected function stringExists($stringIdentifier)
	{
		return isset($this->strings[$stringIdentifier]);
	}
}