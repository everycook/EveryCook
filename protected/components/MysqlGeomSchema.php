<?php
class MysqlGeomSchema extends CMysqlSchema {

	/**
	 * Creates a command builder for the database.
	 * This method overrides parent implementation in order to create a MSSQL specific command builder
	 * @return CDbCommandBuilder command builder instance
	 */
	protected function createCommandBuilder()
	{
		return new MysqlGeomCommandBuilder($this);
	}
}
?>