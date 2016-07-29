<?php

abstract class PagarMe {
	public static $api_key;
	const live = 1;
	const endpoint = "https://api.pagar.me";
	const api_version = '1';

	public static function full_api_url($path) {
		return self::endpoint . '/' . self::api_version . $path;
		// return self::endpoint . $path;
	}

	public static function setApiKey($api_key) {
		self::$api_key = $api_key;
	}

	public static function getApiKey() {
		return self::$api_key;
	}

	public static function validateFingerprint($id, $fingerprint) {
		throw_error("PagarMe::validateFingerprint will be deprecated in a future release. Please use PagarMe::validateRequestSignature instead. Also, notice this function is now calling this new method and its result will be negative.", E_USER_WARNING);
		return self::validateRequestSignature($id, $fingerprint);
	}

	public static function validateRequestSignature($payload, $signature) {
		$parts = explode("=", $signature, 2);
		return ( count($parts) == 2 ) && ( hash_hmac($parts[0], $payload, self::$api_key) == $parts[1] );
	}
}
