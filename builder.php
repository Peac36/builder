<?php

class Builder {

	/**
	 *Contain the begining of query string
	 */
	protected $begin;

	/**
	 *Contain `From` part of query
	 */
	protected $from;

	/**
	 *Contain middle part of query
	 */
	protected $middle;

	/**
	 *Contain end of query;
	 */
	protected $end;

	/**
	 *Contain current query;
	 */
	public $query;

	/**
	 *Contain current params;
	 */
	protected $params = [];

	/**
	 *build up select query
	 *@param params-array including all loking columns
	 *@param from-table name
	 *@return this
	 */

	public function select($params = "*", $from) {
		if (isset($params, $from)) {
			if (is_array($params) and count($params) != 0) {
				$this->begin = "SELECT " . implode(',', $params);
			}
			$this->from = "FROM `" . $from . "`";
			return $this;
		}
	}
	/**
	 *build up a insert query
	 *@param data array containing all input values;
	 *@param from-table name
	 *@return this
	 */

	public function insert($data, $from) {
		if (isset($data, $from) and is_array($data) and count($data) != 0) {
			$keys = array_keys($data);
			$values = array_values($data);
			$this->begin = "INSERT INTO $from(" . implode(',', $keys) . ") VALUES(" . implode(",", array_fill(0, count($values), "?")) . ")";
			foreach ($values as $k) {
				array_push($this->params, $k);
			}
			return $this;
		} else {
			return;
		}
	}

	/**
	 *build up a update query
	 *@param data array containg all input values
	 *@param from-table name
	 *@return this
	 */

	public function update($data, $from) {
		if (isset($data, $from) and is_array($data) and count($data) != 0) {
			$keys = array_keys($data);
			$values = array_values($data);
			foreach ($keys as $a => $v) {
				$input[$a] = "$v=?";
			}
			$this->begin = "UPDATE $from";
			$this->from = "SET ";
			$this->from .= implode(",", $input);
			foreach ($values as $k) {
				array_push($this->params, $k);
			}
			return $this;
		}
	}

	public function where($data) {
		if (isset($data) and is_array($data) and count($data) != 0) {
			$key = array_keys($data);
			$value = array_values($data);
			if (!strpos($this->middle, 'WHERE')) {
				$this->middle = 'WHERE ' . $key[0] . " = ? ";
			} else {
				$this->middle .= " and `" . $key[0] . "` = ? ";
			}
			array_push($this->params, $value[0]);
			return $this;
		} else {
			return false;
		}
	}

	public function execute() {
		$this->query = $this->begin . " " . $this->from . " " . $this->middle . " " . $this->end;
		echo $this->query . "<br>";
	}

}

$obj = new Builder;
$obj->update(array('niko' => '123', 'name' => '321'), 'tablename')->where(array('name' => '123'));
$obj->execute();