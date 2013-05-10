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

class simpleClass extends stdClass implements arrayaccess {
	protected $_classname = '';
	protected $_values = array();
	
	public function __construct($values, $classname = 'unknown'){
		$this->_values = $values;
		$this->_classname = $classname;
	}
	
	public function replaceValues($values){
		$this->_values = $values;
	}
	
	public function __get($name) {
		/*if(isset($this->$name)) {
			return $this->$name;
		} else*/
		if ($name == 'attributes'){
			return $this->_values;
		} else if (isset($this->_values[$name])){
			return $this->_values[$name];
		} else {
			return null;
		}
	}
	
	public function __set($name,$value){
		$this->_values[$name] = $value;
	}
	
	public function __isset($name) {
		/*if(isset($this->$name)) {
			return true;
		} else*/
		if (isset($this->_values[$name])){
			return true;
		} else {
			return false;
		}
	}
	
	
	
	/**
	 * Returns whether there is an element at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param mixed $offset the offset to check on
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return $this->__isset($offset);
	}
	
	/**
	 * Returns the element at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param integer $offset the offset to retrieve element.
	 * @return mixed the element at the offset, null if no element is found at the offset
	 * @since 1.0.2
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}
	
	/**
	 * Sets the element at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param integer $offset the offset to set element
	 * @param mixed $item the element value
	 * @since 1.0.2
	 */
	public function offsetSet($offset,$item)
	{
		$this->$offset=$item;
	}
	
	/**
	 * Unsets the element at the specified offset.
	 * This method is required by the interface ArrayAccess.
	 * @param mixed $offset the offset to unset element
	 * @since 1.0.2
	 */
	public function offsetUnset($offset)
	{
		unset($this->$offset);
	}
}