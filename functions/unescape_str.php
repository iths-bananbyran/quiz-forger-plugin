<?php

function unescape_str($str) {
  $pattern = '/(\\\\)/i';
  $replace_with = '';
  return preg_replace($pattern, $replace_with, $str);
}

?>