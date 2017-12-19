<?php 
$str1="hola saludos, que tal, todo bien";
$str2="hola david, como estas, todo bien";
include 'finediff.php';
$opcodes = FineDiff::getDiffOpcodes($str1, $str2 /* , default granularity is set to character */);
$to_text = FineDiff::renderToTextFromOpcodes($str1, $opcodes);
print $to_text;
?>