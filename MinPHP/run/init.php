<?php
//项目所有文件的入口文件
//防跳墙常量
define('API', 'https://srun.com');
//开启session
session_start();
//关闭错误输出
error_reporting(0);
//设置页面字符编码
header("Content-type: text/html; charset=utf-8");
//设置时区
date_default_timezone_set('Asia/Shanghai');
//加载公用函数
include('./MinPHP/core/function.php');
//数据库连接初始化
M();
if (!function_exists('humanity_time')) {
    /**
     * @param int $timestamp 时间戳
     * @return string 返回更人性化时间
     */
    function humanity_time($timestamp)
    {
        $now = time();
        $diff = $now - $timestamp;
        $day = floor($diff / (24 * 60 * 60));
        switch (true) {
            case $diff > 365 * 24 * 60 * 60:
                return date('Y-m-d', $timestamp);
            case $diff > 4 * 30 * 24 * 60 * 60:
                return floor($diff / (30 * 24 * 60 * 60)) . '个月前';
            case $diff > 1 * 60 * 60:
                if ($day >= 3) {
                    return $day . '天前';
                } elseif ($day >= 2) {
                    return '前天' . date('H:i', $timestamp);
                } elseif ($day >= 1) {
                    return '昨天' . date('H:i', $timestamp);
                } else {
                    return floor($diff / (60 * 60)) . '小时前';
                }
            case $diff > 60:
                return floor($diff / 60) . '分钟前';
            case $diff > 0:
                return '刚刚';
            case $diff == 0:
                return '此时';
            case $diff < 0:
                return '未来';
            default:
                return '参数错误';
        }
    }
}