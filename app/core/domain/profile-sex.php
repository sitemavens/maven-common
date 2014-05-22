<?php

namespace Maven\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class ProfileSex {

	const Female = 'f';
	const Male = 'm';
	const Other = 'o';

	public static function getSexes() {

		return array(
		    self::Female => 'Female',
		    self::Male => 'Male',
		    self::Other => 'Other'
		);
	}

	public static function getSex( $sex ) {
		switch ( $sex ) {
			case self::Female :return 'Female';
				break;
			case self::Male :return 'Male';
				break;
			case self::Other :return 'Other';
				break;
			default : return 'Unknown';
				break;
		}
	}

}