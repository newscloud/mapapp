	
	<?php // echo the provider links
	foreach ($providers as $provider){
		echo CHtml::link('<img src="'.$path.$provider.'.png" alt="'.$provider.'" title="'.$provider.'" />',
		array($url.'/provider/'.$provider)); // ,array("class"=>"popup")
		}
	?>

	<?php // echo the openid links
	foreach($openid_servers as $image=>$server){
		echo CHtml::link('<img src="'.$path.$image.'.png" alt="'.$server.'" title="'.$server.'"/>',
		array($url.'?openid='.$server));
	}

	?>
	<a href="#" id="wordpress" class="extra_info" title="Wordpress">
		<img src="<?=$path;?>wordpress.png"/>
	</a>
	
	<a href="#" id="openid" class="extra_info" title="Diffrent OpenID server">
		<img src="<?=$path;?>openid.png" alt="Diffrent OpenID provider"/> 
	</a>
	
	<!-- target div for jQuery to set dynamic instructions to the user -->
	<div id="extra_info_label"></div>
	<!-- the input where the user can add his wordpress blog or open id server -->
	<input type="text" id="extra_info_input" class="extra_info_form" style="display:none"/>
	<button id="extra_info_button" class="extra_info_form btn-success btn" style="display:none">Login</button>
	<script type="text/javascript">
	/** when the user clicks a link with class extra_info**/
	$(".extra_info").click(function(){
		/** did he click on wordpress or openid**/
		var service = $(this).attr('id');
		/** Depending on service we add diffrent instructions and value into the input. **/
		switch(service)
		{
		case 'wordpress':
		  $("#extra_info_label").html('<h4>Type in your blog</h4>');
		  $("#extra_info_input").val("http://yourblog.wordpress.com");
		break;
		case 'openid':
		$("#extra_info_label").html('<h4>Type in an openid server</h4>');
		 $("#extra_info_input").val("");
		break;
		}
		/**  And finally Show the button + input **/
		$(".extra_info_form").fadeIn('slow');

	});

	/** Take what the user submitted (add some security checks) and let hAuth do the rest **/
	$("#extra_info_button").click(function(){
			var href = $("#extra_info_input").val();
		  window.location.replace('/site/authenticatewith?openid='+href);

	});
	</script>