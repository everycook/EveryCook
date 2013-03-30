<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

class CouPeopleGDA extends CModel
{
	public $amount = null;
	public $gender = null;
	public $gda_id;
	public $kcal;
	
	public function getGda_id_kcal(){
		return $this->gda_id . '_' . $this->kcal;
	}
	
	public function setGda_id_kcal($value){
		if ($value!=''){
			list($this->gda_id, $this->kcal) = explode('_', $value);
		} else {
			$this->gda_id = null;
			$this->kcal = null;
		}
	}
	
	public function attributeNames(){
		return array('amount', 'gender', 'gda_id_kcal', 'gda_id', 'kcal');
	}
	
	public function __get($name) {
		if($name == 'gda_id_kcal'){
			return parent::__get($name);
		}
		if(isset($this->$name)) {
			return $this->$name;
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return MeaToCou the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('amount, gender, gda_id, kcal', 'required'),
			array('amount, gender, gda_id_kcal, gda_id, kcal', 'safe'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
	}

}