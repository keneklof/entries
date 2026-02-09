<?php

spl_autoload_register('AutoLoad');
function AutoLoad($class)
{
  $path = "classes/";
  $extension = ".class.php";
  $fullpath = $path . $class . $extension;
  include_once $fullpath;
}
