-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for my_ecom_db
CREATE DATABASE IF NOT EXISTS `my_ecom_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `my_ecom_db`;

-- Dumping structure for table my_ecom_db.banner
DROP TABLE IF EXISTS `banner`;
CREATE TABLE IF NOT EXISTS `banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'Banner名称，通常作为标识',
  `description` varchar(255) DEFAULT NULL COMMENT 'Banner描述',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COMMENT='banner管理表';

-- Dumping data for table my_ecom_db.banner: ~22 rows (approximately)
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
REPLACE INTO `banner` (`id`, `name`, `description`, `delete_time`, `update_time`) VALUES
	(1, '首页置顶', '首页轮播图', NULL, NULL),
	(42, '首页置顶', '首页轮播图', 1589782942, NULL),
	(43, '首页置顶', '首页轮播图', 1589782942, NULL),
	(44, '首页置顶', '首页轮播图', 1589813323, NULL),
	(45, '首页置顶', '首页轮播图', 1589813181, NULL),
	(46, '首页置顶', '首页轮播图', 1589813247, NULL),
	(47, '首页置顶', '首页轮播图', 1589814313, NULL),
	(48, '首页置顶', '首页轮播图', 1589814313, NULL),
	(49, '首页置顶', '首页轮播图', 1589814471, NULL),
	(50, '首页置顶', '首页轮播图', 1589814546, NULL),
	(51, '首页置顶', '首页轮播图', 1589814546, NULL),
	(52, '首页置顶', '首页轮播图', 1589814615, NULL),
	(53, '首页置顶', '首页轮播图', 1589814615, NULL),
	(54, '首页置顶', '首页轮播图', 1589814685, NULL),
	(55, '首页置顶', '首页轮播图', 1589814685, NULL),
	(56, '\'ok\'', '首页轮播图', NULL, NULL),
	(57, '首页置顶', '首页轮播图', 1589816059, NULL),
	(58, '首页置顶', '首页轮播图', 1589816233, NULL),
	(59, '首页置顶', '首页轮播图', 1589816233, NULL),
	(60, '首页置顶', '首页轮播图', NULL, NULL),
	(61, '首页置顶', '首页轮播图', NULL, NULL),
	(62, '首页置顶', '首页轮播图', NULL, NULL),
	(63, '1325', '6265', 1590772700, NULL),
	(64, 'agjad', 'agag', NULL, NULL);
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.banner_item
DROP TABLE IF EXISTS `banner_item`;
CREATE TABLE IF NOT EXISTS `banner_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_id` int(11) NOT NULL COMMENT '外键，关联image表',
  `key_word` varchar(100) NOT NULL COMMENT '执行关键字，根据不同的type含义不同',
  `type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '跳转类型，可能导向商品，可能导向专题，可能导向其他。0，无导向；1：导向商品;2:导向专题',
  `delete_time` int(11) DEFAULT NULL,
  `banner_id` int(11) NOT NULL COMMENT '外键，关联banner表',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COMMENT='banner子项表';

-- Dumping data for table my_ecom_db.banner_item: ~59 rows (approximately)
/*!40000 ALTER TABLE `banner_item` DISABLE KEYS */;
REPLACE INTO `banner_item` (`id`, `img_id`, `key_word`, `type`, `delete_time`, `banner_id`, `update_time`) VALUES
	(1, 65, '6', 1, NULL, 1, NULL),
	(2, 2, '25', 1, NULL, 1, NULL),
	(3, 3, '11', 1, NULL, 1, NULL),
	(5, 1, '10', 1, NULL, 1, NULL),
	(6, 4, '7', 1, NULL, 1, NULL),
	(7, 5, '8', 1, NULL, 1, NULL),
	(74, 4, '7', 1, 1589782942, 42, NULL),
	(75, 5, '8', 1, 1589782942, 42, NULL),
	(76, 4, '7', 1, 1589782942, 43, NULL),
	(77, 5, '8', 1, 1589782942, 43, NULL),
	(78, 4, '7', 1, 1589813323, 44, NULL),
	(79, 5, '8', 1, 1589813323, 44, NULL),
	(80, 4, '7', 1, 1589813181, 45, NULL),
	(81, 5, '8', 1, 1589813181, 45, NULL),
	(82, 4, '7', 1, 1589813247, 46, NULL),
	(83, 5, '8', 1, 1589813247, 46, NULL),
	(84, 4, '7', 1, 1589814313, 47, NULL),
	(85, 5, '8', 1, 1589814313, 47, NULL),
	(86, 4, '7', 1, 1589814313, 48, NULL),
	(87, 5, '8', 1, 1589814313, 48, NULL),
	(88, 4, '7', 1, 1589814471, 49, NULL),
	(89, 5, '8', 1, 1589814471, 49, NULL),
	(90, 4, '7', 1, 1589814546, 50, NULL),
	(91, 5, '8', 1, 1589814546, 50, NULL),
	(92, 4, '7', 1, 1589814546, 51, NULL),
	(93, 5, '8', 1, 1589814546, 51, NULL),
	(94, 4, '7', 1, 1589814615, 52, NULL),
	(95, 5, '8', 1, 1589814615, 52, NULL),
	(96, 4, '7', 1, 1589814615, 53, NULL),
	(97, 5, '8', 1, 1589814615, 53, NULL),
	(98, 4, '7', 1, 1589814685, 54, NULL),
	(99, 5, '8', 1, 1589814685, 54, NULL),
	(100, 4, '7', 1, 1589814685, 55, NULL),
	(101, 5, '8', 1, 1589814685, 55, NULL),
	(102, 4, '7', 1, 1589815838, 56, NULL),
	(103, 5, '8', 1, 1589815838, 56, NULL),
	(106, 4, '7', 1, 1589816233, 58, NULL),
	(107, 5, '8', 1, 1589816233, 58, NULL),
	(108, 4, '7', 1, 1589816233, 59, NULL),
	(109, 5, '8', 1, 1589816233, 59, NULL),
	(110, 4, '7', 1, 1589816377, 60, NULL),
	(111, 76, '8', 1, NULL, 60, NULL),
	(112, 4, '7', 1, 1589816377, 61, NULL),
	(113, 5, '8', 1, NULL, 61, NULL),
	(114, 74, '7', 1, NULL, 62, NULL),
	(115, 5, '8', 1, NULL, 62, NULL),
	(116, 111, '222', 1, NULL, 61, NULL),
	(117, 77, '442', 1, NULL, 60, NULL),
	(120, 4, '', 1, NULL, 61, NULL),
	(121, 81, '8', 1, NULL, 60, NULL),
	(122, 4, '', 1, NULL, 61, NULL),
	(123, 80, '8', 1, NULL, 60, NULL),
	(124, 4, '7', 1, NULL, 61, NULL),
	(125, 79, '8', 1, NULL, 60, NULL),
	(126, 4, '7', 1, NULL, 61, NULL),
	(127, 78, '8', 1, NULL, 60, NULL),
	(128, 70, '12', 1, 1590772700, 63, NULL),
	(129, 71, '23', 2, 1590772700, 63, NULL),
	(130, 82, '22', 2, NULL, 64, NULL);
/*!40000 ALTER TABLE `banner_item` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.cart
DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `counts` int(11) DEFAULT NULL,
  `selected_status` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.cart: ~0 rows (approximately)
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
REPLACE INTO `cart` (`id`, `user_id`, `product_id`, `counts`, `selected_status`, `create_time`, `delete_time`, `update_time`) VALUES
	(0, 58, 10, 1, 1, NULL, NULL, NULL);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.category
DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '分类名称',
  `topic_img_id` int(11) DEFAULT NULL COMMENT '外键，关联image表',
  `delete_time` int(11) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL COMMENT '描述',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COMMENT='商品类目';

-- Dumping data for table my_ecom_db.category: ~6 rows (approximately)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
REPLACE INTO `category` (`id`, `name`, `topic_img_id`, `delete_time`, `description`, `update_time`) VALUES
	(2, '果味', 6, NULL, NULL, NULL),
	(3, '蔬菜', 5, NULL, NULL, NULL),
	(4, '炒货', 7, NULL, NULL, NULL),
	(5, '点心', 4, NULL, NULL, NULL),
	(6, '粗茶', 8, NULL, NULL, NULL),
	(7, '淡饭', 9, NULL, NULL, NULL);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.deliver_record
DROP TABLE IF EXISTS `deliver_record`;
CREATE TABLE IF NOT EXISTS `deliver_record` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL COMMENT '交易订单号',
  `comp` varchar(10) NOT NULL COMMENT '快递公司编码',
  `number` varchar(20) NOT NULL COMMENT '快递单号',
  `operator` varchar(10) NOT NULL COMMENT '发货人姓名',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `number` (`number`),
  KEY `operator` (`operator`),
  KEY `order_no` (`order_no`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.deliver_record: 4 rows
/*!40000 ALTER TABLE `deliver_record` DISABLE KEYS */;
REPLACE INTO `deliver_record` (`id`, `order_no`, `comp`, `number`, `operator`, `create_time`, `update_time`) VALUES
	(1, 'A518621459600602', 'sf', '630111112222233333', '1', 1589862448, 1589862448),
	(2, 'A518621459600602', 'sf', '630111112222233333', '1', 1589863147, 1589863147),
	(3, 'A519631623438855', 'sf', '630111112222233333', '1', 1589863202, 1589863202),
	(4, 'A519631623438855', 'sf', '630111112222233333', '1', 1589863364, 1589863364);
/*!40000 ALTER TABLE `deliver_record` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.image
DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL COMMENT '图片路径',
  `from` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1 来自本地，2 来自公网',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COMMENT='图片总表';

-- Dumping data for table my_ecom_db.image: ~73 rows (approximately)
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
REPLACE INTO `image` (`id`, `url`, `from`, `delete_time`, `update_time`) VALUES
	(1, '/banner-1a.png', 1, NULL, NULL),
	(2, '/banner-2a.png', 1, NULL, NULL),
	(3, '/banner-3a.png', 1, NULL, NULL),
	(4, '/category-cake.png', 1, NULL, NULL),
	(5, '/category-vg.png', 1, NULL, NULL),
	(6, '/category-dryfruit.png', 1, NULL, NULL),
	(7, '/category-fry-a.png', 1, NULL, NULL),
	(8, '/category-tea.png', 1, NULL, NULL),
	(9, '/category-rice.png', 1, NULL, NULL),
	(10, '/product-dryfruit@1.png', 1, NULL, NULL),
	(13, '/product-vg@1.png', 1, NULL, NULL),
	(14, '/product-rice@6.png', 1, NULL, NULL),
	(16, '/1@theme.png', 1, NULL, NULL),
	(17, '/2@theme.png', 1, NULL, NULL),
	(18, '/3@theme.png', 1, NULL, NULL),
	(19, '/detail-1@1-dryfruit.png', 1, NULL, NULL),
	(20, '/detail-2@1-dryfruit.png', 1, NULL, NULL),
	(21, '/detail-3@1-dryfruit.png', 1, NULL, NULL),
	(22, '/detail-4@1-dryfruit.png', 1, NULL, NULL),
	(23, '/detail-5@1-dryfruit.png', 1, NULL, NULL),
	(24, '/detail-6@1-dryfruit.png', 1, NULL, NULL),
	(25, '/detail-7@1-dryfruit.png', 1, NULL, NULL),
	(26, '/detail-8@1-dryfruit.png', 1, NULL, NULL),
	(27, '/detail-9@1-dryfruit.png', 1, NULL, NULL),
	(28, '/detail-11@1-dryfruit.png', 1, NULL, NULL),
	(29, '/detail-10@1-dryfruit.png', 1, NULL, NULL),
	(31, '/product-rice@1.png', 1, NULL, NULL),
	(32, '/product-tea@1.png', 1, NULL, NULL),
	(33, '/product-dryfruit@2.png', 1, NULL, NULL),
	(36, '/product-dryfruit@3.png', 1, NULL, NULL),
	(37, '/product-dryfruit@4.png', 1, NULL, NULL),
	(38, '/product-dryfruit@5.png', 1, NULL, NULL),
	(39, '/product-dryfruit-a@6.png', 1, NULL, NULL),
	(40, '/product-dryfruit@7.png', 1, NULL, NULL),
	(41, '/product-rice@2.png', 1, NULL, NULL),
	(42, '/product-rice@3.png', 1, NULL, NULL),
	(43, '/product-rice@4.png', 1, NULL, NULL),
	(44, '/product-fry@1.png', 1, NULL, NULL),
	(45, '/product-fry@2.png', 1, NULL, NULL),
	(46, '/product-fry@3.png', 1, NULL, NULL),
	(47, '/product-tea@2.png', 1, NULL, NULL),
	(48, '/product-tea@3.png', 1, NULL, NULL),
	(49, '/1@theme-head.png', 1, NULL, NULL),
	(50, '/2@theme-head.png', 1, NULL, NULL),
	(51, '/3@theme-head.png', 1, NULL, NULL),
	(52, '/product-cake@1.png', 1, NULL, NULL),
	(53, '/product-cake@2.png', 1, NULL, NULL),
	(54, '/product-cake-a@3.png', 1, NULL, NULL),
	(55, '/product-cake-a@4.png', 1, NULL, NULL),
	(56, '/product-dryfruit@8.png', 1, NULL, NULL),
	(57, '/product-fry@4.png', 1, NULL, NULL),
	(58, '/product-fry@5.png', 1, NULL, NULL),
	(59, '/product-rice@5.png', 1, NULL, NULL),
	(60, '/product-rice@7.png', 1, NULL, NULL),
	(62, '/detail-12@1-dryfruit.png', 1, NULL, NULL),
	(63, '/detail-13@1-dryfruit.png', 1, NULL, NULL),
	(65, '/banner-4a.png', 1, NULL, NULL),
	(66, '/product-vg@4.png', 1, NULL, NULL),
	(67, '/product-vg@5.png', 1, NULL, NULL),
	(68, '/product-vg@2.png', 1, NULL, NULL),
	(69, '/product-vg@3.png', 1, NULL, NULL),
	(70, '/20200529/7074e157d2c49afb57f41e55e7c356a4.png', 1, NULL, NULL),
	(71, '/20200529/a2821e82ce7d5dc7678569e227ff43f9.png', 1, NULL, NULL),
	(72, '/20200529/f87d587f4f4cc93acf0f39ce06af07b2.png', 1, NULL, NULL),
	(73, '/20200529/a8617351270a9befebf594ad0c04b0cb.png', 1, NULL, NULL),
	(74, '/20200530/ddf99d3e19c8fd2f215f9684fd855a9f.png', 1, NULL, NULL),
	(75, '/20200530/ebe00e5fedbf42a092a28930ac7a2d0f.png', 1, NULL, NULL),
	(76, '/20200530/885a40ddd3493a1f77f0fe924700f20a.png', 1, NULL, NULL),
	(77, '/20200530/d1ec6104f12648658e53ff13ee46db90.png', 1, NULL, NULL),
	(78, '/20200530/1a4f08a46aad035783b6a34c29339ee0.png', 1, NULL, NULL),
	(79, '/20200530/5edb4e39affe96bc72dabaa15186932a.png', 1, NULL, NULL),
	(80, '/20200530/8a2fb9a484e336f3c71112c720d151ee.png', 1, NULL, NULL),
	(81, '/20200530/457bc2aaba7236097a091e753dfac57a.png', 1, NULL, NULL),
	(82, '/20200530/c701b130b94c93c426dcccc65e200027.png', 1, NULL, NULL),
	(83, '/20200530/0d7c59242f90a6317bacd013bfc05009.png', 1, NULL, NULL),
	(84, '/20200530/e8e237ca3bc7636deb1d99cb7103c936.png', 1, NULL, NULL),
	(85, '/20200530/7e22716fa7306f6a5be1dbfc81d3b985.png', 1, NULL, NULL),
	(86, '/20200530/8d532c7fa44041d9eb3ad259d7c15001.png', 1, NULL, NULL);
/*!40000 ALTER TABLE `image` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.order
DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(20) NOT NULL COMMENT '订单号',
  `user_id` int(11) NOT NULL COMMENT '外键，用户id，注意并不是openid',
  `delete_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `total_price` decimal(6,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:未支付， 2：已支付，3：已发货 , 4: 已支付，但库存不足',
  `snap_img` varchar(255) DEFAULT NULL COMMENT '订单快照图片',
  `snap_name` varchar(80) DEFAULT NULL COMMENT '订单快照名称',
  `total_count` int(11) NOT NULL DEFAULT 0,
  `update_time` int(11) DEFAULT NULL,
  `snap_items` text DEFAULT NULL COMMENT '订单其他信息快照（json)',
  `snap_address` varchar(500) DEFAULT NULL COMMENT '地址快照',
  `prepay_id` varchar(100) DEFAULT NULL COMMENT '订单微信支付的预订单id（用于发送模板消息）',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_no` (`order_no`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=544 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.order: ~5 rows (approximately)
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
REPLACE INTO `order` (`id`, `order_no`, `user_id`, `delete_time`, `create_time`, `total_price`, `status`, `snap_img`, `snap_name`, `total_count`, `update_time`, `snap_items`, `snap_address`, `prepay_id`) VALUES
	(539, 'A518621459600602', 60, NULL, 1589762145, 0.01, 3, 'http://z.cn/images/product-dryfruit@5.png', '万紫千凤梨 300克', 1, 1589863147, '[{"id":10,"inStock":true,"count":1,"name":"\\u4e07\\u7d2b\\u5343\\u51e4\\u68a8 300\\u514b","productPriceSum":0.01,"stock":996,"price":"0.01","main_img_url":"http:\\/\\/z.cn\\/images\\/product-dryfruit@5.png"}]', '{"name":"\\u5f20\\u4e09","mobile":"013342186975","province":"\\u5e7f\\u4e1c\\u7701","city":"\\u5e7f\\u5dde\\u5e02","country":"\\u6d77\\u73e0\\u533a","detail":"\\u65b0\\u6e2f\\u4e2d\\u8def397\\u53f7","update_time":"1970-01-01 08:00:00"}', 'wx201410272009395522657a690389285100'),
	(540, 'A519631623438855', 60, NULL, 1589863162, 0.05, 3, 'http://z.cn/images/product-tea@1.png', '红袖枸杞 6克*3袋等', 5, 1589863364, '[{"id":11,"inStock":true,"count":2,"name":"\\u8d35\\u5983\\u7b11 100\\u514b","productPriceSum":0.02,"stock":994,"price":"0.01","main_img_url":"http:\\/\\/z.cn\\/images\\/product-dryfruit-a@6.png"},{"id":4,"inStock":true,"count":3,"name":"\\u7ea2\\u8896\\u67b8\\u675e 6\\u514b*3\\u888b","productPriceSum":0.03,"stock":998,"price":"0.01","main_img_url":"http:\\/\\/z.cn\\/images\\/product-tea@1.png"}]', '{"name":"\\u5f20\\u4e09","mobile":"013342186975","province":"\\u5e7f\\u4e1c\\u7701","city":"\\u5e7f\\u5dde\\u5e02","country":"\\u6d77\\u73e0\\u533a","detail":"\\u65b0\\u6e2f\\u4e2d\\u8def397\\u53f7","update_time":"1970-01-01 08:00:00"}', 'wx201410272009395522657a690389285100'),
	(541, 'A519634293818055', 60, NULL, 1589863429, 0.03, 1, 'http://z.cn/images/product-dryfruit@7.png', '珍奇异果 3个等', 3, 1589863429, '[{"id":26,"inStock":true,"count":1,"name":"\\u7ea2\\u8863\\u9752\\u74dc \\u6df7\\u642d160\\u514b","productPriceSum":0.01,"stock":999,"price":"0.01","main_img_url":"http:\\/\\/z.cn\\/images\\/product-dryfruit@8.png"},{"id":12,"inStock":true,"count":2,"name":"\\u73cd\\u5947\\u5f02\\u679c 3\\u4e2a","productPriceSum":0.02,"stock":999,"price":"0.01","main_img_url":"http:\\/\\/z.cn\\/images\\/product-dryfruit@7.png"}]', '{"name":"\\u5f20\\u4e09","mobile":"013342186975","province":"\\u5e7f\\u4e1c\\u7701","city":"\\u5e7f\\u5dde\\u5e02","country":"\\u6d77\\u73e0\\u533a","detail":"\\u65b0\\u6e2f\\u4e2d\\u8def397\\u53f7","update_time":"1970-01-01 08:00:00"}', 'wx201410272009395522657a690389285100'),
	(542, 'A519006822831644', 60, NULL, 1589900681, 0.03, 1, 'http://z.cn/images/product-cake@2.png', '小红的猪耳朵 120克', 3, 1589900681, '[{"id":6,"inStock":true,"count":3,"name":"\\u5c0f\\u7ea2\\u7684\\u732a\\u8033\\u6735 120\\u514b","productPriceSum":0.03,"stock":997,"price":"0.01","main_img_url":"http:\\/\\/z.cn\\/images\\/product-cake@2.png"}]', '{"name":"\\u5f20\\u4e09","mobile":"013342186975","province":"\\u5e7f\\u4e1c\\u7701","city":"\\u5e7f\\u5dde\\u5e02","country":"\\u6d77\\u73e0\\u533a","detail":"\\u65b0\\u6e2f\\u4e2d\\u8def397\\u53f7","update_time":"1970-01-01 08:00:00"}', 'wx201410272009395522657a690389285100'),
	(543, 'A521113612512977', 60, NULL, 1590011361, 0.01, 1, 'http://z.cn/images/product-dryfruit@2.png', '春生龙眼 500克', 1, 1590011361, '[{"id":5,"inStock":true,"count":1,"name":"\\u6625\\u751f\\u9f99\\u773c 500\\u514b","productPriceSum":0.01,"stock":995,"price":"0.01","main_img_url":"http:\\/\\/z.cn\\/images\\/product-dryfruit@2.png"}]', '{"name":"\\u5f20\\u4e09","mobile":"013342186975","province":"\\u5e7f\\u4e1c\\u7701","city":"\\u5e7f\\u5dde\\u5e02","country":"\\u6d77\\u73e0\\u533a","detail":"\\u65b0\\u6e2f\\u4e2d\\u8def397\\u53f7","update_time":"1970-01-01 08:00:00"}', 'wx201410272009395522657a690389285100');
/*!40000 ALTER TABLE `order` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.order_product
DROP TABLE IF EXISTS `order_product`;
CREATE TABLE IF NOT EXISTS `order_product` (
  `order_id` int(11) NOT NULL COMMENT '联合主键，订单id',
  `product_id` int(11) NOT NULL COMMENT '联合主键，商品id',
  `count` int(11) NOT NULL COMMENT '商品数量',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`product_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.order_product: ~7 rows (approximately)
/*!40000 ALTER TABLE `order_product` DISABLE KEYS */;
REPLACE INTO `order_product` (`order_id`, `product_id`, `count`, `delete_time`, `update_time`) VALUES
	(540, 4, 3, NULL, 1589863162),
	(543, 5, 1, NULL, 1590011361),
	(542, 6, 3, NULL, 1589900681),
	(539, 10, 1, NULL, 1589762145),
	(540, 11, 2, NULL, 1589863162),
	(541, 12, 2, NULL, 1589863429),
	(541, 26, 1, NULL, 1589863429);
/*!40000 ALTER TABLE `order_product` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.product
DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL COMMENT '商品名称',
  `price` decimal(6,2) NOT NULL COMMENT '价格,单位：分',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT '库存量',
  `delete_time` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `main_img_url` varchar(255) DEFAULT NULL COMMENT '主图ID号，这是一个反范式设计，有一定的冗余',
  `from` tinyint(4) NOT NULL DEFAULT 1 COMMENT '图片来自 1 本地 ，2公网',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL,
  `summary` varchar(50) DEFAULT NULL COMMENT '摘要',
  `img_id` int(11) DEFAULT NULL COMMENT '图片外键',
  `status` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.product: ~34 rows (approximately)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
REPLACE INTO `product` (`id`, `name`, `price`, `stock`, `delete_time`, `category_id`, `main_img_url`, `from`, `create_time`, `update_time`, `summary`, `img_id`, `status`) VALUES
	(1, '芹菜 万斤', 0.01, 998, NULL, 3, '/product-vg@1.png', 1, NULL, 1589900330, NULL, 13, 1),
	(2, '梨花带雨 3个', 0.01, 984, NULL, 2, '/product-dryfruit@1.png', 1, NULL, NULL, NULL, 10, 1),
	(3, '素米 327克', 0.01, 996, NULL, 7, '/product-rice@1.png', 1, NULL, 1589849513, NULL, 31, 1),
	(4, '红袖枸杞 6克*3袋', 0.01, 998, NULL, 6, '/product-tea@1.png', 1, NULL, NULL, NULL, 32, 1),
	(5, '春生龙眼 500克', 0.01, 995, NULL, 2, '/product-dryfruit@2.png', 1, NULL, NULL, NULL, 33, 1),
	(6, '小红的猪耳朵 120克', 0.01, 997, NULL, 5, '/product-cake@2.png', 1, NULL, NULL, NULL, 53, 1),
	(7, '泥蒿 半斤', 0.01, 998, NULL, 3, '/product-vg@2.png', 1, NULL, NULL, NULL, 68, 1),
	(8, '夏日芒果 3个', 0.01, 995, NULL, 2, '/product-dryfruit@3.png', 1, NULL, NULL, NULL, 36, 1),
	(9, '冬木红枣 500克', 0.01, 996, NULL, 2, '/product-dryfruit@4.png', 1, NULL, NULL, NULL, 37, 1),
	(10, '万紫千凤梨 300克', 0.01, 996, NULL, 2, '/product-dryfruit@5.png', 1, NULL, NULL, NULL, 38, 1),
	(11, '贵妃笑 100克', 0.01, 994, NULL, 2, '/product-dryfruit-a@6.png', 1, NULL, NULL, NULL, 39, 1),
	(12, '珍奇异果 3个', 0.01, 999, NULL, 2, '/product-dryfruit@7.png', 1, NULL, NULL, NULL, 40, 1),
	(13, '绿豆 125克', 0.01, 999, NULL, 7, '/product-rice@2.png', 1, NULL, NULL, NULL, 41, 1),
	(14, '芝麻 50克', 0.01, 999, NULL, 7, '/product-rice@3.png', 1, NULL, NULL, NULL, 42, 1),
	(15, '猴头菇 370克', 0.01, 999, NULL, 7, '/product-rice@4.png', 1, NULL, NULL, NULL, 43, 1),
	(16, '西红柿 1斤', 0.01, 999, NULL, 3, '/product-vg@3.png', 1, NULL, NULL, NULL, 69, 1),
	(17, '油炸花生 300克', 0.01, 999, NULL, 4, '/product-fry@1.png', 1, NULL, NULL, NULL, 44, 1),
	(18, '春泥西瓜子 128克', 0.01, 997, NULL, 4, '/product-fry@2.png', 1, NULL, NULL, NULL, 45, 1),
	(19, '碧水葵花籽 128克', 0.01, 999, NULL, 4, '/product-fry@3.png', 1, NULL, NULL, NULL, 46, 1),
	(20, '碧螺春 12克*3袋', 0.01, 999, NULL, 6, '/product-tea@2.png', 1, NULL, NULL, NULL, 47, 1),
	(21, '西湖龙井 8克*3袋', 0.01, 998, NULL, 6, '/product-tea@3.png', 1, NULL, NULL, NULL, 48, 1),
	(22, '梅兰清花糕 1个', 0.01, 997, NULL, 5, '/product-cake-a@3.png', 1, NULL, NULL, NULL, 54, 1),
	(23, '清凉薄荷糕 1个', 0.01, 998, NULL, 5, '/product-cake-a@4.png', 1, NULL, NULL, NULL, 55, 1),
	(25, '小明的妙脆角 120克', 0.01, 999, NULL, 5, '/product-cake@1.png', 1, NULL, NULL, NULL, 52, 1),
	(26, '红衣青瓜 混搭160克', 0.01, 999, NULL, 2, '/product-dryfruit@8.png', 1, NULL, NULL, NULL, 56, 1),
	(27, '锈色瓜子 100克', 0.01, 998, NULL, 4, '/product-fry@4.png', 1, NULL, NULL, NULL, 57, 1),
	(28, '春泥花生 200克', 0.01, 999, NULL, 4, '/product-fry@5.png', 1, NULL, NULL, NULL, 58, 1),
	(29, '冰心鸡蛋 2个', 0.01, 999, NULL, 7, '/product-rice@5.png', 1, NULL, NULL, NULL, 59, 1),
	(30, '八宝莲子 200克', 0.01, 999, NULL, 7, '/product-rice@6.png', 1, NULL, NULL, NULL, 14, 1),
	(31, '深涧木耳 78克', 0.01, 999, NULL, 7, '/product-rice@7.png', 1, NULL, NULL, NULL, 60, 1),
	(32, '土豆 半斤', 0.01, 999, NULL, 3, '/product-vg@4.png', 1, NULL, NULL, NULL, 66, 1),
	(33, '青椒 半斤', 0.01, 999, NULL, 3, '/product-vg@5.png', 1, NULL, NULL, NULL, 67, 1),
	(39, '厕所', 1.00, 2, NULL, 2, '/product-vg@5.png', 1, 1589852064, 1589852064, '', 145, 0);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.product_image
DROP TABLE IF EXISTS `product_image`;
CREATE TABLE IF NOT EXISTS `product_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `img_id` int(11) NOT NULL COMMENT '外键，关联图片表',
  `delete_time` int(11) DEFAULT NULL COMMENT '状态，主要表示是否删除，也可以扩展其他状态',
  `order` int(11) NOT NULL DEFAULT 0 COMMENT '图片排序序号',
  `product_id` int(11) NOT NULL COMMENT '商品id，外键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.product_image: ~18 rows (approximately)
/*!40000 ALTER TABLE `product_image` DISABLE KEYS */;
REPLACE INTO `product_image` (`id`, `img_id`, `delete_time`, `order`, `product_id`) VALUES
	(4, 19, NULL, 1, 11),
	(5, 20, NULL, 2, 11),
	(6, 21, NULL, 3, 11),
	(7, 22, NULL, 4, 11),
	(8, 23, NULL, 5, 11),
	(9, 24, NULL, 6, 11),
	(10, 25, NULL, 7, 11),
	(11, 26, NULL, 8, 11),
	(12, 27, NULL, 9, 11),
	(13, 28, NULL, 11, 11),
	(14, 29, NULL, 10, 11),
	(18, 62, NULL, 12, 11),
	(19, 63, NULL, 13, 11),
	(29, 146, NULL, 0, 38),
	(30, 147, NULL, 1, 38),
	(31, 146, NULL, 0, 39),
	(32, 147, NULL, 1, 39),
	(33, 160, NULL, 0, 1);
/*!40000 ALTER TABLE `product_image` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.product_property
DROP TABLE IF EXISTS `product_property`;
CREATE TABLE IF NOT EXISTS `product_property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT '' COMMENT '详情属性名称',
  `detail` varchar(255) NOT NULL COMMENT '详情属性',
  `product_id` int(11) NOT NULL COMMENT '商品id，外键',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.product_property: ~13 rows (approximately)
/*!40000 ALTER TABLE `product_property` DISABLE KEYS */;
REPLACE INTO `product_property` (`id`, `name`, `detail`, `product_id`, `delete_time`, `update_time`) VALUES
	(1, '品名', '杨梅', 11, NULL, NULL),
	(2, '爱好', '不详', 2, NULL, NULL),
	(3, '产地', '火星', 11, NULL, NULL),
	(4, '保质期', '180天', 11, NULL, NULL),
	(5, '品名', '梨子', 2, NULL, NULL),
	(6, '产地', '金星', 2, NULL, NULL),
	(7, '净含量', '100g', 2, NULL, NULL),
	(8, '保质期', '10天', 2, NULL, NULL),
	(18, '性别', '不想', 38, NULL, NULL),
	(19, '哈哈', '搜索', 38, NULL, NULL),
	(20, '性别', '不想', 39, NULL, NULL),
	(21, '哈哈', '搜索', 39, NULL, NULL),
	(22, '爱好', '不详', 1, NULL, NULL);
/*!40000 ALTER TABLE `product_property` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.theme
DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '专题名称',
  `description` varchar(255) DEFAULT NULL COMMENT '专题描述',
  `topic_img_id` int(11) NOT NULL COMMENT '主题图，外键',
  `delete_time` int(11) DEFAULT NULL,
  `head_img_id` int(11) NOT NULL COMMENT '专题列表页，头图',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COMMENT='主题信息表';

-- Dumping data for table my_ecom_db.theme: ~11 rows (approximately)
/*!40000 ALTER TABLE `theme` DISABLE KEYS */;
REPLACE INTO `theme` (`id`, `name`, `description`, `topic_img_id`, `delete_time`, `head_img_id`, `update_time`) VALUES
	(1, '专题栏位一', '美味水果世界', 16, NULL, 49, NULL),
	(2, '\'ok-theme2\'', '新品推荐', 17, NULL, 50, NULL),
	(3, 'ok_theme3', '做个干物女', 18, NULL, 18, NULL),
	(4, 'zhuanlan-4', 'addtheme', 19, 1589830937, 21, NULL),
	(5, 'zhuanlan-5', 'addtheme', 33, 1589832181, 33, NULL),
	(6, 'zhuanlan-6', 'addtheme', 33, 1589832181, 33, NULL),
	(7, 'zhuanlan-7', 'addtheme', 33, NULL, 33, NULL),
	(8, 'zhuanlan-8', 'addtheme', 33, 1590772921, 33, NULL),
	(9, 'zhuanlan-9', 'addtheme', 33, 1590772917, 33, NULL),
	(10, 'qer', 'qetq', 83, NULL, 73, NULL),
	(11, '3e1', '1315', 84, NULL, 85, NULL);
/*!40000 ALTER TABLE `theme` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.theme_product
DROP TABLE IF EXISTS `theme_product`;
CREATE TABLE IF NOT EXISTS `theme_product` (
  `theme_id` int(11) NOT NULL COMMENT '主题外键',
  `product_id` int(11) NOT NULL COMMENT '商品外键',
  PRIMARY KEY (`theme_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='主题所包含的商品';

-- Dumping data for table my_ecom_db.theme_product: ~27 rows (approximately)
/*!40000 ALTER TABLE `theme_product` DISABLE KEYS */;
REPLACE INTO `theme_product` (`theme_id`, `product_id`) VALUES
	(1, 2),
	(1, 5),
	(1, 8),
	(1, 10),
	(1, 12),
	(2, 1),
	(2, 2),
	(2, 3),
	(2, 5),
	(2, 6),
	(2, 16),
	(2, 33),
	(3, 15),
	(3, 18),
	(3, 19),
	(3, 27),
	(3, 30),
	(3, 31),
	(7, 9),
	(7, 10),
	(7, 11),
	(7, 12),
	(10, 3),
	(10, 4),
	(10, 5),
	(11, 3),
	(11, 4),
	(11, 5);
/*!40000 ALTER TABLE `theme_product` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.third_app
DROP TABLE IF EXISTS `third_app`;
CREATE TABLE IF NOT EXISTS `third_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(64) NOT NULL COMMENT '应用app_id',
  `app_secret` varchar(64) NOT NULL COMMENT '应用secret',
  `app_description` varchar(100) DEFAULT NULL COMMENT '应用程序描述',
  `scope` varchar(20) NOT NULL COMMENT '应用权限',
  `scope_description` varchar(100) DEFAULT NULL COMMENT '权限描述',
  `delete_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='访问API的各应用账号密码表';

-- Dumping data for table my_ecom_db.third_app: ~2 rows (approximately)
/*!40000 ALTER TABLE `third_app` DISABLE KEYS */;
REPLACE INTO `third_app` (`id`, `app_id`, `app_secret`, `app_description`, `scope`, `scope_description`, `delete_time`, `update_time`) VALUES
	(1, 'starcraft', '777*777', 'CMS', '32', 'Super', NULL, NULL),
	(2, 'root', '123456', 'lin_cms', '32', 'Super', NULL, NULL);
/*!40000 ALTER TABLE `third_app` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(50) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `extend` varchar(255) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL COMMENT '注册时间',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `openid` (`openid`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.user: ~0 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
REPLACE INTO `user` (`id`, `openid`, `nickname`, `extend`, `delete_time`, `create_time`, `update_time`) VALUES
	(60, 'oAyqR4oL6I0w7X3_TFVGZnB7S-Ds', 'mark mei', '{"avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/j2icvc8yDS9oEg1prZQoD1vSWO2YHKAUgJRYRfeTzdiaibQVNJnEhXSRvFcTfyVWMBlDnK789VCicsr03bqf1YZqWg/132"}', NULL, 1590011230, 1590011230);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for table my_ecom_db.user_address
DROP TABLE IF EXISTS `user_address`;
CREATE TABLE IF NOT EXISTS `user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL COMMENT '收获人姓名',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `province` varchar(20) DEFAULT NULL COMMENT '省',
  `city` varchar(20) DEFAULT NULL COMMENT '市',
  `country` varchar(20) DEFAULT NULL COMMENT '区',
  `detail` varchar(100) DEFAULT NULL COMMENT '详细地址',
  `delete_time` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT '外键',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table my_ecom_db.user_address: ~2 rows (approximately)
/*!40000 ALTER TABLE `user_address` DISABLE KEYS */;
REPLACE INTO `user_address` (`id`, `name`, `mobile`, `province`, `city`, `country`, `detail`, `delete_time`, `user_id`, `update_time`) VALUES
	(35, '张三', '013342186975', '广东省', '广州市', '海珠区', '新港中路397号', NULL, 58, NULL),
	(36, '张三', '013342186975', '广东省', '广州市', '海珠区', '新港中路397号', NULL, 60, NULL);
/*!40000 ALTER TABLE `user_address` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
