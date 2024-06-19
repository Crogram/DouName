<?php
include("../includes/common.php");
$title = '域名管理';
include './head.php';
if ($admin_islogin != 1) exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>

<div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
        <form onsubmit="return searchSubmit()" method="GET" class="form-inline" id="searchToolbar">
            <div class="form-group">
                <label>搜索</label>
                <input type="text" class="form-control" name="kw" placeholder="要搜索的域名">
            </div>
            <select class="form-control" name="domain_status">
                <option value="">域名状态</option>
                <option value="1" selected>有效</option>
                <option value="0">无效</option>
            </select>
            <select class="form-control" name="domain_provider">
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
            <select class="form-control" name="domain_registrar">
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
            <a href="javascript:appAddDomain()" class="btn btn-success"><i class="fa fa-plus"></i> 添加</a>
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
        const defaultPageSize = 15;
        const pageNumber = typeof window.$_GET['pageNumber'] != 'undefined' ? parseInt(window.$_GET['pageNumber']) : 1;
        const pageSize = typeof window.$_GET['pageSize'] != 'undefined' ? parseInt(window.$_GET['pageSize']) : defaultPageSize;

        $("#listTable").bootstrapTable({
            url: 'ajax_domain.php?act=list',
            pageNumber: pageNumber,
            pageSize: pageSize,
            classes: 'table table-striped table-hover table-bordered',
            columns: [{
                    field: 'domain_id',
                    title: 'ID'
                },
                {
                    field: 'domain_name',
                    title: '域名',
                    formatter: function(value, row, index) {
                        return '<a href="http://' + value.replace('*.', 'www.') + '/" target="_blank" rel="noopener noreferrer">' + value + '</a>';
                    }
                },
                {
                    field: 'domain_status',
                    title: '状态',
                    formatter: function(value, row, index) {
                        switch (value) {
                            case '1':
                                return '<a href="javascript:appSetStatus(' + row.domain_id + ', 0)" class="btn btn-success btn-xs" title="点击设置为无效">有效</a>';
                                break;
                            case '0':
                                return '<a href="javascript:appSetStatus(' + row.domain_id + ', 1)" class="btn btn-warning btn-xs" title="点击设置为有效">无效</a>';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'domain_remark',
                    title: '备注',
                    formatter: function(value, row, index) {
                        return '<span onClick="appEditRemarks(\'' + row.domain_id + '\', \'' + (value || '') + '\')">' + (value || '-') + '</span>';
                    }
                },
                {
                    field: 'domain_icp',
                    title: '备案号'
                },
                {
                    field: 'domain_provider',
                    title: '服务商',
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
                            case 'zzidc':
                                return '景安';
                                break;
                            case 'google':
                                return 'Google';
                                break;
                            case 'cloudflare':
                                return 'Cloudflare';
                                break;
                            case 'squarespace':
                                return 'Squarespace';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'domain_registrar',
                    title: '注册商',
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
                            case '72e':
                                return '联动天下';
                                break;
                            case 'google':
                                return 'Google';
                                break;
                            case 'cloudflare':
                                return 'Cloudflare';
                                break;
                            case 'squarespace':
                                return 'Squarespace';
                                break;
                            default:
                                return value;
                        }
                    }
                },
                {
                    field: 'domain_expire_time',
                    title: '到期时间'
                },
                {
                    field: 'domain_create_time',
                    title: '注册时间'
                },
                {
                    field: 'update_time',
                    title: '修改时间'
                },
                {
                    field: '',
                    title: '操作',
                    width: '120',
                    formatter: function(value, row, index) {
                        var html = '<a href="./order.php?domain=' + row.domain_name + '" class="btn btn-primary btn-xs">订单</a>';
                        // html += '<a href="./domain_details.php?domain=' + row.domain_name + '" class="btn btn-primary btn-xs">详情</a>';
                        html += ' <a href="javascript:appWhoisDomain(\'' + row.domain_name + '\')" class="btn btn-info btn-xs">WHOIS</a>';
                        // html += ' <a href="javascript:appUpdateDomain(' + row.domain_id + ')" class="btn btn-default btn-xs">更新时间</a>';
                        // html += ' <a href="javascript:appDelDomain(' + row.domain_id + ')" class="btn btn-danger btn-xs">删除</a>';
                        // html += ' <a href="record.php?domain=' + row.domain_name + '" class="btn btn-primary btn-xs">解析</a>';
                        return html;
                    }
                },
            ],
        })
    })

    function appAddDomain() {
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
                    url: 'ajax_domain.php',
                    data: {
                        act: 'add',
                        domain: content
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
                    url: 'ajax_domain.php',
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
            url: 'ajax_domain.php',
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

    function appUpdateDomain(id) {
        layer.confirm('更新此域名到期日期吗 ？', {
            icon: 3,
            btn: ['确定', '取消']
        }, function() {
            $.ajax({
                type: 'POST',
                url: 'ajax_domain.php',
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

    function appDelDomain(id) {
        layer.confirm('确定要删除此域名吗 ？', {
            icon: 3,
            btn: ['确定', '取消']
        }, function() {
            $.ajax({
                type: 'POST',
                url: 'ajax_domain.php',
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

    function appWhoisDomain(domain) {
        var ii = layer.load(2, {
            shade: [0.1, '#fff']
        });
        $.ajax({
            type: 'POST',
            url: 'ajax_domain.php',
            data: {
                act: 'whois',
                domain: domain
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