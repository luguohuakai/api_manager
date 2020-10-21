<?php
include './MinPHP/run/init.php';
$biz_id = isset($_GET['biz_id']) ? $_GET['biz_id'] : ''; // 业务ID
$act = !isset($_GET['act']) ? 'index' : $_GET['act'];
$menu = '';
switch ($act) {
    //接口分类
    case 'cate':
        $menu = ' - 分类管理';
        $file = './MinPHP/run/cate.php';
        break;
    //登录退出
    case 'login':
        $menu = ' - 登录';
        $file = './MinPHP/run/login.php';
        break;
    //接口详细页
    case 'api':
        $sql = "select cname from cate where aid='{$_GET['tag']}' and isdel=0";
        $menu = find($sql);
        $menu = ' - ' . $menu['cname'];
        $file = './MinPHP/run/info.php';
        break;
    //接口排序页
    case 'sort':
        $sql = "select cname from cate where aid='{$_GET['tag']}' and isdel=0";
        $menu = find($sql);
        $menu = ' - ' . "<a href='" . U(array('act' => 'api', 'tag' => $_GET['tag'])) . "'>{$menu['cname']}</a>";
        $file = './MinPHP/run/sort.php';
        break;
    //导出静态文件
    case 'export':
        die(include('./MinPHP/run/export.php'));
        break;
    //ajax请求
    case 'ajax':
        die(include('./MinPHP/run/ajax.php'));
        break;
    //修改密码
    case 'modpwd':
        $menu = ' - 修改密码';
        $file = './MinPHP/run/modpwd.php';
        break;
    case 'subject_handle':
        $rs = subjectHandle();
        if ($rs) {
            go(U());
        } else {
            echo alert('创建/更新失败');
        }
        break;
    case 'subject':
        if ($biz_id) {
            $menu = ' - 欢迎';
            $file = './MinPHP/run/hello.php';
            session('subject', json_encode($_GET));
        }
        break;
    //首页
    case 'index':
        session('subject', '');
        $menu = ' - 欢迎';
        $file = './MinPHP/run/hello.php';
        break;
    default :
        $menu = ' - 欢迎';
        $file = './MinPHP/run/hello.php';
        break;
}
include './MinPHP/run/main.php';

// 创建/更新项目
function subjectHandle()
{
    if (!is_supper()) return false;
    $time = time();
    $id = P('id');
    $name = P('name');
    $note = P('note');
    if ($id) {
        // 更新
        $rs = update("update subject set `name`='{$name}',`note`='{$note}',`updated_at`='{$time}' where `id`='{$id}'");
    } else {
        // 新增
        $rs = insert("insert into subject (`name`,`note`,`created_at`,`updated_at`) values ('{$name}','{$note}','{$time}','{$time}')");
    }
    return $rs;
}