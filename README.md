# LDAP Authorization Library for University of Latvia

Install via Composer
```
composer require aaxc/lu-ldap-authorization
```

## Usage
``` php
$ldapAuth = new LDAP($ldap_server, $ldap_dc);
$user = $ldapAuth->authorize($request->username, $request->password);
```

Variable $user will containt `false` on failed authorization and `LDAPUser` object on succesfull authorization. 

## Requirements

 - PHP 7.2
 - PHP Extension LDAP
 