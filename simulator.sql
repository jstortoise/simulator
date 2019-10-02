/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 80015
 Source Host           : localhost:3306
 Source Schema         : simulator

 Target Server Type    : MySQL
 Target Server Version : 80015
 File Encoding         : 65001

 Date: 01/10/2019 21:28:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for fields
-- ----------------------------
DROP TABLE IF EXISTS `fields`;
CREATE TABLE `fields`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `field_label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `unit` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fields
-- ----------------------------
INSERT INTO `fields` VALUES (1, 'f7', 'Type de contrat', 'CDI', NULL, 1);
INSERT INTO `fields` VALUES (2, 'f8', 'feuille de paye', '20', '€', 0);
INSERT INTO `fields` VALUES (3, 'f9', 'Mutuelle', '20', '€', 1);
INSERT INTO `fields` VALUES (4, 'f9', 'Mutuelle', '30', '€', 1);
INSERT INTO `fields` VALUES (5, 'f10', 'Assurance salarié', '26', '€', 0);
INSERT INTO `fields` VALUES (6, 'f11', 'Assurance ESN', '100', '€', 0);
INSERT INTO `fields` VALUES (7, 'f13', 'URSAAF', '1200', '€', 0);
INSERT INTO `fields` VALUES (8, 'f14', 'AG2R', '275', '€', 0);
INSERT INTO `fields` VALUES (9, 'f15', 'Outils', '50', '€', 0);
INSERT INTO `fields` VALUES (10, 'f16', 'Admin CDI', '200', '€', 0);
INSERT INTO `fields` VALUES (11, 'f24', 'Réserve salaire ratio', '0.25', NULL, 0);
INSERT INTO `fields` VALUES (12, 'f27', 'Réserve employabilité', '400', '€', 0);
INSERT INTO `fields` VALUES (13, 'f7', 'Type de contrat', 'NON', NULL, 1);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `role` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`username`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('admin', 'admin', '0');
INSERT INTO `users` VALUES ('user', 'user', '1');

SET FOREIGN_KEY_CHECKS = 1;
