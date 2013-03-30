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

class ViewAction extends CViewAction {
	/**
	 * Runs the action.
	 * This method displays the view requested by the user.
	 * @throws CHttpException if the view is invalid
	 */
	public function run()
	{
		$this->resolveView($this->getRequestedView());
		$controller=$this->getController();
		
		if($this->layout!==null)
		{
			$layout=$controller->layout;
			$controller->layout=$this->layout;
		}
		
		$this->onBeforeRender($event=new CEvent($this));
		if(!$event->handled)
		{
			if($this->renderAsText)
			{
				$text=file_get_contents($controller->getViewFile($this->view));
				$controller->renderText($text);
			}
			else
			{
				if($controller->getIsAjaxRequest()){
					$output=$this->renderPartial($this->view);
				} else {
					$controller->render($this->view);
				}
			}
			$this->onAfterRender(new CEvent($this));
		}
		if($this->layout!==null)
			$controller->layout=$layout;
	}
}