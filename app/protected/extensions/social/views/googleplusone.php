<!--For details: http://developers.google.com/+/plugins/+1button/ -->
<div class="plusone social-<?=$this->style;?>">
	<div id="plusone-div"></div>
	<script type="text/javascript">
	//<![CDATA[
	gapi.plusone.render
	(
	'plusone-div',
	{
		"size": "<?=urlencode($this->networks['googleplusone']['size']);?>",
		"annotation":"<?=urlencode($this->networks['googleplusone']['annotation']);?>",
	}
	);
	//]]>
	</script>
</div>