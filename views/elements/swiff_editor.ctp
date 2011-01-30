
<?php 

//Load JQuery UI
$this->Html->script('/js/jquery/jquery-ui.min', false);
$this->Html->css("/ui-themes/smoothness/jquery-ui", null, array("inline" => false));
$this->Html->css("/swiff/css/swiff", null, array("inline" => false));

//Load TinyMCE Editor
echo $this->TinyMce->editor(
	array(
		"mode" => "textareas",
		"theme" => "advanced",
		"convert_urls" => false,
		"plugins" => "inlinepopups,table,advimage",
		"editor_selector" => "NodeBodyTextarea",
	
		"theme_advanced_buttons1" => "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		"theme_advanced_buttons2" => "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor",
		"theme_advanced_buttons3" => "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,|,insertfile,insertimage",
		"theme_advanced_toolbar_location" => "top",
		"theme_advanced_toolbar_align" => "left",
		"theme_advanced_statusbar_location" => "bottom",
		//"theme_advanced_resizing" => true,
		
		"skin" => "o2k7",
		"skin_variant" => "silver",
	
		//"template_external_list_url" => "\"js/template_list.js\"",
        //"external_link_list_url" => "\"js/link_list.js\"",
        //"external_image_list_url" => "\"js/image_list.js\"",
        //"media_external_list_url" => "\"js/media_list.js\"",
	
		"file_browser_callback" => 'fileBrowserCallBack',
		"setup : function(ed) {
		      ed.onChange.add(function(ed, l) {
		          $('#node-".$node["Node"]["id"]." .node-body').html(tinyMCE.get('NodeBody').getContent());
		          $('#swiff-container #NodeBody').html(tinyMCE.get('NodeBody').getContent());
		      });
		   }
		"
	)
);
//Load TinyMce File Browser
echo $this->Javascript->codeBlock(
	"function fileBrowserCallBack(field_name, url, type, win) {
		browserField = field_name;
		browserWin = win;
		window.open('".Helper::url('/admin/attachments/browse')."', 'browserWindow', 'modal,width=800,height=600,scrollbars=yes');
	}",
	array("inline" => false)
);
//Load Toggle Editor for minimise/maximise
echo $this->Javascript->codeBlock(
	'
	function toggleEditor() {
		$("#swiff-content").animate({
			height:"toggle"
		});
	};
	',
	array("inline" => false)
);
//Load UI Tabs
echo $this->Javascript->codeBlock(
	'$(function (){$("#tabs").tabs()})',
	array("inline" => false)
);

//Ajax Form Submit
echo $this->Javascript->codeBlock(
	'
	var swiffFormSubmit = function() {
		$(\'#node-'.$node["Node"]["id"].' .node-body\').html(tinyMCE.get(\'NodeBody\').getContent());
		$(\'#swiff-container #NodeBody\').html(tinyMCE.get(\'NodeBody\').getContent());
		$("#ajaxSwiffControlsStatusBox").html(\''.$this->Html->image("/swiff/img/ajax-loader.gif").'<br />Saving\');
		$.post(
			"'.Helper::url('/admin/swiff/swiff/ajaxSaveNode').'",
			$("#NodeForm").serialize(),
			function(data) {
				$("#ajaxSwiffControlsStatusBox").html(data);
			}
		);
	}
	',
	array("inline" => false)
);

//Beautify Links and Buttons
echo $this->Javascript->codeBlock(
	"$(document).ready(function() {
		$('#swiffControls a').button();
		$('#swiffControls input').button();
		$('#tabs-3 a').button();
		
	});",
	array("inline" => false)
);

//Swiff Edit Node Title
echo $this->Javascript->codeBlock(
	"$(document).ready(function() {
		$('#nodeTitleInput').change(function(){
			$('#node-".$node["Node"]["id"]."').children('h2').html($('#nodeTitleInput').val());
		});
		
	});",
	array("inline" => false)
);

//Style Page Settings Form Fields
echo $this->Javascript->codeBlock(
	"$(document).ready(function() {
		$('#nodeTitleInput').addClass('ui-corner-all ui-state-default');
		$('#NodeSlug').addClass('ui-corner-all ui-state-default');
		$('#NodeExcerpt').addClass('ui-corner-all ui-state-default');
	})",
	array("inline" => false)
);


