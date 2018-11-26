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
     * @param $sFormName Begin form validation script
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
     * Add text validation to the script
     * @param $sName
     * @param $sLabel
     * @param $iMin
     * @param $iMax
     */
    public static function textValidation($sName, $sLabel, $iMin, $iMax)
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
     * Add payment validation to the script
     * @param $sName
     * @param $sLabel
     * @param $iMin
     * @param $iMax
     */
    public static function paymentValidation($sName, $sLabel, $iMin, $iMax)
    {
        echo "  if (theForm." . $sName . ".value < " . $iMin . ") {\n";
        echo "    alert(\"Please enter a valid " . $sLabel . "\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n\n";

        echo "  if (theForm." . $sName . ".value > " . $iMax . ") {\n";
        echo "    alert(\"The " . $sLabel . " is not a valid.  Please make sure that there is in an actual invoice with this " . $sLabel . ".\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n\n";

        echo "  if(!(/^\d+$/).test(theForm." . $sName . ".value)) {\n";
        echo "    alert(\"Please Enter a valid " . $sLabel . ". Decimal places or letters are not accepted in the " . $sLabel . " field.\");\n";
        echo "    theForm." . $sName . ".focus();\n";
        echo "    return (false);\n";
        echo "  }\n";
    }

    /**
     * Add preference validation to the script
     * @param $sName
     * @param $sLabel
     * @param $iMin
     * @param $iMax
     */
    public static function preferenceValidation($sName, $sLabel, $iMin, $iMax)
    {
        echo "  if (theForm." . $sName . ".value.length < " . $iMin . ") {\n";
        echo "    alert(\"Please select an " . $sLabel . "\");\n";
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
     * @param $sName
     * @param $sLabel
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
     * Add zero number validation to the script.
     * @param $sName
     * @param $sLabel
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
     * Add number test to the script
     * @param $sName
     * @param $sLabel
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
     * Add email validation to the script
     * @param $sName
     * @param $required
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
     * Add user specified RegEx validation to the script.
     * @param $sName
     * @param $regex
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