<?php
require('system/engine/core.php');

foreach (glob("system/vendor/*.php") as $filename)
{
    include $filename;
}

$core = new HF_Core();
$core->run();