<?php
class Model_NavigationElement extends SimpleXMLElement {
	
	const Container = 'item';
	static public $Current = null;
	static $_filter = array('@status !="offline"');
	static $_ids    = array(); // $_single['id'] = object;
	static $_single = array(); // $_single['id'] = object;
   	
	 public static function _new($xml, $url, $ns=NULL, $prefix=TRUE) {
        // allows you to set certain option parameters to new default values,
        // or automatically decide whether input data is a file or not
        // optionally, you can save the object in an intermediate variable
        // and peform other actions on/with it before returning it
       
        $_model = new Model_NavigationElement($xml, LIBXML_NOCDATA);
        #$_model->init($url);
        $first = current($_model->xpath_filter('//'.self::Container));	
        $first -> _addAttribute('first',true);

        #show ($first);

        $xpath = $_model->xpath_filter('//'.self::Container);
		
		/*show($xpath );
		echo '<hr />';
		*/
		foreach ($xpath as $element) {
			//echo '<h4>'.$element->getAttribute('name').'</h4>';
			$element->_getURI();
			#$element->singleElement();
			
		}
        return $_model;
    }
   	public  function init($url) {
   		#show($url);
   		self::$Current = $this->setCurrent($url);
	#	show(self::$Current);

   		$this->getParents();
   		$this->getUncles();

		$this->getBrothers();
		$this->getChildren();
		$this->getLanguage();
   		
   		
		$xpath = $this->xpath_filter('//'.self::Container);
		/*foreach ($xpath as $element) {
			#echo $element->getAttribute('name');
			$element->singleElement();
			#show($element);
		}*/
		#show(self::$_single);
	}
	
	protected function setCurrent($url) {
		$filter[] = 'url="'.$url.'"';
		$element = current($this->xpath_filter('//'.self::Container,$filter));
		if (empty($element)) {
			$element = current($this->xpath_filter('//'.self::Container));	
		} 
		
		$element->_addAttribute('current', 1);
		return $element;
		#return $this->getElementbyID($xpath->getAttribute('id'));
	}
	
	public function getCurrent() {
		
		return self::$Current->singleElement();
	}
	
	protected static function getParents(){
         $path = '..';
       
         while ($object = self::$Current->xpath($path)) {
              if (!empty($object[0])) {
                  $object[0]->  _addAttribute('parent',1);
	               $path .= '/..';

               $return[] = $object[0]->singleElement();
              }
          }
         return $return;
    }
	
	protected static function getLanguage(){
		return self::getInheritance('language');
      }

      protected static function getInheritance($name){
      	$return = '';
		if (!self::$Current->getAttribute($name)) {
			 $path = '..';

			while ($object = self::$Current->xpath($path)) {
				$language = $object[0]->getAttribute($name);
              if (!$language) {
               	 $path .= '/..';
              }else {
              	self::$Current->_addAttribute($name,$object[0]->getAttribute($name));
                # show ($object[0]->getAttribute('language'));
				return true;
              }
         	 }
	         return $return;
			
			
		}
      }
      
      protected static function getBrothers(){
         $path = '..';
         $object = self::$Current->xpath($path);
         $children = $object[0]->children();
         foreach ($children as $child) {
                 $child->_addAttribute('brother',1);
          }
      }
      
    protected static function getUncles(){
         $path = '../../'.self::Container;
         $xpath = self::$Current->xpath($path);
        foreach ($xpath as $i => $object) {
          	  	$object-> _addAttribute('uncle',1);
         	
         }
        # return $return;
      }
      
	protected static function getChildren(){
         $children = self::$Current->children();
         foreach ($children as $child) {
                 $child->_addAttribute('child',1);
         }
      }
	
   	public function getLinear() {
		return self::$_single;
	}
	
   	public function getElementbyID ($id) {
   		return self::$_single[$id];
	}
	
	public function singleElement () {
		$id = self::getAttribute('id');
		if (isset(self::$_single[(string)$id])) {
			# cache
			#echo 'CACHE';
			return self::$_single[(string)$id];
		}
		
		# no cache
	   #	$return = self::$_single[(string)$id] = $this->_deleteChild();
		$return = self::$_single[(string)$id] = $this;
	   	return $return;
	  
	}

	protected function _getFriendlyID ($name) {
        $id = $this->friendly_url($this->getAttribute($name));

		if (isset(self::$_ids[$id])) {
			$i=1;
			$new_id=$id;
			while(isset(self::$_ids[$new_id])){
				$new_id=$id.'-'.$i;
				$i++;
			}
			$id = $new_id;
		}
		self::$_ids[$id] = true;
		return $this['id'] = $id;
    }
	
