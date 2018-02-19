<?php
namespace sergmoro1\blog\components;

/**
 * Params code syntax checker.
 * Only for files with a structure as a params.php (array).
 */
class ParamsSyntaxChecker
{
	public $error;

	private static $valid_tokens = ['T_OPEN_TAG', 'T_CLOSE_TAG', 'T_RETURN', 'T_ARRAY', 'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', 
        'T_COMMENT', 'T_DOUBLE_ARROW', 'T_WHITESPACE', 'T_STRING'];
	private static $valid_symbols = ['[', ']', '(', ')', ',', ';'];

	/**
	 * Syntax check of a string.
	 * @param string code
	 * @return boolean checking result
	 */

	public function check($code) {
		$tokens = token_get_all($code);
		$prev = false;
		foreach($tokens as $token) {
			if(is_array($token)) {
				$tn = token_name($token['0']);
				if(in_array($tn, ['T_WHITESPACE', 'T_COMMENT']))
				    continue;
				$this->error['line'] = $token[2];
				$this->error['token'] = $tn;
				$this->error['prev'] = $prev;
				if(!in_array($tn, self::$valid_tokens))
					return false;
				elseif($tn == 'T_STRING' && !in_array(strtolower($token[1]), ['true', 'false']))
					return false;
				if($this->valid($prev, $tn))
				   $prev = $tn;
				else
				    return false; 
			} else {
				$this->error['token'] = $token;
				$this->error['prev'] = $prev;
				if(in_array($token, self::$valid_symbols) && $this->valid($prev, $token))
				    $prev = $token;
				else
					return false;
			}
		}
		// chek pairs of $braces
		$braces = 0;
		$inString = 0;
		$this->error['line'] = 0;
		foreach($tokens as $token) {
			if($inString & 1) {
				switch ($token) {
					case '`':
					case '"': --$inString; break;
				}
			} else {
				switch ($token) {
					case '`':
					case '"': ++$inString; break;
	 
					case '[': case '(': ++$braces; break;
					case ']': case ')':
						if ($inString) {
							--$inString;
						}
						else {
							--$braces;
							if ($braces < 0) {
								return false;
							}
						}
					break;
				}
			}
		}
		if ($braces) {
			return false;
		}
		return true;
	}
	
	public function valid($prev, $current) {
		if(in_array($current, ['T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER']))
		    return in_array($prev, ['[', '(', ',', 'T_DOUBLE_ARROW']);
		elseif($current == 'T_DOUBLE_ARROW')
		    return in_array($prev, ['T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER']);
		elseif(in_array($current, [']', ')']))
		    return in_array($prev, ['T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', ',', ']', ')']);
		elseif($current == ',')
		    return in_array($prev, ['T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', 'T_STRING', ']', ')']);
		else
		    return true;
	}
}
