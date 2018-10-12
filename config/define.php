<?php

const TB_PREFIX = "si_";

const ENABLED = 1;
const DISABLED = 0;

//invoice styles
const TOTAL_INVOICE = 1;
const ITEMIZED_INVOICE = 2;
const CONSULTING_INVOICE = 3;

//To turn logging on set the below to true - not needed as it is set in System Defaults
const LOGGING = false;

####################
/* Environment*/
####################

// This allows you to have another local config file for your dev or other purposes ie. dev.config.php
// any config.php setting in this extra file(which wont be kept in svn) will overwrite config.php values
// - this way everyone can have their own conf setting without messing with anyone else's setting
//RELEASE TODO: make sure $environment is set back to live
$environment = "production"; //test,staging,dev,live etc..
