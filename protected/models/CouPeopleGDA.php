<?php

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