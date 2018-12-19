<?php

namespace Inc\Claz;

/**
 * @name DynamicJs.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181125
 *
 * Class DynamicJs
 * @package Inc\Claz
 */
class DynamicJs
{
    /**
     * Begin the script
     */
    public static function begin()
    {
        echo "<script>\n";
        echo "<!--\n";
    }

    /**
     * End the script
     */
    public static function end()
    {
        echo "-->\n";
        echo "</script>\n";
    }

    /**
     * @param string $sFormName begin form validation script
     */
    public static function formValidationBegin($sFormName)
    {
        echo "function " . $sFormName . "_Validator(theForm) {\n";
    }

    /**
     * End validation script
     */
    public static function formValidationEnd()
    {
        echo "  return (true);\n";
        echo "}\n";
    }

    /**
     * Verify value is within specified constraints.
     * @param string $sName to reference field content.
     * @param string $sLabel to display for the field.
     * @param int $iMin minimum value of the field.
     * @param int $iMax maximum value of the field.
     * @param bool $noDecimal true if no decimal places allowed; false if it is allowed.
     */
    public static function valueValidation($sName, $sLabel, $iMin, $iMax, $noDecimal)
    {
        echo "  if (theForm." . $sName . ".value < " . $iMin . ") {\n";
        echo "    alert(\"Please enter a valid " . $sLabel . "\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n\n";

        echo "  if (theForm." . $sName . ".value > " . $iMax . ") {\n";
        echo "    alert(\"The " . $sLabel . "  value exceeds the allowed maximum (" . $iMax . ")\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n\n";

        if ($noDecimal) {
            echo "  if(!(/^\d+$/).test(theForm." . $sName . ".value)) {\n";
            echo "    alert(\"Please Enter a valid " . $sLabel . ". Decimal places or letters are not accepted in the " . $sLabel . " field.\");\n";
            echo "    theForm." . $sName . ".focus();\n";
            echo "    return (false);\n";
            echo "  }\n";
        }
    }

    /**
     * Verify field value length is within a specified range.
     * @param string $sName to reference field content.
     * @param string $sLabel to display for the field.
     * @param int $iMin minimum field value length.
     * @param int $iMax maximum field value length.
     */
    public static function lengthValidation($sName, $sLabel, $iMin, $iMax)
    {
        echo "  if (theForm." . $sName . ".value.length < " . $iMin . ") {\n";
        echo "    alert(\"Please select a " . $sLabel . "\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n\n";

        echo "  if (theForm." . $sName . ".value.length > " . $iMax . ") {\n";
        echo "    alert(\"The " . $sLabel . " field can only contain a maximum of " . $iMax . " characters.\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n";
    }

    /**
     * Add required field validation to the script
     * @param string $sName to reference field content.
     * @param string $sLabel to display for the field.
     */
    public static function validateRequired($sName, $sLabel)
    {
        echo "  if (theForm." . $sName . ".value == \"\") {\n";
        echo "    alert(\"Please Enter A Value for the " . $sLabel . " field.\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n";
    }

    /**
     * Do not allow the field to be set to zero.
     * @param string $sName to reference field content.
     * @param string $sLabel to display for the field.
     */
    public static function validateIfNumZero($sName, $sLabel)
    {
        echo "  if(theForm." . $sName . ".value == '0') {\n";
        echo "    alert(\"" . $sLabel . " can't be zero.\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n";
    }

    /**
     * Verify allowed number values with optional leading signs and decimal part.
     * @param string $sName to reference field content.
     * @param string $sLabel to display for the field.
     */
    public static function validateIfNum($sName, $sLabel)
    {
        echo "  if(!(/[-+]?[0-9]*\.?[0-9]+/).test(theForm." . $sName . ".value)) {\n";
        echo "    alert(\"Please Enter a valid Number in the " . $sLabel . " field.\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n";
    }

    /**
     * Verify email is validly formatted and optionally required.
     * @param string $sName to reference field content.
     * @param bool $required true if field is required; false if not.
     */
    public static function validateIfEmail($sName, $required)
    {
        echo "  let val = theForm.{$sName}.value\n";
        echo "  if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/).test(val)) {\n";
        echo "    if ({$required} || val.length > 0) {\n";
        echo "        if (val.length == 0) {\n";
        echo "            alert(\"A valid Email must be entered here.\");\n";
        echo "        } else {\n";
        echo "           alert(\"The format of the Email is not valid.\");\n";
        echo "        }\n";
        echo "        theForm." . $sName . ".focus();\n";
        echo "        return (false);\n";
        echo "    }\n";
        echo "  }\n";
    }

    /**
     * User specified regular expression used to validate the field.
     * @param string $sName to reference field content.
     * @param string $regex to verify field value.
     */
    public static function validateRegEx($sName, $regex)
    {
        echo "  if(!" . $regex . ".test(theForm." . $sName . ".value)) {\n";
        echo "    alert(\"Invalid Input.\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n";
    }
}