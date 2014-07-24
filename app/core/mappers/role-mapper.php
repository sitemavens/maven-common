<?php

namespace Maven\Core\Mappers;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
    exit;

class RoleMapper extends \Maven\Core\Db\Mapper {

    private $systemRoles = array(
        'administrator' => 'Administrator',
        'author' => 'Author',
        'contributor' => 'Contributor',
        'editor' => 'Editor',
        'subscriber' => 'Subscriber',
    );

    public function __construct () {

        parent::__construct();
    }

    public function getRoles () {


        global $wp_roles;

        $wpRoles = $wp_roles->roles;
        $roles = array();

        foreach ( $wpRoles as $key => $roleData ) {

            $mvnRole = new \Maven\Core\Domain\Role();

            $mvnRole->setId( $key );
            $mvnRole->setName( $roleData[ 'name' ] );
            $mvnRole->setCapabilities( $roleData[ 'capabilities' ] );

            if ( isset( $this->systemRoles[ $key ] ) )
                $mvnRole->setSystemRole( true );

            $roles[] = $mvnRole;
        }


        return $roles;
    }

    public function getRolesWithoutCapabilities () {
        global $wp_roles;

        $wpRoles = $wp_roles->roles;
        $roles = array();

        foreach ( $wpRoles as $key => $roleData ) {

            $mvnRole = new \Maven\Core\Domain\Role();

            $mvnRole->setId( $key );

            $mvnRole->setName( $roleData[ 'name' ] );
            //$mvnRole->setCapabilities( $roleData[ 'capabilities' ] );

            if ( isset( $this->systemRoles[ $key ] ) )
                $mvnRole->setSystemRole( true );

            $roles[] = $mvnRole;
        }


        return $roles;
    }

    public function updateRole ( \Maven\Core\Domain\Role $role ) {

        if ( $role->getId() ) {
            global $wp_roles;
            $val = get_option( 'wp_user_roles' );
            $val[ $role->getId() ][ 'name' ] = $role->getName();
            update_option( 'wp_user_roles', $val );
            $wpRole = get_role( $role->getId() );

            if ( !$wpRole )
                throw new \Maven\Exceptions\NotFoundException( "Role missing: {$roleId}" );

            $wpRole->name = $role->getName();

            // We have to find a way to update the role names
        }
        else {
            add_role( $role->getSanitizedName(), $role->getName(), $role->getCapabilities() );
            $role->setId( $role->getSanitizedName() );
        }

        return $role;
    }

    public function get ( $roleId ) {

        global $wp_roles;

        $mvnRole = new \Maven\Core\Domain\Role();

        $wpRole = get_role( $roleId );

        if ( !$wpRole )
            throw new \Maven\Exceptions\NotFoundException( "Role missing: {$roleId}" );

        $mvnRole = new \Maven\Core\Domain\Role();

        $mvnRole->setId( $wpRole->name );
        $mvnRole->setName( $wp_roles->role_names[ $roleId ] );
        $mvnRole->setCapabilities( $wpRole->capabilities );
        if ( isset( $this->systemRoles[ $wpRole->name ] ) )
            $mvnRole->setSystemRole( true );
        //$mvnRole->setSystemRole( in_array( $wpRole->name, $this->systemRoles ) );

        return $mvnRole;
    }

    /**
     * 
     * @param int $userId
     * @return \Maven\Core\Domain\Role[]
     */
    public function getUserRoles ( $userId ) {
        $user = new \WP_User( $userId );

        $roles = array();
        foreach ( $user->roles as $role ) {
            $userRole = $this->get( $role );
            //rempove capabilities
            $userRole->setCapabilities( null );
            $roles[] = $userRole;
        }

        return $roles;
    }

    /**
     * 
     * @param \Maven\Core\Domain\Profile $profile
     * @return \Maven\Core\Domain\Profile
     */
    public function saveUserRoles ( \Maven\Core\Domain\Profile $profile ) {
        if ( !$profile->getUserId() )
            return $profile;

        $user = new \WP_User( $profile->getUserId() );
        //first remove non existant roles
        foreach ( $user->roles as $role ) {
			
            //search the role in the user role array
            $found = array_filter( $profile->getRoles(), function($item) use ($role) {

                if ( $item->getId() == $role ) {
                    return true;
                }

                return false;
            } );
            if ( !$found ) {
                //The role is not in the array, remove from user
                $user->remove_role( $role );
            }
        }


        //then add new roles
        foreach ( $profile->getRoles() as $role ) {
            $user->add_role( $role->getId() );
        }

        return $profile;
    }

    public function delete ( $rolId ) {
		
        $wpRole = get_role( $rolId );
		
        if ( isset( $this->systemRoles[ $wpRole->name ] ) ) {
			return;
		}

		remove_role( $wpRole->name );
          
    }

}
