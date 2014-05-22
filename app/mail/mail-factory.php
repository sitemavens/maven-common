<?php

namespace Maven\Mail;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;

class MailFactory {

	/**
	 *
	 * @var \Maven\Mail\Mail 
	 */
	private static $instance;

	/**
	 * Mail Factory builder
	 * 
	 * @return \Maven\Mail\Mail
	 * @throws MavenException
	 * @throws Exception
	 */
	public static function build( $provider = null ) {
//		if ( !isset( self::$instance ) ) {

			if ( !$provider ) {
				$registry = \Maven\Settings\MavenRegistry::instance();
				$provider = $registry->getEmailProvider();
			}

			switch ( $provider ) {

				case 'mandrill':
					self::$instance = new MandrillMail();
					break;
				case 'postmark':
					self::$instance = new PostmarkMail();
					break;
				case 'amazonSes':
					self::$instance = new AmazonSesMail();
					break;
				case 'wordpress':
				default:
					self::$instance = new WordpressMail();
					break;
			}



			/* if ( class_exists( $type ) ) {
			  self::$instance = new $type;
			  } else {
			  throw new \Maven\Exceptions\MavenException( __( "Email Provider Class Not Found: {$type}" ) );
			  } */
//		}
		return self::$instance;
	}

	/**
	 * Return all the existsing gateways
	 * @return \Maven\Mail\MailBase
	 */
	public static function getAll() {

		$providers = array( );
		$providers[ 'mandrill' ] = new MandrillMail();
		$providers[ 'postmark' ] = new PostmarkMail();
		$providers[ 'amazonSes' ] = new AmazonSesMail();
		$providers[ 'wordpress' ] = new WordpressMail();

		return $providers;
	}

}

