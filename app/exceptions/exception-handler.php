<?php

namespace Maven\Exceptions;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class ExceptionHandler extends \Maven\Core\Observables\ExceptionObservable {

	//protected $observers;

	/* public function __toString() {
	  return "cualquier cosa";
	  } */

	/* public function attach( \Maven\Core\Observer $observer ) {
	  $i = array_search( $observer, $this->observers );
	  if ( $i === false ) {
	  $this->observers[ ] = $observer;
	  }
	  }

	  public function detach( \Maven\Core\Observer $observer ) {
	  if ( ! empty( $this->observers ) ) {
	  $i = array_search( $observer, $this->observers );
	  if ( $i !== false ) {
	  unset( $this->observers[ $i ] );
	  }
	  }
	  }

	  public function notify() {
	  if ( ! empty( $this->observers ) ) {
	  foreach ( $this->observers as $observer ) {
	  $observer->update( $this );
	  }
	  }
	  } */

	private static $instance;
	private $exception;

	/**
	 * 
	 * @return Maven\Exceptions\ExceptionHandler
	 */
	static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( );
		}

		return self::$instance;
	}

	function __construct() {
		$this->observers = array( );

		set_exception_handler( array( $this, 'exceptionHandler' ) );
	}

	function exceptionHandler( $e ) {
		//var_dump( $e );
		if ( $e instanceof MavenException ) {
			//Its a Maven Exception, notify de observers
			$this->exception = $e;
			$this->notify( $e );

			// If we don't have internet, just return, but first notify
			if ( $e instanceof NoInternetConnectionException )
				return;
			
			//The exeption is internal to the plugin, we should handle it
			$message = $e->getDefaultMessage();

			/* if ( $e instanceof \Maven\Exceptions\NotFoundException ) {
			  //In case of not found exection, we show the message like wordpress
			  //Default message, in case none is passed to the exception
			  $message = "The item doesn't exists. ";
			  }

			  if ( $e instanceof \Maven\Exceptions\AdminException ) {
			  //Default message, in case none is passed to the exception
			  $message = "This is an Admin Exception. ";
			  }

			  if ( $e instanceof \Maven\Exceptions\CoreException ) {
			  //Default message, in case none is passed to the exception
			  $message = "Core Exception. ";
			  }

			  if ( $e instanceof \Maven\Exceptions\MapperException ) {
			  //Default message, in case none is passed to the exception
			  $message = "Mapper Exception. ";
			  }

			  if ( $e instanceof \Maven\Exceptions\NotFoundException ) {
			  //In case of not found exection, we show the message like wordpress
			  //Default message, in case none is passed to the exception
			  $message = "The item doesn't exists. ";
			  } */

			//The exception handle routine its the same for all exceptions(TODO: check this!)
			//If we have a custom message, we need to use it.
			if ( $e->getMessage() )
				$message = $e->getMessage();
			?>
			<style type="text/css">
				#error-body {
					background: #f9f9f9;
					color: #333;
					font-family: sans-serif;
					margin: 2em auto;
					padding: 1em 2em;
					-webkit-border-radius: 3px;
					border-radius: 3px;
					border: 1px solid #dfdfdf;
					max-width: 700px;
					margin-top: 50px;
				}
				#error-page {
				}
				#error-page p {
					font-size: 14px;
					line-height: 1.5;
					margin: 25px 0 20px;
				}
				#error-page code {
					font-family: Consolas, Monaco, monospace;
				}
			</style>
			<div id="error-body">
				<div id="error-page">
					<p><?php echo __( $message ); ?></p>

					<?php if ( defined('WP_DEBUG' ) && WP_DEBUG): ?>
						<table class="xdebug-error xe-notice" dir="ltr" border="1" cellspacing="0" cellpadding="1">
							<tbody>
								<tr>
									<td>
									<pre>
								<?php if ( isset($e->xdebug_message))
									echo $e->xdebug_message;
									else
									    echo $e->getTraceAsString();
								 ?>
										</pre>
									</td>
								</tr>
							</tbody>
						</table>
					
					<?php endif;
					?>

				</div>
			</div>
			<?php
			//die( $message );
			die();
		}
		else
			throw $e;
	}

	public function getException() {
		return $this->exception;
	}

}




 