/*
 Navicat Premium Data Transfer

 Source Server         : 华为服务器
 Source Server Type    : MySQL
 Source Server Version : 80013
 Source Host           : www.kyour.cn:8306
 Source Schema         : xsdb

 Target Server Type    : MySQL
 Target Server Version : 80013
 File Encoding         : 65001

 Date: 05/11/2020 16:46:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '发布者',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '主体',
  `type` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '类型 1:text 2:img 3:video',
  `resource` varchar(3000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '资源文件 -如果有',
  `class` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类ID',
  `up` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '赞',
  `low` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '踩',
  `comm_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论数',
  `star` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏数',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发布时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '状态 1正常 2待审核 3审核不通过 4被封禁 5待删除',
  `info` json NULL COMMENT '其他信息',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES (13, 6, '测试图片贴', '测试图片贴', 'img', 'posts/201029/2a1b80658818936d1c5fbebf6c9675f6.jpg', 102, 15, 16, 0, 0, 1603986250, 1604377744, 1, NULL);
INSERT INTO `article` VALUES (14, 6, '这个见过没有', '这个见过没有', 'img', 'posts/201029/84c7112853c6629e5ef30707b902449a.jpg,posts/201029/84c7112853c6629e5ef30707b902449a.jpg,posts/201029/2a1b80658818936d1c5fbebf6c9675f6.jpg,posts/201029/2a1b80658818936d1c5fbebf6c9675f6.jpg,posts/201029/2a1b80658818936d1c5fbebf6c9675f6.jpg', 102, 0, 0, 0, 0, 1603987124, 1603987124, 1, NULL);
INSERT INTO `article` VALUES (16, 8, '2121', '2121', 'video', 'posts/v/201103/7430b396c18faca50e8f4986b5a7bf04.jpg,posts/v/201103/88a88fb7245e05f4995fc724a5bd3894.mp4', 100, 3, 5, 0, 0, 1604027126, 1604482484, 1, NULL);
INSERT INTO `article` VALUES (21, 8, '测试APP上传2', '测试APP上传2', 'img', 'posts/201103/d366d5a59884ffd8d2eddbcf04934262.jpg', 102, 0, 0, 0, 0, 1604383283, 1604383283, 1, NULL);
INSERT INTO `article` VALUES (22, 8, 'app多图上传', 'app多图上传', 'img', 'posts/201103/2047b6e0e9a57546389be23c4c1f3244.jpg,posts/201103/9853a3a8e4f5baf051e0446dccb6a6c0.jpg,posts/201103/bf502168e00c606cc8353beeea4da728.jpg,posts/201103/dcc8408927a7165a59432ad267abec3b.jpg', 102, 1, 0, 0, 0, 1604384087, 1604388453, 1, NULL);
INSERT INTO `article` VALUES (23, 8, '测试', '测试', 'img', 'posts/201103/1bf414baef7d8ef738730de10f6d8b33.jpg,posts/201103/24aad9f348a09b199f21b9e0c4baedeb.jpg', 103, 0, 0, 0, 0, 1604385409, 1604385409, 1, NULL);
INSERT INTO `article` VALUES (24, 8, '哈哈哈测试', '哈哈哈测试', 'img', 'posts/201103/c5cd2c6d38bcca4886d9720d5a972f54.jpg,posts/201103/2926fef74408904e30bc46973446cc47.jpg,posts/201103/8e80db8a67db405ca728f633f6909f62.jpg', 104, 0, 0, 0, 0, 1604387306, 1604387306, 1, NULL);
INSERT INTO `article` VALUES (25, 8, '测试video', '测试video', 'img', 'posts/201103/807b8c29505a70852221bb0601dd2621.jpg', 103, 0, 0, 0, 0, 1604387381, 1604387381, 1, NULL);
INSERT INTO `article` VALUES (29, 8, '测试视频上传1', '测试视频上传1', 'video', 'posts/v/201103/7430b396c18faca50e8f4986b5a7bf04.jpg,posts/v/201103/88a88fb7245e05f4995fc724a5bd3894.mp4', 103, 2, 2, 0, 0, 1604388077, 1604485549, 1, NULL);
INSERT INTO `article` VALUES (30, 8, '猪，你的鼻子有两个洞', '猪，你的鼻子有两个洞', 'video', 'posts/v/201103/6894ff79875f680d4a156680ac0a782f.jpg,posts/v/201103/f3fd8f43a72c41d8d7186395be8cfe6a.mp4', 102, 0, 0, 0, 0, 1604402779, 1604402779, 1, NULL);
INSERT INTO `article` VALUES (31, 8, '富强、民主、文明、和谐、自由、平等、公正、法治、爱国、敬业、诚信、友善', '富强、民主、文明、和谐、自由、平等、公正、法治、爱国、敬业、诚信、友善', 'video', 'posts/v/201104/521712e7a08a8efb3cb6f98b4acbf08f.jpg,posts/v/201104/54be93943eb910dbb0a6341fb3339799.mp4', 102, 0, 0, 0, 0, 1604461463, 1604461463, 1, NULL);
INSERT INTO `article` VALUES (32, 6, 'EasySwoole 是一款基于Swoole Server 开发的常驻内存型的分布式PHP框架，专为API而生，摆脱传统PHP运行模式在进程唤起和文件加载上带来的性能损失。 EasySwoole 高度封装了 Swoole Server 而依旧维持 Swoole Server 原有特性，支持同时混合监听HTTP、自定义TCP、UDP协议，让开发者以最低的学习成本和精力编写出多进程，可异步，高可用的应用服务。在开发上，我们为您准备了以下常用组件：\r\n\r\n    http 服务服务器\r\n    协程ORM(类似', 'EasySwoole 是一款基于Swoole Server 开发的常驻内存型的分布式PHP框架，专为API而生，摆脱传统PHP运行模式在进程唤起和文件加载上带来的性能损失。 EasySwoole 高度封装了 Swoole Server 而依旧维持 Swoole Server 原有特性，支持同时混合监听HTTP、自定义TCP、UDP协议，让开发者以最低的学习成本和精力编写出多进程，可异步，高可用的应用服务。在开发上，我们为您准备了以下常用组件：\r\n\r\n    http 服务服务器\r\n    协程ORM(类似Tp Orm)\r\n    图片验证码\r\n    validate验证器\r\n    协程模板渲染引擎\r\n    jwt组件\r\n    协程TCP、UDP、WEB_SOCKET 服务端\r\n    协程redis连接池\r\n    协程mysql 连接池\r\n    协程Memcached客户端\r\n    协程通用链接池\r\n    协程kafka客户端\r\n    NSQ协程客户端\r\n    分布式跨平台RPC组件\r\n    协程consul客户端\r\n    协程apollo配置中心\r\n    协程Actor\r\n    协程Smtp客', 'text', '', 100, 0, 0, 0, 0, 1604462139, 1604462139, 1, NULL);
INSERT INTO `article` VALUES (33, 8, '2019年，初代AI声明自己对人类有害，人类一笑而过。\n2219年，机器人正式向人类宣战。\n2319年，部分人类逃往外太空，地球人口不足20亿。机器人获得统治权。\n2719年，地球人类颠覆机器人统治，获得地球控制权，开始销毁一切AI设施。\n2800年，受病毒瘟疫肆虐，地球人口减少，约为10亿。\n2850年，东方大陆向世界发出最后一份电波，进入农耕时代。\n2900年，全球进入农耕时代。\n4000年，逃往太空的人类返回地球，由于一直想用科技力量战胜地球机器人而过度开发大脑，变成脑袋和眼睛奇大，而被当时的地球人', '2019年，初代AI声明自己对人类有害，人类一笑而过。\n2219年，机器人正式向人类宣战。\n2319年，部分人类逃往外太空，地球人口不足20亿。机器人获得统治权。\n2719年，地球人类颠覆机器人统治，获得地球控制权，开始销毁一切AI设施。\n2800年，受病毒瘟疫肆虐，地球人口减少，约为10亿。\n2850年，东方大陆向世界发出最后一份电波，进入农耕时代。\n2900年，全球进入农耕时代。\n4000年，逃往太空的人类返回地球，由于一直想用科技力量战胜地球机器人而过度开发大脑，变成脑袋和眼睛奇大，而被当时的地球人类称为外星人。\n4100年，地球人类进入蒸汽时代。\n4300年，地球初代AI声明自己对人类有害，人类一笑而过。', 'text', '', 102, 0, 0, 0, 0, 1604462590, 1604462590, 1, NULL);
INSERT INTO `article` VALUES (34, 8, '测试表情\\ud83d\\ude00', '测试表情\\ud83d\\ude00', 'text', '', 102, 0, 0, 0, 0, 1604463665, 1604463665, 1, NULL);
INSERT INTO `article` VALUES (35, 8, '🤪🥵', '🤪🥵', 'text', '', 102, 0, 0, 0, 0, 1604466519, 1604466519, 1, NULL);
INSERT INTO `article` VALUES (36, 8, '2019年，初代AI声明自己对人类有害，人类一笑而过。\n2219年，机器人正式向人类宣战。\n2319年，部分人类逃往外太空，地球人口不足20亿。机器人获得统治权。\n2719年，地球人类颠覆机器人统治，获得地球控制权，开始销毁一切AI设施。\n2800年，受病毒瘟疫肆虐，地球人口减少，约为10亿。\n2850年，东方大陆向世界发出最后一份电波，进入农耕时代。\n2900年，全球进入农耕时代。\n4000年，...', '2019年，初代AI声明自己对人类有害，人类一笑而过。\n2219年，机器人正式向人类宣战。\n2319年，部分人类逃往外太空，地球人口不足20亿。机器人获得统治权。\n2719年，地球人类颠覆机器人统治，获得地球控制权，开始销毁一切AI设施。\n2800年，受病毒瘟疫肆虐，地球人口减少，约为10亿。\n2850年，东方大陆向世界发出最后一份电波，进入农耕时代。\n2900年，全球进入农耕时代。\n4000年，逃往太空的人类返回地球，由于一直想用科技力量战胜地球机器人而过度开发大脑，变成脑袋和眼睛奇大，而被当时的地球人类称为外星人👽。\n4100年，地球人类进入蒸汽时代。\n4300年，地球初代AI声明自己对人类有害，人类一笑而过。', 'text', '', 102, 0, 0, 0, 0, 1604466572, 1604466572, 1, NULL);
INSERT INTO `article` VALUES (37, 8, '早安呀，小猿', '早安呀，小猿', 'video', 'posts/v/201104/5ace5883d3c6ebc47d5fe8e5b9d56062.jpg,posts/v/201104/2a3c9129260b619e82487c03aa8d729c.mp4', 102, 0, 0, 0, 0, 1604473870, 1604473870, 1, NULL);
INSERT INTO `article` VALUES (39, 8, '晚安，打工人', '晚安，打工人', 'video', 'posts/v/201104/a2acc788e7fffa5e002e3a0f2b2db882.jpg,posts/v/201104/d71063ff07e4f9354c5bb492bebdc029.mp4', 102, 0, 0, 0, 0, 1604496607, 1604496607, 1, NULL);
INSERT INTO `article` VALUES (40, 8, '哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈', '哈哈哈哈哈哈哈哈哈哈哈哈哈哈哈', 'video', 'posts/v/201104/c6c6c289ba6b1d01ceaa3091a5d893be.jpg,posts/v/201104/5d504eccc993eff49136062f6bbfce83.mp4', 102, 0, 0, 0, 0, 1604497962, 1604497962, 1, NULL);
INSERT INTO `article` VALUES (41, 8, '如果我聊着聊着突然没了，你看看你最后一条消息，或者图，那他妈是人能接上的！', '如果我聊着聊着突然没了，你看看你最后一条消息，或者图，那他妈是人能接上的！', 'video', 'posts/v/201104/e0493fc31c3aa96c0f91478f38da1b00.jpg,posts/v/201104/708fd2d06d69f6413327fbd1d46225e4.mp4', 102, 0, 0, 0, 0, 1604498064, 1604498064, 1, NULL);

-- ----------------------------
-- Table structure for article_class
-- ----------------------------
DROP TABLE IF EXISTS `article_class`;
CREATE TABLE `article_class`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `model` char(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '类型',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=一级栏目 >0=上级id',
  `sort` tinyint(3) UNSIGNED NOT NULL DEFAULT 1 COMMENT '倒序 取值 0-255',
  `mark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '描述信息',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '图标链接',
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '百度定位坐标 lat,lon 格式字符串',
  `status` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0=不显示 1=显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 135 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of article_class
-- ----------------------------
INSERT INTO `article_class` VALUES (1, '搞笑', '', 'simple', 0, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (2, '同城', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (3, '涨知识', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (4, '游戏', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (5, '影视', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (6, '生活', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (7, '情感', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (8, '二次元', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (9, '音乐', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (10, '正能量', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (11, '互动', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (12, '资源', '', 'simple', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (100, '吃了吗', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (101, '宜宾', '', 'local', 0, 1, '', '', '', 1);
INSERT INTO `article_class` VALUES (102, '今日打卡', '', 'simple', 6, 1, '', 'location', '', 1);
INSERT INTO `article_class` VALUES (103, '小吃', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (104, '测试分页1', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (105, '测试分页2', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (106, '测试分页3', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (107, '测试分页4', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (108, '测试分页5', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (109, '测试分页6', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (110, '测试分页7', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (111, '测试分页8', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (112, '测试分页9', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (113, '测试分页10', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (114, '测试分页11', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (115, '测试分页12', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (116, '测试分页13', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (117, '测试分页14', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (118, '测试分页15', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (119, '测试分页16', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (120, '测试分页a', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (121, '测试分页b', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (122, '测试分页c', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (123, '测试分页d', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (124, '测试分页e', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (125, '测试分页h', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (126, '测试分页i', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (127, '测试分页j', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (128, '测试分页k', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (129, '测试分页m', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (130, '测试分页l', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (131, '测试分页', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (132, '测试分页', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (133, '测试分页', '', 'simple', 6, 1, '', 'fire', '', 1);
INSERT INTO `article_class` VALUES (134, '测试分页', '', 'simple', 6, 1, '', 'fire', '', 1);

-- ----------------------------
-- Table structure for article_favor
-- ----------------------------
DROP TABLE IF EXISTS `article_favor`;
CREATE TABLE `article_favor`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'favor',
  `aid` int(11) NOT NULL DEFAULT 0 COMMENT '帖子ID',
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  `num` tinyint(2) NOT NULL DEFAULT 0 COMMENT '1:赞 -1:踩',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of article_favor
-- ----------------------------
INSERT INTO `article_favor` VALUES (1, 13, 8, 1, 1604377160);
INSERT INTO `article_favor` VALUES (2, 13, 8, 1, 1604377175);
INSERT INTO `article_favor` VALUES (3, 13, 8, 1, 1604377175);
INSERT INTO `article_favor` VALUES (4, 13, 8, -1, 1604377220);
INSERT INTO `article_favor` VALUES (5, 13, 8, -1, 1604377668);
INSERT INTO `article_favor` VALUES (6, 13, 8, -1, 1604377679);
INSERT INTO `article_favor` VALUES (7, 13, 8, -1, 1604377680);
INSERT INTO `article_favor` VALUES (8, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (9, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (10, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (11, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (12, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (13, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (14, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (15, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (16, 13, 8, 1, 1604377681);
INSERT INTO `article_favor` VALUES (17, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (18, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (19, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (20, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (21, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (22, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (23, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (24, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (25, 13, 8, -1, 1604377682);
INSERT INTO `article_favor` VALUES (26, 13, 8, -1, 1604377737);
INSERT INTO `article_favor` VALUES (27, 13, 8, -1, 1604377737);
INSERT INTO `article_favor` VALUES (28, 13, 8, -1, 1604377737);
INSERT INTO `article_favor` VALUES (29, 16, 8, -1, 1604377738);
INSERT INTO `article_favor` VALUES (30, 16, 8, -1, 1604377739);
INSERT INTO `article_favor` VALUES (31, 16, 8, -1, 1604377739);
INSERT INTO `article_favor` VALUES (32, 16, 8, -1, 1604377739);
INSERT INTO `article_favor` VALUES (33, 16, 8, -1, 1604377739);
INSERT INTO `article_favor` VALUES (34, 13, 8, 1, 1604377744);
INSERT INTO `article_favor` VALUES (35, 13, 8, 1, 1604377744);
INSERT INTO `article_favor` VALUES (36, 13, 8, 1, 1604377744);
INSERT INTO `article_favor` VALUES (37, 16, 8, 1, 1604378991);
INSERT INTO `article_favor` VALUES (38, 22, 6, 1, 1604388453);
INSERT INTO `article_favor` VALUES (39, 16, 8, 1, 1604392285);
INSERT INTO `article_favor` VALUES (40, 29, 6, 1, 1604482477);
INSERT INTO `article_favor` VALUES (41, 16, 6, 1, 1604482484);
INSERT INTO `article_favor` VALUES (42, 29, 6, 1, 1604485547);
INSERT INTO `article_favor` VALUES (43, 29, 6, -1, 1604485548);
INSERT INTO `article_favor` VALUES (44, 29, 6, -1, 1604485549);

-- ----------------------------
-- Table structure for association
-- ----------------------------
DROP TABLE IF EXISTS `association`;
CREATE TABLE `association`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `manstr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` tinyint(2) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for chat
-- ----------------------------
DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1:系统消息 2:私聊 3:群聊',
  `sender` int(255) UNSIGNED NOT NULL DEFAULT 0 COMMENT '发送者',
  `receiver` int(255) UNSIGNED NOT NULL DEFAULT 0 COMMENT '接收者',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '内容',
  `model` tinyint(2) UNSIGNED NOT NULL DEFAULT 1 COMMENT '模型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `aid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '帖子Id',
  `content` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '主体内容',
  `at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '回复的评论id，默认0 ',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1:普通文字 2:图片 3:视频 4:插眼 5:定位',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `time` int(10) UNSIGNED NULL DEFAULT NULL,
  `data` varchar(5000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '',
  `date` datetime(0) NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '类型',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log
-- ----------------------------
INSERT INTO `log` VALUES (1, 1603965944, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (2, 1603974131, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (3, 1603974148, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (4, 1603974385, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (5, 1603974441, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (6, 1603974695, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (7, 1603974738, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (8, 1603974738, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (9, 1603974738, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (10, 1603974739, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (11, 1603975342, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (12, 1603975343, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (13, 1603975343, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (14, 1603975343, 'false', NULL, 'release', NULL);
INSERT INTO `log` VALUES (15, 1603975801, 'false', NULL, 'release', NULL);

-- ----------------------------
-- Table structure for msg_record
-- ----------------------------
DROP TABLE IF EXISTS `msg_record`;
CREATE TABLE `msg_record`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '请求IP',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `code` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '发送的代码',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '时间',
  `ymd` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'Ymd格式时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of msg_record
-- ----------------------------
INSERT INTO `msg_record` VALUES (1, '112.194.107.32', '15181191048', '876941', 1603282415, '20201021');
INSERT INTO `msg_record` VALUES (2, '112.194.107.32', '17628104301', '396254', 1603283802, '20201021');
INSERT INTO `msg_record` VALUES (3, '112.194.107.32', '17628104301', '169511', 1603285322, '20201021');
INSERT INTO `msg_record` VALUES (4, '112.194.107.32', '15181191048', '120596', 1603285425, '20201021');
INSERT INTO `msg_record` VALUES (5, '112.194.107.32', '17628104301', '712328', 1603285498, '20201021');
INSERT INTO `msg_record` VALUES (6, '112.194.107.32', '17628104301', '298338', 1603285969, '20201021');
INSERT INTO `msg_record` VALUES (7, '171.93.25.51', '15708312053', '343806', 1603288767, '20201021');
INSERT INTO `msg_record` VALUES (8, '125.70.158.146', '15181191048', '344795', 1603331759, '20201022');

-- ----------------------------
-- Table structure for swipe
-- ----------------------------
DROP TABLE IF EXISTS `swipe`;
CREATE TABLE `swipe`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
  `pic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '图片地址',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '点击的操作',
  `place` char(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '显示位置',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for topic
-- ----------------------------
DROP TABLE IF EXISTS `topic`;
CREATE TABLE `topic`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '话题名',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nickname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '显示昵称',
  `phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '手机',
  `num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户号',
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '密码 md5',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `token` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'token',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `register_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
  `login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  `credit` int(255) UNSIGNED NOT NULL DEFAULT 0 COMMENT '积分',
  `level` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '等级',
  `sign` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '签名',
  `info` json NULL COMMENT '其他资料',
  `sex` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'm' COMMENT '性别',
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '百度定位坐标 lat,lon 格式字符串',
  `born` int(11) NOT NULL DEFAULT 0 COMMENT '出生-时间戳',
  `status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '用户状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `num`(`num`) USING BTREE,
  UNIQUE INDEX `nickname`(`nickname`) USING BTREE,
  UNIQUE INDEX `phone`(`phone`) USING BTREE,
  INDEX `password`(`password`) USING BTREE,
  INDEX `token`(`token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'Kyour', '15181191049', 10001, 'e10adc3949ba59abbe56e057f20f883e', 'avatar/1.png', 'qnr7eadamo4wysewvkq8mc1gtyshp2p0', '', 1601533084, 1603183815, 0, '1', '', '{\"fans\": 0, \"star\": 0, \"posts\": 0, \"follow\": 1}', 'm', '', 0, 1);
INSERT INTO `user` VALUES (2, 'User-test', '17628104302', 10002, 'e10adc3949ba59abbe56e057f20f883e', 'avatar/2.png', '', '', 1601533084, 0, 0, '1', '', NULL, 'm', '', 0, 1);
INSERT INTO `user` VALUES (6, '我是最胖的', '17628104300', 10003, 'fd7911828861c3c262ba1f11cdef00ba', 'avatar/1.png', 'iczge6q8kiuzulvh3tcyls2rqgsp4xfm', '', 1603285988, 1604564363, 0, '', '签名测试123', '{\"sex\": \"m\", \"born\": 0, \"fans\": 0, \"star\": 0, \"posts\": 0, \"follow\": 1}', 'm', '', 595958400, 1);
INSERT INTO `user` VALUES (7, '用户10004', '15708312053', 10004, '5ad6ec416631e4efd80e4eb60ccab328', 'avatar/default.png', 'tamnzpjbirrsvgivqw4faefz7c2dok5h', '', 1603288782, 1604329739, 0, '', '', '{\"fans\": 0, \"star\": 0, \"posts\": 0, \"follow\": 1}', 'm', '', 0, 1);
INSERT INTO `user` VALUES (8, '痞老板', '15181191047', 10005, 'fd7911828861c3c262ba1f11cdef00ba', 'avatar/default.png', 'm09pp6rtbuckaqdk23ifvseyfnls1qg5', '', 1603331791, 1604564380, 0, '', 'dsds', '{\"fans\": 0, \"star\": 0, \"posts\": 0, \"follow\": 1}', 'm', '', 911491200, 1);

-- ----------------------------
-- Table structure for user_follow
-- ----------------------------
DROP TABLE IF EXISTS `user_follow`;
CREATE TABLE `user_follow`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL COMMENT '关注人Id',
  `fuid` int(10) UNSIGNED NOT NULL COMMENT '被关注人Id',
  `time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '关注时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_follow
-- ----------------------------
INSERT INTO `user_follow` VALUES (1, 6, 8, 0);
INSERT INTO `user_follow` VALUES (2, 8, 6, 0);

-- ----------------------------
-- Table structure for user_rule
-- ----------------------------
DROP TABLE IF EXISTS `user_rule`;
CREATE TABLE `user_rule`  (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '名字',
  `uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '规则',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
