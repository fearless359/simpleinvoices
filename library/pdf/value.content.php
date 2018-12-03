<?php

require_once(HTML2PS_DIR . 'value.content.item.php');

class ValueContent
{
    var $_items;

    public function __construct()
    {
        $this->set_items(array());
    }

    public function add_item(&$item)
    {
        $this->_items[] =& $item;
    }

    public function &copy()
    {
        $copy = new ValueContent();

        foreach ($this->_items as $item) {
            $copy->add_item($item->copy());
        }

        return $copy;
    }

    public function doInherit(&$state)
    {

    }

    public static function &parse($string)
    {
        $value = new ValueContent();

        while ($string !== '') {
            $result = ValueContentItem::parse($string);
            $item =& $result['item'];
            $rest = $result['rest'];

            $string = $rest;

            if (is_null($item)) {
                break;
            }

            $value->add_item($item);
        }

        return $value;
    }

    public function render(&$counters)
    {
        $content = array();
        foreach ($this->_items as $item) {
            $content[] = $item->render($counters);
        }
        return join('', $content);
    }

    public function set_items($value)
    {
        $this->_items = $value;
    }
}
