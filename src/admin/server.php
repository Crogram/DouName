<?php
include("../includes/common.php");
$title = '域名管理';
include './head.php';
if ($admin_islogin != 1) exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>

<div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
        <form onsubmit="return searchSubmit()" method="GET" class="form-inline" id="searchToolbar">
            <input type="hidden" name="server_id" />
            <div class="form-group">
                <label>搜索</label>
                <input type="text" class="form-control" name="kw" placeholder="要搜索的域名">
            </div>
            <select class="form-control" name="server_status">
                <option value="">域名状态</option>
                <option value="1">有效</option>
                <option value="0">无效</option>
            </select>
            <select class="form-control" name="server_provider">
                <option value="">域名服务商</option>
                <option value="aliyun">阿里云</option>
                <option value="tencent">腾讯云</option>
                <option value="juming">聚名网</option>
                <option value="xinnet">新网</option>
                <option value="huaweicloud">华为云</option>
                <option value="zzidc">景安</option>
                <option value="72e">联动天下</option>
                <option value="google">Google</option>
                <option value="cloudflare">Cloudflare</option>
                <option value="squarespace">Squarespace</option>
            </select>
            <select class="form-control" name="server_region">
                <option value="">域名注册商</option>
                <option value="aliyun">阿里云</option>
                <option value="tencent">腾讯云</option>
                <option value="juming">聚名网</option>
                <option value="xinnet">新网</option>
                <option value="72e">联动天下</option>
                <option value="cloudflare">Cloudflare</option>
                <option value="squarespace">Squarespace</option>
            </select>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> 搜索</button>
            <a href="javascript:searchClear()" class="btn btn-default" title="重置筛选列表"><i class="fa fa-refresh"></i> 重置</a>
            <a href="javascript:appAddServer()" class="btn btn-success"><i class="fa fa-plus"></i> 添加</a>
        </form>

        <table id="listTable"></table>
    </div>
