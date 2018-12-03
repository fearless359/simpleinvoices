<?php
// $Header: /cvsroot/html2ps/css.min-height.inc.php,v 1.3 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR . 'value.min-height.php');

class CSSMinHeight extends CSSPropertyHandler
{
    var $_defaultValue;

    public function __construct()
    {
        parent::__construct(true, false);
        $this->_defaultValue = ValueMinHeight::fromString("0px");
    }

    /**
     * 'height' CSS property should be inherited by table cells from table rows
     * (as, obviously, )
     */
    public function inherit($old_state, &$new_state)
    {
        $parent_display = $old_state[CSS_DISPLAY];
        if ($parent_display === "table-row") {
            $new_state[CSS_MIN_HEIGHT] = $old_state[CSS_MIN_HEIGHT];
            return;
        }

        $new_state[CSS_MIN_HEIGHT] =
            is_inline_element($parent_display) ?
                $this->get($old_state) :
                $this->default_value();
    }

    public function _getAutoValue()
    {
        return $this->default_value();
    }

    public function default_value()
    {
        return $this->_defaultValue->copy();
    }

    public static function parse($value)
    {
        return ValueMinHeight::fromString($value);
    }

    public static function getPropertyCode()
    {
        return CSS_MIN_HEIGHT;
    }

    public static function getPropertyName()
    {
        return 'min-height';
    }
}

$css_min_height_inc_reg1 = new CSSMinHeight();
CSS::register_css_property($css_min_height_inc_reg1);
