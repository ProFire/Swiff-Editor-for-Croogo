<?php
class SwiffHelper extends AppHelper {
	var $View;
	var $helpers = array('Session');
	
	public function __construct($options = array()) {
        $this->View =& ClassRegistry::getObject('view');
		/*DEBUG*/ //pr($this->View);
        return parent::__construct($options);
    }
    
	public function beforeNodeBody() {
		/*DEBUG*/ //pr($this->View);
		
		//Initialise
		$htmlReturn = "";
		
		if ($this->View->action == "view" || $this->View->action == "index") {
		}
		return $htmlReturn;
    }
    
	public function afterNodeBody() {
		/*DEBUG*/ //pr($this->View);
		/*DEBUG*/ //pr($this->Session->read());
		
		$auth = $this->Session->read("Auth");
		if (empty($auth)) {
			return;
		}
		
		//Initialise
		$htmlReturn = "";
		
		//Display Swiff Editor only if user is logged in and it's node's index/view page
		if ($this->View->action == "view" || $this->View->action == "index") {
 			$htmlReturn .= $this->View->element("swiff_editor", array("plugin" => "swiff"));
		}
		return $htmlReturn;
		
    }
    
    
}