<?php

class ValueContentItem
{
    public function __construct()
    {
    }

    public static function parse($string)
    {
        $subclasses = array('ValueContentItemString',
            'ValueContentItemUri',
            'ValueContentItemCounter',
            'ValueContentItemAttr',
            'ValueContentItemOpenQuote',
            'ValueContentItemCloseQuote',
            'ValueContentItemNoOpenQuote',
            'ValueContentItemNoCloseQuote');

        foreach ($subclasses as $subclass) {
            $result = call_user_func(array($subclass, 'parse'), $string);
            $rest = $result['rest'];
            $item =& $result['item'];

            if (!is_null($item)) {
                return array('item' => &$item,
                    'rest' => $rest);
            }
        }

        $null = null;
        return array('item' => &$null,
            'rest' => $string);
    }

    public function render(&$counters)
    {
        // abstract
    }
}

class ValueContentItemString extends ValueContentItem
{
    var $_value;

    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemString();
        $copy->set_value($this->get_value());
        return $copy;
    }

    public function get_value()
    {
        return $this->_value;
    }

    public static function parse($string)
    {
        list($value, $rest) = CSS::parse_string($string);
        if (!is_null($value)) {
            $item = new ValueContentItemString();
            $item->set_value(substr($value, 1, strlen($value) - 2));
            return array('item' => &$item,
                'rest' => $rest);
        }

        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        return css_process_escapes($this->_value);
    }

    public function set_value($value)
    {
        $this->_value = $value;
    }
}

class ValueContentItemUri extends ValueContentItem
{
    var $_value;

    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemUri();
        return $copy;
    }

    public static function parse($string)
    {
        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        return '';
    }
}

class ValueContentItemCounter extends ValueContentItem
{
    var $_name;

    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemCounter();
        $copy->set_name($this->get_name());
        return $copy;
    }

    public function get_name()
    {
        return $this->_name;
    }

    public static function parse($string)
    {
        if (preg_match('/^\s*counter\((' . CSS_IDENT_REGEXP . ')\)\s*(.*)$/', $string, $matches)) {
            $value = $matches[1];
            $rest = $matches[2];

            $item = new ValueContentItemCounter();
            $item->set_name($value);
            return array('item' => &$item,
                'rest' => $rest);
        }

        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        $counter =& $counters->get($this->get_name());
        if (is_null($counter)) {
            return '';
        }

        return $counter->get();
    }

    public function set_name($value)
    {
        $this->_name = $value;
    }
}

class ValueContentItemAttr extends ValueContentItem
{
    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemAttr();
        return $copy;
    }

    public static function parse($string)
    {
        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        return '';
    }
}

class ValueContentItemOpenQuote extends ValueContentItem
{
    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemOpenQuote();
        return $copy;
    }

    public static function parse($string)
    {
        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        return '';
    }
}

class ValueContentItemCloseQuote extends ValueContentItem
{
    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemCloseQuote();
        return $copy;
    }

    public static function parse($string)
    {
        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        return '';
    }
}

class ValueContentItemNoOpenQuote extends ValueContentItem
{
    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemNoOpenQuote();
        return $copy;
    }

    public static function parse($string)
    {
        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        return '';
    }
}

class ValueContentItemNoCloseQuote extends ValueContentItem
{
    public function __construct()
    {
        parent::__construct();
    }

    public function &copy()
    {
        $copy = new ValueContentItemNoCloseQuote();
        return $copy;
    }

    public static function parse($string)
    {
        $null = null;
        return array('item' => &$null, 'rest' => $string);
    }

    public function render(&$counters)
    {
        return '';
    }
}
