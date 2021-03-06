<?php

require dirname(__FILE__).'/../lib/openpgp.php';
require dirname(__FILE__).'/../lib/openpgp_crypt_rsa.php';
require dirname(__FILE__).'/../lib/openpgp_crypt_aes_tripledes.php';

$key = OpenPGP_Message::parse(file_get_contents(dirname(__FILE__) . '/../tests/data/helloKey.gpg'));
$data = new OpenPGP_LiteralDataPacket('This is text.', array('format' => 'u', 'filename' => 'stuff.txt'));
$encrypted = OpenPGP_Crypt_AES_TripleDES::encrypt($key, new OpenPGP_Message(array($data)));

echo $encrypted->to_bytes();exit;

// Now decrypt it with the same key
$decryptor = new OpenPGP_Crypt_RSA($key);
$decrypted = $decryptor->decrypt($encrypted);

var_dump($decrypted);
