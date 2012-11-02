<?php

/*
 *      dbal.php
 *      
 *      Copyright 2009 A.K.Karthikeyan <ak@mindaslab.in>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */



include_once "key.php";

/*
 * Database base abstraction layer class. Helps programmers write
 * database applications easily.
 */
  
class dbal{
	
/**
 * Executes a query and returns result if $return_table is true, else
 * just executes the query
 */
static function execute_query($query, $return_table=false){
if(DBAL_DEBUG) { echo $query,"\n"; }
# connect to DB server
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD)
or die("Could not connect to DB SERVER");

# select a database
$db = mysql_select_db(DATABASE, $connection)
or die("Could not select Database");

$result = mysql_query($query)
or die("Query failed: ". mysql_error());

# closing connection
mysql_close($connection);
#print_r($result);

if($return_table){
$table =  Array();

$i=0;

while ($row = mysql_fetch_array($result)){
	$table[$i] = $row;
	$i++;
}
return $table;
}
return true;
}

/**
 * Selects columns specified by $columns from table $table which
 * satisfies condition $condition. One can even set limit by passing
 * $limit value
 */ 
static function select($table, $columns="*", $condition="", $limit="")
{
	$query = "select $columns from $table";
	if($condition != ""){
		$query = $query." where $condition";
	}
	
	if( $limit != "" ){
		$query .= " limit $limit";
	}
	
	# echo $query,"\n";
	$table = dbal::execute_query($query, true);
	
	return $table;
}

/**
 * Inserts values into table given by $table, values are passed to
 * $values and columns into which data must be inserted are passed to
 * $columns
 */ 
static function insert($table, $values, $columns=""){
	
	$query = "insert into $table";
	if($columns != ""){
		$query .= "($columns)";
	}
	$query .= " values($values)";
	 # echo $query;

	return dbal::execute_query($query, false);
	
}

/**
 * Updates a table
 * $table - is the table name in which update must be made
 * $set - what must be set
 * $condtion - what condtion must the row satisfy to be set
 */ 
static function update($table, $set, $condition=""){
	$query = "update $table set $set";
	
	if($condition != ""){
		$query .= " where $condition";
	}
	# echo "\n".$query."\n";
	return dbal::execute_query($query, false);
}

/**
 * Deletes a row(s) in table  which satisfy condtion given by the 
 * $condition
 */
static function delete($table, $condition=""){
	$query = "delete from $table ";
	if($condition != ""){
		$query = $query." where $condition";
	}
	# echo "\n".$query."\n";
	return dbal::execute_query($query, false);
}

/**
 * Function work with table having id field
 */ 
static function getSingleVal($table, $id, $column){
	$table = dbal::select($table, $column, "id = $id");
	$value;
	foreach ($table as $row)
 		{
	 		$value = $row[$column];
 		}
	return $value;
}

/**
 * Function work with table having id field
 */ 
static function setSingleVal($table, $id, $column, $value){
	dbal::update($table, "$column = $value", "id = $id");
} 

}
?>
