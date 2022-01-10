/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : scheme

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2021-12-10 21:10:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `saver`
-- ----------------------------
DROP TABLE IF EXISTS `saver`;
CREATE TABLE `saver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `seat_id` varchar(50) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `place_title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of saver
-- ----------------------------
INSERT INTO `saver` VALUES ('1', '1', 'seat-671', 'Zakrep', null);
INSERT INTO `saver` VALUES ('2', '1', 'seat-672', 'Zakrep', null);
INSERT INTO `saver` VALUES ('3', '1', 'seat-673', 'Zakrep', null);
INSERT INTO `saver` VALUES ('4', '1', 'seat-674', 'Zakrep', null);
INSERT INTO `saver` VALUES ('5', '1', 'seat-671', 'Zakrep', null);
INSERT INTO `saver` VALUES ('6', '1', 'seat-672', 'Zakrep', null);
INSERT INTO `saver` VALUES ('7', '1', 'seat-675', 'Zakrep', null);
INSERT INTO `saver` VALUES ('8', '1', 'seat-310', 'СШ', null);
INSERT INTO `saver` VALUES ('9', '1', 'seat-311', 'СШ', null);
INSERT INTO `saver` VALUES ('10', '1', 'seat-312', 'СШ', null);
INSERT INTO `saver` VALUES ('11', '1', 'seat-324', 'СШ', null);
INSERT INTO `saver` VALUES ('12', '1', 'seat-619', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('13', '1', 'seat-620', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('14', '1', 'seat-621', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('15', '1', 'seat-622', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('16', '1', 'seat-623', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('17', '1', 'seat-624', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('18', '1', 'seat-625', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('19', '1', 'seat-626', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('20', '1', 'seat-627', 'Президент Администрацияси Магрупов', null);
INSERT INTO `saver` VALUES ('21', '1', 'seat-496', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('22', '1', 'seat-497', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('23', '1', 'seat-498', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('24', '1', 'seat-499', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('25', '1', 'seat-500', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('26', '1', 'seat-501', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('27', '1', 'seat-502', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('28', '1', 'seat-503', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('29', '1', 'seat-85', 'Фонд', null);
INSERT INTO `saver` VALUES ('30', '1', 'seat-88', 'Фонд', null);
INSERT INTO `saver` VALUES ('31', '1', 'seat-104', 'Фонд', null);
INSERT INTO `saver` VALUES ('32', '1', 'seat-123', 'Фонд', null);
INSERT INTO `saver` VALUES ('33', '1', 'seat-124', 'Фонд', null);
INSERT INTO `saver` VALUES ('34', '1', 'seat-142', 'Фонд', null);
INSERT INTO `saver` VALUES ('35', '1', 'seat-145', 'Фонд', null);
INSERT INTO `saver` VALUES ('36', '1', 'seat-162', 'Фонд', null);
INSERT INTO `saver` VALUES ('37', '1', 'seat-163', 'Фонд', null);
INSERT INTO `saver` VALUES ('38', '1', 'seat-188', 'Фонд', null);
INSERT INTO `saver` VALUES ('39', '1', 'seat-189', 'Фонд', null);
INSERT INTO `saver` VALUES ('40', '1', 'seat-190', 'Фонд', null);
INSERT INTO `saver` VALUES ('41', '1', 'seat-211', 'Фонд', null);
INSERT INTO `saver` VALUES ('42', '1', 'seat-213', 'Фонд', null);
INSERT INTO `saver` VALUES ('43', '1', 'seat-214', 'Фонд', null);
INSERT INTO `saver` VALUES ('44', '1', 'seat-100', 'МинИКТ', null);
INSERT INTO `saver` VALUES ('45', '1', 'seat-101', 'МинИКТ', null);
INSERT INTO `saver` VALUES ('46', '1', 'seat-102', 'МинИКТ', null);
INSERT INTO `saver` VALUES ('47', '1', 'seat-103', 'МинИКТ', null);
INSERT INTO `saver` VALUES ('48', '1', 'seat-104', 'МинИКТ', null);
INSERT INTO `saver` VALUES ('49', '1', 'seat-69', 'СМИ', null);
INSERT INTO `saver` VALUES ('50', '1', 'seat-70', 'СМИ', null);
INSERT INTO `saver` VALUES ('51', '1', 'seat-71', 'СМИ', null);
INSERT INTO `saver` VALUES ('52', '1', 'seat-72', 'СМИ', null);
INSERT INTO `saver` VALUES ('53', '1', 'seat-102', 'СМИ', null);
INSERT INTO `saver` VALUES ('54', '1', 'seat-643', 'СГБ', null);
INSERT INTO `saver` VALUES ('55', '1', 'seat-665', 'СГБ', null);
INSERT INTO `saver` VALUES ('56', '1', 'seat-688', 'СГБ', null);
INSERT INTO `saver` VALUES ('57', '1', 'seat-708', 'СГБ', null);
INSERT INTO `saver` VALUES ('58', '1', 'seat-226', 'Минкульт', null);
INSERT INTO `saver` VALUES ('59', '1', 'seat-280', 'Минкульт', null);
INSERT INTO `saver` VALUES ('60', '1', 'seat-328', 'Минкульт', null);
INSERT INTO `saver` VALUES ('61', '1', 'seat-354', 'Минкульт', null);
INSERT INTO `saver` VALUES ('62', '1', 'seat-355', 'Минкульт', null);
INSERT INTO `saver` VALUES ('63', '1', 'seat-380', 'Минкульт', null);
INSERT INTO `saver` VALUES ('64', '1', 'seat-432', 'Минкульт', null);
INSERT INTO `saver` VALUES ('65', '1', 'seat-247', 'ТУИТ', null);
INSERT INTO `saver` VALUES ('66', '1', 'seat-262', 'ТУИТ', null);
INSERT INTO `saver` VALUES ('67', '1', 'seat-288', 'ТУИТ', null);
INSERT INTO `saver` VALUES ('68', '1', 'seat-372', 'ТУИТ', null);
INSERT INTO `saver` VALUES ('69', '1', 'seat-240', 'Куку', null);
INSERT INTO `saver` VALUES ('70', '1', 'seat-241', 'Куку', null);
INSERT INTO `saver` VALUES ('71', '1', 'seat-242', 'Куку', null);
INSERT INTO `saver` VALUES ('72', '1', 'seat-243', 'Куку', null);
INSERT INTO `saver` VALUES ('73', '1', 'seat-240', 'Куку', null);
INSERT INTO `saver` VALUES ('74', '1', 'seat-241', 'Куку', null);
INSERT INTO `saver` VALUES ('75', '1', 'seat-242', 'Куку', null);
INSERT INTO `saver` VALUES ('76', '1', 'seat-243', 'Куку', null);
INSERT INTO `saver` VALUES ('77', '1', 'seat-240', 'Куку', null);
INSERT INTO `saver` VALUES ('78', '1', 'seat-241', 'Куку', null);
INSERT INTO `saver` VALUES ('79', '1', 'seat-242', 'Куку', null);
INSERT INTO `saver` VALUES ('80', '1', 'seat-243', 'Куку', null);
INSERT INTO `saver` VALUES ('81', '1', 'seat-160', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('82', '1', 'seat-161', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('83', '1', 'seat-173', 'Абдухакимов', null);
INSERT INTO `saver` VALUES ('84', '2', 'seat-570', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('85', '2', 'seat-571', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('86', '2', 'seat-572', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('87', '2', 'seat-574', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('88', '2', 'seat-575', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('89', '2', 'seat-576', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('90', '2', 'seat-577', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('91', '2', 'seat-585', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('92', '2', 'seat-586', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('93', '2', 'seat-587', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('94', '2', 'seat-588', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('95', '2', 'seat-589', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('96', '2', 'seat-590', 'MIN FIN', null);
INSERT INTO `saver` VALUES ('97', '2', 'seat-557', 'СШ', 'Sector: Sector-C Row: 26 Seat: 27');
INSERT INTO `saver` VALUES ('98', '2', 'seat-558', 'СШ', 'Sector: Sector-C Row: 26 Seat: 26');
INSERT INTO `saver` VALUES ('99', '2', 'seat-1802', 'Абдулла Арипов', 'Sector: Parterre-1 Row: 1 Seat: 23');
INSERT INTO `saver` VALUES ('100', '2', 'seat-1803', 'Абдулла Арипов', 'Sector: Parterre-1 Row: 1 Seat: 22');
