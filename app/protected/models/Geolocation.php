<?php

class Geolocation extends CFormModel
{
	public $lat,$lon,$gps,$neighborhood,$address,$city;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
		);
	}
	
}