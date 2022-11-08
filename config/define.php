<?php

if (!defined('TB_PREFIX')) {
    define('TB_PREFIX', "si_");
}

const ENABLED = 1;
const DISABLED = 0;

//invoice styles
const TOTAL_INVOICE = 1;
const ITEMIZED_INVOICE = 2;

####################
/* Environment*/
####################

// This allows you to have another local config file for your dev or other purposes ie. dev.config.ini
// any config.ini setting in this extra file(which wont be kept in svn) will overwrite config.ini values
// - this way everyone can have their own conf setting without messing with anyone else's setting
//RELEASE TODO: make sure CONFIG_SECTION is set back to production
const CONFIG_SECTION = "production"; //test,staging,dev,live etc..
const SESSION_NAME = 'SiAuth';
