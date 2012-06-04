<?php
class ActiveRecordECPriv extends ActiveRecordEC {

   /**
    * @return CDbConnection
    */
   public function getDbConnection(){
       return Yii::app()->dbp;
   }
}


