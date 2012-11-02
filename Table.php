<?php
include_once "dbal.php";
class Table {
    public $name;

    function __construct($name){
        $this->name = $name;
    }

    public function insert($values, $columns=""){
        dbal::insert($this->name, $values, $columns);
    }

    public function delete($condition=""){
        dbal::delete($this->name, $condition);
    }

    public function update($set, $condition=""){
        dbal::update($this->name, $set, $condition);
    }
    
    public function select($columns="*", $condition="", $limit=""){
        return dbal::select($this->name, $columns, $condition, $limit);
    }

    public function getSingleVal($id, $column){
        return dbal::getSingleVal($this->name, $id, $column);
    }

    public function setSingleVal($id, $column, $value){
        dbal::setSingleVal($this->name, $id, $column, $value);
    }

    public function selectSingleVal($column, $condition){
        $table = $this->select($column, $condition, "0,1");
        $value;
        foreach ($table as $row) {
            $value = $row[$column];
        }
        return $value;
    }
}
?>
