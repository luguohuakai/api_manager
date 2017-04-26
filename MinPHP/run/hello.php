<?php
	defined('API') or exit('http://gwalker.cn');
	if(!is_login()){die('请登录');}
?>
<!--欢迎页-->
<!--info start-->
<div style="font-size:18px;">
    <div class="info" style="font-size:14px;">
        <span style="font-size:30px;" class="glyphicon glyphicon-grain" aria-hidden="true"></span> <span style="font-size:16px;">欢迎使用【SDK】接口 <?php echo C('version->no').'';?></span><br>
        <pre class="info" style="margin:10px 34px 10px 34px">
关于SDK?
&nbsp;&nbsp;&nbsp;&nbsp;。。。。。。
			保密！！！！

			1、sign签名方式

			2、sign签名秘钥

			3、token生成方式

			4、token验证规则

			5、无需签名接口

			6、字段说明
			    status：0（未查询到正确结果）
			            1（请求成功）
			    msg：（相关提示信息）
        </pre>
    </div>
    <div style="font-size:12px;position:absolute;bottom:0;right:20px;height:20px;text-align:right;">
        路过花开 | qq : 769245396 | <a target="_blank" href="http://www.xueshengtai.com">学生态校花</a>
    </div>
</div>
<!--欢迎页 end-->