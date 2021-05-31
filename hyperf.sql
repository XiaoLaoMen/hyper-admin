-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2021-05-30 20:48:36
-- 服务器版本： 5.7.33-log
-- PHP 版本： 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `hyperf`
--

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_admins`
--

CREATE TABLE `hyperf_admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` char(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `email` char(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `password` char(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `role_id` char(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色id',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT '状态 0禁止登录 1允许登录',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `hyperf_admins`
--

INSERT INTO `hyperf_admins` (`id`, `name`, `email`, `password`, `role_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'aaaa', 'admin@admin.com', '$2y$10$y1Uxbt/NBb96.p3EQfj1IuFFd7NGXftJ8CNoCtVcollIqi7GwQZvy', '', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_admin_auths`
--

CREATE TABLE `hyperf_admin_auths` (
  `admin_id` int(11) NOT NULL COMMENT '用户id',
  `menu_id` int(11) NOT NULL COMMENT '菜单id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_admin_login_logs`
--

CREATE TABLE `hyperf_admin_login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` int(11) NOT NULL COMMENT '用户id',
  `ip` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ip',
  `addr` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '具体地址',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_admin_sets`
--

CREATE TABLE `hyperf_admin_sets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` char(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'key值',
  `desc` char(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '描述',
  `val` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '值',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `hyperf_admin_sets`
--

INSERT INTO `hyperf_admin_sets` (`id`, `key`, `desc`, `val`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'upload_ext', '上传后缀', 'mp4,avi,jpg,png,jpeg,ico', '2021-03-31 13:13:46', '2021-05-30 09:28:10', NULL),
(2, 'upload_mime', '上传类型', 'image/png,image/jpeg,image/gif,image/x-icon', '2021-04-08 13:13:46', '2021-05-30 09:28:10', NULL),
(3, 'upload_image_size', '上传图片大小', '10485760', '2021-04-07 13:15:00', '2021-05-30 09:28:10', NULL),
(6, 'logo', '后台logo', '/upload/image/2021-05-30/4a3e46b1487be8abc4578ab9c69c7bfc.png', NULL, '2021-05-30 09:38:50', NULL),
(7, 'icon', 'icon图标', '/upload/image/2021-05-30/c773f26bf21fe80b278683dd3e1724c7.ico', NULL, '2021-05-30 09:41:16', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_menus`
--

