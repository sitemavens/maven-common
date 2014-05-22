<?php

namespace Maven\Core\Db;

abstract class Mapper {

	function __construct() {
		
	}

	protected function fillObject( $object, $row ) {

		\Maven\Core\FillerHelper::fillObject($object, $row);
	}
 

}