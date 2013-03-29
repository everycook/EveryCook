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

class MysqlGeomCommandBuilder extends CDbCommandBuilder {

	/**
	 * Creates a SELECT command for a single table.
	 * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
	 * @param CDbCriteria $criteria the query criteria
	 * @param string $alias the alias name of the primary table. Defaults to 't'.
	 * @return CDbCommand query command.
	 */
	public function createFindCommand($table,$criteria,$alias='t')
	{
		$this->ensureTable($table);
		$select=is_array($criteria->select) ? implode(', ',$criteria->select) : $criteria->select;
		if($criteria->alias!='')
			$alias=$criteria->alias;
		$alias=$this->getSchema()->quoteTableName($alias);

		/*
		// issue 1432: need to expand * when SQL has JOIN
		if($select==='*' && !empty($criteria->join))
		{
			$prefix=$alias.'.';
			$select=array();
			foreach($table->getColumnNames() as $name)
				$select[]=$prefix.$this->getSchema()->quoteColumnName($name);
			$select=implode(', ',$select);
		}
		*/
		//always expand * to be sure geom Fields come as Text
		if($select==='*') {
			$prefix=$alias.'.';
			$select=array();
			foreach($table->columns as $column){
				if (stripos('"Point", "LineString", "Polygon", "MultiPoint", "MultiLineString", "MultiPolygon", "GeometryCollection", "Geometry"', '"'.$column->dbType.'"') !== false) {
					$select[]='AsText('.$prefix.$this->getSchema()->quoteColumnName($column->name) . ') as ' . $column->name;
				} else {
					$select[]=$prefix.$this->getSchema()->quoteColumnName($column->name);
				}
			}
			$select=implode(', ',$select);
		}
		
		$sql=($criteria->distinct ? 'SELECT DISTINCT':'SELECT')." {$select} FROM {$table->rawName} $alias";
		$sql=$this->applyJoin($sql,$criteria->join);
		$sql=$this->applyCondition($sql,$criteria->condition);
		$sql=$this->applyGroup($sql,$criteria->group);
		$sql=$this->applyHaving($sql,$criteria->having);
		$sql=$this->applyOrder($sql,$criteria->order);
		$sql=$this->applyLimit($sql,$criteria->limit,$criteria->offset);
		$command=$this->getDbConnection()->createCommand($sql);
		$this->bindValues($command,$criteria->params);
		return $command;
	}
	
	/**
	 * Creates an INSERT command.
	 * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
	 * @param array $data data to be inserted (column name=>column value). If a key is not a valid column name, the corresponding value will be ignored.
	 * @return CDbCommand insert command
	 */
	public function createInsertCommand($table,$data)
	{
		$this->ensureTable($table);
		$fields=array();
		$values=array();
		$placeholders=array();
		$i=0;
		foreach($data as $name=>$value)
		{
			if(($column=$table->getColumn($name))!==null && ($value!==null || $column->allowNull))
			{
				$fields[]=$column->rawName;
				if($value instanceof CDbExpression)
				{
					$placeholders[]=$value->expression;
					foreach($value->params as $n=>$v)
						$values[$n]=$v;
				}
				else if (stripos('"Point", "LineString", "Polygon", "MultiPoint", "MultiLineString", "MultiPolygon", "GeometryCollection", "Geometry"', '"'.$column->dbType.'"') !== false) 
				{
					$placeholders[]='GeomFromText(' . self::PARAM_PREFIX.$i . ')';
					$values[self::PARAM_PREFIX.$i]=$column->typecast($value);
					$i++;
				}
				else
				{
					$placeholders[]=self::PARAM_PREFIX.$i;
					$values[self::PARAM_PREFIX.$i]=$column->typecast($value);
					$i++;
				}
			}
		}
		if($fields===array())
		{
			$pks=is_array($table->primaryKey) ? $table->primaryKey : array($table->primaryKey);
			foreach($pks as $pk)
			{
				$fields[]=$table->getColumn($pk)->rawName;
				$placeholders[]='NULL';
			}
		}
		$sql="INSERT INTO {$table->rawName} (".implode(', ',$fields).') VALUES ('.implode(', ',$placeholders).')';
		$command=$this->getDbConnection()->createCommand($sql);

		foreach($values as $name=>$value)
			$command->bindValue($name,$value);

		return $command;
	}

	/**
	 * Creates an UPDATE command.
	 * @param mixed $table the table schema ({@link CDbTableSchema}) or the table name (string).
	 * @param array $data list of columns to be updated (name=>value)
	 * @param CDbCriteria $criteria the query criteria
	 * @return CDbCommand update command.
	 */
	public function createUpdateCommand($table,$data,$criteria)
	{
		$this->ensureTable($table);
		$fields=array();
		$values=array();
		$bindByPosition=isset($criteria->params[0]);
		$i=0;
		foreach($data as $name=>$value)
		{
			if(($column=$table->getColumn($name))!==null)
			{
				if($value instanceof CDbExpression)
				{
					$fields[]=$column->rawName.'='.$value->expression;
					foreach($value->params as $n=>$v)
						$values[$n]=$v;
				}
				else if (stripos('"Point", "LineString", "Polygon", "MultiPoint", "MultiLineString", "MultiPolygon", "GeometryCollection", "Geometry"', '"'.$column->dbType.'"') !== false) 
				{
					if($bindByPosition) {
						$fields[]=$column->rawName.'=GeomFromText(?)';
						$values[]=$column->typecast($value);
					} else {
						$fields[]=$column->rawName.'=GeomFromText(' . self::PARAM_PREFIX.$i . ')';
						$values[self::PARAM_PREFIX.$i]=$column->typecast($value);
						$i++;
					}
				}
				else if($bindByPosition)
				{
					$fields[]=$column->rawName.'=?';
					$values[]=$column->typecast($value);
				}
				else
				{
					$fields[]=$column->rawName.'='.self::PARAM_PREFIX.$i;
					$values[self::PARAM_PREFIX.$i]=$column->typecast($value);
					$i++;
				}
			}
		}
		if($fields===array())
			throw new CDbException(Yii::t('yii','No columns are being updated for table "{table}".',
				array('{table}'=>$table->name)));
		$sql="UPDATE {$table->rawName} SET ".implode(', ',$fields);
		$sql=$this->applyJoin($sql,$criteria->join);
		$sql=$this->applyCondition($sql,$criteria->condition);
		$sql=$this->applyOrder($sql,$criteria->order);
		$sql=$this->applyLimit($sql,$criteria->limit,$criteria->offset);

		$command=$this->getDbConnection()->createCommand($sql);
		//echo $sql;
		//print_r($values);
		//print_r($criteria->params);
		$this->bindValues($command,array_merge($values,$criteria->params));
		
		//print_r($command);
		//die();

		return $command;
	}
}
?>