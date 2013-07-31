<?php

/**
 * ES3 class file.
 *
 * ES3 is a wrapper for the excellent S3.php class provided by Donovan Schönknecht (@link http://undesigned.org.za/2007/10/22/amazon-s3-php-class)
 * This wrapper contains minimal functionality as there is only so much I want to allow access to from the Yii public end
 *
 * @version 0.1
 *
 * @uses CFile
 * @author Dana Luther (dana.luther@gmail.com)
 * @copyright Copyright &copy; 2010 Dana Luther
 */
 class ES3 extends CApplicationComponent
{

	private $_s3;
	public $aKey; // AWS Access key
	public $sKey; // AWS Secret key	
	public $bucket;
	public $lastError="";

	private function getInstance(){
		if ($this->_s3 === NULL)
			$this->connect();
		return $this->_s3;
	}

	/**
	 * Instance the S3 object
	 */
	public function connect()
	{
		if ( $this->aKey === NULL || $this->sKey === NULL )
			throw new CException('S3 Keys are not set.');
			
		$this->_s3 = new S3($this->aKey,$this->sKey);
	}
	
	/**
	 * @param string $original File to upload - can be any valid CFile filename
	 * @param string $uploaded Name of the file on destination -- can include directory separators
	 */
	public function upload( $original, $uploaded="", $bucket="" )
	{
		
		
		$s3 = $this->getInstance();
		
		if( $bucket == "" )
		{
			$bucket = $this->bucket;
		}
		
		if ($bucket === NULL || trim($bucket) == "")
		{
			throw new CException('Bucket param cannot be empty');
		}
		
		$file = Yii::app()->file->set($original);
	
		if(!$file->exists)
			throw new CException('Origin file not found');
		
		$fs1 = $file->size;
		
		if ( !$fs1 )
		{
			$this->lastError = "Attempted to upload empty file.";
			return false;
		}
	
		if (trim($uploaded) == ""){
			$uploaded = $original;
		}
		
		//if (!$s3->putObject($s3->inputResource(fopen($file->getRealPath(), 'r'), $fs1), $bucket, $uploaded, S3::ACL_PUBLIC_READ))
		echo $file->getRealPath();
		//if (!$s3->putObject($s3->inputResource( fopen($file->getRealPath(), 'rb'), $fs1), $bucket, $uploaded, S3::ACL_PUBLIC_READ))
		if (!$s3->putObjectFile( $original, $bucket, $uploaded, S3::ACL_PUBLIC_READ))
		{
			$this->lastError = "Unable to upload file.";
			return false;
		}
		return true;
	}
	
	// Testing connection :p
	public function buckets()
	{
		$s3 = $this->getInstance();
		return $this->_s3->listBuckets();
	}
	
	// Passthru function for basic functions
	public function call( $func )
	{
		$s3 = $this->getInstance();
		return $s3->$func();
	}

}
?>