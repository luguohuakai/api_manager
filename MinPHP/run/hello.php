<?php
	defined('API') or exit('http://gwalker.cn');
	if(!is_login()){die('请登录');}
?>
<!--欢迎页-->
<!--info start-->
<div style="font-size:18px;">
    <div class="info" style="font-size:14px;">
        <span style="font-size:30px;" class="glyphicon glyphicon-grain" aria-hidden="true"></span> <span style="font-size:16px;">【SP云】接口<span style="font-size:12px;position:relative;top:-7px;"> v1</span></span><br>
        <pre class="info" style="margin:10px 34px 10px 34px">
关于SDK?
。。。。。。
    密！！！！

    1、sign签名方式
        <span class="label label-default">
            md5( 参数按键名升序连接成字符串 + 秘钥 + 服务器时间 )
        </span>
    2、秘钥
        <span class="label label-default">
            #$%^&**^$$kdjji&*(*7790;fi
        </span>
    5、无需签名接口
        <span class="label label-default">
            base/get-server-time
        </span>
    6、其他说明
        </pre>
    </div>
    <div style="font-size:12px;position:absolute;bottom:0;right:20px;height:20px;text-align:right;">
        路过花开 | qq : 769245396 | <a target="_blank" href="http://www.srun.com/">深澜软件</a>
    </div>
</div>
<!--欢迎页 end-->