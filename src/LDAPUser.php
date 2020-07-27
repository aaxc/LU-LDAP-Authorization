<?php

namespace Aaxc\LDAPAtuhorization;

/**
 * LDAP User
 * Dummy class for LDAP User create
 *
 * @author  Dainis Abols <dainis.abols@lu.lv>
 * @since   27.07.2020
 *
 * @package App
 */
class LDAPUser
{
    /**
     * LDAP server user id
     *
     * @var integer
     */
    public $uid;

    /**
     * Username
     *
     * @var string
     */
    public $username;

    /**
     * Contact e-mail
     *
     * @var string
     */
    public $email;

    /**
     * Phone number
     *
     * @var string
     */
    public $phone;

    /**
     * Given name or first name
     *
     * @var string
     */
    public $givenname;

    /**
     * Surname or last name
     *
     * @var string
     */
    public $surname;

    /**
     * Full name
     *
     * @var string
     */
    public $cn;

    /**
     * Customized display name or cn if none provided
     *
     * @var string
     */
    public $display_name;

    /**
     * Access group array
     *
     * @var array
     */
    public $groups;

    /**
     * Timestamp for last password change
     *
     * @var integer
     */
    public $password_changed_at;

    /**
     * Privided DN paramneters
     *
     * @var string
     */
    public $dn;

}
