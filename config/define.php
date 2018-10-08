<?php

define("TB_PREFIX","si_"); // default table prefix - old var $tb_prefix = "si_";

define("ENABLED","1");
define("DISABLED","0");

//invoice styles
define("TOTAL_INVOICE","1");
define("ITEMIZED_INVOICE","2");
define("CONSULTING_INVOICE","3");

//To turn logging on set the below to true - not needed as it is set in System Defaults
define("LOGGING",false);
//define("LOGGING",true);

define("CONFIG_FILE", "config/config.php");
define("CUSTOM_CONFIG_FILE", "config/custom.config.php");

// Create custom.config.php file if it doesn't already exist
if (!file_exists("./" . CUSTOM_CONFIG_FILE)) {
    copy("./" . CONFIG_FILE, "./" . CUSTOM_CONFIG_FILE);
}

####################
/* Environment*/
####################
/*
This allows you to have another local config file for your dev or other purposes
ie. dev.config.php
any config.php setting in this extra file(which wont be kept in svn) will overwrite config.php values
- this way everyone can have their own conf setting without messing with anyone else's setting
RELEASE TODO: make sure $environment is set back to live
*/
$environment = "production"; //test,staging,dev,live etc..
if ($environment) {} // remove unused warning.
