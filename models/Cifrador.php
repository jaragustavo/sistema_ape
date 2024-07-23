<?php
class Cifrador {
    private $key;
    private $cipher;

    // Constructor de la clase
    public function __construct() {
        $this->key = "mi_key_secret";
        $this->cipher = "aes-256-cbc";
    }

    // Función para cifrar una contraseña
    public function cifrarTexto($texto) {
        
        // Generar un vector de inicialización (IV) único para cada cifrado
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        
        // Cifrar el texto usando la clave y el IV generados
        $cifrado = openssl_encrypt($texto, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        
        // Combinar el IV con el texto cifrado y codificarlo en base64 para su almacenamiento seguro
        $textoCifrado = base64_encode($iv . $cifrado);
        
        // Retornar el texto cifrado
        return $textoCifrado;
    }

    // Función para descifrar un texto cifrado
    public function descifrar($textoCifrado) {
        // Decodificar el texto cifrado desde base64
        $cifradoDecodificado = base64_decode($textoCifrado);
        
        // Extraer el IV del texto cifrado
        $iv = substr($cifradoDecodificado, 0, openssl_cipher_iv_length($this->cipher));
        
        // Extraer el texto cifrado sin el IV
        $cifradoSinIV = substr($cifradoDecodificado, openssl_cipher_iv_length($this->cipher));
        
        // Descifrar el texto cifrado usando la clave y el IV
        $decifrado = openssl_decrypt($cifradoSinIV, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);
        
        // Retornar el texto descifrado
        return $decifrado;
    }
}
