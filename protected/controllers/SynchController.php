<?php
class SynchController extends Controller
{
	function getCreate($dbcon, $table){	
		$datareader = $dbcon->createCommand("SHOW CREATE TABLE `$table`")->query();
		$datareader->setFetchMode(PDO::FETCH_NUM);
		$row = $datareader->read();
		if (count($row)>0) {
			return str_replace("\n", "", $row[1]) . ";\n";
		}
		return "";
	}

	function getContentByUser($dbcon, $table, $uid){
		$insert_sql = "";
		$datareader = $dbcon->createCommand("SELECT * FROM `$table` WHERE PRF_UID = '$uid'")->query();
		$datareader->setFetchMode(PDO::FETCH_NUM);
		$num_fields = $datareader->getColumnCount();
		$processedRow=0;
		foreach ($datareader as $fetch_row){
			if ($processedRow == 0){
				$insert_sql .= "INSERT INTO $table VALUES(";
			} else {
				$insert_sql .= "),\n(";
			}
			for ($n=1;$n<=$num_fields;$n++) {
				$m = $n - 1;
				//$insert_sql .= "'".mysql_real_escape_string($fetch_row[$m])."', ";
				$insert_sql .= "'".@mysql_escape_string($fetch_row[$m])."', ";
			}
			$insert_sql = substr($insert_sql,0,-2);
			
			++$processedRow;
			if ($processedRow == 100){
				$processedRow = 0;
				$insert_sql .= ");\n";
			}
		}
		if ($processedRow != 0){
			$insert_sql .= ");\n";
		}
		return $insert_sql;
	}

	function getContentByIDs($dbcon, $table, $id_field, $ids){
		$insert_sql = "";
		$datareader = $dbcon->createCommand("SELECT * FROM `$table` WHERE $id_field IN ($ids)")->query();
		$datareader->setFetchMode(PDO::FETCH_NUM);
		$num_fields = $datareader->getColumnCount();
		$processedRow=0;
		foreach ($datareader as $fetch_row){
			if ($processedRow == 0){
				$insert_sql .= "INSERT INTO $table VALUES(";
			} else {
				$insert_sql .= "),\n(";
			}
			for ($n=1;$n<=$num_fields;$n++) {
				$m = $n - 1;
				//$insert_sql .= "'".mysql_real_escape_string($fetch_row[$m])."', ";
				$insert_sql .= "'".@mysql_escape_string($fetch_row[$m])."', ";
			}
			$insert_sql = substr($insert_sql,0,-2);
			
			++$processedRow;
			if ($processedRow == 100){
				$processedRow = 0;
				$insert_sql .= ");\n";
			}
		}
		if ($processedRow != 0){
			$insert_sql .= ");\n";
		}
		return $insert_sql;
	}
	
	public function actionExportPrivData(){
		if (isset($_POST['user']) && isset($_POST['pw'])){
			$_identity=new UserIdentity(trim($_POST['user']), trim($_POST['pw']));
			$_identity->authenticate();
				
			if($_identity->errorCode===UserIdentity::ERROR_NONE){
				Yii::app()->user->login($_identity, 0);
			}
		}
		
		if (Yii::app()->user->id != 0){
			if(isset($_POST['with_create'])){
				$with_create = trim(strtolower($_POST['with_create'])) == 'true';
			} else {
				$with_create = true;
			}
			echo "use ec_priv;\n";
			if ($with_create){
				echo "DROP TABLE IF EXISTS meals;\n";
				echo $this->getCreate(Yii::app()->dbp, 'meals');
				echo "DROP TABLE IF EXISTS mea_to_cou;\n";
				echo $this->getCreate(Yii::app()->dbp, 'mea_to_cou');
				echo "DROP TABLE IF EXISTS profiles;\n";
				echo $this->getCreate(Yii::app()->dbp, 'profiles');
				echo "DROP TABLE IF EXISTS shoppinglists;\n";
				echo $this->getCreate(Yii::app()->dbp, 'shoppinglists');
			}
			echo $this->getContentByUser(Yii::app()->dbp, 'meals', Yii::app()->user->id);
			echo $this->getContentByUser(Yii::app()->dbp, 'profiles', Yii::app()->user->id);
			$mealIds = Yii::app()->dbp->createCommand()->select('MEA_ID')->from('meals')->queryColumn();
			echo $this->getContentByIDs(Yii::app()->dbp, 'mea_to_cou', 'MEA_ID', implode(', ', $mealIds));
			echo $this->getContentByIDs(Yii::app()->dbp, 'shoppinglists', 'SHO_ID', implode(', ', Yii::app()->user->shoppinglists));
		} else {
			echo "#Error: " . $this->trans->LOGIN_ERROR . "\n";
		}
	}
}