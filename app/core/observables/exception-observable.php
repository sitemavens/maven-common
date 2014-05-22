<?php

namespace Maven\Core\Observables;

abstract class ExceptionObservable {

	protected $observers;

	public function attach( \Maven\Core\Observers\ExceptionObserver $observer ) {
		$i = array_search( $observer, $this->observers );
		if ( $i === false ) {
			$this->observers[ ] = $observer;
		}
	}

	public function detach( \Maven\Core\Observers\ExceptionObserver $observer ) {
		if ( ! empty( $this->observers ) ) {
			$i = array_search( $observer, $this->observers );
			if ( $i !== false ) {
				unset( $this->observers[ $i ] );
			}
		}
	}

	/**
	 * 
	 * @param \Maven\Exceptions\MavenException $e
	 */
	public function notify( \Maven\Exceptions\MavenException $e ) {
		if ( ! empty( $this->observers ) ) {
			foreach ( $this->observers as $observer ) {
				$observer->update( $this, $e );
			}
		}
	}

	/**
	 * @return \Maven\Exceptions\MavenException
	 */
	public abstract function getException();
}
