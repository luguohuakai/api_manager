-- phpMyAdmin SQL Dump
-- version 4.5.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2016-03-02 10:41:17
-- 服务器版本： 5.5.20
-- PHP Version: 5.5.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api`
--
--
-- Database: `api`
--
CREATE DATABASE IF NOT EXISTS `api` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `api`;
COMMIT;
-- --------------------------------------------------------

--
-- 表的结构 `api`
--

CREATE TABLE `api` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '接口编号',
  `aid` int(11) DEFAULT '0' COMMENT '接口分类id',
  `num` varchar(100) DEFAULT NULL COMMENT '接口编号',
  `url` varchar(240) DEFAULT NULL COMMENT '请求地址',
  `name` varchar(100) DEFAULT NULL COMMENT '接口名',
  `des` varchar(300) DEFAULT NULL COMMENT '接口描述',
  `parameter` text COMMENT '请求参数{所有的主求参数,以json格式在此存放}',
  `memo` text COMMENT '备注',
  `re` text COMMENT '返回值',
  `lasttime` int(11) UNSIGNED DEFAULT NULL COMMENT '提后操作时间',
  `lastuid` int(11) UNSIGNED DEFAULT NULL COMMENT '最后修改uid',
  `isdel` tinyint(4) UNSIGNED DEFAULT '0' COMMENT '{0:正常,1:删除}',
  `type` char(11) DEFAULT NULL COMMENT '请求方式',
  `ord` int(11) DEFAULT '0' COMMENT '排序(值越大,越靠前)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='接口明细表';

--
-- 转存表中的数据 `api`
--

INSERT INTO `api` (`id`, `aid`, `num`, `url`, `name`, `des`, `parameter`, `memo`, `re`, `lasttime`, `lastuid`, `isdel`, `type`, `ord`) VALUES
(1, 2, '000', 'http://api.xxx.com', '会员注册', '会员注册调用此接口', 'a:4:{s:4:"name";a:3:{i:0;s:10:"login_name";i:1;s:8:"password";i:2;s:5:"email";}s:4:"type";a:3:{i:0;s:1:"Y";i:1;s:1:"Y";i:2;s:1:"N";}s:7:"default";a:3:{i:0;s:0:"";i:1;s:0:"";i:2;s:0:"";}s:3:"des";a:3:{i:0;s:9:"登录名";i:1;s:6:"密码";i:2;s:12:"用户邮箱";}}', '', '{\r\n    &quot;status&quot;: 1, \r\n    &quot;info&quot;: &quot;注册成功&quot;, \r\n    &quot;data&quot;: {\r\n        &quot;uid&quot;: &quot;20&quot;\r\n    }\r\n}', 1435588983, 1, 0, 'POST', 0),
(2, 2, '001', 'http://api.xxx.com', '会员登录', '会员登录调用此接口', 'a:4:{s:4:"name";a:3:{i:0;s:10:"login_name";i:1;s:5:"email";i:2;s:8:"password";}s:4:"type";a:3:{i:0;s:1:"Y";i:1;s:1:"Y";i:2;s:1:"Y";}s:7:"default";a:3:{i:0;s:0:"";i:1;s:0:"";i:2;s:0:"";}s:3:"des";a:3:{i:0;s:30:"登录名与邮箱二选其一";i:1;s:30:"邮箱与登录名二选其一";i:2;s:6:"密码";}}', 'login_name 与 email 二选其一', '{\r\n    &quot;status&quot;: 1, \r\n    &quot;info&quot;: &quot;登录成功&quot;, \r\n    &quot;data&quot;: [ ]\r\n}', 1435576729, 2, 0, 'POST', 0);

-- --------------------------------------------------------

--
-- 表的结构 `auth`
--

CREATE TABLE `auth` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) DEFAULT NULL COMMENT '用户',
  `aid` int(11) DEFAULT NULL COMMENT '接口分类权限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限表 - 若用户为普通管理员时，读此表获取权限';

-- --------------------------------------------------------

--
-- 表的结构 `cate`
--

CREATE TABLE `cate` (
  `aid` int(11) UNSIGNED NOT NULL COMMENT '分类id',
  `cname` varchar(200) NOT NULL DEFAULT '' COMMENT '分类名称',
  `cdesc` varchar(200) NOT NULL DEFAULT '' COMMENT '分类描述',
  `isdel` int(11) DEFAULT '0' COMMENT '是否删除{0:正常,1删除}',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='接口分类表';

--
-- 转存表中的数据 `cate`
--

INSERT INTO `cate` (`aid`, `cname`, `cdesc`, `isdel`, `addtime`) VALUES
(1, '分类一', '第一个测试分类', 1, 1435575162),
(2, '注册登录', '注册和登陆接口', 0, 1435575185);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `nice_name` char(20) DEFAULT NULL COMMENT '昵称',
  `login_name` char(20) DEFAULT NULL COMMENT '登录名',
  `last_time` int(11) DEFAULT '0' COMMENT '最近登录时间',
  `login_pwd` varchar(32) DEFAULT NULL COMMENT '登录密码',
  `isdel` int(11) DEFAULT '0' COMMENT '{0正常,1:删除}',
  `issuper` int(11) DEFAULT '0' COMMENT '{0:普通管理员,1超级管理员}'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `nice_name`, `login_name`, `last_time`, `login_pwd`, `isdel`, `issuper`) VALUES
(1, 'maoge', 'maoge', 1456875836, '7f0cb946ef1aba5768fa3682c7df048a', 0, 1),
(2, 'wxd', 'wxd', 1435575693, '2547e73398d848d05fb0022293a3a351', 0, 0),
(3, 'fcz', 'fcz', 1456851025, 'ba006ba7669bb3b52655be17abaae963', 0, 0),
(4, 'wy', 'wy', 1435575693, 'dfcc9dca8ed7aa668e1dcc2996455191', 0, 0),
(5, 'wwx', 'wwx', 1435575693, '483a70a0179b1c6be6a9e83e22615358', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auth`
--
ALTER TABLE `auth`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cate`
--
ALTER TABLE `cate`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api`
--
ALTER TABLE `api`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '接口编号', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `auth`
--
ALTER TABLE `auth`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cate`
--
ALTER TABLE `cate`
  MODIFY `aid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分类id', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
