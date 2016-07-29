<?php

class PagarMe_Util {

	 public static function fromCamelCase($str) {
		 $matches = NULL;
		 if (preg_match_all('/(^|[A-Z])+([a-z]|$)*/', $str, $matches)){
			 $words = $matches[0];
			 $words_clean = array();
			 foreach($words as $key => $word){
				 if (strlen($word) > 0)
					 $words_clean[] = strtolower($word);
			 }
			 return implode('_', $words_clean);
		 } else {
			 return strtolower($str);
		 }
   }

	public static function isList($arr) {
		if(!is_array($arr)) {
			return false;
		}

		foreach (array_keys($arr) as $k) {
			if (!is_numeric($k))
				return false;
		}
		return true;
	}

	public static function convertPagarMeObjectToArray($object)
	{
		$output = Array();
		foreach ($object as $key => $value) {
			if ($value instanceof PagarMe_Object) {
				$output[$key] = $value->__toArray(true);
			}
			else if (is_array($value)) {
				$output[$key] = self::convertPagarMeObjectToArray($value);
			}
			else {
				$output[$key] = $value;
			}
		}
		return $output;
	}

	public static function convertToPagarMeObject($response) {
		$types = array(
			'transaction' => 'PagarMe_Transaction',
			'plan' => 'PagarMe_Plan',
			'customer' => "PagarMe_Customer",
			'address' => "PagarMe_Address",
			'phone' => "PagarMe_Phone",
			'subscription' => 'PagarMe_Subscription',
		);

		if(self::isList($response)) {
			$output = array();
			foreach($response as $j) {
				array_push($output, self::convertToPagarMeObject($j));
			}
			return $output;
		} else if(is_array($response)) {
			if(isset($response['object']) && is_string($response['object']) && isset($types[$response['object']])) {
				$class = $types[$response['object']];
			} else {
				$class = 'PagarMe_Object';
			}

			return PagarMe_Object::build($response, $class);
		} else {
			return $response;
		}
	}

}