CREATE TABLE `hyperf_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pid` int(11) NOT NULL COMMENT '父类id',
  `name` char(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单名称',
  `url` char(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '菜单地址',
  `icon` char(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'icon图标',
  `sort` int(11) NOT NULL DEFAULT '50' COMMENT '排序',
  `status` smallint(6) NOT NULL DEFAULT '1' COMMENT ' 0禁止 1显示',
  `is_default` smallint(6) NOT NULL DEFAULT '1' COMMENT ' 0否 1是',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `hyperf_menus`
--

INSERT INTO `hyperf_menus` (`id`, `pid`, `name`, `url`, `icon`, `sort`, `status`, `is_default`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 0, '系统管理', '', '', 50, 1, 0, '2021-02-28 02:06:01', '2021-05-30 10:53:09', NULL),
(2, 1, '权限设置', '', 'iconfont icon-quanxian', 50, 1, 0, '2021-02-28 02:06:04', '2021-04-23 04:24:30', NULL),
(3, 2, '菜单', '/admin/menu/index', 'iconfont icon-caidan', 50, 1, 0, '2021-02-28 02:06:08', '2021-04-23 04:25:07', NULL),
(4, 3, '菜单列表', '/admin/menu/list', '', 50, 1, 0, '2021-03-07 02:06:10', '2021-03-01 02:06:25', NULL),
(5, 3, '菜单编辑', '/admin/menu/page', '', 50, 1, 0, '2021-02-28 02:06:13', '2021-03-23 05:47:24', NULL),
(6, 3, '菜单修改', '/admin/menu/handler', '', 50, 1, 0, '2021-02-28 02:06:15', '2021-03-23 05:47:38', NULL),
(7, 3, '菜单删除', '/admin/menu/del', '', 50, 1, 0, '2021-02-28 02:06:17', '2021-03-01 02:06:31', NULL),
(8, 2, '角色', '/admin/role/index', 'iconfont icon-jiaoseguanli', 50, 1, 0, '2021-03-23 05:35:56', '2021-04-23 04:25:34', NULL),
(9, 8, '角色列表', '/admin/role/list', '', 50, 1, 0, '2021-03-23 05:48:10', '2021-03-23 05:48:10', NULL),
(10, 8, '角色编辑', '/admin/role/page', '', 50, 1, 0, '2021-03-23 05:48:27', '2021-03-23 05:48:27', NULL),
(11, 8, '角色修改', '/admin/role/handler', '', 50, 1, 0, '2021-03-23 05:48:37', '2021-03-23 05:48:37', NULL),
(12, 8, '角色删除', '/admin/role/del', '', 50, 1, 0, '2021-03-23 05:48:46', '2021-03-23 05:48:46', NULL),
(13, 8, '角色授权编辑', '/admin/role/auth', '', 50, 1, 0, '2021-03-23 05:49:00', '2021-03-23 05:49:00', NULL),
(14, 8, '角色权限获取', '/admin/role/getauth', '', 50, 1, 0, '2021-03-23 08:40:29', '2021-03-23 08:40:29', NULL),
(15, 8, '角色授权', '/admin/auth/role', '', 50, 1, 0, '2021-03-23 05:49:00', '2021-03-23 05:49:00', NULL),
(16, 2, '管理员', '/admin/admin/index', 'iconfont icon-guanliyuan', 50, 1, 0, '2021-03-24 01:59:47', '2021-04-23 04:25:51', NULL),
(17, 16, '管理员列表', '/admin/admin/list', '', 50, 1, 0, '2021-03-24 02:04:34', '2021-03-24 02:04:34', NULL),
(18, 16, '管理员编辑', '/admin/admin/page', '', 50, 1, 0, '2021-03-24 02:04:38', '2021-03-24 02:04:38', NULL),
(19, 16, '管理员修改', '/admin/admin/handler', '', 50, 1, 0, '2021-03-24 02:04:53', '2021-03-24 02:04:53', NULL),
(20, 16, '管理员删除', '/admin/admin/del', '', 50, 1, 0, '2021-03-24 02:05:01', '2021-03-24 02:05:01', NULL),
(21, 16, '登录权限', '/admin/admin/login', '', 50, 1, 0, '2021-03-24 05:55:36', '2021-03-24 05:55:36', NULL),
(22, 16, '管理员授权编辑', '/admin/admin/auth', '', 50, 1, 0, '2021-03-24 02:05:11', '2021-03-24 02:05:11', NULL),
(23, 16, '管理员授权获取', '/admin/admin/getauth', '', 50, 1, 0, '2021-03-24 02:05:11', '2021-05-24 14:42:07', NULL),
(24, 16, '管理员授权', '/admin/auth/admin', '', 50, 1, 0, '2021-03-24 02:05:11', '2021-05-24 14:42:07', NULL),
(25, 2, '白名单权限', '', '', 50, 0, 1, '2021-03-24 01:59:47', '2021-04-23 04:25:51', NULL),
(26, 25, '个人信息', '/admin/admin/info', '', 50, 0, 1, '2021-03-24 02:05:36', '2021-05-24 14:42:48', NULL),
(27, 25, '菜单初始化', '/admin/init/list', '', 50, 0, 1, '2021-05-20 07:24:53', '2021-05-30 11:01:53', NULL),
(28, 25, '主页面', '/admin/index/layout', '', 50, 0, 1, '2021-05-20 07:27:28', '2021-05-30 11:01:36', NULL),
(29, 25, '清除缓存', '/admin/cache/clear', '', 50, 0, 1, '2021-05-20 07:27:28', '2021-05-20 07:27:28', NULL),
(30, 25, '图片上传', '/admin/upload/layui', '', 50, 0, 1, '2021-05-20 07:28:01', '2021-05-29 16:44:38', NULL),
(31, 2, '设置', '/admin/set/index', 'iconfont icon-caidan', 50, 1, 0, '2021-05-29 16:35:50', '2021-05-29 16:54:31', NULL),
(32, 31, '编辑', '/admin/set/handler', '', 50, 1, 0, '2021-05-29 16:44:55', '2021-05-29 16:44:55', NULL),
(33, 2, '登录日志', '/admin/log/index', '', 50, 1, 0, '2021-05-30 12:15:02', '2021-05-30 12:15:02', NULL),
(34, 33, '列表', '/admin/log/list', '', 50, 1, 0, '2021-05-30 12:16:11', '2021-05-30 12:16:11', NULL),
(35, 33, '删除', '/admin/log/del', '', 50, 1, 0, '2021-05-30 12:43:55', '2021-05-30 12:43:55', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_migrations`
--

CREATE TABLE `hyperf_migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `hyperf_migrations`
--

INSERT INTO `hyperf_migrations` (`id`, `migration`, `batch`) VALUES
(2, '2021_03_18_165401_create_roles_table', 1),
(4, '2021_05_29_153554_create_role_auths_table', 1),
(5, '2021_05_30_161659_create_admin_sets_table', 1),
(6, '2021_05_30_161712_create_admin_auths_table', 1),
(8, '2021_03_18_165412_create_menus_table', 2),
(9, '2021_03_18_165353_create_admins_table', 3),
(11, '2021_05_30_200031_create_admin_login_logs_table', 4);

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_roles`
--

CREATE TABLE `hyperf_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` char(60) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `hyperf_role_auths`
--

CREATE TABLE `hyperf_role_auths` (
  `role_id` int(11) NOT NULL COMMENT '角色id',
  `menu_id` int(11) NOT NULL COMMENT '菜单id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转储表的索引
--

--
-- 表的索引 `hyperf_admins`
--
ALTER TABLE `hyperf_admins`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `hyperf_admin_login_logs`
--
ALTER TABLE `hyperf_admin_login_logs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `hyperf_admin_sets`
--
ALTER TABLE `hyperf_admin_sets`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `hyperf_menus`
--
ALTER TABLE `hyperf_menus`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `hyperf_migrations`
--
ALTER TABLE `hyperf_migrations`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `hyperf_roles`
--
ALTER TABLE `hyperf_roles`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `hyperf_admins`
--
ALTER TABLE `hyperf_admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `hyperf_admin_login_logs`
--
ALTER TABLE `hyperf_admin_login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `hyperf_admin_sets`
--
ALTER TABLE `hyperf_admin_sets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `hyperf_menus`
--
ALTER TABLE `hyperf_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- 使用表AUTO_INCREMENT `hyperf_migrations`
--
ALTER TABLE `hyperf_migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `hyperf_roles`
--
ALTER TABLE `hyperf_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
