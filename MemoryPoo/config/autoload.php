<?php
foreach (glob(__DIR__ . '/../classes/*.php') as $file) {
    require_once $file;
}

return true;
