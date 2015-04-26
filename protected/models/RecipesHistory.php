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

/**
 * This is the model class for table "recipes_history".
 *
 * The followings are the available columns in table 'recipes_history':
 * @property integer $REC_ID
 * @property integer $PRF_UID
 * @property string $REC_IMG_FILENAME
 * @property string $REC_IMG_AUTH
 * @property string $REC_IMG_ETAG
 * @property integer $RET_ID
 * @property integer $REC_KCAL
 * @property string $REC_HAS_ALLERGY_INFO
 * @property string $REC_SUMMARY
 * @property string $REC_APPROVED
 * @property integer $REC_SERVING_COUNT
 * @property string $REC_WIKI_LINK
 * @property string $REC_IS_PRIVATE
 * @property integer $DIF_ID
 * @property string $REC_RATING
 * @property integer $REC_TIME_PREP
 * @property integer $REC_TIME_COOK
 * @property integer $REC_TIME_TOTAL
 * @property integer $CUT_ID
 * @property integer $CST_ID
 * @property integer $CSS_ID
 * @property double $REC_CUSINE_GPS_LAT
 * @property double $REC_CUSINE_GPS_LNG
 * @property string $REC_TOOLS
 * @property string $REC_SYNONYM_EN_GB
 * @property string $REC_SYNONYM_DE_CH
 * @property string $REC_NAME_EN_GB
 * @property string $REC_NAME_DE_CH
 * @property integer $CREATED_BY
 * @property integer $CREATED_ON
 * @property integer $CHANGED_BY
 * @property integer $CHANGED_ON
 */
class RecipesHistory extends Recipes
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return RecipesHistory the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName(){
		return 'recipes_history';
	}
	
	public function relations(){
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'recipeTypes' => array(self::BELONGS_TO, 'RecipeTypes', 'RET_ID'),
			'recToCois' => array(self::HAS_MANY, 'RecToCoiHistory', 'REC_ID,CHANGED_ON'),
			'steps' => array(self::HAS_MANY, 'StepsHistory', 'REC_ID,CHANGED_ON'),
		);
	}
	
	protected function beforeValidate() {
		$this->updateChangePointer = false;
		$this->updateChangeTime = false;
		return parent::beforeValidate();
	}
}