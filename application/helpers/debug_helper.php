<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('debug'))
{
    function debug($object, $exit = false)
    {
        echo "<pre>";
		var_dump($object);
		echo "</pre>";
		if($exit) exit();
    }   
}
?>