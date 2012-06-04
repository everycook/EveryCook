<?php
class ActiveRecordEC extends CActiveRecord {

   protected function beforeValidate() {
      if(!Yii::app()->isGuest) {
         if($this->getIsNewRecord()) {
            // get UnixTimeStamp
            $this->created_on = DateTime::getTimestamp();
            $this->created_by = Yii::app()->user->id;
         }
         $this->changed_on = DateTime::getTimestamp();
         $this->changed_by = Yii::app()->user->id;
         return true;
      }
      parent::beforeValidate();
   }

   /**
    * @return string the associated database table name
    */
   public function tableName() {
       preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
       return $matches[1].'.table_name';
   }
}
