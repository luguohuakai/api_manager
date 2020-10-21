<?php
defined('API') or exit('https://srun.com');
if (!is_login()) {
    die('请登录');
}
// 选择所有的项目
$list = select('select * from subject order by updated_at desc limit 100');
?>
<style>
    .thumbnail:hover {
        background: #eee;
    }

    .note {
        color: #999;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<!--欢迎页-->
<!--info start-->
<div style="font-size:18px;">
    <div class="info" style="font-size:14px;">
        <span style="font-size:30px;" class="glyphicon glyphicon-grain" aria-hidden="true"></span>
        <span style="font-size:16px;">【SRUN】项目<span
                    style="font-size:12px;position:relative;top:-7px;"> <?= C('version->no') ?></span></span>
        <div class="info" style="margin:10px 34px 10px 34px;">
            <div class="row">
                <a href="<?= U() ?>">
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail">
                            <!--                        <img src="..." alt="......">-->
                            <div class="caption">
                                <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                                <h3>全部项目</h3>
                                <p class="note">All Subjects</p>
                                <!--                            <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>-->
                            </div>
                        </div>
                    </div>
                </a>
                <?php if ($list): ?>
                    <?php foreach ($list as $item): ?>
                        <div class="col-sm-6 col-md-3">
                            <div class="thumbnail">
                                <!--                        <img src="..." alt="......">-->
                                <div class="caption">
                                    <span class="glyphicon glyphicon-align-left" aria-hidden="true"
                                          data-bizid="<?= $item['id'] ?>" data-toggle="modal" data-target="#createModal"
                                          data-name="<?= $item['name'] ?>"
                                          data-note="<?= $item['note'] ?>"
                                          data-whatever="@edit"></span>
                                    <a href="<?= U(['act' => 'subject', 'biz_id' => $item['id']]) ?>">
                                        <div>
                                            <h3 style="color: #000;"><?= $item['name'] ?></h3>
                                            <p class="note"><?= $item['note'] ?></p>
                                        </div>
                                    </a>
                                    <!--                            <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>-->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if (is_supper()): ?>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#createModal"
                       data-whatever="@create">
                        <div class="col-sm-6 col-md-3">
                            <div class="thumbnail">
                                <!--                        <img src="..." alt="......">-->
                                <div class="caption" style="color: blueviolet">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <h3>创建项目</h3>
                                    <p class="note">New Subject</p>
                                    <!--                            <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>-->
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div style="font-size:12px;position:absolute;bottom:0;right:20px;height:20px;text-align:right;">
        <span>
            路过花开 | qq : 769245396 | <a target="_blank" href="http://www.srun.com/">深澜软件</a>
        </span>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="createModalLabel">创建项目</h4>
            </div>
            <form method="post" action="<?= U(['act' => 'subject_handle']) ?>">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="subject-id" name="id">
                        <label for="subject-name" class="control-label">项目名称:</label>
                        <input type="text" class="form-control" id="subject-name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="subject-text" class="control-label">项目描述:</label>
                        <textarea class="form-control" id="subject-text" name="note"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary modal-handle-yes">创建</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/api/MinPHP/res/jquery.min.js"></script>
<script>
    $(function () {
        $('#createModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let recipient = button.data('whatever');
            let modal = $(this);
            if (recipient === '@edit') {
                modal.find('.modal-title').text('编辑项目');
                modal.find('.modal-handle-yes').text('更新');
                modal.find('#subject-name').val(button.data('name'));
                modal.find('#subject-text').val(button.data('note'));
                modal.find('#subject-id').val(button.data('bizid'));
            }
        })
    })

    // 更新
</script>