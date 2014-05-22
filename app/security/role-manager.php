<?php

namespace Maven\Security;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class RoleManager {

	private $mapper = null;

	public function __construct() {

		$this->mapper = new \Maven\Core\Mappers\RoleMapper();
	}

	/**
	 * Update role
	 * @param \Maven\Core\Domain\Role $role
	 * @return \Maven\Core\Domain\Role
	 */
	public function updateRole( \Maven\Core\Domain\Role $role ) {

		return $this->mapper->updateRole( $role );
	}

	/**
	 * Get existings roles
	 * @return \Maven\Core\Domain\Role[] 
	 */
	public function getRoles() {

		return $this->mapper->getRoles();
	}

	/**
	 * Get existings roles
	 * @return \Maven\Core\Domain\Role[] 
	 */
	public function getRolesWithoutCapabilities() {

		return $this->mapper->getRolesWithoutCapabilities();
	}

	public function getMavenRoles() {
		throw new Exception( 'Not implemented' );
	}

	/**
	 * 
	 * @param string $roleId
	 * @return \Maven\Core\Domain\Role
	 */
	public function get( $roleId ) {
		return $this->mapper->get( $roleId );
	}

	/**
	 * 
	 * @param int $userId
	 * @return \Maven\Core\Domain\Role[]
	 */
	public function getUserRoles( $userId ) {
		return $this->mapper->getUserRoles( $userId );
	}

	/**
	 * 
	 * @param \Maven\Core\Domain\Profile $profile
	 * @return \Maven\Core\Domain\Profile
	 */
	public function saveUserRoles( \Maven\Core\Domain\Profile $profile ) {
		return $this->mapper->saveUserRoles( $profile );
	}

}
