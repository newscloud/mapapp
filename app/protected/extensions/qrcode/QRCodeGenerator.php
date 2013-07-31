<?php

/**
 * QRCode Generator
 *
 * @copyright � BryanTan <www.bryantan.info> 2011
 * @license GNU Lesser General Public License v3.0
 * @author Bryan Jayson Tan
 *
 */

include('phpqrcode/qrlib.php');
        
class QRCodeGenerator extends CWidget {

	/**
	 * the string to be generated for the qrcode
	 */
    public $data=null;
	
	/**
	 * the image filename for the qrcode
	 * default filename is $this->data property
	 */
    public $filename = null;
	
	/**
	 * the physical path of the file
	 * default to Yii::app()->getBasePath().'../uploads'
	 * make sure you have uploads folder and it is writable
 	 */
    public $filePath;
	
	/**
	 * the file url of the file
	 * default to Yii::app()->baseUrl.'/uploads'
	 * make sure you have uploads folder and it is writable
	 */
    public $fileUrl;
    public $subfolderVar = false;
	
	/**
	 * available parameter is L,M,Q,H
	 */
    public $errorCorrectionLevel = 'L';
    public $matrixPointSize = 4;
	
	/**
	 * default to true
	 * if set to true
	 * echo CHtml::image tag if set to true
	 * if set to false
	 * echo $this->fileUrl;
	 */
	public $displayImage=true;
	
	public $imageTagOptions=array();
    
    private $fullUrl;
    
    public function init()
    {
        if (is_null($this->data))
            throw new CException(Yii::t(get_class($this), 'Data must not be empty'));
			
		if (is_null($this->filename)){
			$this->filename = $this->data.'.png';
		}

        if (!$this->filePath){
            $this->filePath = realpath(Yii::app()->getBasePath().'/../uploads');
        }
        
        if(!is_dir($this->filePath)){
            throw new CHttpException(500, "{$this->filePath} does not exists.");
        }else if(!is_writable($this->filePath)){
            throw new CHttpException(500, "{$this->filePath} is not writable.");
        }
        
        if (!isset($this->fileUrl)){
            $this->fileUrl = Yii::app()->baseUrl . '/uploads';
        }
        
        //remember to sanitize user input in real-life solution !!!
        if (!in_array($this->errorCorrectionLevel, array('L','M','Q','H')))
            throw new CException(Yii::t(get_class($this), 'Error Correction Level only accepts L,M,Q,H'));
        
        $this->matrixPointSize = min(max((int)$this->matrixPointSize, 1), 10);
		
		if (is_string($this->subfolderVar)){
            $subfolder = $this->filePath.'/' . $this->subfolderVar;
            if (!is_dir($subfolder)){
                mkdir($subfolder);
				chmod($subfolder, 0777); 
            }
            $this->filePath = $this->filePath . '/' . $this->subfolderVar;
            $this->fileUrl = $this->fileUrl . '/' . $this->subfolderVar;
        }
		$this->filePath = $this->filePath . '/'. $this->filename;
        $this->fullUrl = $this->fileUrl . '/'. $this->filename;
    }
    
    public function run()
    {
        QRcode::png($this->data, $this->filePath, $this->errorCorrectionLevel, $this->matrixPointSize, false);
        
		if ($this->displayImage===true){
			echo CHtml::image($this->fullUrl,$this->data,$this->imageTagOptions);
		}else{
			echo $this->fullUrl;
		}
    }
}
