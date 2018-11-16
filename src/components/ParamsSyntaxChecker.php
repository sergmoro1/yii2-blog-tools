<?php
/**
 * Params code syntax checker.
 * Only for files with a structure as a params.php (array).
 */

namespace sergmoro1\blog\components;

class ParamsSyntaxChecker
{
    public $error;

    private static $valid_tokens = ['T_OPEN_TAG', 'T_CLOSE_TAG', 'T_RETURN', 'T_ARRAY', 'T_CONSTANT_ENCAPSED_STRING', 'T_LNUMBER', 'T_DNUMBER', 
        'T_COMMENT', 'T_DOUBLE_ARROW', 'T_WHITESPACE', 'T_STRING'];
    private static $valid_symbols = ['[', ']', '(', ')', ','];

    /**
     * Syntax check of a string.
     * @param string code
     * @return boolean checking result
     */
    public function check($code) {
        $code = trim($code);
        // last token should be ;
        if(substr($code, -1) != ';') {
            $this->error = ['line' => (substr_count($code, "\n") + 1), 'prev' => ']', 'token' => ';', 'must' => true];
            return false;
        }
        // exclude last token
        $code = substr($code, 0, -1);
        // get all tokens
        $tokens = token_get_all($code);
        $prev = false;
        $this->error['line'] = false;
        foreach($tokens as $token) {
            if(is_array($token)) {
                $tn = token_name($token['0']);
                if(in_array($tn, ['T_WHITESPACE', 'T_COMMENT']))
                    continue;
                $this->error = ['line' => $token[2], 'prev' => $prev, 'token' => $tn];
                if(!in_array($tn, self::$valid_tokens))
                    return false;
                elseif($tn == 'T_STRING' && !in_array(strtolower($token[1]), ['true', 'false']))
                    return false;
                if($this->validSequence($prev, $tn))
                   $prev = $tn;
                else
                    return false; 
            } else {
                // line will be defined in any case, so no needed to define it here 
                $this->error['prev'] = $prev;
                $this->error['token'] = $token;
                if(in_array($token, self::$valid_symbols) && $this->validSequence($prev, $token))
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
    
    /**
     * Valid sequence check.
     * @param string $prev token
     * @param string $current token
     * @return boolean checking result
     */
    public function validSequence($prev, $current) {
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
