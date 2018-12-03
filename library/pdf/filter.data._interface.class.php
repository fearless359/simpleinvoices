<?php

class DataFilter
{
    function process(&$tree)
    {
        die("Oops. In overridden 'process' method called in " . get_class($this));
    }
}
