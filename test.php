<?php

require_once './vendor/autoload.php';
use Utils\TimeUtil;

$result = TimeUtil::getDateBeginAndEndTime('2018-10-11');
var_dump($result);