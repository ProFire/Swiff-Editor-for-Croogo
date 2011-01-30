<?php 
if ($viewData["statusClass"] == "success") {
	$color = "#AAFFAA";
} else {
	$color = "#FFAAAA";
}
?>

<div>
	<span style="<?php echo $html->style(array("background-color" => $color)); ?>"><?php echo $viewData["statusMessage"]; ?></span>
</div>

<?php /*DEBUG*/ //pr($viewData); ?>