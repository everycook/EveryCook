<?php
class MultiLangSolrDocument extends ASolrDocument {
	/**
	 * Required for all ASolrDocument sub classes
	 * @see ASolrDocument::model()
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	/**
	 * @return ASolrConnection the solr connection to use for this model
	 */
	public function getSolrConnection() {
		return Yii::app()->solr;
	}

	/**
	 * Sets the defaults to solr query criteria for the current model.
	 * @param ASolrCriteria $criteria the query criteria to modify
	 */
	public function setDefaults(ASolrCriteria $criteria)
	{
		$criteria->setParam('spellcheck.dictionary','spell_'.strtolower(Yii::app()->language));
		$criteria->setParam('df',Yii::app()->language . '_text');
	}
	
	//Override
	public function getSolrCriteria($createIfNull=true)
	{
		if($this->_solrCriteria===null)
		{
			if(($c=$this->defaultScope())!==array() || $createIfNull) {
				$this->_solrCriteria=new ASolrCriteria($c);
			}
			if ($createIfNull){
				$this->setDefaults($this->_solrCriteria);
			}
		}
		return $this->_solrCriteria;
	}
	
	
	/**
	 * Returns a property value or an event handler list by property or event name.
	 * This method overrides the parent implementation by returning
	 * a key value if the key exists in the collection.
	 * @param string $name the property name or the event name
	 * @return mixed the property value or the event handler list
	 * @throws CException if the property/event is not defined.
	 */
	public function __get($name)
	{
		if($this->_attributes->contains($name)) {
			return $this->_attributes->itemAt($name);
		} else if($this->_attributes->contains($name.'_'.Yii::app()->language)){
			return $this->_attributes->itemAt($name.'_'.Yii::app()->language);
		} else {
			return parent::__get($name);
		}
	}
	
	/**
	 * Checks if a property value is null.
	 * This method overrides the parent implementation by checking
	 * if the key exists in the collection and contains a non-null value.
	 * @param string $name the property name or the event name
	 * @return boolean whether the property value is null
	 * @since 1.0.1
	 */
	public function __isset($name)
	{
		if($this->_attributes->contains($name)) {
			return $this->_attributes->itemAt($name)!==null;
		} else if($this->_attributes->contains($name.'_'.Yii::app()->language)){
			return $this->_attributes->itemAt($name.'_'.Yii::app()->language)!==null;
		} else {
			return parent::__isset($name);
		}	
	}
	
}
?>