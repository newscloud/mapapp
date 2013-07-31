<?php $this->layout ="ajax";?>
	<h4> By connecting a social provider to your account you can use it to login<h4>
<?php $socials = Social::model()->findAll('yiiuser='.Yii::app()->user->id);
		 if($socials){
			echo "<h5>You have currently connected your account with:</h5>";
			foreach($socials as $social){
			echo CHtml::Link('<i class="icon-remove icon-white"></i>Delete',
			array(''),
			array('class'=>'btn btn-mini btn-danger social_delete','id'=>$social->id));
				echo '* <b>'.$social->provider.'</b>';
				echo '<div id="delete_social_'.$social->id.'" style="display:none"></div><br/>';
			}
		 }

		$this->widget('LoginWidget');//displays the possible providers
		 
		 
?>
<script type="text/javascript">

		$(".social_delete").click(function(){
				deletesocial($(this).attr('id'));
			});

		function deletesocial( id ) {
			url = '/yiiauth/social/delete';
			jQuery.getJSON(url, {id: id}, function(data) {
				if (data.status == 'success')
					{
						$('#delete_social_'+id).html(data.div);
						$('#delete_social_'+id).fadeIn('slow');	

					} 
				});
				return false;
			}
</script>