<?php
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Otto\Challenge();
$content = null;
echo $app->getRecords();