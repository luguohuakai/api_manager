<?php
defined('API') or exit();
if (!is_login() && $_GET['mark'] !== 'export') {
    die('请登录');
}
?>
<!--接口详情列表与接口管理start-->
<?php
$_VAL = I($_POST);
//操作类型{add,delete,edit}
$op = $_GET['op'];
$type = $_GET['type'];
//添加接口
if ($op == 'add') {
    if ($type == 'do') {
        if (!is_supper()) {
            die('只有超级管理员才可对接口进行操作');
        }
        $aid = I($_GET['tag']);    //所属分类
        if (empty($aid)) {
            die('<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 所属分类不能为空');
        }
        $num = htmlspecialchars($_POST['num'], ENT_QUOTES);   //接口编号(为了导致编号的前导0去过滤掉。不用用I方法过滤)
        $name = $_VAL['name'];  //接口名称
        $memo = $_VAL['memo']; //备注
        $des = $_VAL['des'];    //描述
        $type = $_VAL['type'];  //请求方式
        $url = $_VAL['url'];

        $parameter = serialize($_VAL['p']);
        $re = $_VAL['re'];  //返回值
        $lasttime = time(); //最后操作时间
        $lastuid = session('id'); //操作者id
        $isdel = 0; //是否删除的标识
        $sql = "insert into api (
            `aid`,`num`,`name`,`des`,`url`,
            `type`,`parameter`,`re`,`lasttime`,
            `lastuid`,`isdel`,`memo`,`ord`
            )values (
            '{$aid}','{$num}','{$name}','{$des}','{$url}',
            '{$type}','{$parameter}','{$re}','{$lasttime}',
            '{$lastuid}','{$isdel}','{$memo}','0'
            )";
        $re = insert($sql);
        if ($re) {
            go(U(array('act' => 'api', 'tag' => $_GET['tag'], 'op' => 'add', 'msg' => '<strong style="color: green">' . $name . '</strong> 添加成功,你可以继续添加 :-)')));
        } else {
            echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 添加失败</div>';
        }
    }
    //修改接口
} else if ($op == 'edit') {
    if (!is_supper()) {
        die('只有超级管理员才可对接口进行操作');
    }
    //执行编辑
    if ($type == 'do') {
        $id = $_VAL['id'];   //接口id
        $num = htmlspecialchars($_POST['num'], ENT_QUOTES);   //接口编号(为了导致编号的前导0去过滤掉。不用用I方法过滤)
        $name = $_VAL['name'];  //接口名称
        $memo = $_VAL['memo']; //备注
        $des = $_VAL['des'];    //描述
        $type = $_VAL['type'];  //请求方式
        $url = $_VAL['url']; //请求地址

        $parameter = serialize($_VAL['p']);
        $re = $_VAL['re'];  //返回值
        $lasttime = time(); //最后操作时间
        $lastuid = session('id'); //操作者id

        $sql = "update api set num='{$num}',name='{$name}',
           des='{$des}',url='{$url}',type='{$type}',
           parameter='{$parameter}',re='{$re}',lasttime='{$lasttime}',lastuid='{$lastuid}',memo='{$memo}',status=4
           where id = '{$id}'";
        $re = update($sql);
        if ($re) {
            go(U(array('act' => 'api', 'tag' => ($_GET['tag'] . '#info_api_' . md5($id)))));
        } else {
            echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 修改失败</div>';
        }
    }
    //编辑界面
    if (empty($id)) {
        $id = I($_GET['id']);
    }
    $aid = I($_GET['tag']);
    //得到数据的详情信息start
    $sql = "select * from api where id='{$id}' and aid='{$aid}'";
    $info = find($sql);
    //得到数据的详情信息end
    if (!empty($info)) {
        $info['parameter'] = unserialize($info['parameter']);
        $count = $info['parameter'] ? count($info['parameter']['name']) : 0;
        $p = array();
        for ($i = 0; $i < $count; $i++) {
            $p[$i]['name'] = $info['parameter']['name'][$i];
            $p[$i]['paramType'] = $info['parameter']['paramType'][$i];
            $p[$i]['type'] = $info['parameter']['type'][$i];
            $p[$i]['default'] = $info['parameter']['default'][$i];
            $p[$i]['des'] = $info['parameter']['des'][$i];
        }
        $info['parameter'] = $info['parameter'];
    }
    //此分类下的接口列表
} else {
    $sql = "select api.id,aid,num,url,name,des,parameter,memo,re,lasttime,lastuid,type,login_name,api.status,api.status_time
        from api
        left join user
        on api.lastuid=user.id
        where aid='{$_GET['tag']}' and api.isdel=0
        order by api.lasttime desc,ord desc";
    $list = select($sql);
}
?>
<?php if ($op == 'add') { ?>
    <!--添加接口 start-->

    <!--js自动保存到cookie  star-->
    <script>
        $(function () {

            $("textarea[name='des'],textarea[name='re'],textarea[name='memo']").keydown(function () {
                AutoSave();
            });

            $(".btn-success").click(function () {
                DeleteCookie('apimanage');
            });

        });
    </script>
    <script>
        /**
         *
         *自动保存文字到cookie中
         *http://www.xuebuyuan.com/1323493.html
         *
         */
        function AutoSave() {
            var des = $("textarea[name='des']").val();
            var re = $("textarea[name='re']").val();
            var memo = $("textarea[name='memo']").val();
            var _value = des + ";" + re + ";" + memo;
            if (_value == ";;") {
                var LastContent = GetCookie('apimanage');

                if (LastContent == ";;") return;
                var text = LastContent.split(";");
                if (des != text[0] || re != text[1] || memo != text[2]) {
                    if (confirm("加载保存的记录")) {
                        $("textarea[name='des']").html(text[0]);
                        $("textarea[name='re']").html(text[1]);
                        $("textarea[name='memo']").html(text[2]);
                        return true;
                    }
                }

            } else {
                var expDays = 30;
                var exp = new Date();
                exp.setTime(exp.getTime() + (expDays * 86400000)); // 24*60*60*1000 = 86400000
                var expires = '; expires=' + exp.toGMTString();

                // SetCookie
                document.cookie = "apimanage=" + escape(_value) + expires;
            }
        }

        function getCookieVal(offset) {
            var endstr = document.cookie.indexOf(";", offset);
            if (endstr == -1) endstr = document.cookie.length;
            return unescape(document.cookie.substring(offset, endstr));
        }

        function GetCookie(name) {
            var arg = name + "=";
            var alen = arg.length;
            var clen = document.cookie.length;
            var i = 0;
            while (i < clen) {
                var j = i + alen;
                if (document.cookie.substring(i, j) == arg) return getCookieVal(j);
                i = document.cookie.indexOf(" ", i) + 1;
                if (i == 0) break;
            }
            return null;
        }

        function DeleteCookie(name) {
            var exp = new Date();
            exp.setTime(exp.getTime() - 1);
            var cval = GetCookie(name);
            document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
        }

        function changeDesc() {
            let c = $('#description_api')
            if (c.val() === "Content-Type: application/x-www-form-urlencoded") {
                c.val("Content-Type: application/json")
            } else {
                c.val("Content-Type: application/x-www-form-urlencoded")
            }
        }
    </script>
    <!--js自动保存到cookie  end-->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <?= $_GET['msg']; ?>
        </div>
    <?php endif; ?>
    <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;position:relative">
            <h4>添加接口<span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
            <div style="margin-left:20px;">
                <form action="?act=api&tag=<?php echo $_GET['tag'] ?>&type=do&op=add" method="post">
                    <h5 onclick="changeDesc()">基本信息</h5>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口编号</div>
                            <input type="text" class="form-control" name="num" placeholder="接口编号" required="required"
                                   value="--">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口名称</div>
                            <input type="text" class="form-control" name="name" placeholder="接口名称" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">请求地址</div>
                            <input type="text" class="form-control" name="url" placeholder="请求地址" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="des" rows="1" class="form-control" id="description_api" placeholder="描述">Content-Type: application/json</textarea>
                    </div>
                    <div class="form-group" required="required">
                        <select class="form-control" name="type">
                            <option value="POST">POST</option>
                            <option value="GET">GET</option>
                            <option value="DELETE">DELETE</option>
                            <option value="PUT">PUT</option>
                            <option value="HEAD">HEAD</option>
                            <option value="PATCH">PATCH</option>
                            <option value="OPTIONS">OPTIONS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h5>请求参数</h5>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-3">参数名</th>
                                <th class="col-md-2">参数类型</th>
                                <th class="col-md-2">必传</th>
                                <th class="col-md-2">默认值</th>
                                <th class="col-md-4">描述</th>
                                <th class="col-md-1">
                                    <button type="button" class="btn btn-success" onclick="add()">新增</button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="parameter">
                            <tr>
                                <td class="form-group has-error">
                                    <input type="text" class="form-control" name="p[name][]" placeholder="参数名"
                                           required="required">
                                </td>
                                <td class="form-group has-error"><input type="text" class="form-control"
                                                                        name="p[paramType][]" placeholder="参数类型"
                                                                        required="required" value="string"></td>
                                <td>
                                    <select class="form-control" name="p[type][]">
                                        <option value="Y">Y</option>
                                        <option value="N">N</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control" name="p[default][]" placeholder="默认值"></td>
                                <td><textarea name="p[des][]" rows="1" class="form-control" placeholder="描述"></textarea>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" onclick="del(this)">删除</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs">&nbsp;&nbsp;提&nbsp;&nbsp;&nbsp;交&nbsp;&nbsp;</button>
                            <button class="btn btn-default btn-xs" type="reset">重置</button>
                        </div>
                        <h5>返回结果 </h5>
                        <textarea name="re" rows="10" class="form-control" placeholder="返回结果"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs">&nbsp;&nbsp;提&nbsp;&nbsp;&nbsp;交&nbsp;&nbsp;</button>
                            <button class="btn btn-default btn-xs" type="reset">重置</button>
                        </div>
                        <h5>备注</h5>
                        <textarea name="memo" rows="10" class="form-control" placeholder="备注"></textarea>
                    </div>
                    <button class="btn btn-success btn-block">提交</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function add() {
            var $html = '<tr>' +
                '<td class="form-group has-error" ><input type="text" class="form-control has-error" name="p[name][]" placeholder="参数名" required="required"></td>' +
                '<td class="form-group has-error">' +
                '<input type="text" class="form-control" name="p[paramType][]" placeholder="参数类型" required="required" value="string"></td>' +
                '<td>' +
                '<select class="form-control" name="p[type][]">' +
                '<option value="Y">Y</option> <option value="N">N</option>' +
                '</select >' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="p[default][]" placeholder="默认值"></td>' +
                '<td>' +
                '<textarea name="p[des][]" rows="1" class="form-control" placeholder="描述"></textarea>' +
                '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-danger" onclick="del(this)">删除</button>' +
                '</td>' +
                '</tr >';
            $('#parameter').append($html);
        }

        function del(obj) {
            $(obj).parents('tr').remove();
        }
    </script>
    <!--添加接口 end-->
<?php } else if ($op == 'edit') { ?>
    <!--修改接口 start-->
    <div style="border:1px solid #ddd">
        <div style="background:#f5f5f5;padding:20px;position:relative">
            <h4>修改接口<span style="font-size:12px;padding-left:20px;color:#a94442">注:"此色"边框为必填项</span></h4>
            <div style="margin-left:20px;">
                <form action="?act=api&tag=<?php echo $_GET['tag'] ?>&type=do&op=edit" method="post">
                    <h5 onclick="changeDesc()">基本信息</h5>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口编号</div>
                            <input type="hidden" name="id" value="<?php echo $info['id'] ?>"/>
                            <input type="text" class="form-control" name="num" placeholder="接口编号"
                                   value="<?php echo $info['num'] ?>" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">接口名称</div>
                            <input type="text" class="form-control" name="name" placeholder="接口名称"
                                   value="<?php echo $info['name'] ?>" required="required">
                        </div>
                    </div>
                    <div class="form-group has-error">
                        <div class="input-group">
                            <div class="input-group-addon">请求地址</div>
                            <input type="text" class="form-control" name="url" placeholder="请求地址"
                                   value="<?php echo $info['url'] ?>" required="required">
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea name="des" id="description_api" rows="1" class="form-control"
                                  placeholder="描述"><?php echo $info['des'] ?></textarea>
                    </div>
                    <div class="form-group" required="required">
                        <select class="form-control" name="type">
                            <option value="POST" <?= $info['type'] == 'POST' ? 'selected' : '' ?>>POST</option>
                            <option value="GET" <?= $info['type'] == 'GET' ? 'selected' : '' ?>>GET</option>
                            <option value="DELETE" <?= $info['type'] == 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                            <option value="PUT" <?= $info['type'] == 'PUT' ? 'selected' : '' ?>>PUT</option>
                            <option value="HEAD" <?= $info['type'] == 'HEAD' ? 'selected' : '' ?>>HEAD</option>
                            <option value="PATCH" <?= $info['type'] == 'PATCH' ? 'selected' : '' ?>>PATCH</option>
                            <option value="OPTIONS" <?= $info['type'] == 'OPTIONS' ? 'selected' : '' ?>>OPTIONS</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <h5>请求参数</h5>
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-3">参数名</th>
                                <th class="col-md-2">参数类型</th>
                                <th class="col-md-2">必传</th>
                                <th class="col-md-2">默认值</th>
                                <th class="col-md-4">描述</th>
                                <th class="col-md-1">
                                    <button type="button" class="btn btn-success" onclick="add()">新增</button>
                                </th>
                            </tr>
                            </thead>
                            <tbody id="parameter">

                            <?php $count = $info['parameter'] ? count($info['parameter']['name']) : 0; ?>
                            <?php for ($i = 0; $i < $count; $i++) { ?>
                                <tr>
                                    <td class="form-group has-error">
                                        <input type="text" class="form-control" name="p[name][]" placeholder="参数名"
                                               value="<?php echo $info['parameter']['name'][$i] ?>" required="required">
                                    </td>
                                    <td class="form-group has-error">
                                        <input type="text" class="form-control" name="p[paramType][]" placeholder="参数类型"
                                               value="<?php echo $info['parameter']['paramType'][$i] ?>"
                                               required="required">
                                    </td>
                                    <td>
                                        <?php
                                        $selected[0] = ($info['parameter']['type'][$i] == 'Y') ? 'selected' : '';
                                        $selected[1] = ($info['parameter']['type'][$i] == 'N') ? 'selected' : '';
                                        ?>
                                        <select class="form-control" name="p[type][]">
                                            <option value="Y" <?php echo $selected[0] ?>>Y</option>
                                            <option value="N" <?php echo $selected[1] ?>>N</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" name="p[default][]" placeholder="默认值"
                                               value="<?php echo $info['parameter']['default'][$i] ?>"></td>
                                    <td><textarea name="p[des][]" rows="1" class="form-control"
                                                  placeholder="描述"><?php echo $info['parameter']['des'][$i] ?></textarea>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" onclick="del(this)">删除</button>
                                    </td>
                                </tr>
                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs">&nbsp;&nbsp;提&nbsp;&nbsp;&nbsp;交&nbsp;&nbsp;</button>
                            <button class="btn btn-default btn-xs" type="reset">重置</button>
                        </div>
                        <h5>返回结果</h5>
                        <textarea name="re" rows="10" class="form-control"
                                  placeholder="返回结果"><?php echo $info['re'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                            <button class="btn btn-default btn-xs">&nbsp;&nbsp;提&nbsp;&nbsp;&nbsp;交&nbsp;&nbsp;</button>
                            <button class="btn btn-default btn-xs" type="reset">重置</button>
                        </div>
                        <h5>备注</h5>
                        <textarea name="memo" rows="1" class="form-control"
                                  placeholder="备注"><?php echo $info['memo'] ?></textarea>
                    </div>
                    <button class="btn btn-success btn-block">提交</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function add() {
            var $html = '<tr>' +
                '<td class="form-group has-error" >' +
                '<input type="text" class="form-control has-error" name="p[name][]" placeholder="参数名" required="required"></td>' +
                '<td class="form-group has-error">' +
                '<input type="text" class="form-control" name="p[paramType][]" placeholder="参数类型" value="string" required="required">' +
                '</td>' +
                '<td>' +
                '<select class="form-control" name="p[type][]">' +
                '<option value="Y">Y</option> <option value="N">N</option>' +
                '</select >' +
                '</td>' +
                '<td>' +
                '<input type="text" class="form-control" name="p[default][]" placeholder="默认值">' +
                '</td>' +
                '<td>' +
                '<textarea name="p[des][]" rows="1" class="form-control" placeholder="描述"></textarea>' +
                '</td>' +
                '<td>' +
                '<button type="button" class="btn btn-danger" onclick="del(this)">删除</button>' +
                '</td>' +
                '</tr >';
            $('#parameter').append($html);
        }

        function changeDesc() {
            let c = $('#description_api')
            if (c.val() === "Content-Type: application/x-www-form-urlencoded") {
                c.val("Content-Type: application/json")
            } else {
                c.val("Content-Type: application/x-www-form-urlencoded")
            }
        }

        function del(obj) {
            $(obj).parents('tr').remove();
        }
    </script>
    <!--修改接口 end-->
<?php } else { ?>
    <!--接口详细列表start-->
    <?php if (count($list)) { ?>
        <?php foreach ($list as $v) { ?>
            <div class="info_api" style="box-shadow: 0 10px 10px rgba(0, 0, 0, 0.1);margin-bottom:20px;"
                 id="info_api_<?php echo md5($v['id']) ?>">
                <div style="background:#f5f5f5;padding:20px;position:relative">
                    <div class="textshadow" style="position: absolute;right:0;top:4px;right:8px;">
                        最后修改者: <?php echo $v['login_name'] ?> &nbsp;<?php echo date('Y-m-d H:i:s', $v['lasttime']) ?>
                        &nbsp;
                        <?php if (is_supper()) { ?>
                            <button class="btn btn-danger btn-xs "
                                    onclick="deleteApi(<?php echo $v['id'] ?>,'<?php echo md5($v['id']) ?>')">删除
                            </button>&nbsp;
                            <button class="btn btn-info btn-xs "
                                    onclick="editApi('<?php echo U(array('act' => 'api', 'op' => 'edit', 'id' => $v['id'], 'tag' => $_GET['tag'])) ?>')">
                                编辑
                            </button>
                        <?php } ?>
                    </div>
                    <div>
                        <h4 class="textshadow"><?php echo $v['name'] ?></h4>
                        <div class="textshadow" style="position: absolute;right:0;top:34px;right:14px;">
                            <label for="is_invoked<?= $v['id'] ?>">
                                <input type="checkbox"
                                       onclick="changeInvokeStatus(this,<?= $v['id'] ?>,'<?= md5($v['id']) ?>')" <?= $v['status'] == 2 ? 'checked' : '' ?>
                                       name="is_invoked" id="is_invoked<?= $v['id'] ?>"> 标记为已读
                            </label>
                            <?php if (is_supper()): ?>
                                <label for="is_abandoned<?= $v['id'] ?>">
                                    <input type="checkbox"
                                           onclick="changeAbandonStatus(this,<?= $v['id'] ?>)" <?= $v['status'] == 3 ? 'checked' : '' ?>
                                           name="is_abandoned" id="is_abandoned<?= $v['id'] ?>"> 废弃
                                </label>
                            <?php endif; ?>
                            <?php if ($v['status'] == 3): ?>
                                <span>已废弃</span>
                            <?php endif; ?>
                            <?php if ($v['status'] == 4): ?>
                                <span>已更新</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p>
                        <!--                    <b>编号&nbsp;&nbsp;:&nbsp;&nbsp;<span style="color:red">-->
                        <?php //echo $v['num']?><!--</span></b>-->
                    </p>
                    <div>
                        <?php
                        $color = 'yellow';
                        if ($v['type'] == 'POST') {
                            $color = 'red';
                        }
                        ?>
                        <kbd style="color:<?php echo $color ?>"><?php echo $v['type'] ?></kbd> - <kbd
                                id="c_<?= $v['id'] ?>"><?= $v['url'] ?></kbd>
                        <button class="btn btn-xs" data-clipboard-target="#c_<?= $v['id'] ?>">复制 <span
                                    class="glyphicon glyphicon-copy" aria-hidden="true"></span></button>
                    </div>
                </div>
                <?php if (!empty($v['des'])): ?>
                    <div class="info">
                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                        &nbsp;
                        <?= $v['des'] ?>
                    </div>
                <?php endif; ?>
                <?php
                $parameter = unserialize($v['parameter']);
                $p_num = $parameter ? count($parameter['name']) : 0;
                ?>
                <?php if ($p_num): ?>
                    <div style="background:#ffffff;padding:20px;">
                        <!--                <h5 class="textshadow" >请求参数</h5>-->
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="col-md-3">请求参数</th>
                                <th class="col-md-2">参数类型</th>
                                <th class="col-md-2">必传</th>
                                <th class="col-md-2">默认值</th>
                                <th class="col-md-5">描述</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 0; $i < $p_num; $i++) { ?>
                                <tr>
                                    <td><?php echo $parameter['name'][$i] ?></td>
                                    <td><?php echo $parameter['paramType'][$i] ?></td>
                                    <td><?php if ($parameter['type'][$i] == 'Y') {
                                            echo '<span style="color:red">Y<span>';
                                        } else {
                                            echo '<span style="color:green">N<span>';
                                        } ?></td>
                                    <td><?php echo $parameter['default'][$i] ?></td>
                                    <td><?php echo $parameter['des'][$i] ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <?php if (!empty($v['re'])) { ?>
                    <div style="background:#ffffff;padding:20px;">
                        <h5 class="textshadow">返回值</h5>
                        <pre><?php echo $v['re'] ?></pre>
                    </div>
                <?php } ?>
                <?php if (!empty($v['memo'])) { ?>
                    <div style="background:#ffffff;padding:20px;">
                        <h5 class="textshadow">备注</h5>
                        <pre style="background:honeydew"><?php echo $v['memo'] ?></pre>
                    </div>
                <?php } ?>
            </div>
            <!--接口详细列表end-->
            <!--接口详情返回顶部按钮start-->
            <div id="gotop" onclick="goTop()"
                 style="z-index:999999;font-size:18px;display:none;color:#e6e6e6;cursor:pointer;width:42px;height:42px;border:#ddd 1px solid;line-height:42px;text-align:center;background:rgba(91,192,222, 0.8);position:fixed;right:20px;bottom:200px;border-radius:50%;box-shadow: 0px 0px 0px 1px #cccccc;">
                T
            </div>
            <!--接口详情返回顶部按钮end-->
        <?php } ?>
    <?php } else { ?>
        <div style="font-size:16px;">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 此分类下还没有任何接口
        </div>
    <?php } ?>
    <script>
        // 删除某个接口
        var $url = '<?php echo U(array('act' => 'ajax', 'op' => 'apiDelete'))?>';

        function deleteApi(apiId, divId) {
            if (confirm('是否确认删除此接口?')) {
                $.post($url, {id: apiId}, function (data) {
                    if (data == '1') {
                        $('#api_' + divId).remove();//删除左侧菜单
                        $('#info_api_' + divId).remove();//删除接口详情
                    }
                })
            }
        }

        // 更新接口状态
        var $url2 = '<?php echo U(array('act' => 'ajax', 'op' => 'changeInvokeStatus'))?>';

        function changeInvokeStatus(obj, id, span_id) {
            $.post($url2, {id: id}, function (data) {
                if (data != '0') {
                    $('#span_' + span_id).html(data);
                }
            })
        }

        // 更新接口状态
        var $url3 = '<?php echo U(array('act' => 'ajax', 'op' => 'changeAbandonStatus'))?>';

        function changeAbandonStatus(obj, id) {
            $.post($url3, {id: id}, function (data) {
                if (data == '1') {
                    //
                }
            })
        }

        //编辑某个接口
        function editApi(gourl) {
            window.location.href = gourl;
        }

        //返回顶部
        function goTop() {
            $('#mainwindow').animate(
                {scrollTop: '0px'}, 200
            );
        }

        //检测滚动条,显示返回顶部按钮
        document.getElementById('mainwindow').onscroll = function () {
            if (document.getElementById('mainwindow').scrollTop > 100) {
                document.getElementById('gotop').style.display = 'block';
            } else {
                document.getElementById('gotop').style.display = 'none';
            }
        };
    </script>
<?php } ?>
<!--接口详情列表与接口管理end-->
<script src="./MinPHP/res/jquery.min.js"></script>
<script src="./MinPHP/res/clipboard.js"></script>
<script type="text/javascript">
    new ClipboardJS('.btn');
</script>