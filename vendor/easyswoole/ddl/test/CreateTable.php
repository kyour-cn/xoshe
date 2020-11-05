<?php

namespace EasySwoole\DDL\Test;

require_once '../vendor/autoload.php';

use EasySwoole\DDL\Blueprint\Create\Table as CreateTable;
use EasySwoole\DDL\DDLBuilder;
use EasySwoole\DDL\Enum\Character;
use EasySwoole\DDL\Enum\Engine;
use EasySwoole\DDL\Enum\Foreign;

$stuSql = DDLBuilder::create('student', function (CreateTable $table) {
    $table->setIfNotExists()->setTableComment('学生表');          //设置表名称
    $table->setTableCharset(Character::UTF8MB4_GENERAL_CI);     //设置表字符集
    $table->setTableEngine(Engine::INNODB);                     //设置表引擎
    $table->setTableAutoIncrement(100);                    //设置表起始自增数
    $table->int('stu_id')->setIsAutoIncrement()->setIsPrimaryKey()->setIsUnsigned()->setColumnComment('学生ID');  //创建stu_id设置主键并自动增长
    $table->varchar('stu_name', 30)->setColumnComment('学生姓名');
    $table->char('sex', 1)->setColumnComment('性别：1男，2女')->setDefaultValue(1);
    $table->int('age', 2)->setColumnComment('年龄')->setDefaultValue(0);
    $table->date('birthday')->setIsNotNull(false)->setColumnComment('出生日期');
    $table->int('created_at', 10)->setColumnComment('创建时间');
    $table->int('updated_at', 10)->setColumnComment('更新时间');
    $table->normal('ind_stu_name', 'stu_name')->setIndexComment('学生姓名--普通索引');//设置索引
    $table->normal('ind_age', 'age')->setIndexComment('学生年龄--普通索引');//设置索引
});
echo $stuSql . PHP_EOL . PHP_EOL;

$courseSql = DDLBuilder::create('course', function (CreateTable $table) {
    $table->setIfNotExists()->setTableComment('课程表');          //设置表名称
    $table->setTableCharset(Character::UTF8MB4_GENERAL_CI);     //设置表字符集
    $table->setTableEngine(Engine::INNODB);                     //设置表引擎
    $table->int('id', 3)->setIsPrimaryKey()->setIsAutoIncrement()->setIsUnsigned()->setZeroFill()->setColumnComment('课程id');
    $table->varchar('course_name', 100)->setColumnComment('课程名称');
    $table->char('status', 1)->setDefaultValue(1)->setColumnComment('课程状态：1正常，0隐藏');
    $table->int('created_at', 10)->setColumnComment('创建时间');
    $table->int('updated_at', 10)->setColumnComment('更新时间');
    $table->unique('uni_course_name', 'course_name')->setIndexComment('课程名称--唯一索引');//设置索引
});
echo $courseSql . PHP_EOL . PHP_EOL;

$scoreSql = DDLBuilder::create('score', function (CreateTable $table) {
    $table->setIfNotExists()->setTableComment('成绩表');          //设置表名称
    $table->setTableCharset(Character::UTF8MB4_GENERAL_CI);     //设置表字符集
    $table->setTableEngine(Engine::INNODB);                     //设置表引擎
    $table->int('id')->setIsUnsigned()->setIsAutoIncrement()->setIsPrimaryKey()->setColumnComment('自增ID');
    $table->int('stu_id')->setIsUnsigned()->setColumnComment('学生id');
    $table->int('course_id')->setIsUnsigned()->setZeroFill()->setColumnComment('课程id');
    $table->float('score', 3, 1)->setColumnComment('成绩');
    $table->int('created_at', 10)->setColumnComment('创建时间');
    $table->foreign('fk_stu_id', 'stu_id', 'student', 'stu_id')
        ->setOnDelete(Foreign::CASCADE)->setOnUpdate(Foreign::CASCADE);
    $table->foreign('fk_course_id', 'course_id', 'course', 'id')
        ->setOnDelete(Foreign::CASCADE)->setOnUpdate(Foreign::CASCADE);
});
echo $scoreSql;

//以下是输出sql语句
/*

CREATE TABLE IF NOT EXISTS `student` (
  `stu_id` int UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '学生ID',
  `stu_name` varchar(30) NOT NULL COMMENT '学生姓名',
  `sex` char(1) NOT NULL DEFAULT 1 COMMENT '性别：1男，2女',
  `age` int(2) NOT NULL DEFAULT 0 COMMENT '年龄',
  `birthday` date NULL DEFAULT NULL COMMENT '出生日期',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(10) NOT NULL COMMENT '更新时间',
  INDEX `ind_stu_name` (`stu_name`) COMMENT '学生姓名--普通索引',
  INDEX `ind_age` (`age`) COMMENT '学生年龄--普通索引'
)
ENGINE = INNODB AUTO_INCREMENT = 100 DEFAULT COLLATE = 'utf8mb4_general_ci' COMMENT = '学生表';

CREATE TABLE IF NOT EXISTS `course` (
  `id` int(3) UNSIGNED ZEROFILL NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '课程id',
  `course_name` varchar(100) NOT NULL COMMENT '课程名称',
  `status` char(1) NOT NULL DEFAULT 1 COMMENT '课程状态：1正常，0隐藏',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  `updated_at` int(10) NOT NULL COMMENT '更新时间',
  UNIQUE INDEX `uni_course_name` (`course_name`) COMMENT '课程名称--唯一索引'
)
ENGINE = INNODB DEFAULT COLLATE = 'utf8mb4_general_ci' COMMENT = '课程表';

CREATE TABLE IF NOT EXISTS `score` (
  `id` int UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '自增ID',
  `stu_id` int UNSIGNED NOT NULL COMMENT '学生id',
  `course_id` int UNSIGNED ZEROFILL NOT NULL COMMENT '课程id',
  `score` float(3,1) NOT NULL COMMENT '成绩',
  `created_at` int(10) NOT NULL COMMENT '创建时间',
  CONSTRAINT `fk_stu_id` FOREIGN KEY (`stu_id`) REFERENCES `student` (`stu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_course_id` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB DEFAULT COLLATE = 'utf8mb4_general_ci' COMMENT = '成绩表';

*/
