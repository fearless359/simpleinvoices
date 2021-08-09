<?php

namespace Inc\Claz;

/**
 * Class Encode
 * @package Inc\Claz
 */
class Encode
{
    /**
     * @param array|string $array
     * @param int $level
     * @return string
     */
    public static function xml(array $array, int $level = 1): string
    {
        $xml = '';
        if ($level == 1) {
            $xml .= "<?xml version='1.0' encoding='UTF-8'?>\n" .
                    "<array>\n";
        }
        foreach ($array as $key => $value) {
            $key = strtolower($key);
            if (is_array($value)) {
                $multiTags = false;
                foreach ($value as $value2) {
                    /** @noinspection PhpIfWithCommonPartsInspection */
                    if (is_array($value2)) {
                        $xml .= str_repeat("\t", $level) . "<$key>\n";
                        $xml .= self::xml($value2, $level + 1);
                        $xml .= str_repeat("\t", $level) . "</$key>\n";
                        $multiTags = true;
                    } else {
                        if (trim($value2) != '') {
                            $xml .= str_repeat("\t", $level);
                            if (Util::htmlSafe($value2) != $value2) {
                                $xml .= "<$key><![CDATA[$value2]]></$key>\n";
                            } else {
                                $xml .= "<$key>$value2</$key>\n";
                            }
                        }
                        $multiTags = true;
                    }
                }
                if (!$multiTags && count($value) > 0) {
                    $xml .= str_repeat("\t", $level);
                    $xml .= "<$key>\n";
                    $xml .= self::xml($value, $level + 1);
                    $xml .= str_repeat("\t", $level);
                    $xml .= "</$key>\n";
                }
            } elseif (!empty($value)) {
                $xml .= str_repeat("\t", $level);
                if (Util::htmlSafe($value) != $value) {
                    $xml .= "<$key><![CDATA[$value]]></$key>\n";
                } else {
                    $xml .= "<$key>$value</$key>\n";
                }
            }
        }
        if ($level == 1) {
            $xml .= "</array>\n";
        }
        return $xml;
    }

    /**
     * @param mixed $data
     * @param string $format
     * @return false|string
     */
    public static function json($data, string $format = 'plain')
    {
        $message = json_encode($data);
        if ($format == 'pretty') {
            return self::prettyPrint($message, ["format" => "html"]);
        }
        return $message;
    }

    public static function prettyPrint(string $json, array $options = []): string
    {
        /** @noinspection RegExpRedundantEscape */
        $pattern = '|([\{\}\]\[,])|';
        $tokens = preg_split($pattern, $json, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = '';
        $indent = 0;

        $format= 'txt';

        if (isset($options['format'])) {
            $format = $options['format'];
        }

        switch ($format) {
            case 'html':
                $lineBreak = '<br />';
                $ind = '&nbsp;&nbsp;&nbsp;&nbsp;';
                break;
            default:
            case 'txt':
                $lineBreak = "\n";
                $ind = "\t";
                break;
        }

        // override the defined indent setting with the supplied option
        if (isset($options['indent'])) {
            $ind = $options['indent'];
        }

        $inLiteral = false;
        foreach($tokens as $token) {
            if($token == '') {
                continue;
            }

            $prefix = str_repeat($ind, $indent);
            if (!$inLiteral && ($token == '{' || $token == '[')) {
                $indent++;
                if ($result != '' && $result[(strlen($result)-1)] == $lineBreak) {
                    $result .= $prefix;
                }
                $result .= $token . $lineBreak;
            } elseif (!$inLiteral && ($token == '}' || $token == ']')) {
                $indent--;
                $prefix = str_repeat($ind, $indent);
                $result .= $lineBreak . $prefix . $token;
            } elseif (!$inLiteral && $token == ',') {
                $result .= $token . $lineBreak;
            } else {
                $result .= ( $inLiteral ? '' : $prefix ) . $token;

                // Count # of unescaped double-quotes in token, subtract # of
                // escaped double-quotes and if the result is odd then we are
                // inside a string literal
                if ((substr_count($token, "\"")-substr_count($token, "\\\"")) % 2 != 0) {
                    $inLiteral = !$inLiteral;
                }
            }
        }
        return $result;
    }

}

