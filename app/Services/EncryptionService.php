<?php
namespace App\Services;

use OpenSSLAsymmetricKey;

class EncryptionService
{
    protected string $privateKeyPath;
    protected string $publicKeyPath;
    protected string $cipher = 'aes-256-gcm';

    public function __construct()
    {
        $this->privateKeyPath = base_path(env('RSA_PRIVATE_KEY_PATH'));
        $this->publicKeyPath = base_path(env('RSA_PUBLIC_KEY_PATH'));
    }

    protected function loadPublicKey(): OpenSSLAsymmetricKey
    {
        $pub = file_get_contents($this->publicKeyPath);
        if ($pub === false) {
            throw new \RuntimeException('Public key not found');
        }
        $res = openssl_pkey_get_public($pub);
        if ($res === false)
            throw new \RuntimeException('Invalid public key');
        return $res; // automatically freed when out of scope
    }

    protected function loadPrivateKey(): OpenSSLAsymmetricKey
    {
        $priv = file_get_contents($this->privateKeyPath);
        if ($priv === false) {
            throw new \RuntimeException('Private key not found');
        }
        $res = openssl_pkey_get_private($priv);
        if ($res === false)
            throw new \RuntimeException('Invalid private key');
        return $res;
    }

    public function encrypt(string $plaintext): array
    {
        $aesKey = random_bytes(32); // AES-256
        $iv = random_bytes(12);     // 12 bytes recommended for GCM
        $tag = '';

        $ciphertext = openssl_encrypt(
            $plaintext,
            $this->cipher,
            $aesKey,
            OPENSSL_RAW_DATA,
            $iv,
            $tag // Required for GCM mode
        );

        if ($ciphertext === false) {
            throw new \RuntimeException('AES encryption failed');
        }

        $pubKey = $this->loadPublicKey();
        if (!openssl_public_encrypt($aesKey, $encryptedKey, $pubKey, OPENSSL_PKCS1_OAEP_PADDING)) {
            throw new \RuntimeException('RSA public encrypt failed');
        }

        return [
            'encrypted_data' => base64_encode($ciphertext),
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
            'encrypted_key' => base64_encode($encryptedKey),
            'cipher' => $this->cipher,
        ];
    }


}
