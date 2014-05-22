<?php

namespace Maven\Core\Domain\IntelligenceReport;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class Table extends Element{
	
	private $rows = array();
	private $columns = array();
	
	public function __construct() {
		;
	}
	
	public function getRows() {
		return $this->rows;
	}

	public function setRows( $rows ) {
		$this->rows = $rows;
	}

	public function getColumns() {
		return $this->columns;
	}

	public function setColumns( $columns ) {
		$this->columns = $columns;
	}

	public function addColumn( $column ){
		$this->columns[] = $column;
	}
	
	public function addRow( $row ){
		$this->rows[] = $row;
	}

	public function hasColumns(){
		return ! \Maven\Core\Utils::isEmpty( $this->columns );
	}
	
	public function hasRows(){
		return ! \Maven\Core\Utils::isEmpty( $this->rows );
	}
	
	public function toHtml(){
		
		
		return $this->processTemplate( "table", $this );
		
		
	}
	
}