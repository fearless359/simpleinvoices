<?php

namespace Inc\Claz;

use Zend_Json;

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
            $xml .= "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n" .
                    "<array>\n";
        }
        foreach ($array as $key => $value) {
            $key = strtolower($key);
            if (is_array($value)) {
                $multiTags = false;
                foreach ($value as $value2) {
                    if (is_array($value2)) {
                        $xml .= str_repeat("\t", $level) . "<$key>\n";
                        $xml .= array_to_xml($value2, $level + 1);
                        $xml .= str_repeat("\t", $level) . "</$key>\n";
                        $multiTags = true;
                    } else {
                        if (trim($value2) != '') {
                            if (Util::htmlsafe($value2) != $value2) {
                                $xml .= str_repeat("\t", $level) .
                                    "<$key><![CDATA[$value2]]>" .
                                    "</$key>\n";
                            } else {
                                $xml .= str_repeat("\t", $level) .
                                    "<$key>$value2</$key>\n";
                            }
                        }
                        $multiTags = true;
                    }
                }
                if (!$multiTags && count($value) > 0) {
                    $xml .= str_repeat("\t", $level) . "<$key>\n";
                    $xml .= array_to_xml($value, $level + 1);
                    $xml .= str_repeat("\t", $level) . "</$key>\n";
                }
            } elseif (trim($value) != '') {
                if (Util::htmlsafe($value) != $value) {
                    $xml .= str_repeat("\t", $level) . "<$key>" .
                        "<![CDATA[$value]]></$key>\n";
                } else {
                    $xml .= str_repeat("\t", $level) .
                        "<$key>$value</$key>\n";
                }
            }
        }
        if ($level == 1) {
            $xml .= "</array>\n";
        }
        return $xml;
    }

    /**
     * @param $data
     * @param string $format
     * @return mixed
     */
    public static function json($data, $format = 'plain')
    {
        if ($format == 'pretty') {
            $message = Zend_Json::encode($data);
            return Zend_Json::prettyPrint($message, ["format" => "html"]);
        } else {
            return Zend_Json::encode($data);
        }
    }

}

