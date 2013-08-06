<?php

/**
 * This is the model class for table "{{neighborhoods}}".
 *
 * The followings are the available columns in table '{{neighborhoods}}':
 * @property integer $OGR_FID
 * @property string $SHAPE
 * @property string $state
 * @property string $county
 * @property string $city
 * @property string $name
 * @property double $regionid
 */
class Neighborhoods extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Neighborhoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{neighborhoods}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SHAPE', 'required'),
			array('regionid', 'numerical'),
			array('state', 'length', 'max'=>2),
			array('county', 'length', 'max'=>43),
			array('city, name', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('OGR_FID, SHAPE, state, county, city, name, regionid', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'OGR_FID' => 'Ogr Fid',
			'SHAPE' => 'Shape',
			'state' => 'State',
			'county' => 'County',
			'city' => 'City',
			'name' => 'Name',
			'regionid' => 'Regionid',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('OGR_FID',$this->OGR_FID);
		$criteria->compare('SHAPE',$this->SHAPE,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('county',$this->county,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('regionid',$this->regionid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
  public function prepareMap($id) {
    $pg = Yii::app()->db->createCommand()
             ->select('AsText(SHAPE) as region,ASTEXT(Centroid(SHAPE)) as center')
             ->from(Yii::app()->getDb()->tablePrefix.'neighborhoods')
             ->where('OGR_FID=:ogr_fid', array(':ogr_fid'=>$id))
             ->queryRow();		     
    Yii::import('ext.gmap.*');
    $gMap = new EGMap();
    $gMap->setJsName('map_region');
    $gMap->width = '500';
    $gMap->height = '500';
    $gMap->zoom = 13;      
    $center = new stdClass;
    list($center->lat, $center->lon) = $this->string_to_lat_lon($pg['center']);
    $gMap->setCenter($center->lat, $center->lon);
    $coords = $this->string_to_coords($pg['region']);
    $polygon = new EGMapPolygon($coords);
    $gMap->addPolygon($polygon);	            
    return $gMap;
  }
  
  public function lookupFromLatLon($pt) {
    $nList = Yii::app()->db->createCommand()
        ->select('OGR_FID,name')
        ->from(Yii::app()->getDb()->tablePrefix.'neighborhoods')
        ->where('mbrwithin(geomfromtext(:point),Shape)', array(':point'=>$pt))
        ->queryRow();
        return $nList;
	}
  
	public function string_to_lat_lon($string) {
      $string = str_replace('POINT', '', $string); // remove POINT
      $string = str_replace('(', '', $string); // remove leading bracket
      $string = str_replace(')', '', $string); // remove trailing bracket
      return explode(' ', $string);
  }  

	public function string_to_coords($string) {	  
      $string = str_replace('POLYGON', '', $string); 
      $string = str_replace('(', '', $string); // remove leading bracket
      $string = str_replace(')', '', $string); // remove trailing bracket
      $arr = explode(',', $string);
      $coords= array();
      foreach ($arr as $i) {
        $pt = explode(' ', $i);
        $coords[]=new EGMapCoord($pt[0], $pt[1]);
      }
      return $coords;
  }

  public function reverseNeighborhoodData() {
    // corrects coordinates in polygon SHAPE in Neighborhoods 
    // so that order becomes lat, long 
    // marks is_corrected = 1
  
  $pg = Yii::app()->db->createCommand()
           ->select('OGR_FID as id, AsText(SHAPE) as shape,name as name')
           ->from(Yii::app()->getDb()->tablePrefix.'neighborhoods')
           ->where('is_corrected=0')
           ->queryAll();		
  $write_safe = true;                       
  foreach ($pg as $n) {
   $polygon = $n['shape'];
   echo '&gt; Reversing '.$n['name'].'<br />';
   //echo 'Original Polygon:<br />';
	 //var_dump($polygon);
	 //lb();
   $isPoint=false;
   if (stristr($polygon,'point')) {
     $isPoint=true;
     // skip
     echo '<br />problem'.$n['id'].'<br>';
     yexit();
   } else if (stristr($polygon,'GEOMETRYCOLLECTION') OR stristr($polygon,'MULTILINESTRING')) {
     // error
     echo '<br />problem'.$n['id'].'<br>';
     yexit();
   } else if (stristr($polygon,'multipolygon')) {
     $multiPoly = true;
     $poly=$this->reverseMultiPolygon($polygon);
   } else if (stristr($polygon,'polygon')) {
     $multiPoly = false;
	   $poly = $this->reversePolygonCoordinates($polygon);	     
	 }
//   echo 'Reversed Polygon:<br />';
	 // var_dump($poly);
	 //lb();
	 if (!$isPoint and $write_safe) {
     $update = Neighborhoods::model()->findByPk($n['id']);
     $update->SHAPE = new CDbExpression("PolygonFromText('".$poly."')");
     $update->is_corrected = 1;
	   $update->save();        	            
	 }
	} // end foreach
}

public function reverseMultiPolygon($string) {
  $string = str_ireplace('MultiPolygon(', '', $string); // remove POINT
  $string = substr ( $string , 0 , (strlen($string)-1) );
  $string = str_replace(')),((', '))|((', $string); // use diff boundary
  $polyList = explode('|',$string);
  // echo 'count: '.count($polyList);
  $newMulti='MULTIPOLYGON(';
  foreach($polyList as $pl) {
    $newMulti.=$this->reversePolygonCoordinates($pl,false);
    $newMulti.=',';
  }
  $newMulti = rtrim($newMulti,',');
  $newMulti.= ')';
  //echo $newMulti;
  return $newMulti;
}

public function reversePolygonCoordinates($string,$addPrefix = true) {
    if (stristr($string,'multipolygon')) {
      return false;
    }
    $string = str_ireplace('Polygon', '', $string); // remove POINT
    $string = str_replace('((', '', $string); // remove leading bracket
    $string = str_replace('))', '', $string); // remove trailing bracket
    $pointList = explode(',',$string);
    $newPoly='';
    if ($addPrefix)
      $newPoly ='POLYGON';
    $newPoly.='((';
    foreach ($pointList as $p) {
//        echo $p.'<br />';
      $pt = implode(array_reverse(explode(' ',$p)),' ');
//        echo $pt.'<br />';
      $newPoly.=$pt.',';
    }
    $newPoly=rtrim($newPoly,',');
    $newPoly.='))';
    return $newPoly;
}	
}