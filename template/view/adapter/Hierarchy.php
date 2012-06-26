<?php

namespace li3_hierarchy\template\view\adapter;

use \lithium\core\Libraries;
use \lithium\core\Environment;
use \lithium\core\Object;
use \li3_hierarchy\extensions\inheritance\Lexer;
use \li3_hierarchy\extensions\inheritance\Parser;
use \li3_hierarchy\extensions\inheritance\Cache;

class Hierarchy extends \lithium\template\view\adapter\File {

	protected static $_hierarchy;

	protected static $_blocks;

	public function __construct(array $config = array()) {

		$defaults = array(
			'classes' => array(), 'compile' => true, 'extract' => true, 'paths' => array()
		);

		parent::__construct($config + $defaults);

		// Start the hierarchy lexer
		static::$_hierarchy = Lexer::_init();

	}

	/**
	 * Render the template
	 * Uses the final view/template returned from the `Lexer::$_blockSet`
	 * @param  string $template full path to template file
	 * @param  array  $data     data vars
	 * @param  array  $options
	 * 
	 * @uses Lexer::$_blockSet `BlockSet` returned from templates
	 * 
	 * @return string           parsed template with block sections replaced
	 */
	public function render($template, $data = array(), array $options = array()) {

		$defaults = array('context' => array());
		$options += $defaults; 

		$this->_context = $options['context'] + $this->_context;
		$this->_data = (array) $data + $this->_vars;
		$template__ = $template;

		unset($options, $template, $defaults, $data);

		if ($this->_config['extract']) {
			extract($this->_data, EXTR_OVERWRITE);
		} elseif ($this->_view) {
			extract((array) $this->_view->outputFilters, EXTR_OVERWRITE);
		}

		$cleanTemplate = Lexer::_template($template__);

		// Load pages/layouts 
		if(!preg_match("/element/", $cleanTemplate)){

			$cache = new Cache();

			// Get all the template blocks
			static::$_blocks = Lexer::run($template__);

			if(gettype(static::$_blocks) == 'object'){

				// parse the template contents, master is the final template
				$cacheFile = Parser::parse(static::$_blocks);

			} else {
				// print_r($cleanTemplate);
				$cacheFile = static::$_blocks;
			}

			$template__ = $cacheFile;
		}

		ob_start();
		include $template__;
		return ob_get_clean();

	}


}
