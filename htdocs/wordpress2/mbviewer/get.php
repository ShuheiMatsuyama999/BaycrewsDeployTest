<?php
function attak($string) {
$buf = mb_convert_encoding(file_get_contents('$string'), 'UTF-8', 'Shift_JIS');
echo $buf;
}
?>