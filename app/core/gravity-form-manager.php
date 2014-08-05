<?php

namespace Maven\Core;

if ( !defined( 'ABSPATH' ) )
	exit;

class GravityFormManager {

	public function __construct() {
		;
	}

	public static function isGFMissing () {

		$result = class_exists( '\GFForms' );

		// If the common plugin isn't activate, lets add a default option.
		if ( !$result ) {
			$exists = in_array( 'gravityforms/gravityforms.php', (array) get_option( 'active_plugins', array() ) );
			if ( $exists ) {
				$result = require_once( ABSPATH . "wp-content/plugins/gravityforms/gravityforms.php" );
			} 
		}

		return !$result;
	}
	
	public function getEntries( $searchValue ) {
		
		$search_criteria[ "field_filters" ][] = array( 'value' => $searchValue );

		$entries = \GFAPI::get_entries( 0, $search_criteria, array( 'key' => 'date_created', 'direction' => "DESC" ) );
		$forms = array();

		foreach ( $entries as $entry ) {

			if ( !isset( $forms[ $entry[ 'form_id' ] ] ) ) {

				$form = $this->getForm( $entry[ 'form_id' ] );

				$fields = $this->getFormFields( $entry[ 'form_id' ] );

				$forms[ $form->id ] = array(
					'formName' => $form->title,
					'id' => $form->id,
					'link' => get_admin_url( null, "admin.php?page=gf_edit_forms&id={$form->id}" ),
					'active' => $form->is_active === 1 ? true : false,
					'entries' => array(
					),
					'fields' => $fields
				);

//				if ( $form->id == 3){
//					die(print_r($fields,true));
//				}
			}

			$value = array(
				'id' => uniqid(),
				'value' => get_admin_url( null, "admin.php?page=gf_entries&view=entry&id={$entry[ 'form_id' ]}&lid={$entry[ 'id' ]}&filter=&paged=1&pos=0&field_id=&operator=" )
			);
			$forms[ $entry[ 'form_id' ] ][ 'entries' ][ $entry[ 'id' ] ] = array(
				'id' => $entry[ 'id' ],
				'values' => array( $value )
			);

			foreach ( $forms[ $entry[ 'form_id' ] ][ 'fields' ] as $field ) {

				$value = isset( $entry[ $field[ 'id' ] ] ) ? $entry[ $field[ 'id' ] ] : '';

//				if ( $field['id']===8.3){
//					die(print_r($field['id'],true));
//				}

				$forms[ $entry[ 'form_id' ] ][ 'entries' ][ $entry[ 'id' ] ][ 'values' ][] = array( 'id' => uniqid(), 'value' => $value );
			}
		}

//		\GFSeoMarketingAddOn\Core\Output::sendCollection( $forms );

		return $forms;
	}

	private function getForm( $form_id ) {

		$form = \RGFormsModel::get_form( $form_id );

		return $form;
	}

	private function getFormFields( $formId ) {
		$form = \RGFormsModel::get_form_meta( $formId );
		$fields = array();

		if ( is_array( $form[ "fields" ] ) ) {
			foreach ( $form[ "fields" ] as $field ) {
				if ( isset( $field[ "inputs" ] ) && is_array( $field[ "inputs" ] ) ) {

					foreach ( $field[ "inputs" ] as $input ) {
						$fields[] = array(
							"id" => ( string ) $input[ "id" ],
							"label" => \GFCommon::get_label( $field, $input[ "id" ] )
						);
					}
				} else if ( !rgar( $field, 'displayOnly' ) ) {
					$fields[] = array(
						"id" => ( string ) $field[ "id" ],
						"label" => \GFCommon::get_label( $field )
					);
				}
			}
		}
		return $fields;
	}

}
