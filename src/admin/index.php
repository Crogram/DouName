<?php
define('IN_ADMIN', true);
include("../includes/common.php");
$title = '管理中心';
include './head.php';
if ($admin_islogin == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<?php
$mysqlversion = $DB->getColumn("select VERSION()");
$checkupdate = '//auth.cccyun.cc/app/pan.php?ver=' . VERSION;
?>
<link href="../assets/css/admin.css" rel="stylesheet" />
<div class="container" style="padding-top:70px;">
    <div class="col-md-12 center-block" style="float: none;">
        <div id="browser-notice"></div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-globe fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">
                                    <span id="count_domain">0</span>/<span class="count-all" id="count_domains">0</span>
                                </div>
                                <div>域名数量</div>
                            </div>
                        </div>
                    </div>
                    <a href="domain.php">
                        <div class="panel-footer">
                            <span class="pull-left" herf="file.php">查看详情</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">
                                    <span id="count_record">0</span>/<span class="count-all" id="count_records">0</span>
                                </div>
                                <div>域名解析</div>
                            </div>
                        </div>
                    </div>
                    <a href="record.php">
                        <div class="panel-footer">
                            <span class="pull-left">查看详情</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">
                                    <span id="count_orders_today">0</span>/<span class="count-all" id="count_orders">0</span>
                                </div>
                                <div>今日订单/总订单数</div>
                            </div>
                        </div>
                    </div>
                    <a href="order.php">
                        <div class="panel-footer">
                            <span class="pull-left">查看详情</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-rmb fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" id="count_costs">0</div>
                                <div>累计消费金额/元</div>
                            </div>
                        </div>
                    </div>
                    <a href="order.php">
                        <div class="panel-footer">
                            <span class="pull-left">查看详情</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="panel panel-danger">
            <div class="list-group">
                <div class="list-group-item">
                    <span class="fa fa-trash fa-fw"></span> <b>数据清理：</b>
                    <a href="javascript:cleanlogin()" class="btn btn-sm btn-danger">删除1天前的登录记录</a>&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-sm-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">服务器信息</h3>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <b>PHP 版本：</b><?php echo phpversion() ?>
                            <?php if (ini_get('safe_mode')) {
                                echo '线程安全';
                            } else {
                                echo '非线程安全';
                            } ?>
                        </li>
                        <li class="list-group-item">
                            <b>MySQL 版本：</b><?php echo $mysqlversion ?>
                        </li>
                        <li class="list-group-item">
                            <b>WEB软件：</b><?php echo $_SERVER['SERVER_SOFTWARE'] ?>
                        </li>
                        <li class="list-group-item">
                            <b>操作系统：</b><?php echo php_uname(); ?>
                        </li>
                        <li class="list-group-item">
                            <b>服务器时间：</b><?php echo $date ?>
                        </li>
                        <li class="list-group-item">
                            <b>POST许可：</b><?php echo ini_get('post_max_size'); ?>
                        </li>
                        <li class="list-group-item">
                            <b>文件上传许可：</b><?php echo ini_get('upload_max_filesize'); ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">版本信息</h3>
                    </div>
                    <ul class="list-group text-dark">
                        <li class="list-group-item">当前版本：V<?php echo APP_VERSION;?> (Build <?php echo VERSION;?>)</li>
                        <li class="list-group-item">Powered by <a href="https://crogram.com/" target="_blank" rel="noopener noreferrer">CROGRAM</a></li>
                    </ul>
                    <ul class="list-group text-dark" id="checkupdate"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "ajax.php?act=stat",
            dataType: 'json',
            async: true,
            success: function(data) {
                $('#count_domain').html(data.domain);
                $('#count_domains').html(data.domains);
                $('#count_orders_today').html(data.orders_today);
                $('#count_orders').html(data.orders);
                $('#count_costs').html(data.costs);
                // $.ajax({
                //     url: '<?php echo $checkupdate ?>',
                //     type: 'get',
                //     dataType: 'jsonp',
                //     jsonpCallback: 'callback'
                // }).done(function(data){
                //     $("#checkupdate").html(data.msg);
                // })
            }
        })
    })

    function cleanlogin() {
        var confirmobj = layer.confirm('是否确定删除1天前的登录记录 ？', {
            btn: ['确定', '取消']
        }, function() {
            var ii = layer.load(2);
            $.ajax({
                type: "POST",
                url: "ajax.php?act=cleanlogin",
                dataType: 'json',
                success: function(data) {
                    layer.close(ii);
                    if (data.code == 0) {
                        layer.alert(data.msg || '删除成功！', {
                            icon: 1,
                            closeBtn: false
                        }, function() {
                            window.location.reload()
                        });
                    } else {
                        layer.alert(data.msg || '删除失败！', {
                            icon: 2
                        });
                    }
                },
                error: function(data) {
                    layer.close(ii);
                    layer.msg('服务器错误');
                }
            })
        }, function() {
            layer.close(confirmobj);
        });
    }
</script>
<script>
    function speedModeNotice() {
        var ua = window.navigator.userAgent;
        if (ua.indexOf('Windows NT') > -1 && ua.indexOf('Trident/') > -1) {
            var html = "<div class=\"panel panel-default\"><div class=\"panel-body\">当前浏览器是兼容模式，为确保后台功能正常使用，请切换到<b style='color:#51b72f'>极速模式</b>！<br>操作方法：点击浏览器地址栏右侧的IE符号<b style='color:#51b72f;'><i class='fa fa-internet-explorer fa-fw'></i></b>→选择“<b style='color:#51b72f;'><i class='fa fa-flash fa-fw'></i></b><b style='color:#51b72f;'>极速模式</b>”</div></div>";
            $("#browser-notice").html(html)
        }
    }
    speedModeNotice();
</script>