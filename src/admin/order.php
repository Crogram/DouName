<?php
include("../includes/common.php");
$title = '订单管理';
include './head.php';
if ($admin_islogin != 1) exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>

<div class="container" style="padding-top:70px;">
    <div class="col-xs-12 center-block" style="float: none;">
        <div id="searchToolbar">
            <form onsubmit="return searchSubmit()" method="GET" class="form-inline">
                <input type="hidden" name="did">
                <div class="form-group">
                    <label>搜索</label>
                    <input type="text" class="form-control" name="domain" placeholder="域名">
                </div>
                <select class="form-control" name="order_type">
                    <option value="">订单类型</option>
                    <option value="注册">注册</option>
                    <option value="续费">续费</option>
                    <option value="购买">购买</option>
                    <option value="转入">转入</option>
                    <option value="转出">转出</option>
                    <option value="售出">售出</option>
                </select>
                <select class="form-control" name="order_provider">
                    <option value="">订单服务商</option>
                    <option value="aliyun">阿里云</option>
                    <option value="tencent">腾讯云</option>
                    <option value="juming">聚名网</option>
                    <option value="xinnet">新网</option>
                    <option value="huaweicloud">华为云</option>
                    <option value="zzidc">景安</option>
                    <option value="72e">联动天下</option>
                    <option value="google">Google</option>
                    <option value="cloudflare">Cloudflare</option>
                </select>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜索</button>
                    <a href="javascript:searchClear()" class="btn btn-default" title="重置筛选列表"><i class="fa fa-repeat"></i> 重置</a>
                    <a href="javascript:itemAdd()" class="btn btn-success"><i class="fa fa-plus"></i> 添加</a>
                </div>
            </form>
        </div>

        <table id="listTable"></table>
    </div>
</div>

<form class="form-horizontal" id="form-store" style="padding:15px 15px 0 15px;display: none;">
    <input type="hidden" name="act" value="add" />
    <input type="hidden" name="id" value="" />
    <div class="form-group">
        <label class="col-sm-3 control-label">订单域名</label>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="order_domain" value="" required maxlength="225" autocomplete="off" placeholder="请输入域名">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">订单类型</label>
        <div class="col-sm-9">
            <select name="order_type" class="form-control">
                <option value="">订单类型</option>
                <option value="注册">注册</option>
                <option value="续费">续费</option>
                <option value="购买">购买</option>
                <option value="转入">转入</option>
                <option value="转出">转出</option>
                <option value="售出">售出</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">订单服务商</label>
        <div class="col-sm-9">
            <select name="order_provider" class="form-control">
            <option value="">订单服务商</option>
                <option value="aliyun">阿里云</option>
                <option value="tencent">腾讯云</option>
                <option value="juming">聚名网</option>
                <option value="xinnet">新网</option>
                <option value="huaweicloud">华为云</option>
                <option value="zzidc">景安</option>
                <option value="72e">联动天下</option>
                <option value="google">Google</option>
                <option value="cloudflare">Cloudflare</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">下单时间</label>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="create_time" autocomplete="off" placeholder="请输入下单时间">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">订单金额</label>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="order_costs" required autocomplete="off" placeholder="请输入订单金额">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">备注</label>
        <div class="col-sm-9">
            <input class="form-control" type="text" name="order_remark" autocomplete="off" placeholder="请输入备注信息">
        </div>
    </div>
</form>

