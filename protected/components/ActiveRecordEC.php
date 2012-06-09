<?php
class ActiveRecordEC extends ActiveRecordECSimple {
	protected function beforeValidate() {
		if(!Yii::app()->user->isGuest) {
			$dateTime = new DateTime();
			if($this->getIsNewRecord()) {
				// get UnixTimeStamp
				$this->CREATED_ON = $dateTime->getTimestamp();
				$this->CREATED_BY = Yii::app()->user->id;
			}
			$this->CHANGED_ON = $dateTime->getTimestamp();
			$this->CHANGED_BY = Yii::app()->user->id;
			return true;
		}
		return parent::beforeValidate();
	}

	/**
	* @return string the associated database table name
	*/
	public function tableName() {
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'.table_name';
	}
}
