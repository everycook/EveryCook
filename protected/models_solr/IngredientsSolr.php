<?php
class IngredientsSolr extends MultiLangSolrDocument {
	/**
	 * Required for all ASolrDocument sub classes
	 * @see ASolrDocument::model()
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}
	/**
	 * Returns the name of the primary key of the associated solr index.
	 * Child classes should override this if the primary key is anything other than "id"
	 * @return mixed the primary key attribute name(s). Defaults to "id"
	 */
	public function primaryKey()
	{
		return "ING_ID";
	}
	
	/**
	 * @return ASolrConnection the solr connection to use for this model
	 */
	public function getSolrConnection() {
		return Yii::app()->solrIng;
	}
}
?>