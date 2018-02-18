<?php
namespace sergmoro1\blog\components;

/**
 * Params code syntax checker.
 * Only for files with a structure as a params.php (array).
 */
class ParamsSyntaxChecker
{
	public $error_line;

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
		foreach ($tokens as $token) {
			if (is_array($token)) {
				$this->error_line = $token[2];
				if(!in_array(token_name($token['0']), self::$valid_tokens))
					return false;
				elseif(token_name($token['0']) == 'T_STRING' && !in_array(strtolower($token[1]), ['true', 'false']))
					return false;
			} else {
				if(!in_array($token, self::$valid_symbols))
					return false;
			}
		}
		// chek pairs of $braces
		$braces = 0;
		$inString = 0;
		$this->error_line = 0;
		foreach ( token_get_all($code) as $token ) {
			if ($inString & 1) {
				switch ($token) {
					case '`':
					case '"': --$inString; break;
				}
			}
			else {
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
}
