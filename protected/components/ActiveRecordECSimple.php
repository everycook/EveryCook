<?php
class ActiveRecordECSimple extends CActiveRecord {
	public function getAttributeLabel($attribute) {
		$label = Yii::app()->controller->trans->__get('FIELD_' . $attribute);
		if ($label != null){
			return $label;
		} else if (isset($label)){
			return 'FIELD_' . $attribute . ' is empty.';
		} else {
			//return parent::getAttributeLabel($attribute);
			return 'FIELD_' . $attribute . ' not defined.';
		}
	}
}