//Reset Button
echo $this->Javascript->codeBlock(
	"
	var originalBody = '';
	var originalTitle = '';
	var originalSlug = '';
	var originalExcerpt = '';
	
	$(document).ready(function() {
		originalBody = '".$node["Node"]["body"]."';
		originalTitle = '".$node["Node"]["title"]."';
		originalSlug = '".$node["Node"]["slug"]."';
		originalExcerpt = '".$node["Node"]["excerpt"]."';
		
		$('#resetButton').click(function(){
			$('#node-".$node["Node"]["id"]." .node-body').html(originalBody);
			$('#swiff-container #NodeBody').html(originalBody);
			tinyMCE.get('NodeBody').setContent(originalBody);
			
			$('#nodeTitleInput').val(originalTitle);
			$('#node-".$node["Node"]["id"]."').children('h2').html(originalTitle);
			$('#NodeSlug').val(originalSlug);
			$('#NodeExcerpt').val(originalExcerpt);
			
			swiffFormSubmit();
			
			return false;
		});
	})
	",
	array("inline" => false)
);


?>


<div id="swiff-container">
	<div style="text-align: center;">
		<?php echo $html->link("Show/Hide Editor", "javascript: toggleEditor();", array("id" => "minmaxeditor", "class" => "ui-state-active ui-corner-all", "style" => "padding: 5px; font-weight: bold;")); ?>
		<?php echo $html->link("Return to Croogo Admin", "/admin/nodes/", array("class" => "ui-state-active ui-corner-all", "style" => "padding: 5px; font-weight: bold;")); ?>
	</div>
	
	<?php echo $form->create("Node", array("url" => "/admin/swiff/swiff/swiffEdit/".$node["Node"]["id"], "id" => "NodeForm")); ?>
	<?php echo $form->input("Node.id", array("value" => $node["Node"]["id"]))?>
	<div id="swiff-content" style="width: 95%; margin: 0 auto; height: 300px;">
		<div id="tabs" style="height: 290px; width: 80%; float: left;">
			<ul style="margin: 0;">
				<li style="margin-bottom: 0;"><?php echo $html->link("Page", "#tabs-1", array("style" => "font-size: 12px;")); ?></li>
				<li style="margin-bottom: 0;"><?php echo $html->link("Page Settings", "#tabs-2", array("style" => "font-size: 12px;")); ?></li>
				<li style="margin-bottom: 0;"><?php echo $html->link("Log Out", "#tabs-3", array("style" => "font-size: 12px;")); ?></li>
			</ul>
			<div id="tabs-1" style="padding:2px;">
				<?php echo $form->input("Node.body", array("class" => "NodeBodyTextarea", "style" => "width: 100%; height: 100%;", "label" => "", "value" => $node["Node"]["body"]))?>
			</div>
			<div id="tabs-2" style="padding:2px;">
				<?php echo $form->input("Node.title", array("value" => $node["Node"]["title"], "id" => "nodeTitleInput")); ?>
				<?php echo $form->input("Node.slug", array("value" => $node["Node"]["slug"]));?>
				<?php echo $form->input("Node.excerpt", array("value" => $node["Node"]["excerpt"]));?>
			</div>
			<div id="tabs-3" style="padding:2px;">
				<p>When you log out, Swiff Editor will no longer display. To display Swiff Editor again, you need to login.</p>
				<p>Are you sure you want to logout? Unsaved changes will be lost.</p>
				<p><?php echo $html->link("Log Out", "/users/logout"); ?></p>
			</div>
		</div>
		<div id="swiff-controls" style="width: 16%; float: left; height: 290px;" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
			<h2 style="text-align: center; font-size: 15px;">Swiff Controls</h2>
			<div id="swiffControls" style="<?php echo $html->style(array("text-align" => "center", "height" => "140px"), true); ?>">
				<?php echo $html->link("Save Only", "javascript: swiffFormSubmit();", array("style" => "font-size: 12px; width: 90%;")); ?>
				<?php echo $html->link("Reset to Original", "#", array("style" => "font-size: 12px; width: 90%;", "id" => "resetButton")); ?>
				<?php echo $form->submit("Save and Exit", array("style" => "font-size: 12px; width: 90%;")); ?>
				<?php echo $html->link("Exit without Saving", "/admin/nodes", array("style" => "font-size: 12px; width: 90%;")); ?>
			</div>
			<div id="ajaxSwiffControlsStatusBox" class="ui-state-active ui-corner-all" style="<?php echo $html->style(array("padding" => "5px", "text-align" => "center", "height" => "70px", "font-size" => "12px"), true); ?>">
				Thank you for using Swiff Editor! <br />By ProFire
			</div>
			<div style="<?php echo $html->style(array("text-align" => "right", "font-size" => "10px"), true); ?>">Plugin by <?php echo $html->link("ProFire", "http://ProFire.blogspot.com/", array("target" => "_blank")); ?></div>
		</div>
	</div>
	<?php echo $form->end(); ?>
</div>
<?php /*DEBUG*/ //pr($node); ?>