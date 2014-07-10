<?php

namespace Maven\Core\Db;

abstract class WordpressMapper extends Mapper {

	/**
	 *
	 * @var maven_db 
	 */
	protected $db;
	protected $tableName = "";
	protected $usermeta;
	protected $users;
	protected $posts;
	protected $postmeta;
	protected $comments;
	protected $commentmeta;
	protected $terms;
	protected $term_taxonomy;
	protected $term_relationships;
	protected $links;
	protected $options;

	function __construct( $tableName ) {

		global $wpdb;

		$this->db = $wpdb;

		//$this->db->hide_errors();
		if ( \Maven\Core\Utils::isEmpty( $tableName ) )
			throw new \Maven\Exceptions\MySqlException( "Table name is required! " );

		$this->tableName = $tableName;

		//Map the wp tables
		$this->posts = $this->db->posts;
		$this->postmeta = $this->db->postmeta;
		$this->comments = $this->db->comments;
		$this->commentmeta = $this->db->commentmeta;
		$this->terms = $this->db->terms;
		$this->term_taxonomy = $this->db->term_taxonomy;
		$this->term_relationships = $this->db->term_relationships;
		$this->users = $this->db->users;
		$this->usermeta = $this->db->usermeta;
		$this->links = $this->db->links;
		$this->options = $this->db->options;
	}

//    function find( $id ) {
//        $this->selectStmt()->execute( array( $id ) );
//        $array = $this->selectStmt()->fetch( );
//        $this->selectStmt()->closeCursor( );
//        if ( ! is_array( $array ) ) { return null; }
//        if ( ! isset( $array['id'] ) ) { return null; }
//        $object = $this->createObject( $array );
//        return $object;
//	}
//	
//    function createObject( $array ) {
//        $obj = $this->doCreateObject( $array );
//        return $obj;
//	}
//	
//    function insert( \woo\domain\DomainObject $obj ) {
//        $this->doInsert( $obj );
//	}

	protected function getQuery( $query ) {

		return $this->db->get_results( $query );
	}

	protected function getResults( $order_by, $order_type = 'asc', $limit = false, $row_count = false, $published = false ) {


		$query = "SELECT t.* FROM {$this->tableName} AS t ";
		if ( $published ) {
			$query .= "INNER JOIN {$this->db->posts} AS p ON t.id = p.id AND p.post_status = 'publish' ";
		}
		if ( $order_by ) {
			$query .= "ORDER BY {$order_by} {$order_type} ";
		}
		if ( $limit && $row_count ) {
			$query.="LIMIT {$limit}, {$row_count}";
		} else if ( $row_count ) {
			$query.="LIMIT {$row_count}";
		}
		//var_dump($query);
		return $this->db->get_results( $query );
	}

	protected function getRowCount() {
		$query = "select count(*) from {$this->tableName} ";
		return $this->db->get_var( $query );
	}

	/**
	 * 
	 * @param string $column
	 * @param mixed $value
	 * @param string $order_by
	 * @param string $order_type
	 * @param array $format
	 * @param string $tableName
	 * @return object
	 */
	protected function getResultsBy( $column, $value, $order_by = "", $order_type = "asc", $format = "%d", $tableName = false ) {

		if ( ! $tableName )
			$tableName = $this->tableName;

		if ( $order_by )
			$order_by = " ORDER BY {$order_by} {$order_type}";

		$query = $this->db->prepare( "SELECT * FROM {$tableName} WHERE {$column} = {$format} {$order_by}", $value );

		return $this->db->get_results( $query );
	}

	/**
	 * 
	 * @param type $column
	 * @param type $value
	 * @param type $format
	 * @param type $tableName
	 * @return type
	 */
	protected function getRowBy( $column, $value, $format = "%d", $tableName = false ) {

		if ( ! $tableName )
			$tableName = $this->tableName;

		$query = $this->db->prepare( "SELECT * FROM {$tableName} WHERE {$column} = {$format} ", $value );

		return $this->db->get_row( $query );
	}

	protected function getQueryRow( $query ) {

		return $this->db->get_row( $query );
	}

	/**
	 * Get row by id
	 * @param string $value
	 * @param string $format
	 * @param string $tableName
	 * @return array
	 */
	protected function getRowById( $value, $format = "%d", $tableName = false ) {

		if ( ! $tableName )
			$tableName = $this->tableName;

		$query = $this->db->prepare( "SELECT * FROM {$tableName} WHERE id = {$format} ", $value );

		$row = $this->db->get_row( $query );
		$this->checkError();

		return $row;
	}

	/**
	 * 
	 * @param type $data
	 * @param type $format
	 * @param type $tableName
	 * @return type
	 */
	protected function insert( $data, $format = null, $tableName = false ) {

		if ( ! $tableName )
			$tableName = $this->tableName;

		$this->db->insert( $tableName, $data, $format );
		$this->checkError();
		return $this->db->insert_id;
	}

	private function checkError() {

		if ( $this->db->last_error ) {
			throw new \Maven\Exceptions\MySqlException( $this->db->last_error );
		}
	}

	/**
	 * 
	 * @param type $id
	 * @param type $format
	 * @return type
	 */
	protected function delete( $id, $format = '%d', $tableName = false ) {

		if ( ! $tableName )
			$tableName = $this->tableName;

		$query = $this->db->prepare( "DELETE FROM {$tableName} WHERE id = {$format}", $id );

		return $this->db->query( $query );
	}

	/**
	 * 
	 * @param type $id
	 * @param type $format
	 * @return type
	 */
	protected function deleteByColumn( $column, $value, $format = '%d', $tableName = false ) {

		if ( ! $tableName )
			$tableName = $this->tableName;

		$query = $this->db->prepare( "DELETE FROM {$tableName} WHERE {$column} = {$format}", $value );

		$result = $this->db->query( $query );

		$this->checkError();

		return $result;
	}

	/**
	 * 
	 * @param int $id
	 * @param array $data
	 * @param array $format
	 * @param string $tableName
	 * @return array
	 */
	protected function updateById( $id, $data, $format = null, $tableName = false ) {

		if ( ! $tableName ) {
			$tableName = $this->tableName;
		}

		$result = $this->db->update( $tableName, $data, array( 'id' => $id ), $format, array( '%d' ) );

		$this->checkError();

		return $result;
	}

	protected function insertQuery( $query ) {

		$this->db->query( $query );

		$this->checkError();

		return $this->db->insert_id;
	}

	protected function executeQuery( $query ) {
		return $this->db->query( $query );
	}

	/**
	 * 
	 * @param string $query
	 * @param array $args
	 * @return type
	 */
	protected function prepare( $query, $args ) {
		//if not placeholder defined, return original query (to avoid warning on wp 3.9)
		if ( strpos( $query, '%' ) === false ) {
			return $query;
		}

		$argsAux = func_get_args();
		if ( count( $argsAux ) > 2 )
			array_shift( $argsAux );
		else
			$argsAux = $args;

		return $this->db->prepare( $query, $argsAux );
	}

	protected function getVar( $query ) {

		$result = $this->db->get_var( $query );

		$this->checkError();

		return $result;
	}

	public function getCount() {

		$query = "select count(*) from {$this->tableName}";
		return $this->getVar( $query );
	}

//    protected abstract function doCreateObject( array $array );
//    protected abstract function doInsert( \woo\domain\DomainObject $object );
//    protected abstract function selectStmt();
}
