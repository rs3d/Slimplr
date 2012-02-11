<?php
/**
 * Slimpr extends Slim with 'some' controller logic :)
 * 
 * @author Robin Schmitz
 * @license There is no license
 * @link https://github.com/rs3d/Slimplr
 * @version 0.0.1
 * 
*/

require BASE_PATH.'/../Slim/Slim.php';

class Slimpr extends Slim {

	/**
	 * Slim
	 *
	 * @package Slimpr
	 * @author  Robin Schmitz <rob@rs3d.de>
	 * @since   Version 0.0.1
 	*/

 	/**
     * @var array Key-value array of application settings
     */
    protected $settings;
	
	/**
     * @var string Requested URI
     */
    
	static public $URI = REQUEST_URI;


	/**
     * Constructor
     * calls parent Slim::__construct
     * @param   array $userSettings
     * @return  void
     */
	function __construct ($userSettings = array()) {
		 $this->settings = array_merge(array(
            //About
            'about' => 'There is no about',
        ), $userSettings);

        parent :: __construct($this -> settings);
		
		
		show($this->settings['about']);
		show($this->settings['mode']);
		#show($this->request);

		$this ->get(self::$URI, function () {
			echo 'hello world';
		});
		$this->run();

	}

}