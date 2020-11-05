<?php

namespace EasySwoole\DDL\Test;

require_once '../vendor/autoload.php';

use EasySwoole\DDL\Blueprint\Alter\Table as AlterTable;
use EasySwoole\DDL\DDLBuilder;

$alterStuInfoSql = DDLBuilder::alter('student', function (AlterTable $table) {
    $table->setRenameTable('student_info')->setTableComment('学生信息表');
    $table->dropColumn('age');
    $table->dropIndex('ind_age');
    $table->modifyColumn()->varchar('stu_name', 50)->setColumnComment('学生姓名');
    $table->changeColumn('sex')->tinyint('gender', 1)->setDefaultValue('2')->setColumnComment('性别：1男，2未知');
    $table->modifyIndex('ind_stu_name')->normal('ind_stu_name', 'stu_name')->setIndexComment('学生姓名--普通索引');
    $table->addColumn()->varchar('phone', 30)->setColumnComment('学生联系方式');
    $table->addIndex()->normal('ind_phone', 'phone')->setIndexComment('学生联系方式-普通索引');
});
echo $alterStuInfoSql . PHP_EOL . PHP_EOL;


$alterStuScoreSql = DDLBuilder::alter('score', function (AlterTable $table) {
    $table->setRenameTable('student_score')->setTableComment('学生成绩表');
    $table->dropForeign('fk_course_id');
    $table->addIndex()->normal('ind_score', 'score')->setIndexComment('学生成绩--普通索引');
    $table->modifyForeign('fk_stu_id')->foreign('fk_stu_id', 'stu_id', 'student_info', 'stu_id');
});
echo $alterStuScoreSql . PHP_EOL;

//以下是输出sql语句

/*

ALTER TABLE `student` RENAME TO `student_info`;
ALTER TABLE `student_info`
COMMENT = '学生信息表',
DROP `age`,
CHANGE `sex` `gender` tinyint(1) NOT NULL DEFAULT '2' COMMENT '性别：1男，2未知',
MODIFY `stu_id` bigint(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '学生ID',
ADD `phone` varchar(30) NOT NULL COMMENT '学生联系方式',
DROP INDEX `ind_age`,
DROP INDEX `ind_stu_name`,
ADD INDEX `ind_stu_name` (`stu_name`) COMMENT '学生姓名--普通索引',
ADD INDEX `ind_phone` (`phone`) COMMENT '学生联系方式-普通索引';

ALTER TABLE `score` RENAME TO `student_score`;
ALTER TABLE `student_score`
COMMENT = '学生成绩表',
DROP INDEX `ind_score`,
ADD INDEX `ind_score` (`score`) COMMENT '学生成绩--普通索引';
ALTER TABLE `student_score` DROP FOREIGN KEY `fk_course_id`;
ALTER TABLE `student_score` DROP FOREIGN KEY `fk_stu_id`;
ALTER TABLE `student_score` ADD CONSTRAINT `fk_stu_id` FOREIGN KEY (`stu_id`) REFERENCES `student_info` (`stu_id`);

 */
