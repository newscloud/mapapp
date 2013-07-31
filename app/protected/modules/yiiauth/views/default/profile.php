<h1>User profile</h1>
<h4>Please see <?=  __FILE__;?> for the code for more example api calls</h4>
This is a demo profile file rendered by defaultcontroller's authenticatewith action. <br/>
Designed to show what variables/objects you can pass and use after login, and some ways to interact with them in a view<br/>
This page is rendered after a successfull login thrue one of the social providers. ,you can change this to any page <br/>

<?php
	if( isset( $error ) ){
		echo  $error;
	}
//	echo "Your yii-user id: " .$yiiuser->id. "<br/>";
	echo "Connected with".$provider ."<br/>";
	
	
	//store a session you can retrieve later if a user is logged in, 
	//with only $this->loadSession(); 
	//$this->storeSession($provider);
	
	//how to update the users status
	//You can update the user status for some providers, see Yiiauth::updateStatus() for a list
	//$this->updateStatus($provider,'This is a test message');	
	
	//example of integrating with the facebook api
	//$response = $this->facebookApi($api) 
	# now try to play with theses social apis
	# Facebook: https://developers.facebook.com/docs/reference/api/
// Example, ask facebook for friends list
if($provider=="Facebook"){
	$response = $this->facebookApi('/me/friends');
	if($response){
	echo "<h1> Example fb api call for friends</h1>";
	var_export($response);
	}
	}
	//Post to facebook wall 
	/*$this->facebookApi("/me/feed", "post", array(
		'message' => "Hi there",
		'picture' => "http://www.mywebsite.com/path/to/an/image.jpg",
		'link' => "http://www.mywebsite.com/path/to/a/page/",
		'name' => "My page name",
		'caption' => "And caption"
		));*/
		
		
	//	# Twitter: https://dev.twitter.com/docs/api
// Returns the current count of friends, followers, updates (statuses) ...
	//	$response = $this->twitterApi('account/totals.json'); 
	//userinfo	
	echo "<h1>User info woop woop</h1>";
	var_export($provideruser);
	
?>