<?php
include("./includes/common.php");
$title = '帮助文档';
// if ($admin_islogin == 1) {
// } else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>帮助文档</title>
    <link rel="stylesheet" href="<?php echo $cdnpublic; ?>twitter-bootstrap/4.6.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $cdnpublic; ?>github-markdown-css/5.1.0/github-markdown.min.css">
    <style>
        body {
            background-color: #eee !important;
        }

        .center-block {
            margin: 0 auto;
            float: none;
            padding: 0;
        }

        .markdown-body {
            box-sizing: border-box;
            margin: 18px auto;
            padding: 45px;
            box-shadow: 2px 2px 2px 2px #888888;
        }

        @media (max-width: 767px) {
            .markdown-body {
                padding: 15px;
                margin: 0 auto;
            }
        }

        code {
            color: #24292f;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12 center-block">
                <div class="markdown-body">
                    <h1 id="wxredirect">微信多域名回调功能</h1>
                    <h3>功能简介：</h3>
                    <p>微信公众号后台默认只能授权2个网页域名，用本系统突破这个限制，用同一个公众号对接无限多个网站。系统支持域名白名单和公众平台的管理，通过【回调记录】可查看接口使用情况。</p>
                    <p>现已支持接口如下：</p>
                    <p>获取基础 access_token：<code><?php echo $site_url; ?></code>/cgi-bin/token</p>
                    <p>获取公众号网页授权code：<code><?php echo $site_url; ?></code>/connect/oauth2/authorize</p>
                    <p>获取开放平台登录授权code：<code><?php echo $site_url; ?></code>/connect/qrconnect</p>
                    <p>获取 openid 或 unionid：<code><?php echo $site_url; ?></code>/sns/oauth2/access_token</p>
                    <p>获取用户信息：<code><?php echo $site_url; ?></code>/sns/userinfo</p>
                    <hr />
                    <h3>使用方法：</h3>
                    <p>首先需要在微信管理后台将本系统域名（<?php echo $site_url; ?>）添加到授权网页域名的白名单中。</p>
                    <p>支持微信公众号与微信开放平台2种登录的回调。</p>
                    <p>微信公众号网页授权跳转链接：<code><?php echo $site_url; ?>/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect</code></p>
                    <p>微信开放平台登录跳转链接：<code><?php echo $site_url; ?>/connect/qrconnect?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect</code></p>
                    <p>可以看出URL路径和参数与官方的<a href="https://developers.weixin.qq.com/doc/offiaccount/OA_Web_Apps/Wechat_webpage_authorization.html" target="_blank" rel="noopener noreferrer">微信公众号网页授权接口</a>、<a href="https://developers.weixin.qq.com/doc/oplatform/Website_App/WeChat_Login/Wechat_Login.html" target="_blank" rel="noopener noreferrer">微信开放平台登录接口</a>完全一样，只是把官方接口的<code>https://open.weixin.qq.com</code>替换成了<code><?php echo $site_url; ?></code>。因此对接也很简单，只需在业务代码里面替换域名即可！</p>
                    <p><strong>注：所传参数 REDIRECT_URI 里面的域名，需要先在本系统后台【域名管理】里面添加。微信公众号/开放平台后台只需要授权本站的域名。</strong></p>
                    <h2 id="wxtoken">微信 access_token 获取功能</h2>
                    <h3>功能简介：</h3>
                    <p>可将本系统站点作为中控服务器来统一获取和刷新 access_token，其他业务逻辑站点所使用的 access_token 均调用当前站点获取，这样可避免各自刷新造成冲突，导致 access_token 覆盖而影响业务。</p>
                    <h3>使用方法：</h3>
                    <p>先在系统后台【平台管理】添加公众号/小程序/开放平台账号信息。</p>
                    <p>获取 access_token 接口调用说明</p>
                    <p>GET请求URL：<code><?php echo $site_url; ?>/cgi-bin/token?appid=APPID&secret=APPSECRET</code></p>
                    <p>具体参数说明和返回内容格式与<a href="https://developers.weixin.qq.com/doc/offiaccount/Basic_Information/Get_access_token.html" target="_blank" rel="noopener noreferrer">微信官方获取 access_token接口</a>完全一样，只是将<code>https://api.weixin.qq.com</code>替换成了<code><?php echo $site_url; ?></code>，只需在业务代码里面替换接口地址即可！</p>
                    <p><strong>注：本站会对 access_token 进行缓存并自动刷新，如果在其他地方调用了官方获取接口，导致本站的 access_token 失效，此时可以在后台【平台管理】列表点【测试】按钮，强制刷新 access_token 缓存</strong>。</p>
                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>