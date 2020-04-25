<?php
defined('API') or exit('https://srun.com');
//if (!is_supper()) die('只有超级管理员才可进行ajax操作');
if (!is_login()) die('请登录');
//得到ajax操作
$op = I($_GET['op']);
//执行ajax操作
$op();
// 删除某个接口
function apiDelete()
{
    //接口id
    $id = I($_POST['id']);
    $sql = "update api set isdel='1' where id='{$id}'";
    $re = update($sql);
    die ($re ? '1' : '0');
}

// 更改接口调用状态
function changeInvokeStatus()
{
    $status_1 = '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>';
    $status_2 = '<span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>';
    $id = I($_POST['id']);
    $sql1 = "select status from api where id='{$id}'";
    $rs = select($sql1);
    $status = $rs[0]['status'];
    if (in_array($status, [1, 4])) $new_status = 2;
    if ($status == 2) $new_status = 1;
    if (!isset($new_status)) die('0');
    $sql = "update api set status='{$new_status}' where id='{$id}'";
    $re = update($sql);
    if ($re) {
        if ($new_status == 1) die($status_1);
        if ($new_status == 2) die($status_2);
    } else {
        die('0');
    }
}

// 更改接口调用状态
function changeAbandonStatus()
{
    $id = I($_POST['id']);
    $sql1 = "select status from api where id='{$id}'";
    $rs = select($sql1);
    $status = $rs[0]['status'];
    if (in_array($status, [1, 2, 4])) $new_status = 3;
    if ($status == 3) $new_status = 4;
    if (!isset($new_status)) die('0');
    $sql = "update api set status='{$new_status}' where id='{$id}'";
    $re = update($sql);
    die ($re ? '1' : '0');

}