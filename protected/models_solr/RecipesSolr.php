<?php
class RecipesSolr extends MultiLangSolrDocument {
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
		return "REC_ID";
	}

	/**
	 * Sets the defaults to solr query criteria for the current model.
	 * @param ASolrCriteria $criteria the query criteria to modify
	 */
	public function setDefaults(ASolrCriteria $criteria)
	{
		parent::setDefaults($criteria);
		$criteria->setParam('qt','searchRecipes_' . strtolower(Yii::app()->language));
	}
	
}
?>