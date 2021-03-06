<?php

// USAGE: php examples/deASCIIdeCrypt.php secretkey.asc password message.asc
// This will fail if the algo on key or message is not 3DES or AES

require dirname(__FILE__).'/../lib/openpgp.php';
require dirname(__FILE__).'/../lib/openpgp_crypt_rsa.php';
require dirname(__FILE__).'/../lib/openpgp_crypt_aes_tripledes.php';

$keyASCII = file_get_contents($argv[1]);
$msgASCII = file_get_contents($argv[3]);

$keyEncrypted = OpenPGP_Message::parse(OpenPGP::unarmor($keyASCII, 'PGP PRIVATE KEY BLOCK'));

// Try each secret key packet
foreach($keyEncrypted as $p) {
	if(!($p instanceof OpenPGP_SecretKeyPacket)) continue;

	$key = OpenPGP_Crypt_AES_TripleDES::decryptSecretKey($argv[2], $p);

	$msg = OpenPGP_Message::parse(OpenPGP::unarmor($msgASCII, 'PGP MESSAGE'));

	$decryptor = new OpenPGP_Crypt_RSA($key);
	$decrypted = $decryptor->decrypt($msg);

	var_dump($decrypted);
}
