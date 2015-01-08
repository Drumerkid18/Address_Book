<?php

class addressFunctions {
	public $filename = '';
	public $addressBook = [];

	function __construct($filename = ''){
        $this->filename = $filename;
    }

	function saveFile($array){
		$handle = fopen($this->filename, 'w');

		foreach ($array as $row) {
		    fputcsv($handle, $row);
		}
		fclose($handle);
	}

	function readCSV(){

		$handle = fopen($this->filename, 'r');

		$addressBook = [];

		while(!feof($handle)) {
		    $row = fgetcsv($handle);

		    if (!empty($row)) {
		        $addressBook[] = $row;
		    }
		}
		fclose($handle);
		return $addressBook;
	}

	function cleanInput($value){
		$value = htmlentities(strip_tags($value));
		return $value;
	}
}

?>