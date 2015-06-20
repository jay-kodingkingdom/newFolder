<?php
echo '???????';

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

require_once('User.php');
require_once('login.php');
//require_once('Crypt/RSA.php');

dataObject::getInstance('objname');
dataObject::getInstance('objname2');
dataObject::getInstance('objname3');
dataObject::getInstance('objname');
dataObject::getInstance('objname');
dataObject::getInstance('objname');
dataObject::getInstance('objname');
dataObject::getInstance('objname');


User::getUser('jamesMan','jammypw');
User::getUser('john','kidscoding1113');
User::getUser('brainy','manybrains');

$john = User::fetchUser('john','kidscoding1113');

$obj3=dataObject::getInstance('objname3');

echo '<br><br><br><br><br>';
var_dump($obj3);

echo '<br><br><br><br><br>';
var_dump($obj3->getName());

echo '<br><br><br><br><br>';
var_dump(dataObject::getInstances());

echo '<br><br><br><br><br>';
var_dump(dataObject::getClassName());

echo '<br><br><br><br><br>';
var_dump(User::getClassName());

$john->unfreeze();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

echo '<br><br><br><br><br>';
var_dump($john->getUsername());

echo '<br><br><br><br><br>';
var_dump($john->getPassword());

$john->freeze();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

$john->unfreeze();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

$john->destroy();

echo '<br><br><br><br><br>';
var_dump(User::getInstances());

$john->setUsername('notjohn');
$john->setPassword('bidscoding1113');
$john=User::getUser('notjohn','bidscoding1113');
$realnotjohn=User::getUser('actuallyjohn','bidscoding1113');

echo '<br><br><br><br><br>';
var_dump($john);

echo '<br><br><br><br><br>';
var_dump($realnotjohn);

$keypair = getKeyPair();

echo '<br><br><br><br><br>';
var_dump($keypair);

$private = '-----BEGIN PRIVATE KEY-----
MIIBUwIBADANBgkqhkiG9w0BAQEFAASCAT0wggE5AgEAAkEAroiq34bCOQzQ2bXk
+xhG9N7mDqkaokDDUtJnKO+9pKXaKGis0j4OxKiSq0YcF2UjtV8XvhwCX9RiHERf
9i2DJwIDAQABAkBnF7UO2XOp7ScEIgwCQUHQbEUpzbs8sdJt/ngO1yWGtbJOD3fZ
T79Z2V17OmCBBCW7vXEhbsX+lhqV1WGEUlaBAiEA4k8YhSFiBXIov2DhPOndk5cy
tRLKjJpKz2KZgvr+lkECIQDFbqHH/nk+1OKdyxKjuuMTqPERE9h6HuSfC7/iSyFP
ZwIgFHzOnnbINfAAylqN6YLOgWcFuyjJV3M8ZIvrk9T/KUECIBPQO3onpqFQmgF9
7LvzuHAzpyWwmSwAR69SbYpXQduHAiAA6jDsdwwu8unXuT4KGRgGERujeAqTiIb2
xHQ0S6TczQ==
-----END PRIVATE KEY-----'
;

$public = '-----BEGIN PUBLIC KEY-----'."\n".
'MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAK6Iqt+GwjkM0Nm15PsYRvTe5g6pGqJA'."\n".
'w1LSZyjvvaSl2ihorNI+DsSokqtGHBdlI7VfF74cAl/UYhxEX/YtgycCAwEAAQ=='."\n".
'-----END PUBLIC KEY-----';


$publicKey=openssl_pkey_get_public($public);

$encrypted_text = "";
openssl_public_encrypt("test",$encrypted_text,$publicKey);
echo '<br><br><br><br><br>';
var_dump($encrypted_text);
echo '<br><br><br><br><br>';
$privateKey = openssl_pkey_get_private($private, "");
$decrypted_text = "";
openssl_private_decrypt($encrypted_text, $decrypted_text, $privateKey);
var_dump($decrypted_text);




echo '<br><br><br><br><br>';echo '<br><br><br><br><br>';
if (isset($_POST['input'])) {

    //Load private key:
    if (!$privateKey = openssl_pkey_get_private($private, "")) die('Loading Private Key failed');

echo $_POST['input'];


    //Decrypt
    $decrypted_text = "";
    if (!openssl_private_decrypt(hex2bin($_POST['input']), $decrypted_text, $privateKey)) die('Failed to decrypt data');
echo '<br><br><br><br><br>';echo '<br><br><br><br><br>';
	$decrypted_text=base64_decode($decrypted_text);

    //Decrypted :) 
    var_dump($decrypted_text);

    //Free key
    openssl_free_key($privateKey);
}
