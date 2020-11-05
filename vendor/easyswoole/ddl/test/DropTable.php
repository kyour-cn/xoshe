<?php

namespace EasySwoole\DDL\Test;

require_once '../vendor/autoload.php';

use EasySwoole\DDL\DDLBuilder;

$dropStuScoreSql = DDLBuilder::drop('student_score');
echo $dropStuScoreSql . PHP_EOL;

$dropStuScoreSql = DDLBuilder::dropIfExists('student_score');
echo $dropStuScoreSql . PHP_EOL;

//以下是输出sql语句
/*

DROP TABLE `student_score`;

DROP TABLE IF EXISTS `student_score`;

 */

