-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 06 月 04 日 16:45
-- 服务器版本: 5.5.46
-- PHP 版本: 5.5.35-1+donate.sury.org~precise+4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `hct_manage`
--

-- --------------------------------------------------------

--
-- 表的结构 `hct_access`
--

CREATE TABLE IF NOT EXISTS `hct_access` (
  `sector_id` smallint(5) unsigned NOT NULL,
  `node_id` smallint(5) unsigned NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='权限表,目前只和部门对应';

--
-- 转存表中的数据 `hct_access`
--

INSERT INTO `hct_access` (`sector_id`, `node_id`, `level`) VALUES
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 3),
(1, 5, 3),
(1, 6, 3),
(1, 33, 3),
(2, 1, 1),
(2, 2, 2),
(2, 3, 3),
(2, 4, 3),
(2, 5, 3),
(2, 6, 3),
(2, 33, 3),
(4, 1, 1),
(4, 2, 2),
(4, 3, 3),
(4, 7, 2),
(4, 8, 3),
(4, 9, 3),
(4, 10, 3),
(4, 11, 3),
(4, 34, 3),
(4, 45, 3),
(4, 46, 3),
(4, 24, 2),
(4, 25, 3),
(4, 26, 3),
(4, 27, 3),
(4, 28, 2),
(4, 29, 3),
(4, 30, 3),
(4, 31, 3),
(4, 32, 3),
(4, 35, 2),
(4, 36, 3),
(4, 37, 2),
(4, 39, 3),
(4, 38, 3),
(4, 49, 1),
(4, 50, 2),
(4, 51, 3),
(4, 52, 3),
(4, 53, 3),
(4, 54, 3);

-- --------------------------------------------------------

--
-- 表的结构 `hct_attachment`
--

CREATE TABLE IF NOT EXISTS `hct_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` int(10) unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `hct_build`
--

CREATE TABLE IF NOT EXISTS `hct_build` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` int(10) NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL,
  `wait_id` int(10) unsigned NOT NULL,
  `stat` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '用于记录是否成功',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `more` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '状态或者编译好的路径,或者出错的日志',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `hct_build`
--

INSERT INTO `hct_build` (`id`, `ip`, `start_time`, `end_time`, `user_id`, `wait_id`, `stat`, `name`, `path`, `more`) VALUES
(1, -1062730948, 1465021773, 1465021795, 118, 1, 7, 'test-01', '6753_c2k_alios3.2.0/t625_36cpt_256g16g/t625g-dh-g435-36cpt-hd-256g16g-alios-c2k-volte', '6753_c2k_alios3.2.0/t625_36cpt_256g16g/t625g-dh-g435-36cpt-hd-256g16g-alios-c2k-volte.tar.gz'),
(2, -1062730948, 1465021943, 1465021965, 118, 5, 7, 'test-01', '6753_c2k_alios3.2.0/t625_36cpt_128g24g/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte-ruku', '6753_c2k_alios3.2.0/t625_36cpt_128g24g/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte-ruku.tar.gz'),
(3, -1062730948, 1465022444, 1465022466, 118, 6, 7, 'test-01', '6753_c2k_alios3.2.0/t625_36cpt_128g24g/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte-ruku', '6753_c2k_alios3.2.0/t625_36cpt_128g24g/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte-ruku.tar.gz');

-- --------------------------------------------------------

--
-- 表的结构 `hct_confirm_task`
--

CREATE TABLE IF NOT EXISTS `hct_confirm_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `task_id` int(10) unsigned NOT NULL,
  `faedebug_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `to` int(10) unsigned NOT NULL COMMENT '提交者id',
  `cc` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '抄送者id',
  `comment` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注',
  `subs` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fae确认版本记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `hct_faedebug`
--

CREATE TABLE IF NOT EXISTS `hct_faedebug` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `faeenv_id` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `task_id` int(10) unsigned NOT NULL,
  `faeuser_id` int(10) unsigned NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `isok` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `hct_faedebug`
--

INSERT INTO `hct_faedebug` (`id`, `user_id`, `faeenv_id`, `time`, `task_id`, `faeuser_id`, `path`, `attachment`, `comment`, `isok`) VALUES
(1, 11, 181, 1463368646, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038\r\n', '', '', 0),
(2, 11, 181, 1463368681, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038\r\n', '', '', 0),
(3, 11, 181, 1463368777, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038\r\n', '', '', 0),
(4, 11, 181, 1463368993, 0, 0, 'where(''`id`=''.$data[''faeenv_id''])', '', '', 0),
(5, 11, 181, 1463369202, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038 ', '', '', 0),
(6, 11, 181, 1463369238, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038', '', '', 0),
(7, 11, 181, 1463369632, 0, 0, 'reply_count', '', '', 0),
(8, 11, 181, 1463369933, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038 ', '', '', 0),
(9, 11, 181, 1463370402, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038 ', '', '', 0),
(10, 11, 178, 1463370546, 0, 0, 'weewwewqefweqwe', '', '', 0),
(11, 117, 180, 1463985302, 0, 0, '这是测试邮件.................................', '', '', 1),
(12, 117, 180, 1463986115, 0, 0, '还是测试 .....................................', '', '', 1),
(13, 117, 180, 1463986212, 0, 0, '这是测试邮件.................................', '', '', 1),
(14, 117, 180, 1463986582, 0, 0, '这是测试邮件.................................', '', '', 1),
(15, 117, 180, 1463986730, 0, 0, '这是测试邮件.................................', '', '', 1),
(16, 117, 173, 1464006603, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/tsf_request/20160517/T625o-oq-s10-6735_c2k_alios3.2.0-ov8856-ob64-wanghuadong', '', '只合入申请的tsf文件CAM（ov8856）临时效果版本路径，tsf代码提供的路径为：smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/tsf_request/20160517/T625o-oq-s10-6735_c2k_alios3.2.0-ov8856-ob64-wanghuadong\r\n', 0),
(17, 118, 173, 1464703180, 0, 1, '这是测试.中aaaxxxxxxxxxxxxxxxxxxx', '', 'xxxxxxxxxxxxxxx', 0),
(19, 118, 181, 1464757171, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038 ', '', '', 0),
(20, 118, 181, 1464757176, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038 ', '', '', 0),
(21, 118, 181, 1464757182, 0, 0, 'smb://192.168.1.50/6580-we-a-l-mp6-v2.19/临时效果软件/20160516/t823a-dh-g430d-63u-hd-256g24g-bom70-volte-Algeria-m_cam_05-16_1038 ', '', '', 0),
(23, 118, 179, 1464851853, 0, 0, 'GFDDFGSFGSDSDFGASDFGASDAFGDFASSDFGH', '', 'DFSA\r\nFSDFASDFSADF\r\nSADF\r\nSADF\r\nASDF\r\nSADFSADFASDFASDF\r\nDFGDSFGASDFGASDF\r\n', 1);

-- --------------------------------------------------------

--
-- 表的结构 `hct_faeenv`
--

CREATE TABLE IF NOT EXISTS `hct_faeenv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '项目经理的id',
  `faepc_id` int(10) unsigned NOT NULL COMMENT 'fae区电脑',
  `path` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'fae区的路径',
  `dists` text COLLATE utf8_unicode_ci NOT NULL,
  `sub` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '注释',
  `sort` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reply_time` int(10) unsigned NOT NULL DEFAULT '0',
  `reply_count` smallint(6) unsigned NOT NULL DEFAULT '0',
  `isdel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='FAE区环境表' AUTO_INCREMENT=184 ;