</div>
<script src="<?php echo $cdnpublic; ?>layer/3.1.1/layer.js"></script>
<script src="<?php echo $cdnpublic; ?>bootstrap-table/1.20.2/bootstrap-table.min.js"></script>
<script src="<?php echo $cdnpublic; ?>bootstrap-table/1.20.2/extensions/page-jump-to/bootstrap-table-page-jump-to.min.js"></script>
<script src="../assets/js/custom.js"></script>
<script>
    $(document).ready(function() {
        updateToolbar();
        const defaultPageSize = 2;
        const pageNumber = typeof window.$_GET['pageNumber'] != 'undefined' ? parseInt(window.$_GET['pageNumber']) : 1;
        const pageSize = typeof window.$_GET['pageSize'] != 'undefined' ? parseInt(window.$_GET['pageSize']) : defaultPageSize;

        $("#listTable").bootstrapTable({
            url: 'ajax_server.php?act=list',
            pageNumber: pageNumber,
            pageSize: pageSize,
            classes: 'table table-striped table-hover table-bordered',
            columns: [{
                    field: 'server_id',
                    title: 'ID'
                },
                {
                    field: 'server_name',
                    title: '名称',
                    formatter: function(value, row, index) {
                        return '<a href="http://' + value.replace('*.', 'www.') + '/" target="_blank" rel="noopener noreferrer">' + value + '</a>';
                    }
                },
                {
                    field: 'server_status',
                    title: '状态',
                    formatter: function(value, row, index) {
                        switch (value) {
                            case '1':
                                return '<a href="javascript:appSetStatus(' + row.server_id + ', 0)" class="btn btn-success btn-xs" title="点击设置为无效">有效</a>';
                                break;
                            case '0':
                                return '<a href="javascript:appSetStatus(' + row.server_id + ', 1)" class="btn btn-warning btn-xs" title="点击设置为有效">无效</a>';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'server_ip',
                    title: '服务器IP',
                    formatter: function(value, row, index) {
                        switch (String(row.server_ip_type)) {
                            case 'dedicated':
                                return '独享' + value;
                                break;
                            case 'shared':
                                return '共享' + value;
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'server_type',
                    title: '托管类型',
                    formatter: function(value, row, index) {
                        switch (String(value)) {
                            case 'dedicated':
                                return '独享';
                                break;
                            case 'shared':
                                return '共享主机';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'server_region',
                    title: '服务器位置',
                },
                {
                    field: 'server_remark',
                    title: '备注',
                    formatter: function(value, row, index) {
                        return '<span onClick="appEditRemarks(\'' + row.server_id + '\', \'' + (value || '') + '\')">' + (value || '-') + '</span>';
                    }
                },
                {
                    field: 'server_provider',
                    title: '服务商',
                    formatter: function(value, row, index) {
                        switch (String(value)) {
                            case 'aliyun':
                                return '阿里云';
                                break;
                            case 'tencent':
                                return '腾讯云';
                                break;
                            case 'zzidc':
                                return '景安';
                                break;
                            case 'ucloud':
                                return '优刻得';
                                break;
                            case 'virmach':
                                return 'VirMach';
                                break;
                            case 'amazon':
                                return '亚马逊';
                                break;
                            case 'raksmart':
                                return 'Raksmart';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'server_expire_time',
                    title: '到期时间'
                },
                {
                    field: 'server_create_time',
                    title: '购买时间'
                },
                {
                    field: 'update_time',
                    title: '修改时间'
                },
                {
                    field: '',
                    title: '操作',
                    formatter: function(value, row, index) {
                        var html = '<a href="./server_order.php?order_server_id=' + row.server_id + '" class="btn btn-primary btn-xs">订单</a>';
                        // html += ' <a href="javascript:appWhoisServer(\'' + row.server_name + '\')" class="btn btn-info btn-xs">WHOIS</a>';
                        // html += ' <a href="javascript:appUpdateServer(' + row.server_id + ')" class="btn btn-default btn-xs">更新时间</a>';
                        // html += ' <a href="javascript:appDelServer(' + row.server_id + ')" class="btn btn-danger btn-xs">删除</a>';
                        return html;
                    }
                },
            ],
        })
    })

    function appAddServer() {
        var adduid = $("input[name='uid']").val();
        layer.open({
            type: 1,
            area: ['350px'],
            closeBtn: 2,
            title: '添加域名',
            content: '<div style="padding:15px 15px 0 15px"><div class="form-group"><input class="form-control" type="text" name="content" value="" autocomplete="off" placeholder="请输入域名，顶级域名，不带www"></div></div>',
            btn: ['确认', '取消'],
            yes: function() {
                var content = $("input[name='content']").val();
                if (content == '') {
                    $("input[name='content']").focus();
                    return;
                }
                var ii = layer.load(2, {
                    shade: [0.1, '#fff']
                });
                $.ajax({
                    type: 'POST',
                    url: 'ajax_server.php',
                    data: {
                        act: 'add',
                        server: content
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

    function appEditRemarks(id, current) {
        layer.prompt({
                title: '请输入域名备注',
                value: current || '',
            },
            function(value, index, elem) {
                // if (value === '') return elem.focus();
                $.ajax({
                    type: 'post',
                    url: 'ajax_server.php',
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

    function appSetStatus(id, status) {
        var ii = layer.load(2, {
            shade: [0.1, '#fff']
        });
        $.ajax({
            type: 'post',
            url: 'ajax_server.php',
            data: {
                act: 'set',
                id: id,
                status: status
            },
            dataType: 'json',
            success: function(ret) {
                layer.close(ii);
                if (ret.code != 0) {
                    alert(ret.msg);
                }
                searchSubmit();
            },
            error: function(data) {
                layer.close(ii);
                layer.msg('服务器错误');
            }
        });
    }

    function appUpdateServer(id) {
        layer.confirm('更新此域名到期日期吗 ？', {
            icon: 3,
            btn: ['确定', '取消']
        }, function() {
            $.ajax({
                type: 'POST',
                url: 'ajax_server.php',
                data: {
                    act: 'update',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.code == 0) {
                        layer.msg('更新成功', {
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

    function appDelServer(id) {
        layer.confirm('确定要删除此域名吗 ？', {
            icon: 3,
            btn: ['确定', '取消']
        }, function() {
            $.ajax({
                type: 'POST',
                url: 'ajax_server.php',
                data: {
                    act: 'del',
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

    function appWhoisServer(server) {
        var ii = layer.load(2, {
            shade: [0.1, '#fff']
        });
        $.ajax({
            type: 'POST',
            url: 'ajax_server.php',
            data: {
                act: 'whois',
                server: server
            },
            dataType: 'json',
            success: function(data) {
                layer.close(ii);
                if (data.code == 0) {
                    layer.open({
                        type: 1,
                        title: '域名信息查看', // 不显示标题栏
                        closeBtn: 0,
                        area: ['700px', '500px'], // 宽高
                        content: '<div style="padding: 15px;"><pre>' + data.data + '</pre></div>',
                        btn: ['关闭'],
                    });
                } else {
                    layer.alert(data.msg || '查询异常', {
                        icon: 2
                    });
                }
            }
        });
    }
</script>