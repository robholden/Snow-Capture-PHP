<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/_code/configs/config.hidden.php';

$_tok = !empty($_GET['off']) ? $_GET['off'] : (!empty($_GET['on']) ? $_GET['on'] : false);

if (! $_tok || $_tok !== MAINTENANCE_TOKEN)
{
  exit();
}

$_url = WEB_ROOT . 'things/.maintenance.cfg';
file_put_contents($_url, (! empty($_GET['off']) ? 'false' : 'true'));

echo 'Maintenance is ' . (! empty($_GET['off']) ? 'off' : 'on');