<script src="<?php echo $cdnpublic; ?>layer/3.1.1/layer.js"></script>
<script src="<?php echo $cdnpublic; ?>bootstrap-table/1.20.2/bootstrap-table.min.js"></script>
<script src="<?php echo $cdnpublic; ?>bootstrap-table/1.20.2/extensions/page-jump-to/bootstrap-table-page-jump-to.min.js"></script>
<script src="../assets/laydate/5.3.1/laydate.js"></script>
<script src="../assets/js/custom.js"></script>
<script>
    $(document).ready(function() {
        updateToolbar();
        const defaultPageSize = 15;
        const pageNumber = typeof window.$_GET['pageNumber'] != 'undefined' ? parseInt(window.$_GET['pageNumber']) : 1;
        const pageSize = typeof window.$_GET['pageSize'] != 'undefined' ? parseInt(window.$_GET['pageSize']) : defaultPageSize;

        $("#listTable").bootstrapTable({
            url: 'ajax_order.php',
            pageNumber: pageNumber,
            pageSize: pageSize,
            classes: 'table table-striped table-hover table-bordered',
            columns: [{
                    field: 'order_id',
                    title: 'ID',
                    formatter: function(value, row, index) {
                        return '<b>' + value + '</b>';
                    }
                },
                {
                    field: 'order_domain',
                    title: '域名',
                    formatter: function(value, row, index) {
                        var html = '<a href="domain.php?kw=' + value + '">' + value + '</a>';
                        return html;
                    }
                },
                {
                    field: 'order_type',
                    title: '订单类型',
                    formatter: function(value, row, index) {
                        switch (String(value)) {
                            case '注册':
                                return '注册';
                                break;
                            case '续费':
                                return '续费';
                                break;
                            case '购买':
                                return '购买';
                                break;
                            case '转入':
                                return '转入';
                                break;
                            case '转出':
                                return '转出';
                                break;
                            case '售出':
                                return '售出';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'order_provider',
                    title: '订单服务商',
                    formatter: function(value, row, index) {
                        switch (String(value)) {
                            case 'aliyun':
                                return '阿里云';
                                break;
                            case 'tencent':
                                return '腾讯云';
                                break;
                            case 'juming':
                                return '聚名网';
                                break;
                            case 'xinnet':
                                return '新网';
                                break;
                            case 'huaweicloud':
                                return '华为云';
                                break;
                            case '72e':
                                return '联动天下';
                                break;
                            case 'google':
                                return 'Google';
                                break;
                            case 'zzidc':
                                return '景安';
                                break;
                            case 'cloudflare':
                                return 'Cloudflare';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'order_costs',
                    title: '订单金额',
                    formatter: function(value, row, index) {
                        return value ? + value + ' 元' : null;
                    }
                },
                {
                    field: 'create_time',
                    title: '订单时间'
                },
                {
                    field: 'order_remark',
                    title: '订单备注',
                    formatter: function(value, row, index) {
                        return '<span onClick="appEditRemarks(\'' + row.order_id + '\', \'' + (value || '') + '\')">' + (value || '-') + '</span>';
                    }
                },
                {
                    field: '',
                    title: '操作',
                    formatter: function(value, row, index) {
                        var html = '<a href="javascript:itemUpdate(' + row.order_id + ')" class="btn btn-info btn-xs">编辑</a> <a href="javascript:itemDelete(' + row.order_id + ')" class="btn btn-danger btn-xs">删除</a>';
                        return html;
                    }
                },
                // {
                //     field: 'status',
                //     title: '回调结果',
                //     formatter: function(value, row, index) {
                //         return value == 1 ? '<span class="label label-success">成功</span>' : '<span class="label label-default">失败</span>';
                //     }
                // }
            ]
        })
    })
    laydate.render({
        elem: '[name="create_time"]' // 指定元素
        ,type: 'datetime'
        ,theme: 'grid'
    });

    function itemAdd() {
        $("#form-store")[0].reset();
        $("#form-store input[name='act']").val('add');
        layer.open({
            type: 1,
            area: ['430px'],
            closeBtn: 2,
            title: '添加订单',
            content: $('#form-store'),
            btn: ['保存', '取消'],
            yes: function() {
                var order_domain = $("#form-store input[name='order_domain']").val();
                if (order_domain == '') {
                    layer.msg('域名不能为空');
                    $("#form-store input[name='order_domain']").focus();
                    return;
                }
                var order_type = $("#form-store select[name='order_type']").val();
                if (order_type == '') {
                    layer.msg('订单类型不能为空');
                    $("#form-store select[name='order_type']").focus();
                    return;
                }
                var order_provider = $("#form-store select[name='order_provider']").val();
                if (order_provider == '') {
                    layer.msg('订单服务商不能为空');
                    $("#form-store select[name='order_provider']").focus();
                    return;
                }
                var order_costs = $("#form-store input[name='order_costs']").val();
                if (order_costs == '') {
                    layer.msg('金额不能为空');
                    $("#form-store select[name='order_costs']").focus();
                    return;
                }
                var create_time = $("#form-store input[name='create_time']").val();
                if (create_time == '') {
                    layer.msg('下单时间不能为空');
                    $("#form-store select[name='create_time']").focus();
                    return;
                }
                var ii = layer.load(2, {
                    shade: [0.1, '#fff']
                });
                $.ajax({
                    type: 'POST',
                    url: 'ajax_order.php',
                    data: $("#form-store").serialize(),
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
                                icon: 0
                            });
                        }
                    },
                    error: function(data) {
                        layer.close(ii);
                        layer.msg('服务器错误');
                    }
                });
            }
        });
    }

    function itemUpdate(id) {
        $("#form-store")[0].reset();
        $("#form-store input[name='id']").val(id);
        $("#form-store input[name='act']").val('update');
        var ii = layer.load(2);
        $.ajax({
            type: 'GET',
            url: 'ajax_order.php',
            data: {
                act: 'info',
                id: id
            },
            dataType: 'json',
            success: function(data) {
                layer.close(ii);
                if (data.code == 0) {
                    $("#form-store input[name='order_domain']").val(data.data.order_domain);
                    $("#form-store select[name='order_type']").val(data.data.order_type);
                    $("#form-store select[name='order_provider']").val(data.data.order_provider);
                    $("#form-store input[name='create_time']").val(data.data.create_time);
                    $("#form-store input[name='order_costs']").val(data.data.order_costs);
                    $("#form-store input[name='order_remark']").val(data.data.order_remark);
                    layer.open({
                        type: 1,
                        area: ['430px'],
                        closeBtn: 2,
                        title: '编辑订单信息',
                        content: $('#form-store'),
                        btn: ['保存', '取消'],
                        yes: function() {
                            var order_domain = $("#form-store input[name='order_domain']").val();
                            if (order_domain == '') {
                                layer.msg('域名不能为空');
                                $("#form-store input[name='order_domain']").focus();
                                return;
                            }
                            var order_type = $("#form-store select[name='order_type']").val();
                            if (order_type == '') {
                                layer.msg('订单类型不能为空');
                                $("#form-store select[name='order_type']").focus();
                                return;
                            }
                            var order_provider = $("#form-store select[name='order_provider']").val();
                            if (order_provider == '') {
                                layer.msg('订单服务商不能为空');
                                $("#form-store select[name='order_provider']").focus();
                                return;
                            }
                            var order_costs = $("#form-store input[name='order_costs']").val();
                            if (order_costs == '') {
                                layer.msg('金额不能为空');
                                $("#form-store input[name='order_costs']").focus();
                                return;
                            }
                            var create_time = $("#form-store input[name='create_time']").val();
                            if (create_time == '') {
                                layer.msg('下单时间不能为空');
                                $("#form-store input[name='create_time']").focus();
                                return;
                            }
                            var ii = layer.load(2, {
                                shade: [0.1, '#fff']
                            });
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_order.php',
                                data: $("#form-store").serialize(),
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
                                            icon: 0
                                        });
                                    }
                                },
                                error: function(data) {
                                    layer.close(ii);
                                    layer.msg('服务器错误');
                                }
                            });
                        }
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
    function appEditRemarks(id, current) {
        layer.prompt({
                title: '请输入备注信息',
                value: current || '',
            },
            function(value, index, elem) {
                // if (value === '') return elem.focus();
                $.ajax({
                    type: 'post',
                    url: 'ajax_order.php',
                    data: {
                        act: 'remark',
                        id: id,
                        remark: value
                    },
                    dataType: 'json',
                    success: function(ret) {
                        layer.close(index);
                        if (ret.code != 0) {
                            layer.msg(ret.msg);
                        }
                        searchSubmit();
                    },
                    error: function(data) {
                        layer.close(index);
                        layer.msg('服务器错误');
                    }
                });
            }
        );
    }

    function itemDelete(id) {
        layer.confirm('确定要删除此订单吗 ？', {
            icon: 3,
            btn: ['确定', '取消']
        }, function() {
            $.ajax({
                type: 'POST',
                url: 'ajax_order.php',
                data: {
                    act: 'delete',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.code == 0) {
                        layer.msg('删除成功', {
                            icon: 1,
                            time: 1000
                        });
                        searchSubmit();
                    } else {
                        layer.alert(data.msg, {
                            icon: 2
                        });
                    }
                }
            });
        });
    }
</script>

</body>

</html>