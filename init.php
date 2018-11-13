<?php

$debug = true;

if($debug) {
    require_once('apikey_debug.php');
}
else {
    require_once('apikey_prod');
}