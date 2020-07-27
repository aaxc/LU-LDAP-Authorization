<?php

namespace Aaxc\LDAPAtuhorization;

/**
 * Class LDAP
 * Independent model for LDAP Authentications
 *
 * @author  Dainis Abols <dainis.abols@lu.lv>
 * @since   18.04.2020
 *
 * @package App
 */
class LDAP
{
    /**
     * LDAP server name.
     *
     * @var mixed
     */
    private $server;

    /**
     * LDAP domain component.
     *
     * @var string
     */
    private $dc;

    /**
     * LDAP main connection.
     *
     * @var resource
     */
    public $conn;

    /**
     * Retrieve domain component
     *
     * @return string
     */
    public function getDc()
    {
        return $this->dc;
    }

    /**
     * Retrieve connection status
     *
     * @return resource
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * LDAP constructor.
     */
    public function __construct($server, $dc)
    {
        $this->server = $server;
        $this->dc     = $dc;

        $this->conn = @ldap_connect('ldap://'.$this->server);
        ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->conn, LDAP_OPT_REFERRALS, 0);
    }

    /**
     * Bind user, if credentials match and return info array
     *
     * @param $username
     * @param $password
     *
     * @return array|bool
     */
    public function authorize($username, $password)
    {
        if ($this->bind("uid={$username},{$this->dc}", $password)) {
            if ($info = $this->fetchUser("uid={$username},{$this->dc}", "(cn=*)")) {
                // Build user object and return
                return $this->makeUser($info);
            }
        }

        return false;
    }

    /**
     * Bind LDAP
     *
     * @param $connection
     * @param $uid
     * @param $password
     *
     * @return bool
     */
    public function bind($uid, $password)
    {
        return @ldap_bind($this->conn, $uid, $password);
    }

    /**
     * Search entry and retrieve first result (username should be unique!)
     *
     * @param $uid
     * @param $cn
     *
     * @return mixed
     */
    public function fetchUser($uid, $cn)
    {
        $search = ldap_search($this->conn, $uid, $cn);
        return ldap_get_entries($this->conn, $search)[0];
    }

    /**
     * Build LDAP User object
     *
     * @param $data
     *
     * @return \App\LDAPUser
     */
    private function makeUser($info)
    {
        $user = new LDAPUser();

        $user->uid                 = $info['uidnumber'][0];
        $user->username            = $info['uid'][0];
        $user->email               = $info['mail']['0'];
        $user->phone               = $info['telephonenumber']['0'];
        $user->givenname           = $info['givenname']['0'];
        $user->surname             = $info['sn']['0'];
        $user->cn                  = $info['cn']['0'];
        $user->display_name        = $info['displayname']['0'];
        $user->password_changed_at = $info['sambapwdlastset']['0'];
        $user->dn                  = $info['dn'];
        $user->groups              = $this->getGroups($info['edupersonaffiliation']);

        return $user;
    }

    /**
     * Retireve groups
     *
     * @param $group_array
     *
     * @return array
     */
    private function getGroups($group_array)
    {
        unset($group_array['count']);

        return array_values($group_array);
    }
}
