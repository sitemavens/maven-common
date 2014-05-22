<?php

namespace Maven\Core\Observers;

abstract class ExceptionObserver {

	public abstract function update( \Maven\Core\Observables\ExceptionObservable $observable );
}

