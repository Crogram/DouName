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
                <select class="form-control" name="order_from">
                    <option value="">订单来源</option>
                    <option value="aliyun">阿里云</option>
                    <option value="tencent">腾讯</option>
                    <option value="juming">聚名网</option>
                    <option value="xinnet">新网</option>
                    <option value="huaweicloud">华为云</option>
                    <option value="72e">联动天下</option>
                    <option value="google">Google Domains</option>
                </select>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜索</button>
                    <a href="javascript:searchClear()" class="btn btn-default" title="重置筛选列表"><i class="fa fa-repeat"></i> 重置</a>
                </div>
            </form>
        </div>

        <table id="listTable"></table>
    </div>
</div>

<script src="//cdn.staticfile.org/layer/3.1.1/layer.js"></script>
<script src="//cdn.staticfile.org/bootstrap-table/1.20.2/bootstrap-table.min.js"></script>
<script src="//cdn.staticfile.org/bootstrap-table/1.20.2/extensions/page-jump-to/bootstrap-table-page-jump-to.min.js"></script>
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
                        }
                    }
                },
                {
                    field: 'order_from',
                    title: '订单来源',
                    formatter: function(value, row, index) {
                        switch (String(value)) {
                            case 'aliyun':
                                return '阿里云';
                                break;
                            case 'tencent':
                                return '腾讯';
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
                                return 'Google Domains';
                                break;
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
</script>

</body>

</html>