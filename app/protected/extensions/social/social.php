<?php
/**
 *social.php
 *
 * @author Ovidiu Pop <matricks@webspider.ro>
 * @copyright 2011 Binary Technology
 * @license released under dual license BSD License and LGP License
 * @package social
 * @version 0.1
 */

class social extends CInputWidget
{

	/**
	 * @var string buttons alignment - horizontal, vertical
	 */
	public $style='horizontal';//vertical
	/**
	 * @var array available social network buttons 
	 */
	public $networks = array(
		'twitter'=>array(
			'data-via'=>'', //http://twitter.com/#!/YourPageAccount if exists else leave empty
		), 
		'googleplusone'=>array(
			"size"=>"medium",
			"annotation"=>"bubble",
		), 
		'facebook'=>array(
			'href'=>'http://www.facebook.com/page',//asociate your page http://www.facebook.com/page 
			'action'=>'recommend',//recommend
			'colorscheme'=>'light',
			'width'=>'200px',
			)
	);

	/**
	 * The extension initialisation
	 *
	 * @return nothing
	 */

	public function init()
	{
		self::registerFiles();
		self::renderSocial();
	}

	/**
	 * Register assets file
	 *
	 * @return nothing
	 */
	private function registerFiles()
	{
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);

		if(is_dir($assets)){
			Yii::app()->clientScript->registerCssFile($baseUrl . '/social.css');
		}else
			throw new Exception(Yii::t('social - Error: Couldn\'t find assets folder to publish.'));

		if(array_key_exists('googleplusone', $this->networks))
			Yii::app()->clientScript->registerScriptFile('https://apis.google.com/js/plusone.js?parsetags=explicit', CClientScript::POS_HEAD);
	}

	/**
	 * Render social extension
	 *
	 * @return nothing
	 */
	private function renderSocial(){
		$rendered = '';
		foreach($this->networks as $network => $params)
			$rendered .= $this->render($network, array(), true);
		echo $this->render('social', array('rendered'=>$rendered));
	}
}

?>