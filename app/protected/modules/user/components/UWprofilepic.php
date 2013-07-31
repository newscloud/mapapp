<?php

class UWprofilepic {
	
	/**
	 * @var array
	 * @name widget parametrs
	 */
	public $params = array(
		'rawPath'=>'assets/pic/raw/',
		'path'=>'assets/pic/thumb/',
		'defaultPic'=>'',
		'maxRawW'=>600,
		'maxRawH'=>600,
		'thumbW'=>50,
		'thumbH'=>50,
		'maxSize'=>'2',
		'types'=>'jpg,jpeg,png,gif',
	);
	
	/**
	 * Widget initialization
	 * @return array
	 */
	public function init() {
		return array(
			'name'=>__CLASS__,
			'label'=>UserModule::t('Profile Picture Widget'),
			'fieldType'=>array('VARCHAR'),
			'params'=>$this->params,
			'paramsLabels' => array(
				'rawPath'=>UserModule::t('Original Image path'),
				'path'=>UserModule::t('Thumbnail path'),
				'defaultPic'=>UserModule::t('Default Profile Picture URL'),
				'maxRawW' =>UserModule::t('Maximum Raw Width (px)'),
				'maxRawH' =>UserModule::t('Maximum Raw Height (px)'),
				'thumbW' =>UserModule::t('Thumbnail Width (px)'),
				'thumbH' =>UserModule::t('Thumbnail Height (px)'),
				'maxSize' =>UserModule::t('Maximum File Size (in Mb)'),
				'types' =>UserModule::t('Alowed File types'),
			),
		);
	}
	
	/**
	 * @param $value
	 * @param $model
	 * @param $field_varname
	 * @return string
	 */
	public function setAttributes($value,$model,$field_varname) {
		$value = CUploadedFile::getInstance($model,$field_varname);
		
		if ($value) {
			$old_file = $model->getAttribute($field_varname);
			$file_name = self::addSlash($this->params['rawPath']) .$value->name; //fix!!
			if (file_exists($file_name)) {
				$file_name = str_replace('.'.$value->extensionName,'-'.time().'.'.$value->extensionName,$file_name);
			}
			if ($model->validate()) {
				if ($old_file && $old_file != $this->params['defaultPic'] &&file_exists($old_file))
					unlink($old_file);
				$value->saveAs($file_name);
			}
			$value = $file_name;
		} else {
			if (isset($_POST[get_class($model)]['uwfdel'][$field_varname])&&$_POST[get_class($model)]['uwfdel'][$field_varname]) {
				$old_file = $model->getAttribute($field_varname);
				if ($old_file&&file_exists($old_file))
					unlink($old_file);
				$value='';
			} else {
				$value = $model->getAttribute($field_varname);
			}
		}
		return $value;
	}
		
	/**
	 * @param $value
	 * @return string
	 */
	public function viewAttribute($model,$field) {
		$file = $model->getAttribute($field->varname);
		if ($file) {
			$file = Yii::app()->baseUrl.'/'.$file;
			return CHtml::image($file);
		} else
			return '';
	}
		
	/**
	 * @param $value
	 * @return string
	 */
	public function editAttribute($model,$field,$params=array()) {
		if (!isset($params['options'])) $params['options'] = array();
		$options = $params['options'];
		unset($params['options']);
		
		ob_start();
		include('UWprofilepic/edit.php');
		$html = ob_get_clean();
		
		return $html;
	}
	
	public static function handleProfilePic($model,$profile) {
        if (isset($_GET['ajax_upload'])) {
            if (isset($_GET['thumb'])) {
                self::generateThumb($model, $profile, $_GET['x'], $_GET['y'], $_GET['w'], $_GET['h']);
            } else {
                self::handlePictureUpload($model, $profile);
            }
            Yii::app()->end();
        }
	}
	
	private static function addSlash($path) {
		if (substr($path, -1) !== '/') return $path .'/';
		else return $path;
 	}
	private static function getFields($profile) {
		$field = $profile->getFields();
		
        foreach ($field as $f) {
            if ($f->varname == $_GET['field']) {
                $field = $f;
                break;
            }
        }
		return $field;
	}
	
	private static function getParams($profile, $field = null) {
		if ($field == null) $field = self::getFields($profile);
        $params = json_decode($field->widgetparams);
		return $params;
	}
	
	private static function checkDir($params) {
		if (!is_dir($params->rawPath)) {
			if (!mkdir($params->rawPath,0755,true)) { 
				throw new CHttpException(403,"Can not create rawPath directory for profile picture field");
			}
		}
		
		if (!is_dir($params->path)) {
			if (!mkdir($params->path,0755,true)) { 
				throw new CHttpException(403,"Can not create Path directory for profile picture field");
			}
		}
		
	}
	
    private static function handlePictureUpload($model, $profile) {
		require_once 'UWprofilepic/qqFileUploader.php';
		require_once 'UWprofilepic/qqUploadedFileXhr.php';
		
		$field = self::getFields($profile);
		$params = self::getParams($profile,$field);
		$upload_dir = self::addSlash($params->rawPath);
			
		self::checkDir($params);
			
		$max_size = (isset($params->maxSize) ? $params->maxSize : 2);
		$allowedExtensions = explode(",", $params->types);
		
		foreach ($allowedExtensions as $k=>$v) {
			$allowedExtensions[$k] = trim($v);
		}
		$sizeLimit = $params->maxSize * 1024 * 1024;
		
        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
        $result = $uploader->handleUpload($upload_dir);

        if (isset($result['success'])) {
            if ($result['success'] == true) {

                $imageinfo = getimagesize($result['fullpath']);
                $mimetype = $imageinfo['mime']; // it is important when you work with images
				
                if ($profile->{$field->varname} != $field->default && is_writeable($profile->{$field->varname})) {
                    unlink($profile->{$field->varname});
					$raw_path = str_replace($params->path,$params->rawPath,$profile->{$_GET['field']});
                    if (is_writeable($raw_path)) {
                        unlink($raw_path);
                    }
                }
 
                //preserve aspect ratio
				$width = $imageinfo[0];
				$height = $imageinfo[1];
				$maxW = $params->maxRawW;
				$maxH = $params->maxRawH;
				if ($width > $height) {
					$height = ($height/$width) * $maxH;
				} else if($height > $width){
					$maxW = ($width/$height)* $maxW;
				}
				$width = $maxW;
				$height = $maxH;

                $pic = Yii::app()->phpThumb->create($result['fullpath']);
                $pic->resize($width, $height);
                $pic->save($result['fullpath']);

				$thumb_path = str_replace($params->rawPath,$params->path,$result['fullpath']);
				$thumb = Yii::app()->phpThumb->create($result['fullpath']);
				$thumb->crop(0, 0, $params->thumbW, $params->thumbH);
				$thumb->save($thumb_path);

				$profile->{$field->varname} = $thumb_path;
				
				$profile->save();
				
				$result['thumbpath'] = $thumb_path;
            }
        }

        echo htmlspecialchars(CJSON::encode($result), ENT_NOQUOTES);
    }

    public static function generateThumb($model, $profile, $x, $y, $w, $h) {

		$params = self::getParams($profile);
        $raw_path = str_replace($params->path,$params->rawPath,$profile->{$_GET['field']});
		
        $pic = Yii::app()->phpThumb->create($raw_path);
        $pic->crop($x, $y, $w, $h);
        $pic->resize($params->thumbW, $params->thumbH);
        $pic->save($profile->{$_GET['field']});

        echo "success";
    }
	
}