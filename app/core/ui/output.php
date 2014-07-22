<?php

namespace Maven\Core\UI;

/**
 *
 * @author Emilliano Jankowski
 */
class Output {

	private $pluginPath;
	private $baseAdminView = "";
	private $data = array();
	private $html = null;
	private $themePath;
	private $templatePath = "";

	/**
	 *
	 * @var \Maven\Core\UI\OutputTranslator 
	 */
	private $outputTranslator = null;

	/**
	 * 
	 * @param string $pluginPath
	 * @param array $data
	 */
	public function __construct( $pluginPath = "", $data = array() ) {

		//TODO: I don't like the idea of gettint the registry directly here, but...
		$registry = \Maven\Settings\MavenRegistry::instance();

		if ( \Maven\Core\Utils::isEmpty( $pluginPath ) ) {
			$pluginPath = $registry->getPluginDir();
		}

		$this->pluginPath = $pluginPath;
		$this->templatePath = $registry->getTemplatePath();
		$this->outputTranslator = new OutputTranslator();
		$this->data = $data;
	}

	public function sendData( $data ) {

		$this->outputTranslator->sendData( $data );
	}

	public function sendError( $data ) {

		$this->outputTranslator->sendError( $data );
	}

	public function sendApiResponse( $data, $status = 200, $statusText = 'OK' ) {
		$this->outputTranslator->sendApiResponse( $data, $status, $statusText );
	}

	public function sendApiSuccess( $object, $message = 'OK' ) {
		$this->outputTranslator->sendApiSuccess( $object, $message );
	}

	public function sendApiError( $object, $message ) {
		$this->outputTranslator->sendApiError( $object, $message );
	}

	public function toJSON( $data ) {

		$data = $this->outputTranslator->convert( $data );

		return json_encode( $data );
	}

	public function getBaseAdminView() {
		return $this->baseAdminView;
	}

	public function setBaseAdminView( $value ) {
		$this->baseAdminView = $value;
	}

	/**
	 * It lets you add an array of data objects. 
	 * @param array $data
	 */
	function addMassiveData( $data ) {

		if ( is_array( $data ) ) {
			$this->data = array_merge( $this->data, $data );
		}
	}

	/**
	 * Add a value to the data array
	 * @param string $key
	 * @param mixed $value
	 */
	function addData( $key, $value ) {
		$this->data[ $key ] = $value;
	}

	function getData( $key ) {

		if ( isset( $this->data[ $key ] ) ) {
			return $this->data[ $key ];
		}

		return false;
	}

	function loadAdminView( $viewName ) {

		$this->data[ "view" ] = $this->getAdminView( $viewName );
		extract( $this->data );
		include $this->baseAdminView;
	}

	function loadThemeView( $viewName ) {

		$this->data[ "view" ] = $this->getThemeView( $viewName );
		extract( $this->data );
		include $this->baseAdminView;
	}

	function getThemeView( $viewName ) {

		ob_start();
		extract( $this->data );
		require( $this->themePath . "views/{$viewName}-view.php" );
		$output = ob_get_clean();

		return $output;
	}

	function loadView( $viewName ) {

		extract( $this->data );
		include "{$viewName}";
	}

	function getView( $view_name ) {

		ob_start();
		extract( $this->data );
		require "{$view_name}";
		$output = ob_get_clean();
		return $output;
	}

	function getTemplate( $templateName ) {
		ob_start();
		extract( $this->data );

		$templatePath = $this->templatePath . $templateName;

		if ( ! file_exists( $templatePath ) ) {
			throw new \Maven\Exceptions\MavenException( 'File not found: ' . $templatePath );
		}

		require $templatePath;
		$output = ob_get_clean();
		return $output;
	}

	function getExternalTemplate( $templatePath ) {
		ob_start();
		extract( $this->data );

		if ( ! file_exists( $templatePath ) ) {
			throw new \Maven\Exceptions\MavenException( 'File not found: ' . $templatePath );
		}

		require $templatePath;
		$output = ob_get_clean();
		return $output;
	}

	function loadFrontView( $viewName ) {

		extract( $this->data );
		include $this->pluginPath . "front/views/{$viewName}-view.php";
	}

	function getFrontView( $viewName ) {

		ob_start();
		extract( $this->data );
		require_once($this->pluginPath . "front/views/{$viewName}-view.php");
		$output = ob_get_clean();
		return $output;
	}

	function getAdminView( $viewName ) {

		ob_start();
		extract( $this->data );
		require_once($this->pluginPath . "/views/admin/{$viewName}.php");
		$output = ob_get_clean();

		return $output;
	}

	function getWpAdminView( $viewName ) {

		ob_start();
		extract( $this->data );
		require_once($this->pluginPath . "admin/views/wp/{$viewName}-view.php");
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Set default title
	 * @param string $title 
	 */
	function setTitle( $title ) {
		$this->data[ "title" ] = $title;
	}

}
