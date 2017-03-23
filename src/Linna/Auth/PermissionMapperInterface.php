<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */
declare(strict_types=1);

namespace Linna\Auth;

use Linna\DataMapper\MapperInterface;
use Linna\DataMapper\DomainObjectInterface;

/**
 * Permission Mapper Interface
 * Contain methods required from concrete Permission Mapper.
 */
interface PermissionMapperInterface extends MapperInterface
{
    /**
     * Fetch a premission by name.
     * From permission name as argument, this method must return an instance
     * of Permission class or an instance of NullDomainObject class.
     * 
     * @param string $permissionName Name of required permission
     * @return DomainObjectInterface
     */
    public function fetchByName(string $permissionName) : DomainObjectInterface;
    
    /**
     * Fetch permissions for a role
     * From role id as argument, this method must return an array containing
     * a Permission object instance for every permission of the
     * given role.
     * 
     * @param int $roleId Id of role
     * @return array
     */
    public function fetchPermissionsByRole(int $roleId) : array;
    
    /**
     * Fetch permissions for a user
     * From user id as argument, this method must return an array containing
     * a Permission object instance for every permission of the
     * given user.
     * 
     * @param int $userId Id of user
     * @return array
     */
    public function fetchPermissionsByUser(int $userId) : array;

    /**
     * Combine al users-roles-permissions in storage
     * Return an array containing a sha256 hash of
     * user_id.permission_id string.
     * 
     * Example
     * Hash will be match with below code sample
     *      <?php
     *      $hash = hash('sha256', $userId.'.'.$permission->getId());
     * 
     * @return array
     */
    public function generatePermissionHashTable() : array;
    
    /**
     * Check if a permission exist.
     * 
     * @param string $permission
     * @return bool
     */
    public function permissionExist(string $permission) : bool;
}
