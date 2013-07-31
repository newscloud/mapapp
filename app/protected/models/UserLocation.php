<?php

/**
 * This is the model class for table "{{user_location}}".
 *
 * The followings are the available columns in table '{{user_location}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $gps
 * @property string $address
 * @property string $address_ext
 * @property string $city
 * @property integer $city_id
 * @property integer $state_id
 * @property integer $country_id
 * @property integer $neighborhood_id
 * @property string $modified_at
 */
class UserLocation extends CActiveRecord
{
  public $lat;
  public $lon;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserLocation the static model class
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
		return '{{user_location}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_id, state_id, country_id, neighborhood_id', 'numerical', 'integerOnly'=>true), 
			array('address, address_ext', 'length', 'max'=>255),
			array('city', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, gps, address, address_ext, city, city_id, state_id, country_id, neighborhood_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'gps' => 'Gps',
			'address' => 'Address',
			'address_ext' => 'Address Ext',
			'city' => 'City',
			'city_id' => 'City',
			'state_id' => 'State',
			'country_id' => 'Country',
			'neighborhood_id' => 'Neighborhood',
			'modified_at' => 'Modified At',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('gps',$this->gps,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('address_ext',$this->address_ext,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('state_id',$this->state_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('neighborhood_id',$this->neighborhood_id);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function createDefault($id = 0) {
	  // creates default UserLocation entry for a user if not exists
	  $x =UserLocation::model()->findByAttributes(array('user_id'=>$id));
    if ($x === null) {
      $ul = new UserLocation();
      $ul->neighborhood_id = 0; // default
      $ul->user_id = $id;
      $ul->gps = new CDbExpression("GeomFromText('Point(0 0)')"); // default
      $ul->address ='';
      $ul->address_ext ='';     
      $ul->save();
    } 
  }

  public function reverseLookupGeo($latitude,$longitude) {
    $result = Yii::app()->geocoder->reverse($latitude, $longitude);
    return $result;
  }

  //
  
  public function lookupGeoLatLon ($address='', $city='', $state_id =0, $country_id =0) {
    $location_info = array();
    if ($address<>'' and $city<>'') {
      $geo_addr = $address.', '.$city;
      if ($state_id>0) {
        // to do - fetch state
        $geo_addr = $geo_addr . ', ';
      }
      if ($country_id>=0) {
        // to dofetch country        
        $geo_addr = $geo_addr . ' ';
      }
      $result = Yii::app()->geocoder->geocode($geo_addr);
      // to do - check if it returns successfully
      $location_info['gps'] = "Point(".$result['latitude']." ".$result['longitude'].")";
      // to do
      // fetch country code from geocode results 
      // fetch state code from geocode results
      return $location_info;
    }
  }
  
  public function getLocationAsText($user_id) {
		// look up user location
    $ul = Yii::app()->db->createCommand()
             ->select('AsText(gps) as gps')
             ->from(Yii::app()->getDb()->tablePrefix.'user_location ul')
             ->where('ul.user_id=:user_id', array(':user_id'=>$user_id))
             ->queryRow();		             
    return $ul['gps'];
  }

  public function setLocationFlag($user_id) {
    $user = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'users',array('is_located'=>1),'id=:id', array(':id'=>$user_id));   
    // UserIdentity::model()->setState('is_located', $user->is_located); 
  }
  
}