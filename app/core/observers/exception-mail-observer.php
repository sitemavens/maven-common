<?php

namespace Maven\Core\Observers;

class ExceptionMailObserver extends ExceptionObserver {

	public function update( \Maven\Core\Observables\ExceptionObservable $observable ) {
		$registry = \Maven\Settings\MavenRegistry::instance();

		if ( $registry->getExceptionNotification() ) {
			$message = ( string ) $observable->getException() . '<hr/><pre>' . $observable->getException()->getTraceAsString() . '</pre>';

			$mail = \Maven\Mail\MailFactory::build();

			$mail->fromAccount( $registry->getSenderEmail() )
				->fromMessage( $registry->getSenderName() )
				->to( $registry->getExceptionNotification() )
				->cc( 'enzo@dinkuminteractive.com' )
				->cc( 'guillermo@dinkuminteractive.com' )
				->cc( 'enzo@dinkuminteractive.com' )
				->cc( 'dinkum@montazze.com' )
				->subject( 'Error in ' . get_bloginfo() . ' <' . get_bloginfo( 'url' ) . '> (' . date( 'd-m-Y h:i:s' ) . ')' )
				->message( $message )
				->send();
		}
	}

}
