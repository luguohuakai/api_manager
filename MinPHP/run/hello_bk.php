<?php
defined('API') or exit('https://srun.com');
if (!is_login()) {
    die('请登录');
}
?>
<!--欢迎页-->
<!--info start-->
<div style="font-size:18px;">
    <div class="info" style="font-size:14px;">
        <span style="font-size:30px;" class="glyphicon glyphicon-grain" aria-hidden="true"></span> <span
                style="font-size:16px;">【SRUN】规范<span
                    style="font-size:12px;position:relative;top:-7px;"> v1</span></span><br>
        <pre class="info" style="margin:10px 34px 10px 34px;display: none">
关于
。。。。。。
    密！！！！

    0、接口请求说明
        除特殊说明外,所有接口请求时都需附带sign time两个参数,否则请求不予通过,sign time获取方式如下.
        (提示:为避免每次请求前都获取服务器时间,可将服务器时间与本地时间的差值存于本地,每次请求时的服务器时间则为:本地时间 + 差值)
    1、sign 签名生成方式
        <span class="label label-default">
            sign = md5( 请求参数按键名升序连接成字符串 + 秘钥 + 服务器时间 )
        </span>
    2、秘钥
        <span class="label label-default">
            key = #$%^&**^$$kdjji&*(*7790;fi
        </span>
    3、服务器时间(此接口无需签名验证)
        <span class="label label-default">
            time = base/get-server-time
        </span>
        </pre>
        <pre class="info" style="margin:10px 34px 10px 34px;">
    [代码规范]
    PSR-1 PSR-2 PSR-4

            <span style="display: none">
    [数据库规范]
    标准版(dev分支) 基础库(srun.sql) 更新库(srun_update.sql) 基础数据(srun_data.sql) 更新库基础数据(srun_update_data.sql)
    每个具体项目禁止更改基础库和更新库 均使用 "英文或拼音项目名.sql" 形式保存于 "/srun3/www/srun4-mgr/db/" 目录下
    如:Tsinghua.sql Tsinghua_update.sql Tsinghua_data.sql Tsinghua_update_data.sql
                </span>
        </pre>
    </div>
    <div style="font-size:12px;position:absolute;bottom:0;right:20px;height:20px;text-align:right;">
        路过花开 | qq : 769245396 | <a target="_blank" href="http://www.srun.com/">深澜软件</a>
    </div>
</div>
