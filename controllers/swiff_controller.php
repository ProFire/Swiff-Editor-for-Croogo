<?php
/**
 * Swiff Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   ProFire <ProFire88@hotmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class SwiffController extends SwiffAppController {
/**
 * Controller name
 *
 * @var string
 * @access public
 */
    public $name = 'Swiff';
/**
 * Models used by the Controller
 *
 * @var array
 * @access public
 */
    public $uses = array(
    	'Setting',
    	"Node",
    );
    
    public $helpers = array(
    	"Javascript",
    	"Html",
    	"Swiff.TinyMce",
    );
    
    public function admin_index() {
        $this->set('title_for_layout', __('Swiff Editor', true));
    }

    public function index() {
        $this->set('title_for_layout', __('Example', true));
        $this->set('exampleVariable', 'value here');
    }
	
    public function admin_swiffEdit($nodeId = 0) {
    	if ($nodeId == 0) {
    		$this->flash("Node ID not provided.", "/admin/nodes");
    		return;
    	}
    	
    	//Get Node
    	$this->viewData["nodeData"] = $this->Node->find(
    		"first",
    		array(
    			"conditions" => array(
    				"Node.id" => $nodeId,
    			),
    		)
    	);
    	
    	//Set Theme/Layout
		$this->layout = "default";
		$this->theme = Configure::read('Site.theme');
    	
		
		//Get Menus
		$this->Croogo->menus();
		//Get Types
		$this->Croogo->types();
		//Get Blocks
		$this->Croogo->blocks();
		//Get Nodes
		$this->Croogo->nodes();
		//Get Vocabularies
		$this->Croogo->vocabularies();
    	
		
		if ($this->data) {
			/*DEBUG*/ //pr($this->data);
    		
            if ($this->Node->saveWithMeta($this->data)) {
                $this->Session->setFlash(sprintf(__('%s has been saved', true), $this->data["Node"]['title']), 'default', array('class' => 'success'));
                $this->redirect(array("controller" => "nodes", 'action'=>'index', "plugin" => ""));
            } else {
                $this->Session->setFlash(sprintf(__('%s could not be saved. Please, try again.', true), $this->data["Node"]['title']), 'default', array('class' => 'error'));
            }
		}
    }
    
    public function admin_ajaxSaveNode() {
    	$this->layout = "ajax";
    	if ($this->data) {
    		/*DEBUG*/ //pr($this->data);
    	
            if ($this->Node->saveWithMeta($this->data)) {
                $this->viewData["statusMessage"] = $this->data["Node"]['title']." has been saved";
                $this->viewData["statusClass"] = "success";
            } else {
                $this->viewData["statusMessage"] = $this->data["Node"]['title']." could not be saved";
                $this->viewData["statusClass"] = "fail";
            }
    	}
    }
    
}
?>