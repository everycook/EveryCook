<?php
class Translations
{
	private $_textes=array();				// text name => text value
	
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
		if(isset($this->_textes[$name]))
			return $this->_textes[$name];
			
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
		if(isset($this->_textes[$name]))
			return true;
	}
}