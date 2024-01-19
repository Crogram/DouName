# 程江域名管理系统

这是一款基于 PHP 语言开发的域名管理系统。

通过本系统可实现实现对所有域名进行管理。

系统后台支持域名添加管理，进行域名基本信息管理、域名订单记录管理、域名 DNS 解析记录管理、域名 Whois 查询工具，域名证书申请和续期管理。

### 部署方法

- 运行环境要求 PHP5.4+，MySQL5.6+
- 将 `src` 目录内文件全部上传到网站运行目录
- 访问网站，会自动跳转到安装页面，根据提示填写配置信息，进行安装
- 安装完成后，访问 /admin 进入后台管理
- 设置伪静态，规则见下方

### 伪静态规则

- Nginx

```nginx
location / {
  if (!-e $request_filename) {
    rewrite ^(.*)$ /index.php?s=$1 last; break;
  }
}
```

- Apache

```xml
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
</IfModule>
```

### 使用方法

### 版权信息

版权所有 Copyright © 2023 [CROGRAM](https://crogram.com)
