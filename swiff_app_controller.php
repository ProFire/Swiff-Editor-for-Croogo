<?php
class SwiffAppController extends AppController {
	var $viewData = array();
	
	function beforeRender() {
		$this->set("viewData", $this->viewData);
	}
	
}