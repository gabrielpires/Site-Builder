<?php
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'mypage.php';

echo $host . '<br/>';
echo $uri . '<br/>';
echo $extra . '<br/>';

?>