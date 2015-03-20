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

class SolrExportController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('recipes','ingredients','recipesDelete'),
				'roles'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIngredients(){
		$ingredientDetailSelectFields = Ingredients::model()->getExportSelectFields() . ', nutrient_data.*' . GroupNames::model()->getExportSelectFields() . SubgroupNames::model()->getExportSelectFields() . Origins::model()->getExportSelectFields() . IngredientConveniences::model()->getExportSelectFields() . Storability::model()->getExportSelectFields() . IngredientStates::model()->getExportSelectFields() . Conditions::model()->getExportSelectFields() . TempGroups::model()->getExportSelectFields();
		
		$command = Yii::app()->db->createCommand()
		->select($ingredientDetailSelectFields)
		->from('ingredients')
		->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
		->leftJoin('group_names', 'ingredients.GRP_ID=group_names.GRP_ID')
		->leftJoin('subgroup_names', 'ingredients.SGR_ID=subgroup_names.SGR_ID')
		->leftJoin('origins', 'ingredients.ORI_ID=origins.ORI_ID')
		->leftJoin('ingredient_conveniences', 'ingredients.ICO_ID=ingredient_conveniences.ICO_ID')
		->leftJoin('storability', 'ingredients.STB_ID=storability.STB_ID')
		->leftJoin('ingredient_states', 'ingredients.IST_ID=ingredient_states.IST_ID')
		->leftJoin('conditions', 'ingredients.CND_ID=conditions.CND_ID')
		->leftJoin('temp_groups', 'ingredients.TGR_ID=temp_groups.TGR_ID');
		//->order('steps.CHANGED_ON desc')
		//->group('ingredients.ING_ID')
		
		$ingredients = $command->queryAll();
		foreach ($ingredients as $ingredient){
			$ingredient['id']='ING_ID:'.$ingredient['ING_ID'];
			$ingredient['CREATED_ON_dt'] = date("Y-m-d H:i:s.000Z", $ingredient['CREATED_ON']); //format:'c' =  	ISO 8601 date, but not te suggestet one by solr...
			$ingredient['CHANGED_ON_dt'] = date("Y-m-d H:i:s.000Z", $ingredient['CHANGED_ON']); //format:'c' =  	ISO 8601 date, but not te suggestet one by solr...
			
			$ingredientSolr = new IngredientsSolr();
			foreach ($ingredient as $key=>$value){
				//$ingredientSolr->add($key, $value);
				if (strpos($key, "ING_SYNONYM_") !== false){
					if (strlen($value)>0){
						$value = split(';', $value);
					}
				}
				$ingredientSolr->$key=$value;
				// 				echo "$key = $value<br>\n";
			}
			
			if ($ingredientSolr->save()){ // adds the document to solr
				echo $ingredientSolr->id . ':' . "<br>\n";
			} else {
				echo 'save error: ' . $ingredientSolr->id . ':' . "<br>\n";
			}
		}
		//$ingredientSolr->getSolrConnection()->commit();
		Yii::app()->solrIng->commit();
	}
	
	public function actionRecipesDelete(){
		$recipes = Yii::app()->db->createCommand()
		->select('recipes.REC_ID')
		->from('recipes')
		->where('recipes.REC_IS_PRIVATE = "Y" OR recipes.REC_APPROVED = "N"')
		->order('recipes.CHANGED_ON desc')
		//->group('recipes.REC_ID')
		//->limit(self::RECIPES_AMOUNT * 2,0)
		->queryAll();
		
		foreach ($recipes as $recipe){
			$recipe['id']='REC_ID:'.$recipe['REC_ID'];

			$recipeSolr = new RecipesSolr();
			foreach ($recipe as $key=>$value){
				$recipeSolr->$key=$value;
			}
			
			if ($recipeSolr->delete()){ // delete the document from solr
				echo $recipeSolr->id . ':' . "<br>\n";
			} else {
				echo 'delete error: ' . $recipeSolr->id . ':' . "<br>\n";
			}
		}
		//$recipeSolr->getSolrConnection()->commit();
		Yii::app()->solr->commit();
	}
	
	public function actionRecipes(){
		$recipes = Yii::app()->db->createCommand()
		->select('recipes.*, professional_profiles.PRF_FIRSTNAME, professional_profiles.PRF_LASTNAME, ' . CusineTypes::model()->getExportSelectFields() . CusineSubTypes::model()->getExportSelectFields() . CusineSubSubTypes::model()->getExportSelectFields() . RecipeTypes::model()->getExportSelectFields())
		->from('recipes')
		//->join('steps', 'recipes.REC_ID = steps.REC_ID')
		->leftJoin('cusine_types', 'recipes.CUT_ID = cusine_types.CUT_ID')
		->leftJoin('cusine_sub_types', 'recipes.CST_ID = cusine_sub_types.CST_ID')
		->leftJoin('cusine_sub_sub_types', 'recipes.CSS_ID = cusine_sub_sub_types.CSS_ID')
		->leftJoin('recipe_types', 'recipes.RET_ID = recipe_types.RET_ID')
		->leftJoin('professional_profiles', 'recipes.PRF_UID = professional_profiles.PRF_UID')
		//->where('recipes.REC_IS_PRIVATE NOT "Y"')
		->where('recipes.REC_IS_PRIVATE != "Y" AND recipes.REC_APPROVED != "N"')
		->order('recipes.CHANGED_ON desc')
		//->group('recipes.REC_ID')
		//->limit(self::RECIPES_AMOUNT * 2,0)
		->queryAll();

		//TODO: change only "upload" changed recipes/ingredients/... last severel hours
		//TODO: don't filter them out that are recipes.REC_IS_PRIVATE/recipes.REC_APPROVED, but check fields on result and remove them from solr index!
		
		$ingredientDetailSelectFields = Ingredients::model()->getExportSelectFields() . GroupNames::model()->getExportSelectFields() . SubgroupNames::model()->getExportSelectFields() . Origins::model()->getExportSelectFields() . IngredientConveniences::model()->getExportSelectFields() . Storability::model()->getExportSelectFields() . IngredientStates::model()->getExportSelectFields() . Conditions::model()->getExportSelectFields() . TempGroups::model()->getExportSelectFields();
		foreach ($recipes as $recipe){
			$command = Yii::app()->db->createCommand()
			->select($ingredientDetailSelectFields)
			->from('steps')
			//->join('steps', 'recipes.REC_ID = steps.REC_ID')
			->join('ingredients', 'steps.ING_ID = ingredients.ING_ID')
			//->leftJoin('nutrient_data', 'ingredients.NUT_ID=nutrient_data.NUT_ID')
			->leftJoin('group_names', 'ingredients.GRP_ID=group_names.GRP_ID')
			->leftJoin('subgroup_names', 'ingredients.SGR_ID=subgroup_names.SGR_ID')
			->leftJoin('origins', 'ingredients.ORI_ID=origins.ORI_ID')
			->leftJoin('ingredient_conveniences', 'ingredients.ICO_ID=ingredient_conveniences.ICO_ID')
			->leftJoin('storability', 'ingredients.STB_ID=storability.STB_ID')
			->leftJoin('ingredient_states', 'ingredients.IST_ID=ingredient_states.IST_ID')
			->leftJoin('conditions', 'ingredients.CND_ID=conditions.CND_ID')
			->leftJoin('temp_groups', 'ingredients.TGR_ID=temp_groups.TGR_ID')
			->where('steps.REC_ID = :id', array(':id'=>$recipe['REC_ID']));
			//->order('steps.CHANGED_ON desc')
			//->group('ingredients.ING_ID')
			

			$command->distinct = true;
			$ingredients = $command->queryAll();
			
			$ingFields = array();
			foreach($ingredients as $ing){
				foreach($ing as $key=>$value){
					if (!isset($ingFields[$key])){
						$ingFields[$key] = array();
					}
					$ingFields[$key][] = $value;
				}
			}
			unset($ingFields['PRF_UID']);
			
			
			
			
			$recipe['id']='REC_ID:'.$recipe['REC_ID'];
			$recipe['CREATED_ON_dt'] = date("Y-m-d\TH:i:s.000\Z", $recipe['CREATED_ON']); //format:'c' =  	ISO 8601 date, but not te suggestet one by solr...
			$recipe['CHANGED_ON_dt'] = date("Y-m-d\TH:i:s.000\Z", $recipe['CHANGED_ON']); //format:'c' =  	ISO 8601 date, but not te suggestet one by solr...
			
			if (isset($recipe['PRF_FIRSTNAME'])){
				if (isset($recipe['PRF_LASTNAME'])){
					$recipe['PRF_NAME'] = $recipe['PRF_FIRSTNAME'] . ' ' . $recipe['PRF_LASTNAME'];
				} else {
					$recipe['PRF_NAME'] = $recipe['PRF_FIRSTNAME'];
				}
				$recipe['autor'] = 'Professional';
			} else if (isset($recipe['PRF_LASTNAME'])){
				$recipe['PRF_NAME'] = $recipe['PRF_LASTNAME'];
				$recipe['autor'] = 'Professional';
			} else {
				$recipe['autor'] = 'user';
			}
// 			echo CJSON::encode($recipe);
// 			die();	
			//var_dump($recipe);
			$recipeSolr = new RecipesSolr();
			foreach ($recipe as $key=>$value){
				if (strpos($key, "REC_SYNONYM_") !== false){
					if (strlen($value)>0){
						$value = split(';', $value);
					}
				}
				//$recipeSolr->add($key, $value);
				$recipeSolr->$key=$value;
// 				echo "$key = $value<br>\n";
			}
// 			echo 'attributes<br>';
// 			foreach($recipeSolr->attributeNames() as $attribute) {
// 				echo "$attribute<br>\n";
// 			}
			foreach ($ingFields as $key=>$value){
				$recipeSolr->$key=$value;
			}
			
			//$doc = $recipeSolr->getInputDocument();
			//var_dump($doc);
// 			echo 'FieldNames<br>';
// 			foreach ( $doc->getFieldNames() as $attribute ) {
// 				echo "$attribute<br>\n";
// 			}
			
			if ($recipeSolr->save()){ // adds the document to solr
				echo $recipeSolr->id . ':' . "<br>\n";
			} else {
				echo 'save error: ' . $recipeSolr->id . ':' . "<br>\n";
			}
		}
		//$recipeSolr->getSolrConnection()->commit();
		Yii::app()->solr->commit();
		
		//rebuild suggest indexes
		$criteria = new ASolrCriteria();
		//$criteria->setParam('qt','suggest' . strtolower(Yii::app()->language));
		$criteria->setParam('qt','suggest');
		$criteria->setParam('suggest.build','true');
		$criteria->setParam('spellcheck.build','true');
		
		Yii::app()->solr->search($criteria);
	}
	
}
