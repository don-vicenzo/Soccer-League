<?php

function autoload($classname) {
    require_once "classes/{$classname}.class.php";   
}

spl_autoload_register('autoload');

?>