<?php
/**
 * Whois查询工具
 */
$nosession = true;
$nosecu = true;
include("./includes/common.php");

$query = '';
$query_string = '';
$result_string = '';

if (isset($_GET['query'])) {
    $query = strip_tags($_GET['query']);
    $query_string = $query . ' 的完整 WHOIS 信息:';

    $output = empty($_GET['output']) ? '' : $_GET['output'];

    include_once(SYSTEM_ROOT . '/phpwhois/whois.main.php');
    include_once(SYSTEM_ROOT . '/phpwhois/whois.utils.php');

    $whois = new Whois();
    $whois_verson = $whois->CODE_VERSION;

    // Set to true if you want to allow proxy requests
    $allowproxy = false;

    // get faster but less acurate results
    $whois->deep_whois = empty($_GET['fast']);

    // To use special whois servers (see README)
    //$whois->UseServer('uk','whois.nic.uk:1043?{hname} {ip} {query}');
    //$whois->UseServer('au','whois-check.ausregistry.net.au');

    // Comment the following line to disable support for non ICANN tld's
    $whois->non_icann = true;
    $result = $whois->Lookup($query);
    switch ($output) {
        case 'object':
            if ($whois->Query['status'] < 0) {
                $result_string = implode($whois->Query['errstr'], "\n<br></br>");
            } else {
                $utils = new utils;
                $result_string = $utils->showObject($result);
            }
            break;
        case 'nice':
            if (!empty($result['rawdata'])) {
                $utils = new utils;
                $result_string = $utils->showHTML($result);
            } else {
                if (isset($whois->Query['errstr']))
                    $result_string = implode($whois->Query['errstr'], "\n<br></br>");
                else {
                    $result_string = 'Unexpected error';
                }
            }
            break;
        case 'proxy':
            if ($allowproxy)
                exit(serialize($result));
        default:
            if (!empty($result['rawdata'])) {
                $result_string .= '<pre>' . implode($result['rawdata'], "\n") . '</pre>';
            } else {
                $result_string = implode($whois->Query['errstr'], "\n<br></br>");
            }
    }
}
?>
<!DOCTYPE HTML>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Whois 查询工具 - <?php echo $conf['site_title']; ?></title>
    <meta name="keywords" content="<?php echo $conf['site_keywords'] ?>,Whois查询工具,Whois,phpWhois">
    <meta name="description" content="<?php echo $conf['site_description'] ?>，Whois 查询工具">
    <meta name="author" content="Jackson" />
    <meta name="copyright" content="<?php echo $conf['site_copyright']?>" />

    <!-- Mobile support -->
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no,email=no">

    <link rel="icon" href="favicon.ico" />
    <link rel="stylesheet" href="//cdn.staticfile.org/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            padding-top: 20px;
            margin-bottom: 160px;
        }

        .page-header {
            margin-top: 0;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .footer>.container {
            padding-right: 15px;
            padding-left: 15px;
        }

        .footer p {
            margin: 20px 0;
        }

        code {
            font-size: 80%;
        }
    </style>
</head>

<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">MENU</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $site_url; ?>"><?php echo $conf['site_title']; ?></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>">Whois 查询工具</a>
                    </li>
                    <!-- <li class="<?php echo checkIfActive('domain'); ?>">
                        <a href="./domain.php"><i class="fa fa-globe fa-fw"></i> 域名</a>
                    </li> -->
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </div>
    <div class="container">
        <p class="page-header">本 Whois 查询工具基于 <a href="http://www.phpwhois.org" title="phpWhois web page" target="_blank">phpWhois</a> 开发。</p>
        <form method="get" class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h3>输入任意域名、IP地址</h3>
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="query" value="<?php echo $query; ?>" required class="form-control" placeholder="输入任意域名、IP地址" />
                    <span class="input-group-btn">
                        <input type="submit" class="btn btn-default" value="查询" />
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label class="checkbox-inline"><input type="checkbox" name="fast" value="1" /> 快速查找</label>
                <label class="radio-inline"><input type="radio" name="output" value="normal" /> 显示原始内容</label>
                <label class="radio-inline"><input type="radio" name="output" value="nice" checked="checked" /> 显示HTML</label>
                <label class="radio-inline"><input type="radio" name="output" value="object" /> 显示PHP Object</label>
            </div>
        </form>
    </div>
    <div class="container">
        <p><b><?php echo $query_string; ?></b></p>
        <?php echo $resout; ?>
    </div>
    <div class="footer">
        <div class="container">
            <footer class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-text pull-left">
                        <span>&copy; 2011-2024 <a href="https://github.com/jksdou" target="_blank">Jackson Dou</a></span>
                        <span> | <a href="https://github.com/jksdou/php-app-whois" target="_blank">Github</a></span>
                        <span class="hidden-xs"> | 友情链接：<a href="https://crogram.org" target="_blank" title="程江开源项目中心">CROGRAM</a> &bull; <a href="https://uinote.com" target="_blank">UINOTE</a> &bull; <a href="https://uiisc.org" target="_blank">UIISC</a></span>
                    </div>
                    <div class="navbar-text pull-right">
                        <span class="hidden-xs">Powered by </span><span><a href="https://crogram.com/" target="_blank" title="程江科技">CROGRAM</a></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="//cdn.staticfile.org/jquery/2.1.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>