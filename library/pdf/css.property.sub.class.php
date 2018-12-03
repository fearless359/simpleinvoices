<?php

class CSSSubProperty extends CSSPropertyHandler
{
    var $_owner;

    public function __construct(&$owner)
    {
        $this->_owner =& $owner;
    }

    public function &get(&$state)
    {
        $owner =& $this->owner();
        $value =& $owner->get($state);
        $subvalue =& $this->getValue($value);
        return $subvalue;
    }

    public static function is_subproperty()
    {
        return true;
    }

    function &owner()
    {
        return $this->_owner;
    }

// Commented out by RCR 20181129
//    function default_value()
//    {
//    }

    public function inherit($old_state, &$new_state)
    {
    }

    public function inherit_text($old_state, &$new_state)
    {
    }

    public function replace_array($value, &$state_array)
    {
        $owner =& $this->owner();

        $owner_value = $state_array[$owner->getPropertyCode()];

        if (is_object($owner_value)) {
            $owner_value = $owner_value->copy();
        }

        if (is_object($value)) {
            $this->setValue($owner_value, $value->copy());
        } else {
            $this->setValue($owner_value, $value);
        }

        $state_array[$owner->getPropertyCode()] = $owner_value;
    }

    public function replace($value, &$state)
    {
        $owner =& $this->owner();
        $owner_value = $owner->get($state->getState());

        if (is_object($owner_value)) {
            $owner_value =& $owner_value->copy();
        }

        if (is_object($value)) {
            $value_copy =& $value->copy();
            $this->setValue($owner_value, $value_copy);
        } else {
            $this->setValue($owner_value, $value);
        }

        $owner->replaceDefault($owner_value, $state);
        $state->setPropertyDefaultFlag($this->getPropertyCode(), false);
    }

    public function setValue(&$owner_value, &$value)
    {
        error_no_method('setValue', get_class($this));
    }

    public function &getValue(&$owner_value)
    {
        error_no_method('getValue', get_class($this));
    }
}
