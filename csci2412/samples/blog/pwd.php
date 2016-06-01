<?php

$usrName = 'JohnDoe';
$password = 'testpwd1234!';

// higher "cost" is more secure but consumes more processing power
$cost = 10;

// salt
$first = base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
echo $first;
echo '<br />';

$salt = strtr($first, '+', '.');
echo $salt;
echo '<br />';

$salt = sprintf("$2a$%02d$", $cost) . $salt;
echo $salt . '<br />';

$hash = crypt($password, $salt);
echo $hash;
echo '<br />';
echo strlen($hash);

?>