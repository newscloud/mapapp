<?php
	if($this->networks['twitter']['data-via'] !== "")
		$data_via = " data-via=".urlencode($this->networks['twitter']['data-via']);
	else
		$data_via = "";
?>

<!-- Thanks to http://techoctave.com/c7/posts/40-xhtml-strict-tweet-button-and-facebook-like-button -->
<div class="twitter social-<?=$this->style;?>">
	<script type="text/javascript">
	//<![CDATA[
	(function() {
	document.write('<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal"<?=$data_via;?>>&nbsp;</a>');
	var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
	s.type = 'text/javascript';
	s.async = true;
	s.src = 'http://platform.twitter.com/widgets.js';
	s1.parentNode.insertBefore(s, s1);
	})();
	//]]>
	</script>
</div>