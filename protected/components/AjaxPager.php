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

class AjaxPager extends CBasePager
{
	/**
	 * @var boolean if there is also a previous link / pager.
	 */
	public $prev = false;
	/**
	 * @var array HTML attributes for the enclosing 'div' tag.
	 */
	public $htmlOptions=array();

	/**
	 * Initializes the pager by setting some default property values.
	 */
	public function init()
	{
		if(!isset($this->htmlOptions['id']))
			$this->htmlOptions['id']=$this->getId();
	}

	/**
	 * Executes the widget.
	 * This overrides the parent implementation by displaying the generated page buttons.
	 */
	public function run()
	{
		if(($pageCount=$this->getPageCount())<=1)
			return;
		$currentPage = $this->getCurrentPage();
		$params = $this->getController()->getActionParams();
		$autoLoad = true;
		if ($this->prev){
			if ($currentPage<=0)
				return;
			if (isset($_GET['noPrev']) && $_GET['noPrev'])
				return;
			$nextPage = $currentPage-1;
			$params['noNext'] = true;
			$text = Yii::app()->controller->trans->GENERAL_LOADING_PREVPAGE;
			//$text = Yii::app()->controller->trans->GENERAL_CLICK_TO_LOAD_PREVPAGE;
			//$autoLoad = false;
		} else {
			if (isset($_GET['noNext']) && $_GET['noNext'])
				return;
			$nextPage = $currentPage+1;
			if ($nextPage>=$pageCount)
				return;
			$params['noPrev'] = true;
			$text = Yii::app()->controller->trans->GENERAL_LOADING_NEXTPAGE;
			//Yii::app()->controller->trans->GENERAL_CLICK_TO_LOAD_NEXTPAGE;
		}
		
		$params['ajaxPaging'] = true;
		$this->getPages()->params = $params;
		
		$url = $this->getPages()->createPageUrl($this->getController(),$nextPage);
		
		echo '<div id="ajaxPaging' . (($nextPage<$currentPage)?'Prev':'') . '" class="ajaxPaging' . (($autoLoad)?'AutoLoad':'') . '">';
		echo '<input type="hidden" class="pagingUrl" value="' . $url . '"/>';
		echo '<div class="pagingText">' . $text . '</div>';
		echo '<div class="pagingLoading backpic"></div>';
		echo '</div>';
	}
}