   	protected function _getURI() {
   		 #echo $this->getAttribute('path').' /:'.strpos($this->getAttribute('path'),'/').'<br />';

   		$id = self::getAttribute('id');

		if ((string) $id == 'auto()') {
			$new_id = self::_getFriendlyID($this->getAttribute('name'));
			$id = $new_id;
			$this['id'] = $id;
		}

   		if (strpos($this->getAttribute('path'),'/') === 0 ) {
   		 	$this->url =(string) $this->getAttribute('path');
   		 	#show($this->url);
   		 	return $this->url;
   		}

        $path = '..';
       
        #$return = array();

        if (!$this->getAttribute('path')) {
			$attribute = 'id';
			$return[] = $id;	
        }else{
        	 $attribute = 'path';
        	$return[] = $this->getAttribute($attribute);	
        }

		#show ( $return);
       	#show($this);
        while ($object = $this->xpath($path)) {
         	//show($object[0]-> _deleteChild());
         	
            if (!empty($object[0])) {
            	#	echo '1';
               	#show($object[0]-> _deleteChild());
	           	$path .= '/..';
	           	$return[] = $object[0]->getAttribute($attribute);
	           	if (strpos($object[0]->getAttribute($attribute),'/') === 0) {
               		break;
               	}
            }
        }
		$return = array_filter($return);
		
		#$return[] = '/';
		$return = array_reverse($return);
 
		#show ($return);
		#if  ($return[0] == '/' && sizeof($return) > 1) { $return[0] = ''; }
		if (empty($return)) { 
			return false;
		}

		if (strpos($return[0],'/') !== 0 ) {  array_unshift($return,''); }
		if ($return[0] == '/' && sizeof($return) > 1) { $return[0] = ''; }
		

		$url = implode('/',$return);
	    $this->url = $url;
		#show($this->url);
        return $return;
   	}
	
	
	public function xpath_filter($path, $filter = array()) {
		#show($path.$this->_getFilter($filter));
		$xpath = parent::xpath($path.$this->_getFilter($filter));
		return $xpath;
	}
	
	public function getAttribute($name){
		 if (!$this || !$name) {
		 	#show ($this->attributes());
		 	return null;
		 }
		 if ($this->$name == 'auto()' || 
		 	 $this->attributes()->$name == 'auto()')  {
		 	return $this -> _getFriendlyID('name');
		 }

		 if ($this->$name) return (string) trim($this->$name);
		 if(($this->attributes()->$name)) return (string) trim($this->attributes()->$name);
		 return null;
    }
	
    protected function _addAttribute ($attribute, $value) {
         $this[$attribute] = $value;
    }
  
   	protected function _getFilter($temporary_filter = array()) { // Example _getFilter(array('@id="de"')
   		$filter_array = self::$_filter;
   		if (!empty($temporary_filter)) {
   			$filter_array= array_merge($filter_array , $temporary_filter); 
   		}
   		
   		if (!empty($filter_array)) {
   			$filter = implode (' and ', $filter_array);
   			$filter = '['.$filter.']';
   			#show($filter);
   		}
   		return $filter;
   	}
	
	
   	protected function _setFilter() {
   		
   		
   	}
	
	protected function _deleteChild ($child=self::Container) {
	  if (isset($this->$child)) {
	      $clone = clone ($this);
	      unset ($clone->$child);
	      return $clone;
	  }
	  return $this;
	}



	public function friendly_url($url) {
		// everything to lower and no spaces begin or end
		#$url = iconv("UTF-8", "UTF-8", $url);
		$url = trim($url);
		$url = utf8_encode(strtolower(utf8_decode($url))); ;

		// decode html maybe needed if there's html I normally don't use this
		$url = html_entity_decode($url,ENT_QUOTES,"UTF-8");
	 	
		//replace accent characters, depends your language is needed
		$url=self::replace_friendly($url);
	 	// adding - for spaces and union characters
		$find = array(' ', '&', '\r\n', '\n', '+',',');
		$url = str_replace ($find, '-', $url);
	 
		//delete and replace rest of special chars
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
		$repl = array('', '-', '');
		$url = preg_replace ($find, $repl, $url);
	 
	 	#show($url);
		//return the friendly url
		return $url; 
	}

	public function replace_friendly($string){ // Special and German characters 

		$replacements = array(
			' ' => '-',
			'&' => '+',
			'\\' => '-',
			#'/' => '-',
			'*' => '-',
			'#' => '-',
			'\'' => '-',
			'ä' => 'ae',
			'ö' => 'oe',
			'ü' => 'ue',
			'ß' => 'ss',
		);
    	$string = str_replace(array_keys($replacements), $replacements, $string);
    	return $string; 
	}
	
}
