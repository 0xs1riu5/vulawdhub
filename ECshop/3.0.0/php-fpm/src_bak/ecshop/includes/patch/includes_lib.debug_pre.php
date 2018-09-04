<?php
$string = preg_replace_callback('/\t+/m', function($r) use($tabwidth){return str_repeat(' ', strlen($r[0]) * $tabwidth);}, $string); /* replace all tabs with spaces */