<?php
include("../includes/common.php");
$title = '页面管理';
include './head.php';
if ($admin_islogin != 1) exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>

<div class="modal" id="modal-store" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content animated flipInX">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modal-title">停放页面 修改/添加</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form-store">
                    <input type="hidden" name="action" id="action" />
                    <input type="hidden" name="id" id="id" />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">类别</label>
                        <div class="col-sm-9">
                            <select name="type" id="type" class="form-control">
                                <option value="0">微信公众号</option>
                                <option value="1">微信小程序</option>
                                <option value="2">微信开放平台</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">名称</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="name" id="name" placeholder="仅用于显示，不要重复">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">APPID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="appid" id="appid" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label no-padding-right">APPSECRET</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="appsecret" id="appsecret" placeholder="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="store" onclick="save()">保存</button>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
        <form onsubmit="return searchSubmit()" method="GET" class="form-inline" id="searchToolbar">
            <div class="form-group">
                <label>搜索</label>
                <input type="text" class="form-control" name="kw" placeholder="名称或APPID">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
            <a href="javascript:searchClear()" class="btn btn-default" title="重置筛选列表"><i class="fa fa-refresh"></i> 重置</a>
            <a href="javascript:addframe()" class="btn btn-success"><i class="fa fa-plus"></i> 添加</a>
        </form>
        <table id="listTable"></table>
    </div>
</div>

<script src="<?php echo $cdnpublic; ?>bootstrap-table/1.20.2/bootstrap-table.min.js"></script>
<script src="<?php echo $cdnpublic; ?>bootstrap-table/1.20.2/extensions/page-jump-to/bootstrap-table-page-jump-to.min.js"></script>
<script src="../assets/js/custom.js"></script>
<script>
    $(document).ready(function() {
        updateToolbar();
        const defaultPageSize = 15;
        const pageNumber = typeof window.$_GET['pageNumber'] != 'undefined' ? parseInt(window.$_GET['pageNumber']) : 1;
        const pageSize = typeof window.$_GET['pageSize'] != 'undefined' ? parseInt(window.$_GET['pageSize']) : defaultPageSize;

        $("#listTable").bootstrapTable({
            url: 'ajax_pages.php?act=list',
            pageNumber: pageNumber,
            pageSize: pageSize,
            classes: 'table table-striped table-hover table-bordered',
            columns: [{
                    field: 'id',
                    title: 'ID'
                },
                {
                    field: 'type',
                    title: '类别',
                    formatter: function(value, row, index) {
                        switch (String(value)) {
                            case '0':
                                return '微信公众号';
                                break;
                            case '1':
                                return '微信小程序';
                                break;
                            case '2':
                                return '微信开放平台';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'name',
                    title: '名称'
                },
                {
                    field: 'appid',
                    title: 'APPID'
                },
                {
                    field: 'create_time',
                    title: '添加时间'
                },
                {
                    field: 'updatetime',
                    title: '最后更新时间'
                },
                {
                    field: '',
                    title: '操作',
                    formatter: function(value, row, index) {
                        var html = '<a href="javascript:editframe(' + row.id + ')" class="btn btn-info btn-xs">编辑</a> <a href="javascript:delItem(' + row.id + ')" class="btn btn-danger btn-xs">删除</a> <a href="javascript:textwxtoken(' + row.id + ')" class="btn btn-default btn-xs" title="同时强制刷新 Token">测试</a>';
                        return html;
                    }
                },
            ],
        })
    })

    function addframe() {
        $("#modal-store").modal('show');
        $("#modal-title").html("新增域名停放页");
        $("#action").val("add");
        $("#id").val('');
        $("#type").val(0);
        $("#name").val('');
        $("#appid").val('');
        $("#appsecret").val('');
    }

    function editframe(id) {
        var ii = layer.load(2);
        $.ajax({
            type: 'GET',
            url: 'ajax_pages.php',
            data: {
                act: 'info',
                id: id
            },
            dataType: 'json',
            success: function(data) {
                layer.close(ii);
                if (data.code == 0) {
                    $("#modal-store").modal('show');
                    $("#modal-title").html("修改域名停放页");
                    $("#action").val("edit");
                    $("#id").val(data.data.id);
                    $("#type").val(data.data.type);
                    $("#name").val(data.data.name);
                    $("#appid").val(data.data.appid);
                    $("#appsecret").val(data.data.appsecret);
                } else {
                    layer.alert(data.msg, {
                        icon: 2
                    })
                }
            },
            error: function(data) {
                layer.close(ii);
                layer.msg('服务器错误');
            }
        });
    }

    function save() {
        if ($("#name").val() == '' || $("#appid").val() == '' || $("#appsecret").val() == '') {
            layer.alert('请确保各项不能为空！');
            return false;
        }
        var ii = layer.load(2);
        $.ajax({
            type: 'POST',
            url: 'ajax_pages.php?act=save',
            data: $("#form-store").serialize(),
            dataType: 'json',
            success: function(data) {
                layer.close(ii);
                if (data.code == 0) {
                    layer.alert(data.msg, {
                        icon: 1,
                        closeBtn: false
                    }, function() {
                        window.location.reload()
                    });
                } else {
                    layer.alert(data.msg, {
                        icon: 2
                    })
                }
            },
            error: function(data) {
                layer.close(ii);
                layer.msg('服务器错误');
            }
        });
    }

    function delItem(id) {
        var confirmobj = layer.confirm('你确实要删除此域名停放页吗？', {
            btn: ['确定', '取消']
        }, function() {
            var ii = layer.load(2);
            $.ajax({
                type: 'POST',
                url: 'ajax_pages.php',
                data: {
                    act: 'del',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    layer.close(ii);
                    if (data.code == 0) {
                        layer.alert(data.msg, {
                            icon: 1,
                            closeBtn: false
                        }, function() {
                            window.location.reload()
                        });
                    } else {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                    }
                },
                error: function(data) {
                    layer.close(ii);
                    layer.msg('服务器错误');
                }
            });
        }, function() {
            layer.close(confirmobj);
        });
    }

    function textwxtoken(id) {
        var confirmobj = layer.confirm('你确实要测试接口是否可用 ？<br />本操作将同时强制刷新 Token ?<br />注意：每日调用上限2000次', {
            title: '请谨慎操作 ！',
            btn: ['确定', '取消']
        }, function() {
            var ii = layer.load(2);
            $.ajax({
                type: 'POST',
                url: 'ajax_pages.php',
                data: {
                    act: 'checkapi',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    layer.close(ii);
                    if (data.code == 0) {
                        layer.alert(data.msg, {
                            icon: 1
                        }, function() {
                            layer.closeAll();
                            searchSubmit()
                        });
                    } else {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                    }
                },
                error: function(data) {
                    layer.close(ii);
                    layer.msg('服务器错误');
                }
            });
        }, function() {
            layer.close(confirmobj);
        });
    }
</script>

</body>

</html>