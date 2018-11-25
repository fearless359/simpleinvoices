<?php

/************************************************************
 * Commentted out 20181122 by RCR to see if actually needed.
 ************************************************************/
/*
global $auth_session;

// Cannot redefine LOGGING (without PHP PECL run kit extension) since already true in define.php
// Ref: http://php.net/manual/en/function.runkit-method-redefine.php
// Hence take from system_defaults into new variable
// Initialize so that while it is being evaluated, it prevents logging
$can_log = false;
$can_chk_log = (LOGGING && (isset($auth_session->id) && $auth_session->id > 0) && SystemDefaults::getDefaultLoggingStatus());
$can_log = $can_chk_log;
unset($can_chk_log);
*/
