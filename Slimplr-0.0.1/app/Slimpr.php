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
	 * @var object of XML Navigation Model
	 */
	protected $_Navigation;

	/**
	 * @var array HTML-Navigation
	 */
	protected $_NavigationHTML;

	/**
	 * @var current object by URI from the XML Navigation Model
	 */
	protected $page;

	/**
     * Constructor
     * calls parent Slim::__construct
     * @param   array $userSettings
     * @return  void
     */
	public function __construct ($userSettings = array()) {
		 $this->settings = array_merge_recursive(array(
            //Meta
           	'meta' => array (
	            'title' => 'Slimplr Framework', // for test purpose
	            'about' => 'A fork of http://www.slimframework.com/',
            ),
            //Templates
            'templates.path' 	=> './templates',
            'templates.default' => 'page_html5.php',
			//Navigation
			'navigation' => array(
				'main' => array(
					'filter' => array(
    					'status' => 'online',
    				),
    				'level' => array(
    					'start' => 0,
    					'depth' => 100,
    				),
    				'modus' => 'nested',
    			),
    			'global' => array(
					'filter' => array(
    					'status' => 'online',
    				),
    				'selection' => array(
    					'global' => "true",
    				),
    				'level' => array(
    					'start' => 0,
    					'depth' => 100,
    				),
    				'modus' => 'nested',
    			),
    			'breadcrumb' => array(
					'filter' => array(
    					'status' => 'online',
    				),
    				'selection' => array(
    					
    					'first' => 1,
    					'current' => 1,
    					'parent' => 1,
    				),
    				'level' => array(
    					'start' => 0,
    					'depth' => 100,
    				),
    				'modus' => 'linear',
    			),
    			'context' => array(
					'filter' => array(
    					'status' => 'online',
    				),
    				'selection' => array(
    					'current' => 1,
    					/*'parent' => 1,*/
    					'child' => 1,
    					'brother' => 1,
    					/*'uncle' => 1,*/
    				),
    				'level' => array(
    					'start' => 0,
    					'depth' => 100,
    				),
    				'modus' => 'nested',
    			),
			),


        ), $userSettings);

        parent :: __construct($this -> settings);
		
		
		#show($this->settings['meta']['about']);
		#show($this->settings['mode']);
		#show($this->request);

		$this -> getRouting();
		
		#$this->run();

	}

	private function getRouting () {
		/** anomymous functions are supported by php >= 5.3 
			
		**/

		/*$this->get('/admin(/:options)', function ($options='(not set)') {
			echo 'Hello admin! This is a dummy siteaccess for admins only.';
			echo '<br />';
			echo 'Your option is: '.$options;
		});*/

		/**
		 * This gets tne navigation model and current page object
		 */
		
		$app = new Slim();
		$request = $app->request(); 
		// Deletes Get-Parms
		self::$URI = $app->request()->getResourceUri();
		//show($request);


		/**/

		/**
		 * WALK all Single, get Param and make Routing 
		 * **/


		$this->_Navigation = $this->_getNavigationModel();
		$this->page = $this -> _Navigation -> getCurrent();
		if ($app->request()->isAjax()) {

			$this->get($this->page-> url, $this->getAjax($this->page));
		} else {
			$this->get($this->page-> url, $this->getPage($this->page));
		}
		/*
		$this->get('/', function () {
			show ('Hello world! This is the root node.');
		});

		$this->get('/me', function () {
			echo 'Hello world! You know me?';
		});

		$this->get(self::$URI, function () {
			echo 'Hello world! This is very generic!!!';
		});
		*/
		
		// ToDos
		// redirection
		// hardcoded link?!
		// Logik vor modules?
		// JS-Linking

		// else: We should determine a 404-error for files like Google authorization files
	}

	private function getAjax ($page) {
		//sleep(1);
		$navigation = $this -> _getNavigation();
		$updates = 
		array(
				'_title' => $page->getInheritance('title').' – '.$page->getAttribute('name'),
				'h3#name' => $page->getAttribute('name'),
				'.ajax-content' => $this->_getContenFile($page->getAttribute('id').'.html'),
				'name' => $page->getAttribute('name'),
				'#navigation-breadcrumb' => '<h4 class="section">Breadcrumb</h4>'
								 		.$navigation['breadcrumb'],

				/*'#navigation' => '								<div id="navigation-main">
										<h4 class="section">Main</h4>
										'.$navigation['main'].'
									</div>

									<div id="navigation-breadcrumb">
										<h4 class="section">Breadcrumb</h4>
								 		'.$navigation['breadcrumb'].'
									</div>
									',*/

			);
		echo json_encode($updates);

	}

	private function getPage ($page, $template="default") {
			$view = $this->view();
			#show ($view);
			#show($page);
		   	#show(Model_NavigationElement::$_single);
			#show($this->page);
			$view->setData($this->settings['meta']);
					
			$navigation = $this -> _getNavigation();
			$view->appendData(array('navigation'=>$navigation));

			#show($navigation);
			#show('This is from /routes/navigation-pub.xml');
			if ($this->page-> url != self::$URI) {
				$this->render($this->settings['templates.'.$template], array(
				'page' => $page,
				'class' => null,
				'lang' =>  $page->getAttribute('language'),
				'title' => $page->getInheritance('title').' – '.$page->getAttribute('name'),
				'claim' => $page->getInheritance('claim'),
				'name' => $page->getAttribute('name'),
				'content' => 'Seems to be a 404',
				));
				#show('Seems to be a 404');
				die();
			}
				
			$this->render($this->settings['templates.'.$template], array(
				'page' => $page,
				'class' => null,
				'lang' =>  $page->getAttribute('language'),
				'title' => $page->getInheritance('title'),
				'claim' => $page->getInheritance('claim'),
				'name' => $page->getAttribute('name'),
				'content' => $this->_getContenFile($page->getAttribute('id').'.html'),
			));

	}
	private function _getContenFile ($file) {
		$data_dir = BASE_PATH.'/data/';
		$content = 'Content File '.$file.' not found!';

		if (file_exists($data_dir.$file)) {
			$content = file_get_contents($data_dir.$file);
		}

		return $content;
	}

	private function _getNavigation () {
		if (null === $this->_NavigationHTML) {
			require_once 'Navigation.php';
			foreach ($this->settings['navigation'] as $name => $config) {
	    		//Workaround Array to Object
	    		$config = json_decode(json_encode($config));
    			$this->_NavigationHTML[$name] = new Model_Navigation( $config, $this->_Navigation );
    		}
        }
        return $this->_NavigationHTML;
	}

	private function _getNavigationModel () {
		if (null === $this->_Navigation) {
			require_once 'NavigationElement.php';
			$xmldata = file_get_contents(BASE_PATH .$this -> settings['xml-navigation-pub']);
    	    $this->_Navigation = Model_NavigationElement::_new($xmldata, self::$URI);   		
			$this->_Navigation->init(self::$URI);
			#show($this->_Navigation);
        }
        return $this->_Navigation;
	}


}