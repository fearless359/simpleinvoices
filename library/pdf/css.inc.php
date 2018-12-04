<?php
// $Header: /cvsroot/html2ps/css.inc.php,v 1.28 2007/04/07 11:16:34 Konstantin Exp $

class CSS
{
    var $_handlers;
    var $_mapping;
    var $_defaultState;
    var $_defaultStateFlags;
    var $_handlersInheritabletext;

    public function __construct()
    {
        $this->_handlers = array();
        $this->_mapping = array();
    }

    public function _getDefaultState()
    {
        if (!isset($this->_defaultState)) {
            $this->_defaultState = array();

            $handlers = $this->getHandlers();
            foreach ($handlers as $property => $handler) {
                $this->_defaultState[$property] = $handler->default_value();
            }
        }

        return $this->_defaultState;
    }

    public function _getDefaultStateFlags()
    {
        if (!isset($this->_defaultStateFlags)) {
            $this->_defaultStateFlags = array();

            $handlers = $this->getHandlers();
            foreach ($handlers as $property => $handler) {
                $this->_defaultStateFlags[$property] = true;
            }
        }

        return $this->_defaultStateFlags;
    }

    public function getHandlers()
    {
        return $this->_handlers;
    }

    public function getInheritableTextHandlers()
    {
        if (!isset($this->_handlersInheritableText)) {
            $this->_handlersInheritabletext = array();
            foreach ($this->_handlers as $property => $handler) {
                if ($handler->isInheritableText()) {
                    $this->_handlersInheritableText[$property] =& $this->_handlers[$property];
                }
            }
        }

        return $this->_handlersInheritableText;
    }

    public function getInheritableHandlers()
    {
        if (!isset($this->_handlersInheritable)) {
            $this->_handlersInheritable = array();
            foreach ($this->_handlers as $property => $handler) {
                if ($handler->isInheritable()) {
                    $this->_handlersInheritable[$property] =& $this->_handlers[$property];
                }
            }
        }

        return $this->_handlersInheritable;
    }

    public static function &get()
    {
        global $__g_css_handler_set;

        if (!isset($__g_css_handler_set)) {
            $__g_css_handler_set = new CSS();
        }

        return $__g_css_handler_set;
    }

    public static function getDefaultValue($property)
    {
        $css =& self::get();
        $handler =& $css->_get_handler($property);
        $value = $handler->default_value();

        if (is_object($value)) {
            return $value->copy();
        } else {
            return $value;
        }
    }

    public static function &get_handler($property)
    {
        $css =& self::get();
        $handler =& $css->_get_handler($property);
        return $handler;
    }

    public function &_get_handler($property)
    {
        if (isset($this->_handlers[$property])) {
            return $this->_handlers[$property];
        } else {
            $dumb = null;
            return $dumb;
        }
    }

    public function _word2code($key)
    {
        if (!isset($this->_mapping[$key])) {
            return null;
        }

        return $this->_mapping[$key];
    }

    public static function word2code($key)
    {
        $css =& self::get();
        return $css->_word2code($key);
    }

    public static function register_css_property(&$handler)
    {
        $property = $handler->getPropertyCode();
        $name = $handler->getPropertyName();

        $css =& self::get();
        $css->_handlers[$property] =& $handler;
        $css->_mapping[$name] = $property;
    }

    /**
     * Refer to CSS 2.1 G.2 Lexical scanner
     * h        [0-9a-f]
     * nonascii    [\200-\377]
     * unicode        \\{h}{1,6}(\r\n|[ \t\r\n\f])?
     * escape        {unicode}|\\[^\r\n\f0-9a-f]
     * nmstart        [_a-z]|{nonascii}|{escape}
     * nmchar        [_a-z0-9-]|{nonascii}|{escape}
     * ident        -?{nmstart}{nmchar}*
     */
    public static function get_identifier_regexp()
    {
        return '-?(?:[_a-z]|[\200-\377]|\\[0-9a-f]{1,6}(?:\r\n|[ \t\r\n\f])?|\\[^\r\n\f0-9a-f])(?:[_a-z0-9-]|[\200-\377]|\\[0-9a-f]{1,6}(?:\r\n|[ \t\r\n\f])?|\\[^\r\n\f0-9a-f])*';
    }

    public static function is_identifier($string)
    {
        return preg_match(sprintf('/%s/', self::get_identifier_regexp()), $string);
    }

    public static function parse_string($string)
    {
        if (preg_match(sprintf('/^(%s)\s*(.*)$/s', CSS_STRING1_REGEXP), $string, $matches)) {
            $value = $matches[1];
            $rest = $matches[2];

            $value = self::remove_backslash_at_newline($value);

            return array($value, $rest);
        }

        if (preg_match(sprintf('/^(%s)\s*(.*)$/s', CSS_STRING2_REGEXP), $string, $matches)) {
            $value = $matches[1];
            $rest = $matches[2];

            $value = self::remove_backslash_at_newline($value);

            return array($value, $rest);
        }

        return array(null, $string);
    }

    public static function remove_backslash_at_newline($value)
    {
        return preg_replace("/\\\\\n/", '', $value);
    }
}
