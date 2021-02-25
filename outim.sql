/*
 Navicat Premium Data Transfer

 Source Server         : 本地站群数据库
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : outim

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 25/02/2021 19:05:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for out_company
-- ----------------------------
DROP TABLE IF EXISTS `out_company`;
CREATE TABLE `out_company`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '公司名称',
  `appid` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'appid',
  `secret` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'secret',
  `fire_time` int(255) NULL DEFAULT 0 COMMENT '过期时间,为0则永不过期',
  `add_time` datetime NULL DEFAULT NULL COMMENT '加入时间',
  `access_num` int(11) NULL DEFAULT NULL COMMENT '允许注册人数,为0则不限制人数',
  `c_is_close` tinyint(255) NULL DEFAULT 0 COMMENT '是否被关闭:1:是;0:否',
  `now_num` int(11) NULL DEFAULT 0 COMMENT '已注册人数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '加盟公司表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of out_company
-- ----------------------------
INSERT INTO `out_company` VALUES (3, '测试公司', '39319ec4cf48c947c8cdba5ab1879399', '0f8acbd4c43da2513f02db82fb8b5c94', 0, '2021-02-24 05:54:45', 0, 0, 0);

-- ----------------------------
-- Table structure for out_friend_ship
-- ----------------------------
DROP TABLE IF EXISTS `out_friend_ship`;
CREATE TABLE `out_friend_ship`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `friend_id` int(11) NULL DEFAULT NULL COMMENT '好友id',
  `user_remarks` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户对好友备注',
  `friend_remarks` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '好友对用户备注',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '好友关系表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of out_friend_ship
-- ----------------------------

-- ----------------------------
-- Table structure for out_join_plan
-- ----------------------------
DROP TABLE IF EXISTS `out_join_plan`;
CREATE TABLE `out_join_plan`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `effective_day` int(255) NOT NULL DEFAULT 0 COMMENT '有效天数,如果为0则永久有效',
  `access_num` int(11) NULL DEFAULT 0 COMMENT '允许注册最大人数,如果为0则不限制',
  `add_time` datetime NULL DEFAULT NULL COMMENT '类型添加时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '类型修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of out_join_plan
-- ----------------------------
INSERT INTO `out_join_plan` VALUES (1, 0, 0, '2021-02-24 16:04:16', '2021-02-24 16:04:16');

-- ----------------------------
-- Table structure for out_user
-- ----------------------------
DROP TABLE IF EXISTS `out_user`;
CREATE TABLE `out_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `c_id` int(11) NULL DEFAULT NULL COMMENT '关联公司id',
  `accid` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户在改公司数据库中唯一标识',
  `nick_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户昵称',
  `age` tinyint(4) NULL DEFAULT 0 COMMENT '年龄',
  `sex` tinyint(255) NULL DEFAULT 0 COMMENT '性别:0:女;1男',
  `u_is_close` tinyint(255) NULL DEFAULT 0 COMMENT '用户是否被关闭:0:否;1:是',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '用户登录使用token,唯一',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `token`(`token`(191)) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of out_user
-- ----------------------------
INSERT INTO `out_user` VALUES (1, 3, '123', 'heh', 0, 0, 0, '$2y$10$o0tdRSgSIORQEz6ddY6/ce2KCLOrbojzQxRvESk2uydnZvCcYPdR2');

SET FOREIGN_KEY_CHECKS = 1;
