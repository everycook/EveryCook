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

class Translations
{
	private $_textes=array();				// text name => text value
	
	public $showKeyIfAbsent = false;
	public $showKeyAlways = false;
	
	/**
	 * Constructor.
	 * @param string $lang language name.
	 */
	public function __construct($lang){
		$result = Yii::app()->db->createCommand()->select('TXT_NAME,'.$lang)->from('textes')->queryAll();
		$this->_textes = CHtml::listData($result,'TXT_NAME',$lang);
	}	
	
	/**
	 * PHP getter magic method.
	 * This method is overridden so that attributes can be accessed like properties.
	 * @param string $name property name
	 * @return mixed property value
	 * @see getAttribute
	 */
	public function __get($name) {
		if($this->showKeyAlways){
			return $name;
		} else if(isset($this->_textes[$name])) {
			return $this->_textes[$name];
		} else {
			if ($this->showKeyIfAbsent){
				return '???' . $name . '???';
			} else {
				return null;
			}
		}
	}

	/**
	 * Checks if a property value is null.
	 * This method overrides the parent implementation by checking
	 * if the named attribute is null or not.
	 * @param string $name the property name or the event name
	 * @return boolean whether the property value is null
	 * @since 1.0.1
	 */
	public function __isset($name) {
		if(isset($this->_textes[$name])){
			return true;
		} else {
			return false;
		}
	}
}