--
-- 转存表中的数据 `hct_faeenv`
--

INSERT INTO `hct_faeenv` (`id`, `date`, `user_id`, `faepc_id`, `path`, `dists`, `sub`, `comment`, `sort`, `reply_time`, `reply_count`, `isdel`) VALUES
(1, '2016-03-05', 100, 1, '/home/ubuntu/code/FAE/t875g-qz-t875-33fpgu-hd-128g16g-fdd-3m-dingding-single', '', '', 'hct6753-65C-l1-mp3-v2.0/dists/targets/t875_33fpgu_128g16g', 0, 0, 0, 0),
(2, '2016-03-05', 101, 2, '/home/liunx/桌面/code/w821ak-bl-f5-wg-hd-64g8g-bom1-gw-B1-RU', '', '', '', 0, 0, 0, 0),
(3, '2016-04-14', 102, 7, '/home/root2/code/FAE/g931bz-hd-r100b-wg-hd-64g8g-bom15', '', '', '', 0, 0, 0, 0),
(4, '2016-01-27', 103, 6, '/home/ubuntu/code/t823ah-jykj-jy899-33fagu-fhd-128g16g-bom21', '', '', '', 0, 0, 0, 0),
(5, '2016-04-21', 101, 1, '/home/ubuntu/code/FAE/t875h-otd-s600-33fgu-fhd-128g24g-bom10', '', '', '', 0, 0, 0, 0),
(6, '2016-01-16', 104, 2, '/home/liunx/code/t89n-ts-t96-33pgu-hd-128g16g', '', '', '', 0, 0, 0, 0),
(7, '2016-03-25', 100, 4, '/home/liunx/code/t875e-cq-h5201c-33fagu-hd-128g24g-fdd-5p', '', '', '', 0, 0, 0, 0),
(8, '2016-01-31', 105, 1, '/home/ubuntu/code/FAE/t525c-yxd-M8-33fpgc-fwvga-64g8g-bom3', '', '', '', 0, 0, 0, 0),
(9, '2016-04-12', 103, 5, '/media/sdb/code/t525f-az-35pgc-hd-128g8g-bom5', '', '', '', 0, 0, 0, 0),
(10, '2016-04-14', 102, 1, '/home/ubuntu/code/FAE/t933bj-yuxin-bl160a-33pgc-hd-128g8g-alios', '', '', '', 0, 0, 0, 0),
(11, '2016-02-26', 105, 9, '/home/gc/code/t99h-haier-i701-65vfgu-4m-fhd-128g16g_53', '', '', 'hct6753_65c_v_l1-mp3-v2.75/dists/targets/t99_65vfgu_128g16g_53/t99h-haier-i701-65vfgu-4m-fhd-128g16g_53', 0, 0, 0, 0),
(12, '2016-04-11', 103, 2, '/home/liunx/code/alio/t525f-az-35pgc-hd-128g8g-bom5-alios-volte', '', '', '', 0, 0, 0, 0),
(13, '2016-04-19', 101, 5, '/media/sdb/code/t925af-wf-zeta-a3-33gu-fhd-128g24g-53-1851', '', '', '', 0, 0, 0, 0),
(14, '2016-01-26', 107, 1, '/home/ubuntu/code/FAE/t875f-oq-t95-33fpgu-hd-128g16g-fdd-3m-5p', '', '', '', 0, 0, 0, 0),
(15, '2016-05-06', 100, 7, '/home/root2/code/FAE/t875b-hs-hs550c-33fpgu-hd-128g16g-fdd-bom25', '', '', '', 0, 0, 0, 0),
(16, '2016-04-05', 103, 1, '/home/ubuntu/code/FAE/t823ae-hk-hk5007-33pgu-hd-64g8g-1851', '', '', '', 0, 0, 0, 0),
(17, '2016-01-05', 106, 10, '/home/fae10/code/code2/t825l-nyx-k1-33pgc-hd-64g8g-tdd-alios-volte', '', '', '6753_c2k_alios3.2.0/dists/targets/t825_33pgc_64g8g/t825l-nyx-k1-33pgc-hd-64g8g-tdd-alios-volte', 0, 0, 0, 0),
(18, '2016-01-14', 101, 10, '/code2/FAE/t525j-ryx-k2-35pgc-hd-128g8g-bom5-alios-volte', '', '', '', 0, 0, 0, 0),
(19, '2016-01-07', 108, 1, '/media/sdb/code/t25y-onix-lt422-35mgu-wvga-64g8g-fdd13720-ctc-bom29', '', '', '', 0, 0, 0, 0),
(20, '2016-01-07', 108, 10, '/code1/FAE/t25y-hs-lt422-35mgu-wvga-32g4g-fdd13720-ctc', '', '', '', 0, 0, 0, 0),
(21, '2016-01-08', 108, 10, '/code2/FAE/j11af-dk-s1-wg-850-2100-hd-64g8g-kk-imi', '', '', 'dists/targets/j11_emmc_wg/j11_emmc_wg_64g8g/j11af-dk-s1-wg-850-2100-hd-64g8g-kk-imi', 0, 0, 0, 0),
(22, '2016-01-09', 0, 5, '/media/sdb/code/t87-cq-saga-m6-33pgc-hd-128g8g-cb-cta', '', '', '验证modem', 0, 0, 0, 0),
(23, '2016-04-15', 102, 4, '/media/sdb/code/g931bm-dg-a37-wg-hd-64g8g-ddr3', '', '', '', 0, 0, 0, 0),
(24, '2016-01-14', 105, 7, '/home/root2/code/FAE/t93e-tiansheng-t81w-33mgu-qhd-64g8g-fdd-bom26-km', '', '', '', 0, 0, 0, 0),
(25, '2016-01-16', 101, 10, '/code2/FAE/t823t-hs-hs501-35fagu-hd-128g8g-bom54-onix', '', '', '', 0, 0, 0, 0),
(26, '2016-01-16', 109, 8, '/home/root2/code/t625b-bl-bl35-36cat-bom14-hd-128g16g-alios', '', '', '', 0, 0, 0, 0),
(27, '2016-01-16', 103, 5, '/home/dev/code/FAE/t823ah-jykj-jy899-33fagu-fhd-128g16g-bom1', '', '', '', 0, 0, 0, 0),
(28, '2016-04-21', 102, 10, '/home/fae10/code/code2/t933bj-yuxin-bv160-33pgc-v-hd-64g8g-alios', '', '', '', 0, 0, 0, 0),
(29, '2016-03-02', 103, 10, '/home/fae10/code/code1/t525b-lf-g420-33cpgc-hd-64g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(30, '2016-01-23', 104, 2, '/home/liunx/code/alio/t89n-ts-t96-33pgc-hd-128g8g-3m-1851', '', '', '', 0, 0, 0, 0),
(31, '2016-01-26', 103, 9, '/media/Disk2/code/t823ap-jykj-jy991-35fpgu-hd-128g8g', '', '', '', 0, 0, 0, 0),
(32, '2016-01-26', 109, 1, '/media/sdb/code/t625a-lg-t625-36cpt-hd-128g8g-alios', '', '', '', 0, 0, 0, 0),
(33, '2016-04-13', 105, 5, '/home/dev/code/FAE/t525h-blf-t6a-35fpgc-hd-64g8g-fdd5m-BOM7-alios-volte', '', '', '', 0, 0, 0, 0),
(34, '2016-01-27', 110, 2, '/home/liunx/code/t823aq-hk5000-33agu-fhd-128g16g-1851', '', '', '', 0, 0, 0, 0),
(35, '2016-02-25', 101, 9, '/home/gc/code/w821ak-bl-f5-wg-hd-64g8g-bom1-gw-B1', '', '', '', 0, 0, 0, 0),
(36, '2016-01-27', 103, 3, '/home/liunx/code/FAE/t823ac-jykj-jy898-33fagu-fhd-128g16g-bom21', '', '', '', 0, 0, 0, 0),
(37, '2016-01-27', 101, 2, '/home/liunx/code/w821t-hs-hs501c-wg-hd-64g8g-bom1-B1', '', '', '6580-we-a-l-mp6-v2.19/dists/targets/w821_wg_64g8g_ddr3/w821t-hs-hs501c-wg-hd-64g8g-bom1-B1', 0, 0, 0, 0),
(38, '2016-01-27', 101, 2, '/home/liunx/code/w821t-hs-hs501d-wg-hd-64g8g-bom2-B1', '', '', '', 0, 0, 0, 0),
(39, '2016-01-29', 106, 8, '/home/root2/code/Ali/t825s-nyx-k3-35pgc-hd-128g8g-alios-volte ', '', '', '', 0, 0, 0, 0),
(40, '2016-01-29', 104, 10, '/home/fae10/code/code2/t89m-tx-m6-33pgc-hd-128g8g-3m-bom29', '', '', '', 0, 0, 0, 0),
(41, '2016-04-12', 111, 6, '/media/sdb/code/g931ac-qz-t551s-wg-hd-128g8g-bom20', '', '', '', 0, 0, 0, 0),
(42, '2016-02-18', 108, 7, '/home/root2/code/FAE/t823k-cq-5171-33fgu-hd-128g16g-fdd5p-zx-1851', '', '', '', 0, 0, 0, 0),
(43, '2016-02-18', 104, 1, '/home/ubuntu/code/FAE/g931ad-zax-s688-wg-hd-64g8g-bom16', '', '', '6580-we-a-l-mp6-v2.19/dists/targets/g931_wg_64g8g_ddr3/g931ad-zax-s688-wg-hd-64g8g-bom16', 0, 0, 0, 0),
(44, '2016-01-15', 101, 2, '/home/liunx/code/w821u-dh-g432a-wg-hd-64g8g-bom3-B1', '', '', '', 0, 0, 0, 0),
(45, '2016-02-20', 101, 3, '/home/liunx/code/FAE/t823t-hs-hs501b-33fpgu-hd-64g8g-fdd-bom117-1851', '', '', 'hct6753-65C-l1-mp3-v2.0/dists/targets/t823_35pgu_64g8g/t823t-hs-hs501b-33fpgu-hd-64g8g-fdd-bom117-1851', 0, 0, 0, 0),
(46, '2016-02-22', 106, 6, '/media/sdb/code/t823l-nyx-k1-33fpgu-hd-128g8g-fdd-1851', '', '', '', 0, 0, 0, 0),
(47, '2016-02-23', 103, 1, '/media/sdb/code/t525c-yxd-M8-33cpgc-fwvga-64g8g-bom3-alios-volte', '', '', '', 0, 0, 0, 0),
(48, '2016-02-23', 102, 6, '/home/ubuntu/code/t933bj-yuxin-bv160a-33pgc-v-hd-128g8g-alios', '', '', '', 0, 0, 0, 0),
(49, '2016-02-25', 108, 5, '/home/dev/code/FAE/t635a-dh-g438-33pgc-hd-64g8g-alios-bom2-tiepian', '', '', '', 0, 0, 0, 0),
(50, '2016-02-25', 106, 6, '/home/ubuntu/code/t93z-kst-33mgu-fwvga-64g8g-fdd24571217-bom77-claro', '', '', '', 0, 0, 0, 0),
(51, '2016-03-01', 102, 10, '/code1/FAE/t933ba-dh-g429-33fagc-hd-64g8g-newpa', '', '', '', 0, 0, 0, 0),
(52, '2016-03-01', 103, 2, '/home/liunx/code/alio/t525k-ch-p01-35pgc-hd-128g16g-tdd-bom13-alios-volte', '', '', '', 0, 0, 0, 0),
(53, '2016-03-02', 107, 5, '/media/sdb/code/t525l-blf-t6pro-35pgc-hd-128g8g-bom9-alios-volte-kh', '', '', '', 0, 0, 0, 0),
(54, '2016-04-13', 107, 6, '/media/sdb/code/t826u-dh-g432-34agu-hd-128g24g', '', '', 'hct6753-65t-m0-mp1-v2.39.1/dists/targets/t826_34agu_128g24g/t826u-dh-g432-34agu-hd-128g24g', 0, 0, 0, 0),
(55, '2016-03-03', 106, 4, '/home/liunx/code/w800f-dkgj-p4021-wg-wvga-64g8g-bom1', '', '', '', 0, 0, 0, 0),
(56, '2016-03-03', 110, 3, '/media/sdb/code/t825a-dh-g430-35fpgc-hd-128g8g-volte', '', '', '', 0, 0, 0, 0),
(57, '2016-03-04', 104, 4, '/home/liunx/code/t93bn-bmg-4502-33mgu-fwvga-64g8g-bom22-fdd24571217-tigo', '', '', '', 0, 0, 0, 0),
(58, '2016-03-04', 112, 8, '/home/root2/code/t89p-dg-a46-hd-33gu-128g16g', '', '', '', 0, 0, 0, 0),
(59, '2016-03-07', 109, 6, '/home/ubuntu/code/t625b-mg-bl-t625-36cpt-hd-128g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(60, '2016-03-07', 109, 1, '/home/ubuntu/code/FAE/t625e-yx-bl198-hd-36cpt-128g24g-alios-volte', '', '', '', 0, 0, 0, 0),
(61, '2016-04-12', 109, 6, '/home/ubuntu/code/t625h-yg-q5015-36cpt-hd-128g8g-bom17', '', '', '', 0, 0, 0, 0),
(62, '2016-05-05', 109, 10, '/code2/FAE/t625i-um-s910-36cpt-hd-128g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(63, '2016-03-09', 107, 4, '/home/liunx/code/t525j-ryx-ch-k2-35pgc-hd-128g16g-tdd-bom13-alios-volte', '', '', '6753_c2k_alios3.2.0/dists/targets/t525_35pgc_128g16g/T525j-ryx-ch-k2-35pgc-hd-128g16g-tdd-bom13-alios-volte', 0, 0, 0, 0),
(64, '2016-03-17', 102, 10, '/code2/FAE/t823y-dg-a44-33fpgu-hd-256g16g-fdd5m', '', '', '', 0, 0, 0, 0),
(65, '2016-03-12', 100, 4, '/home/liunx/code/t875d-gq-3026-33fagu-hd-128g16g-bom7', '', '', 'hct6753-65C-l1-mp3-v2.0/dists/targets/t875_33fagu_128g16g/t875d-gq-3026-33fagu-hd-128g16g-bom7', 0, 0, 0, 0),
(66, '2016-03-14', 103, 3, '/media/sdb/code/t525f-az-ebest-u5582-33pgc-hd-128g8g-cb-cta', '', '', '', 0, 0, 0, 0),
(67, '2016-03-14', 100, 8, '/home/root2/code/t875b-hs-HS550D-1-33fpgu-hd-128g8g-fdd-bom38', '', '', '', 0, 0, 0, 0),
(68, '2016-03-15', 104, 8, '/home/root2/code/t89q-wdl-h1-35cpgc-hd-128g8g-bom31-alios-volte', '', '', '', 0, 0, 0, 0),
(69, '2016-03-15', 111, 10, '/code1/FAE/t93h-klt-v12-33mgu-qhd-128g8g-fdd', '', '', '', 0, 0, 0, 0),
(70, '2016-03-15', 109, 2, '/home/liunx/code/t625b-bl-bl35-36cat-bom14-hd-128g16g-volte', '', '', '', 0, 0, 0, 0),
(71, '2016-04-11', 109, 6, '/media/sdb/code/t625j-dj-d8-5v1a-36cpt-hd-128g8g-alios-volte-bom20', '', '', '', 0, 0, 0, 0),
(72, '2016-03-17', 105, 7, '/home/root2/code/FAE/t93e-tiansheng-t81w-33mgu-qhd-64g8g-fdd-bom26-dz-zhx', '', '', '', 0, 0, 0, 0),
(73, '2016-03-17', 113, 7, '/home/root2/code/FAE/t93aa-otd-s508-33mgu-hd-64g8g-elite-y', '', '', '', 0, 0, 0, 0),
(74, '2016-04-23', 108, 3, '/home/liunx/code/FAE/t635a-dh-g438-33gc-hd-128g8g-alios', '', '', '', 0, 0, 0, 0),
(75, '2016-04-29', 111, 4, '/media/sdb/code/t93ac-qz-t551-35gc-fhd-256g24g-bom80', '', '', 'hct6753-65C-l1-mp3-v2.0/dists/targets/t93_35gc_256g24g_53/t93ac-qz-t551-35gc-fhd-256g24g-bom80', 0, 0, 0, 0),
(76, '2016-03-22', 109, 5, '/home/dev/code/FAE/t625g-dh-g435-36cpt-hd-128g16g-alios-c2k-volte-yepen-1851', '', '', 't625g-dh-g435-36cpt-hd-128g16g-alios-c2k-volte-1851   修改过来的', 0, 0, 0, 0),
(77, '2016-03-17', 106, 8, '/home/root2/code/w800f-dkgj-p4021-wg-wvga-64g8g-bom11', '', '', '', 0, 0, 0, 0),
(78, '2016-03-22', 106, 3, '/home/ubuntu/code/w800d-oq-80A-s06-wg-hd-64g8g-W128', '', '', '', 0, 0, 0, 0),
(79, '2016-03-17', 106, 1, '/media/sdb/code/w800e-ot-t841-wg-fwvga-64g8g-hd-w125-bom6', '', '', '', 0, 0, 0, 0),
(80, '2016-04-05', 113, 6, '/media/Disk2/code/t93bc-hd-f4593b-fwvga-64g8g-bom78-logicom', '', '', 'hct6753-65t-m0-mp1-v2.39.1/dists/targets/t93_33mgu_64g8g/t93bc-hd-f4593b-fwvga-64g8g-bom78-logicom', 0, 0, 0, 0),
(81, '2016-03-18', 112, 5, '/home/dev/code/FAE/t89b-otd-s557-cronic-hd-33gu-128g16g', '', '', '', 0, 0, 0, 0),
(82, '2016-04-21', 110, 5, '/home/dev/code/FAE/t823aw-jka7s-35fpgu-hd-64g8-bom117', '', '', '', 0, 0, 0, 0),
(83, '2016-05-10', 106, 3, '/home/liunx/code/FAE/w800j-jk-a5-fwvga-64g8g-w800_mbb1_bom9_v01', '', '', '(3月28更新）', 0, 0, 0, 0),
(84, '2016-03-19', 106, 3, '/home/liunx/code/FAE/w800h-ot-t891-wg-fwvga-64g4g-gsm4w2-bom8', '', '', '', 0, 0, 0, 0),
(85, '2016-03-19', 113, 3, '/media/sdb/code/t93p-dk-p5015l-33fpgu-qhd-32g4g-bom82', '', '', '', 0, 0, 0, 0),
(86, '2016-03-19', 106, 4, '/home/liunx/code/w800g-ot-t851-wg-fwvga-64g4g-gsm4w2-W800_MBB1_BOM7_V02', '', '', '', 0, 0, 0, 0),
(87, '2016-03-19', 106, 4, '/media/sdb/code/w800e-ot-t841-wg-fwvga-64g8g-hd-w125-bom6', '', '', '', 0, 0, 0, 0),
(88, '2016-03-19', 108, 6, '/home/ubuntu/code/t635h-yxd-t635-33pgc-64g8g-alios-volte', '', '', '4-9号更新了', 0, 0, 0, 0),
(89, '2016-04-20', 110, 2, '/home/liunx/code/t823a-dh-g430b-33gu-hd-128g16g', '', '', '', 0, 0, 0, 0),
(90, '2016-04-12', 112, 5, '/home/dev/code/FAE/t89b-otd-s557-nm-hd-33gu-128g16g', '', '', '', 0, 0, 0, 0),
(91, '2016-03-28', 100, 10, '/code2/t875k-oq-s02-33gu-fhd-256g24g-fdd-3m-5p-M', '', '', '', 0, 0, 0, 0),
(92, '2016-03-23', 102, 10, '/code1/FAE/t825p-bl-bl12-35fpgc-hd-128g8g-jw5001', '', '', '', 0, 0, 0, 0),
(93, '2016-03-23', 109, 1, '/home/ubuntu/code/FAE/t625e-yx-bl198-hd-36cpt-128g16g-alios-volte', '', '', '', 0, 0, 0, 0),
(94, '2016-04-13', 108, 10, '/home/fae10/code/code1/t635b-lf-g426-33pgc-fwvga-64g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(95, '2016-03-24', 112, 8, 'hct6735m-65C-l1-mp3-v1.0/dists/targets/t89_35mgu_128g16g/t89b-otd-s557-hd-33gu-128g16g', '', '', '', 0, 0, 0, 0),
(96, '2016-03-24', 112, 10, '/code1/FAE/t89b-otd-s557-cronic-hd-33gu-128g16g', '', '', '', 0, 0, 0, 0),
(97, '2016-03-24', 112, 10, '/code1/FAE/t89j-jk-Hyundai-hd-33pgu-128g16g', '', '', '', 0, 0, 0, 0),
(98, '2016-03-24', 106, 1, '/home/ubuntu/code/FAE/w800g-ot-t851-wg-fwvga-64g4g-gsm4w2-W800_MBB1_BOM8_V01', '', '', '', 0, 0, 0, 0),
(99, '2016-03-24', 106, 5, '/media/sdb/code/w800e-ot-t841-wg-fwvga-64g4g-gsm4w2-W800_MBB1_BOM8_V01', '', '', '', 0, 0, 0, 0),
(100, '2016-03-24', 106, 5, '/home/dev/code/FAE/w800h-ot-t891-wg-fwvga-64g4g-gsm4w2-bom7', '', '', '', 0, 0, 0, 0),
(101, '2016-03-24', 106, 7, '/home/root2/code/FAE/w800j-jk-a5-fwvga-64g8g-w800_mbb1_bom10_v02', '', '', '', 0, 0, 0, 0),
(102, '2016-04-12', 114, 1, '/media/sdb/code/t637a-dh-g438a-33pgu-64g8g-hd-bom3-eur', '', '', '', 0, 0, 0, 0),
(103, '2016-03-25', 101, 10, '/home/fae10/code/code1/t925af-wf-zeta-a3-33gu-fhd-128g24g-53-1851', '', '', '', 0, 0, 0, 0),
(104, '2016-03-25', 103, 3, '/home/liunx/code/FAE/t525m-xy-h01a-33cpgc-hd-64g8g-bom17-alios-volte', '', '', '', 0, 0, 0, 0),
(105, '2016-03-25', 100, 5, '/home/dev/code/FAE/t875c-daoge-a43b-33fpgu-hd-128g16g-fdd-bom27', '', '', '', 0, 0, 0, 0),
(106, '2016-03-26', 114, 1, '/media/sdb/code/t985d-haidi-r200-35vfgu-4m-hd-128g16g ', '', '', '', 0, 0, 0, 0),
(107, '2016-03-26', 113, 5, '/home/dev/code/FAE/t93ab-dk-p5512d-64g8g-hd-logicom', '', '', '', 0, 0, 0, 0),
(108, '2016-04-25', 113, 7, '/home/root2/code/FAE/t93bd-dk-p5018d-hd-64g8g-bom49-logicom', '', '', '', 0, 0, 0, 0),
(109, '2016-03-28', 111, 1, '/home/ubuntu/code/FAE/t93u-bmg-4501-35mgu-hd-128g8g-bom67-fdd137820-india', '', '', '', 0, 0, 0, 0),
(110, '2016-03-28', 106, 4, '/home/liunx/code/t823l-nyx-k1-33fpgu-hd-128g8g-fdd-1851-bom54', '', '', '', 0, 0, 0, 0),
(111, '2016-03-29', 113, 5, '/home/dev/code/FAE/t93aa-otd-s508-33mgu-hd-64g8g-fdd137820', '', '', '', 0, 0, 0, 0),
(112, '2016-04-19', 106, 5, '/home/dev/code/FAE/w800a-dkgj-p5019-wg-hd-64g8g-bom1', '', '', '', 0, 0, 0, 0),
(113, '2016-03-30', 109, 6, '/media/sdb/code/t925l-sb-tetc-35pgc-hd-128g8g-tdd-alios-volte', '', '', '', 0, 0, 0, 0),
(114, '2016-03-31', 110, 9, '/media/Disk2/code/t823u-dh-g432-33pgu-hd-128g8g-bom115', '', '', '', 0, 0, 0, 0),
(115, '2016-03-31', 105, 1, '/media/sdb/code/t99h-haier-i701-65vfgu-3m-fhd-128g16g_53_bom41', '', '', '', 0, 0, 0, 0),
(116, '2016-04-08', 110, 4, '/media/sdb/code/t635d-oq-s08-33pgc-fwvga-64g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(117, '2016-04-05', 111, 9, '/home/gc/code/t93h-klt-v12-33mgu-hd-128g8g-fdd', '', '', '', 0, 0, 0, 0),
(118, '2016-04-06', 109, 6, '/home/ubuntu/code/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte', '', '', '', 0, 0, 0, 0),
(119, '2016-04-06', 108, 9, '/media/Disk2/code/t635h-yxd-t635-33pgc-fwvga-64g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(120, '2016-04-06', 113, 3, '/media/sdb/code/t215b-lf-lephone-c7-34cpt-fwvga-64g8g-cb-cta ', '', '', '', 0, 0, 0, 0),
(121, '2016-04-06', 113, 3, '/media/sdb/code/t215b-lf-lephone-c2-34cpt-fwvga-64g8g-cb-cta', '', '', '', 0, 0, 0, 0),
(122, '2016-04-06', 113, 5, '/home/dev/code/FAE/t215b-lf-lephone-c9-34cpt-fwvga-64g8g-cb-cta', '', '', '', 0, 0, 0, 0),
(123, '2016-04-25', 113, 4, '/home/liunx/code/t93bd-dk-p5018e-33pgu-hd-128g8g-bom85-logicom', '', '', '', 0, 0, 0, 0),
(124, '2016-04-21', 110, 6, '/media/sdb/code/t823am-jka6-plus-33fpgu-hd-128g16-bom32_IC1851', '', '', '', 0, 0, 0, 0),
(125, '2016-04-11', 112, 3, '/media/sdb/code/t89j-jk-bv5000-33mgu-hd-128g16g-fdd3m-bom21', '', '', '', 0, 0, 0, 0),
(126, '2016-04-11', 108, 2, '/home/liunx/code/t637f-tyc-x12-33pgu-64g8g-hd-bom6', '', '', '', 0, 0, 0, 0),
(127, '2016-04-12', 114, 5, '/home/dev/code/FAE/t985d-hd-r200c-hd-35pgu-128g16g-bom36', '', '', '', 0, 0, 0, 0),
(128, '2016-04-12', 106, 7, '/home/root2/code/FAE/t823l-nyx-k1-33fagu-hd-128g8g-fdd-bom54', '', '', '', 0, 0, 0, 0),
(129, '2016-04-12', 112, 8, '/home/root2/code/w821t-hs-w502b-spot-wg-hd-64g8g-bom2-B1', '', '', '', 0, 0, 0, 0),
(130, '2016-04-12', 111, 3, '/media/sdb/code/t93z-kst-33mgu-fwvga-64g8g-fdd137-bom83', '', '', '', 0, 0, 0, 0),
(131, '2016-04-12', 114, 3, '/home/liunx/code/FAE/t637a-dh-g438a-33pgu-hd-64g8g-bom5', '', '', '', 0, 0, 0, 0),
(132, '2016-05-07', 101, 4, '/home/liunx/code/t823t-hs-hs501-33fpgu-hd-64g8g-fdd-bom20-innjoo-1851', '', '', '', 0, 0, 0, 0),
(133, '2016-04-13', 112, 5, '/media/sdb/code/w821bc-hw-y2m-wg-hd-128g8g-bom4', '', '', '', 0, 0, 0, 0),
(134, '2016-04-14', 103, 3, '/home/liunx/code/FAE/t525k-ch-p01-35pgc-hd-128g16g-tdd-bom20-alios-volte', '', '', '', 0, 0, 0, 0),
(135, '2016-04-14', 114, 8, '/home/root2/code/t637e-ps-a1-33pgu-128g8g-hd-bom8', '', '', '', 0, 0, 0, 0),
(136, '2016-04-15', 102, 5, '/media/sdb/code/t93bm-dg-a37f--33pgu-hd-64g8g-fdd137820-dg', '', '', '', 0, 0, 0, 0),
(137, '2016-04-18', 112, 3, '/home/liunx/code/FAE/t89j-jk-bv5000-hd-33pgu-128g16g-1851', '', '', '', 0, 0, 0, 0),
(138, '2016-04-18', 114, 8, '/home/root2/code/t93k-xingfei-bk-33fagu-hd-256g16g-w8', '', '', '', 0, 0, 0, 0),
(139, '2016-04-19', 113, 4, '/home/liunx/code/t215b-blf-c9-4m-fwvga-34cpt-64g8g-alios', '', '', '', 0, 0, 0, 0),
(140, '2016-04-19', 108, 4, '/home/liunx/code/t635c-dh-g439-33pgc-hd-128g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(141, '2016-04-19', 108, 8, '/home/root2/code/t255aa_dkgj_p4018_35vpgu-wvga-64g8g', '', '', '', 0, 0, 0, 0),
(142, '2016-04-19', 112, 9, '/home/gc/code/t89p-dg-a46-33mgu-hd-128g16g-fdd3m-bom30', '', '', '', 0, 0, 0, 0),
(143, '2016-04-22', 108, 5, '/home/dev/code/FAE/t637-atks-s69-33pgu-hd-64g8g-bom6', '', '', '', 0, 0, 0, 0),
(144, '2016-04-23', 103, 5, '/home/dev/code/FAE/t823k-cq-5171-33pgu-hd-128g16g-bom64', '', '', '', 0, 0, 0, 0),
(145, '2016-04-23', 100, 4, '/home/liunx/code/t825aa-yg-q511-d-35agc-hd-128g16g-alios-volte', '', '', '', 0, 0, 0, 0),
(146, '2016-04-23', 108, 4, '/home/liunx/code/t635a-dh-g438-33pgc-hd-128g16g-alios-volte', '', '', '', 0, 0, 0, 0),
(147, '2016-04-23', 111, 5, '/home/dev/code/FAE/c115b-dk-s1170-wg-wvga-32g4g-solo-bom13', '', '', '', 0, 0, 0, 0),
(148, '2016-04-25', 112, 1, '/home/ubuntu/code/FAE/w821ax-ot-t921-wg-hd-64g8g-gsmw2-bom5-EUR ', '', '', '', 0, 0, 0, 0),
(149, '2016-04-25', 112, 1, '/home/ubuntu/code/FAE/w821ax-ot-t921-wg-hd-64g8g-gsmw2-bom6-US', '', '', '', 0, 0, 0, 0),
(150, '2016-04-25', 112, 9, '/media/Disk2/code/t625l-dh-g501-36cpt-hd-128g8g-alios-volte-bom29', '', '', '', 0, 0, 0, 0),
(151, '2016-04-25', 103, 6, '/home/ubuntu/code/t525m-xinyu-h01a-hd-33pgc-128g16g-alios-volte-bom21', '', '', '', 0, 0, 0, 0),
(152, '2016-04-26', 109, 4, '/media/sdb/code/t625-ps-a1-35gt-hd-512g24g-bom28-volte', '', '', '', 0, 0, 0, 0),
(153, '2016-04-26', 109, 4, '/home/liunx/code/t625a-lg-a1-36cpt-hd-256g16g-bom27-alios-c2k-volte', '', '', '', 0, 0, 0, 0),
(154, '2016-04-26', 109, 8, '/home/root2/code/t625m-yg-q557-36cmt-qhd-128g8g-bom30', '', '', '', 0, 0, 0, 0),
(155, '2016-04-26', 109, 10, '/home/fae10/code/code1/t625b-km-w08-36cpt-hd-128g8g-alios-volte', '', '', '', 0, 0, 0, 0),
(156, '2016-04-26', 102, 6, '/WORK/code/t826p-dm-dm12-33fpgu-hd-128g16g-fps1198S', '', '', '', 0, 0, 0, 0),
(157, '2016-04-27', 112, 4, '/media/sdb/code/t89j-jk-bv5000-63mgu-hd-128g16g-fdd3m-bom21', '', '', '', 0, 0, 0, 0),
(158, '2016-04-27', 105, 4, '/media/sdb/code/t525c-yxd-t525-33cpgc-hd-128g16g-bom22-alios-volte', '', '', '', 0, 0, 0, 0),
(159, '2016-04-28', 103, 5, '/media/sdb/code/t823k-cq-5171-33gu-fhd-256g24g-bom52 ', '', '', '', 0, 0, 0, 0),
(160, '2016-04-28', 100, 2, '/home/liunx/code/t875d-gq-3026-63fu-fhd-128g24g-bom6-m', '', '', '', 0, 0, 0, 0),
(161, '2016-04-28', 111, 8, '/home/root2/code/c115c-dk-s1160-wg-fwvga-64g8g-bom5', '', '', '', 0, 0, 0, 0),
(162, '2016-04-28', 113, 10, '/code1/FAE/t93aa-otd-s508-33pgu-hd-64g8g-bom53-leagoo-elite-y', '', '', '', 0, 0, 0, 0),
(163, '2016-04-28', 110, 2, '/home/liunx/code/t823a-dh-g430b-65u-hd-128g16g', '', '', '', 0, 0, 0, 0),
(164, '2016-04-29', 105, 2, '/home/liunx/code/t525h-blf-t6a-33cpgc-hd-64g8g-T525_MBA1_BOM19_V01-alios-volte', '', '', '', 0, 0, 0, 0),
(165, '2016-05-03', 111, 3, '/media/sdb/code/t93ad-zax-s68-ms-33fpgu-hd-64g8g-bom62', '', '', '', 0, 0, 0, 0),
(166, '2016-05-04', 103, 9, '/media/Disk2/code/t525d-jlt-35pgc-hd-128g8g-bom11-volte', '', '', '', 0, 0, 0, 0),
(167, '2016-05-04', 103, 9, '/home/gc/code/t823bc-hw-y2-33fpgu-hd-128g16g', '', '', '', 0, 0, 0, 0),
(168, '2016-05-04', 109, 10, '/home/fae10/code/code2/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte', '', '', '6号机器那个环境周围改动', 0, 0, 0, 0),
(169, '2016-05-05', 113, 10, '/home/fae10/code/code1/t215a-blf-c7-4m-fwvga-34cpt-64g8g-alios', '', '', '', 0, 0, 0, 0),
(170, '2016-05-06', 112, 6, '/media/sdb/code/w821ak-bl-f5-wg-hd-64g8g-bom1-gw-B1-SLK', '', '', '', 0, 0, 0, 0),
(171, '2016-05-06', 114, 10, '/home/fae10/code/code2/t985d-hd-r200c-hd-65pu-128g16g-bom36', '', '', '', 0, 0, 0, 0),
(172, '2016-05-07', 108, 9, '/home/gc/code/t635a-dh-g438-33gc-hd-128g8g-alios-tiepian', '', '', '', 0, 0, 0, 0),
(173, '2016-05-07', 111, 10, '/home/fae10/code/code2/w800h-ot-t891-wg-fwvga-64g4g-gsm4w2-bom8-tiepian', '', '', '', 0, 1464750473, 3, 0),
(174, '2016-05-07', 113, 10, '/home/fae10/code/code2/t93t-dk-p5512-33mgu-qhd-64g8g-fdd3720-logicom', '', '', '', 0, 0, 0, 0),
(175, '2016-05-09', 111, 10, '/home/fae10/code/code2/t93ac-qz-t551-35gc-fhd-256g24g-bom80-3d', '', '', '', 0, 0, 0, 0),
(176, '2016-05-10', 108, 6, '/home/ubuntu/code/t635a-dh-g438-33gc-hd-128g8g-alios', '', '', '', 0, 0, 0, 0),
(177, '2016-05-11', 110, 10, '/home/fae10/code/code2/t823k-cq-5171-63au-hd-128g24g-fdd5p-zx-1851-m', '', '', '', 0, 0, 0, 0),
(178, '2016-05-11', 114, 10, '/home/fae10/code/code2/t87a-oq-t92-33pgc-hd-128g16g-tdd-alios-volte-3m-3p', '', '', '', 0, 1463370546, 1, 0),
(179, '2016-05-12', 102, 10, '/home/fae10/code/code1/t633b-hd-laaboo-v9-33gc-hd-64g8g-alios-37-vi-cta', '', '', 'sdfsdf', 0, 1464851853, 2, 0),
(180, '2016-05-12', 102, 10, '/home/fae10/code/code2/t633b-hdcx-33g-fwvga-64g8g-37-vilte', '', '', '', 0, 1463986730, 5, 0),
(181, '2016-05-12', 102, 9, '/home/gc/code/t633a-ryx-x12-33g-hd-64g8g-37-vi', '', '', '', 0, 1464757182, 11, 0),
(182, '2016-05-30', 12, 4, 'testtesttesttesttesttesttesttesttesttesttesttest', '', '', '', 0, 0, 0, 1),
(183, '2016-05-30', 12, 3, '6580/dddddddddddddddddd/dddddddddddddd', '', '', '', 0, 1464590297, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `hct_faepc`
--

CREATE TABLE IF NOT EXISTS `hct_faepc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `faepc` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `faePC` (`faepc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fae区机器列表' AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `hct_faepc`
--

INSERT INTO `hct_faepc` (`id`, `faepc`, `comment`) VALUES
(1, 'FAE01', ''),
(2, 'FAE02', ''),
(3, 'FAE03', ''),
(4, 'FAE04', ''),
(5, 'FAE05', ''),
(6, 'FAE06', ''),
(7, 'FAE07', ''),
(8, 'FAE08', ''),
(9, 'FAE09', ''),
(10, 'FAE10', '');

-- --------------------------------------------------------

--
-- 表的结构 `hct_faeuser`
--

CREATE TABLE IF NOT EXISTS `hct_faeuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pinyin` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `last_id` int(10) unsigned NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fae登记的表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `hct_faeuser`
--

INSERT INTO `hct_faeuser` (`id`, `name`, `pinyin`, `company`, `email`, `phone`, `time`, `last_id`, `comment`) VALUES
(1, 'xxxxxxx', 'xxxxxxx', '', '', '', 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `hct_fae_id`
--

CREATE TABLE IF NOT EXISTS `hct_fae_id` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `fae_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='每天登记的fae记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `hct_fae_record`
--

CREATE TABLE IF NOT EXISTS `hct_fae_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `faeuser_id` int(10) unsigned NOT NULL DEFAULT '0',
  `time` int(10) unsigned NOT NULL,
  `type` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `lunch` tinyint(4) NOT NULL DEFAULT '0',
  `dinner` tinyint(4) NOT NULL DEFAULT '0',
  `task_ids` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fae到访记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `hct_msg`
--

CREATE TABLE IF NOT EXISTS `hct_msg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否读取,0未读,1已读',
  `type` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `msg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `task_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `hct_node`
--

CREATE TABLE IF NOT EXISTS `hct_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `show` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- 转存表中的数据 `hct_node`
--

INSERT INTO `hct_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`, `show`) VALUES
(1, 'Home', '前台应用', 1, NULL, 1, 0, 1, 1),
(2, 'Task', '任务管理', 1, NULL, 10, 1, 2, 1),
(3, 'index', '任务列表', 1, NULL, 10, 2, 3, 1),
(4, 'addTask', '发布任务', 1, NULL, 10, 2, 3, 1),
(5, 'editTask', '修改任务', 1, NULL, 10, 2, 3, 1),
(6, 'delTask', '删除任务', 1, NULL, 10, 2, 3, 1),
(7, 'FaeEnv', 'FAE环境管理', 1, NULL, 10, 1, 2, 1),
(8, 'index', 'FAE环境列表', 1, NULL, 10, 7, 3, 1),
(9, 'addFaeEnv', '添加FAE环境', 1, NULL, 10, 7, 3, 1),
(10, 'editFaeEnv', '修改FAE环境', 1, NULL, 10, 7, 3, 1),
(11, 'delFaeEnv', '删除FAE环境', 1, NULL, 10, 7, 3, 1),
(17, 'poyjfgnfg', '345weftg', 1, NULL, 10, 16, 2, 1),
(53, 'editBuild', '编辑等待编译项目', 1, NULL, 10, 50, 3, 1),
(24, 'Debug', 'FAE调试管理', 1, NULL, 10, 1, 2, 1),
(25, 'addDebug', '增加调试记录', 1, NULL, 10, 24, 3, 1),
(26, 'editDebug', '编辑调试记录', 1, NULL, 10, 24, 3, 1),
(27, 'delDebug', '删除调试记录', 1, NULL, 10, 24, 3, 1),
(28, 'FaePC', 'FAE电脑管理', 1, NULL, 10, 1, 2, 1),
(29, 'index', 'FAE电脑列表', 1, NULL, 10, 28, 3, 1),
(30, 'addFaePC', '添加FAE电脑', 1, NULL, 10, 28, 3, 1),
(31, 'editFaePC', '编辑FAE电脑', 1, NULL, 10, 28, 3, 1),
(32, 'delFaePC', '删除FAE电脑', 1, NULL, 10, 28, 3, 1),
(33, 'confirmTask', '确认临时版本', 1, NULL, 10, 2, 3, 1),
(34, 'addFaeEnvRecord', '增加FAE环境调试记录', 1, NULL, 10, 7, 3, 1),
(35, 'FaeUser', 'FAE人员管理', 1, NULL, 10, 1, 2, 1),
(36, 'addFaeUser', '增加FAE人员', 1, NULL, 10, 35, 3, 1),
(37, 'UserManage', '用户管理', 1, NULL, 10, 1, 2, 1),
(38, 'editUser', '编辑用户', 1, NULL, 10, 37, 3, 1),
(39, 'index', '用户列表', 1, NULL, 9, 37, 3, 1),
(52, 'addBuild', '添加编译项目', 1, NULL, 10, 50, 3, 1),
(51, 'index', '编译服务器列表', 1, NULL, 10, 50, 3, 1),
(50, 'Build', '编译服务器管理', 1, NULL, 10, 49, 2, 1),
(49, 'Gitweb', 'Gitweb', 1, NULL, 10, 0, 1, 1),
(45, 'editFaeEnvRecord', '编辑FAE环境调试记录', 1, NULL, 10, 7, 3, 1),
(46, 'delFaeEnvRecord', '删除FAE环境调试记录', 1, NULL, 10, 7, 3, 1),
(54, 'delBuild', '删除等待编译项目', 1, NULL, 10, 50, 3, 1);

-- --------------------------------------------------------

--
-- 表的结构 `hct_sector`
--

CREATE TABLE IF NOT EXISTS `hct_sector` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sector` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `remark` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='部门列表' AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `hct_sector`
--

INSERT INTO `hct_sector` (`id`, `sector`, `remark`, `comment`) VALUES
(1, '项目', 'pm', ''),
(2, '软测', 'sw_qa', ''),
(3, '硬测', 'hw_qa', ''),
(4, '驱动', 'drv', '');

-- --------------------------------------------------------

--
-- 表的结构 `hct_task`
--

CREATE TABLE IF NOT EXISTS `hct_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sub` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `base` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `reply_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL COMMENT '发布人id',
  `faeenv_id` int(10) unsigned NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `bugfree_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `reply_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `isattachment` tinyint(4) NOT NULL DEFAULT '0',
  `isdel` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='发布任务表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `hct_tasktype`
--

CREATE TABLE IF NOT EXISTS `hct_tasktype` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(3) unsigned NOT NULL,
  `type` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '100',
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='任务种类的分类,level 2为种类,3为模块,4为标签' AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `hct_tasktype`
--

INSERT INTO `hct_tasktype` (`id`, `level`, `type`, `sort`, `color`, `comment`) VALUES
(1, 2, '调试', 10, '', ''),
(2, 2, '客户反馈', 20, '', ''),
(3, 2, '测试问题', 30, '', ''),
(4, 2, '射频干扰', 40, '', ''),
(5, 3, 'LCM', 1, '#0055ff', ''),
(6, 3, 'CAM', 2, '#0055ff', ''),
(7, 3, 'TP', 3, '#0055ff', ''),
(10, 3, '其它', 4, '', ''),
(12, 4, '新建环境', 20, '#fa0000', ''),
(13, 4, '紧急', 10, '#ff0000', ''),
(14, 1, '新问题', 1, '#ff0000', ''),
(15, 1, '进行中', 2, '#50e7db', ''),
(16, 1, '已回复', 3, '#0059ff', ''),
(17, 1, '待确认', 4, '#00ffea', ''),
(19, 1, '已解决', 5, '#00ff00', ''),
(20, 4, '更新环境', 30, '#ff0000', ''),
(21, 1, '已关闭(1)', 6, '#000000', '用户关闭'),
(22, 1, '已关闭(2)', 7, '#000000', '管理员关闭');

-- --------------------------------------------------------

--
-- 表的结构 `hct_task_tasktype`
--

CREATE TABLE IF NOT EXISTS `hct_task_tasktype` (
  `task_id` int(10) unsigned NOT NULL DEFAULT '0',
  `tasktype_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'tasktype 的id',
  UNIQUE KEY `task_id_2` (`task_id`,`tasktype_id`),
  KEY `task_id` (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='任务对应属性,将tasktype 和 task关联';

-- --------------------------------------------------------

--
-- 表的结构 `hct_user`
--

CREATE TABLE IF NOT EXISTS `hct_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pinyin` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sector_id` int(10) unsigned NOT NULL,
  `register_time` int(10) unsigned NOT NULL,
  `login_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `login_passwd` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `email_passwd` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0为正常,1注册,2关闭,3为离职',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='公司员工' AUTO_INCREMENT=120 ;

--
-- 转存表中的数据 `hct_user`
--

INSERT INTO `hct_user` (`id`, `name`, `pinyin`, `email`, `phone`, `sector_id`, `register_time`, `login_name`, `login_passwd`, `email_passwd`, `comment`, `status`) VALUES
(11, '驱动test', 'qd', 'qudong@hcr.com', '18500000000', 4, 1459587269, 'qudong', '123456', '', '', 0),
(100, '张艳玲', '', '', '', 0, 0, '', '', '', '', 0),
(101, '张丽艳', '', '', '', 0, 0, '', '', '', '', 0),
(102, '伍伦东', '', '', '', 0, 0, '', '', '', '', 0),
(103, '刘欢', '', '', '', 0, 0, '', '', '', '', 0),
(104, '李佳岭', '', '', '', 0, 0, '', '', '', '', 0),
(105, '聂新秀', '', '', '', 0, 0, '', '', '', '', 0),
(106, '唐艳妮', '', '', '', 0, 0, '', '', '', '', 0),
(107, '姜武欢', '', '', '', 0, 0, '', '', '', '', 0),
(108, '董瑾', '', '', '', 0, 0, '', '', '', '', 0),
(109, '华飞', '', '', '', 0, 0, '', '', '', '', 0),
(110, '储兰芳', '', '', '', 0, 0, '', '', '', '', 0),
(111, '石雨芳', '', '', '', 0, 0, '', '', '', '', 0),
(112, '陈万昌', '', '', '', 0, 0, '', '', '', '', 0),
(113, '王巧莲', '', '', '', 0, 0, '', '', '', '', 0),
(114, '罗梦佳', '', '', '', 0, 0, '', '', '', '', 0),
(116, '杨润成', 'yrc', 'yangruncheng@hct.sh.cn', '', 4, 1463203582, 'yangruncheng', '123456', 'Hct12345', '', 0),
(117, '李少凯', 'lsk', 'lishaokai@hct.sh.cn', '', 4, 1463203627, 'lishaokai', '123456', 'Hct12345', '', 0),
(118, '周围', 'zw', 'zhouwei@hct.sh.cn', '', 4, 1463985461, 'zhouwei', '123456', 'Hct12345', '', 0),
(119, 'test', 'test', 'test01@test.com', '', 4, 1464696715, 'test', '123456', '', '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `hct_wait_build`
--

CREATE TABLE IF NOT EXISTS `hct_wait_build` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `start_time` int(10) unsigned NOT NULL COMMENT '检查超时用',
  `user_id` int(10) unsigned NOT NULL,
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '优先级',
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `want_pc` int(10) unsigned NOT NULL DEFAULT '0',
  `stat` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1为等待编译,0已完成的,错误的,2,锁定中,3,用户锁定',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `hct_wait_build`
--

INSERT INTO `hct_wait_build` (`id`, `time`, `start_time`, `user_id`, `sort`, `path`, `want_pc`, `stat`) VALUES
(1, 1465016101, 1465021773, 118, 0, '6753_c2k_alios3.2.0/t625_36cpt_256g16g/t625g-dh-g435-36cpt-hd-256g16g-alios-c2k-volte', 0, 0),
(2, 1465019696, 1465020350, 118, 2, '6753_c2k_alios3.2.0/t625_36cpt_256g16g/t625g-dh-g435-36cpt-hd-256g16g-alios-c2k-volte', 0, 2),
(3, 1465020756, 1465020978, 118, 1, '6753_c2k_alios3.2.0/t625_36cpt_256g16g/t625g-dh-g435-36cpt-hd-256g16g-alios-c2k-volte-dx6', 0, 2),
(4, 1465021117, 1465021117, 118, 0, '6753_c2k_alios3.2.0/t625_36cpt_256g16g/t625g-dh-g435-36cpt-hd-256g16g-alios-c2k-volte-dx6', 0, 2),
(5, 1465021943, 1465021943, 118, 0, '6753_c2k_alios3.2.0/t625_36cpt_128g24g/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte-ruku', 0, 0),
(6, 1465022444, 1465022444, 118, 0, '6753_c2k_alios3.2.0/t625_36cpt_128g24g/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte-ruku', 0, 0),
(8, 1465022689, 1465029147, 118, 2, '6753_c2k_alios3.2.0/t625_36cpt_128g24g/t625k-yx-bl167-hd-36cpt-128g24g-alios-volte-ruku', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
