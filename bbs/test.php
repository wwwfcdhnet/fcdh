<?php
$suan=Array('234567892345678923456789','+-','234567892345678923456789','z!@#$^&()%/');
//echo strlen($suan[1]);
echo substr($suan[0], rand(0, strlen($suan[0]) - 1), 1);
?>