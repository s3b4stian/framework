<?php

/**
 * Leviu.
 *
 * This work would be a little PHP framework, a learn exercice. 
 * 
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2015, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 *
 * @version 0.1.0
 */
namespace Leviu\Auth;

/**
 * Password
 * - Class for manage password, using PHP 5.5.0 password see php documentation for more information
 * http://php.net/manual/en/ref.password.php.
 * 
 * @todo Implement password Rehashing
 */
class Password
{
    /**
     *
     * @var array
     */
    protected $options = [
            'cost' => 11
            //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];
    
    public function __construct()
    {
    }
    
    /**
     * Check if password matches a hash.
     * 
     * @param string $hash     Password hashed.
     * @param string $password Password to be verified.
     * 
     * @return bool Result of password_verify PHP function.
     *
     * @deprecated since version 0.1.0
     *
     * @todo Remove this method
     */
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Create a password hash.
     * 
     * @param string $password Password to be hashed.
     * 
     * @return string Return the hashed password.
     *
     * @since 0.1.0
     */
    public function hash($password)
    {
       
        //setting option
        //$options = [
        //    'cost' => 11
            //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        //];

        //generate hash from password
        //$hash = password_hash($password, PASSWORD_BCRYPT, $options);*/

        //generate hash from password
        $hash = password_hash($password, PASSWORD_DEFAULT, $this->options);
        
        return $hash;
    }
    
    /**
     * Check if password need rehash
     * 
     * @param string $password Password for check.
     * @param string $hash Hash for check.
     * 
     * @return string Return the hashed password.
     *
     * @since 0.1.4
     */
    public function needs_rehash($hash)
    {
        if (password_needs_rehash($hash, PASSWORD_DEFAULT, $this->options)) {
            // If so, create a new hash, and replace the old one
            return true;
        }
        
        return false;
    }
}
