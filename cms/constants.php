<?php

require_once($_SERVER['DOCUMENT_ROOT']."/cms/json.php");
require_once($_SERVER['DOCUMENT_ROOT']."/cms/config/dbconfig.php");

$_CONSTANTS = array();
$CMS_SITEMAP = array();


//important global functions
if(!function_exists('json_encode') ) {
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}

if( !function_exists('json_decode') ) {
    function json_decode($data) {
        $json = new Services_JSON();
        return( $json->decode($data) );
    }
}

?>