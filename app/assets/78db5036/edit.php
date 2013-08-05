<?php

	$basePath=Yii::getPathOfAlias('application.modules.user.components.UWprofilepic');
	$baseUrl=Yii::app()->getAssetManager()->publish($basePath);
	$cs = Yii::app()->getClientScript();
	$cs->registerScriptFile($baseUrl.'/js/fileuploader.js');
	$cs->registerCssFile($baseUrl.'/css/fileuploader.css');
	$cs->registerScriptFile($baseUrl.'/js/jquery.Jcrop.min.js');
	$cs->registerCssFile($baseUrl.'/css/jquery.Jcrop.min.css');
	
	$params = $this->params;
	$id = get_class($model) . "_" . $field->varname;
	$csrf = Yii::app()->request->csrfTokenName . "=" .Yii::app()->request->getCsrfToken();
	
	$url_separator = (Yii::app()->urlManager->urlFormat == 'get' ? "&" : "?");
?>
<div id="<?php echo $id;?>_normal">
	<?php 
		echo CHtml::activeFileField($model,$field->varname,$options);
	?>
	<br/><br/>
</div>
<div id="<?php echo $id;?>_container" style="display:none;margin:10px 0px;">
    <div style="width:<?php echo $params['thumbW']; ?>px;height:<?php echo $params['thumbH']; ?>px;overflow:hidden;float:left;margin:0px 5px 0px 0px;border:1px solid #ccc;">
		<?php echo CHtml::image(Yii::app()->request->baseUrl . "/" . $model->{$field->varname},'',array('id'=>'preview')); ?>
    </div>
	<img id="thumb_loading" style="float:right;margin:4px 475px 0px 0px;display:none;"/>
	<div id="<?php echo $id;?>_js" ></div>
	<div class="jcrop-container">
		<img id="jcrop" />
	</div>
	<div style="clear:both;"></div>
</div>
<script>
// put your actions path in a clear place
//
$(function() {
	var id = '<?php echo $id; ?>';
	var form = $("#"+id);
	while (form.prop('tagName') != "FORM") {
		form = form.parent();
	}
	var actionForUpload = form.attr('action');
	
	function nocache() {
		var d = new Date();
		return "<?php echo $url_separator; ?>ajax_upload=1&<?php echo $csrf; ?>&field=<?php echo $field->varname ?>&t=" + d.getTime();
	}
	
	function generateThumb(c) {
		updatePreview(c);
		$("#thumb_loading").show();
		$.get("<?php echo Yii::app()->request->requestUri . $url_separator; ?>ajax_upload=1&field=<?php echo $field->varname ?>&thumb=1&x="+c.x+"&y="+c.y+"&w="+c.w+"&h="+c.h+"",function() {
			$("#thumb_loading").hide();
		});
	}

	function updatePreview(c)
	{
		if (parseInt(c.w) > 0)
		{
			var rx = '<?php echo $params['thumbW']; ?>' / c.w;
			var ry = '<?php echo $params['thumbH']; ?>' / c.h;

			$('#preview').css({
				width: Math.round(rx * boundx) + 'px',
				height: Math.round(ry * boundy) + 'px',
				marginLeft: '-' + Math.round(rx * c.x) + 'px',
				marginTop: '-' + Math.round(ry * c.y) + 'px',
				maxWidth:'none',
				minWidth:'none',
				maxHeight:'none',
				minHeight:'none'						
			});
		}
	};
	
	
	var jcrop_api = null;
	var uploader = new qq.FileUploader({
		element: document.getElementById(id+'_js'), //dont use jQuery selector here, do as it does.
		action: actionForUpload+nocache(), 
		label:'Upload <?php echo $field->varname; ?>',     
		disableDragDrop:true,
		debug: false,
		onSubmit:function () {
			$(".qq-upload-list li").remove();
			$(".jcrop-container").hide();
			if (jcrop_api != null) { 
				jcrop_api.destroy();
			}
		},
		onComplete: function(id, fileName, result){ 
			if (typeof result.error != "undefined") return;
		
			$(".qq-upload-list li").remove();
			$(".jcrop-container").show();
			
			$("#preview").attr("src",'<?php echo Yii::app()->request->baseUrl . "/" ?>' + result.fullpath);
			$("#jcrop").attr("src",$("#preview").attr('src')).unbind('load').load(function() {
				if ($("#jcrop").attr('src') != '') {
					$('#jcrop').removeAttr('style');
					$('.jcrop-holder').remove();
				} 

				$('#jcrop').Jcrop({
					onChange: updatePreview,
					onSelect: generateThumb,
					aspectRatio: 1,
					allowSelect: false,
					minSize: [ <?php echo $params['thumbW']; ?>, <?php echo $params['thumbH']; ?> ]
				},function(){
					// Use the API to get the real image size
					var bounds = this.getBounds();
					boundx = bounds[0];
					boundy = bounds[1];
					// Store the API in the jcrop_api variable
					jcrop_api = this;
					jcrop_api.setSelect([0,0,<?php echo $params['thumbW']; ?>,<?php echo $params['thumbH']; ?>]);
					jcrop_api.focus(); 
				});
			});
			

		},
		onCancel: function(id, fileName){
			return false;
		},
		showMessage: function(m){
			alert("ERROR:\n"+m);
		}
	});
	
	$("#"+id+"_normal").hide();
	$("#"+id+"_container").show();
	$(".qq-upload-button").css({"padding":"4px 15px","width":"auto","display":"inline-block"});

});

</script>
