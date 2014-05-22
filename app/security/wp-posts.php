<?php

namespace Maven\Security;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class WpPosts {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function init () {

		add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function addMetaBox () {

		add_meta_box(
				'Roles'
				, 'Roles'
				, array( $this, 'renderMetaBoxContent' )
				, 'page'
				, 'normal'
				, 'high'
		);
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save ( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		$request = \Maven\Core\Request::current();

		// Check if our nonce is set.
		if ( ! $request->getProperty( 'mvnCapabilityNonce' ) ) {
			return $post_id;
		}

		$nonce = $request->getProperty( 'mvnCapabilityNonce' );

		// Verify that the nonce is valid.
		if ( !wp_verify_nonce( $nonce, 'mvnCapabilityNonce' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $request->getProperty( 'post_type' ) ) {

			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {

			if ( !current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, its safe for us to save the data now. */
		$registry = \Maven\Settings\MavenRegistry::instance();

		// Sanitize the user input.
		$mydata = array( sanitize_text_field( $request->getProperty( 'mvnCapability' ) ) );

		// Update the meta field.
		update_post_meta( $post_id, $registry->getSecurityMetaKey(), $mydata );
	}

	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function renderMetaBoxContent ( $post ) {

		$roleManager = new RoleManager();

		$registry = \Maven\Settings\MavenRegistry::instance();

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'mvnCapabilityNonce', 'mvnCapabilityNonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$securityMeta = get_post_meta( $post->ID, $registry->getSecurityMetaKey(), true );

		$selectedValue = '';
		
		// Since you can only set one role per page now, we get the first capability
		if ( $securityMeta && count( $securityMeta ) > 0 ) {
			$selectedValue = $securityMeta[ 0 ];
		}

		$roles = $roleManager->getRoles();



		// Display the form, using the current value.
		echo '<label for="mvnCapability"> Role </label> ';
		echo "<select name='mvnCapability' >";
		echo "<option value=''></option>";
		foreach ( $roles as $role ) {

			$checked = $selectedValue == $role->getId() ? "selected='selected'" : "";

			echo "<option value='" . $role->getId() . "'" . $checked . ">" . $role->getName() . "</option>";
		}
		echo "</select>";
	}

}
