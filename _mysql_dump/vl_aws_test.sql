/*

 Target Server Type    : MySQL
 Target Server Version : 50610
 File Encoding         : utf-8

 Date: 12/31/2015 15:21:13 PM

*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `vl_media_list`
-- ----------------------------
DROP TABLE IF EXISTS `vl_media_list`;
CREATE TABLE `vl_media_list` (
  `vl_media_id` int(9) NOT NULL AUTO_INCREMENT,
  `vl_media_name` varchar(255) DEFAULT NULL,
  `vl_media_file` varchar(255) DEFAULT NULL,
  `vl_media_file_custom` varchar(255) DEFAULT NULL,
  `vl_media_type` int(9) DEFAULT NULL,
  PRIMARY KEY (`vl_media_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `vl_media_list`
-- ----------------------------
BEGIN;
INSERT INTO `vl_media_list` VALUES ('1', 'Test File 01', '62960_dezeen_la-cornue-w-by-jean-michel-wilmotte-for-la-cornue-13.jpg', '56277_5800706e466d60b6035f8ca075f0f02c.jpg', '1'), ('2', 'Test File 02', '112002_william-obrien-jr-allandale-house-004.thumbnail.jpg', '136652_504797629403_zj9tzavx_l.jpg', '1');
COMMIT;

-- ----------------------------
--  Table structure for `vl_media_type_list`
-- ----------------------------
DROP TABLE IF EXISTS `vl_media_type_list`;
CREATE TABLE `vl_media_type_list` (
  `vl_media_type_id` int(9) NOT NULL AUTO_INCREMENT,
  `vl_media_type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`vl_media_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `vl_media_type_list`
-- ----------------------------
BEGIN;
INSERT INTO `vl_media_type_list` VALUES ('1', 'Image');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
