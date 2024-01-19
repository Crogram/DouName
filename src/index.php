<?php
$nosession = true;
$nosecu = true;
include("./includes/common.php");
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $conf['site_title']; ?></title>
    <meta name="keywords" content="<?php echo $conf['site_keywords'] ?>">
    <meta name="description" content="<?php echo $conf['site_description'] ?>">
    <meta name="copyright" content="<?php echo $conf['site_copyright']?>" />

    <!-- Mobile support -->
    <meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <style>
        body {
            font-family: '微软雅黑', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            background-color: #fafafa;
            text-align: center;
            background: url(./assets/img/btbg.png) no-repeat fixed bottom;
            background-size: 100% auto;
        }

        .title_logo {
            opacity: 0.5;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title_logo">
            <h1><?php echo $conf['site_title']; ?></h1>
            <svg t="1666179437018" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2569" width="200" height="200">
                <path d="M308.73856 119.23456C23.65696 170.15296-71.37024 492.23936 155.392 639.66464c12.43392 7.99232 12.43392 7.104-6.21824 62.76096l-15.98464 47.65952 57.43104-30.784 57.43104-30.78656 30.49216 7.40096c31.96928 7.99232 72.82432 13.61664 100.0576 13.61664l16.28416 0-5.62688-21.61152c-44.70016-164.5952 109.82912-327.71072 310.8352-327.71072l27.2384 0-5.62432-19.53792C677.59616 186.43456 491.392 86.67136 308.73856 119.23456zM283.87072 263.40352c30.1952 20.4288 31.97184 64.5376 2.95936 83.48416-47.06816 30.78656-102.1312-23.38816-70.45632-69.57056C230.28736 256.59648 263.74144 249.78688 283.87072 263.40352zM526.62016 263.40352c49.73568 33.45408 12.43392 110.71744-43.22304 89.40288-40.25856-15.39328-44.99712-70.75072-7.40096-90.5856C490.79808 254.22848 513.88928 254.81984 526.62016 263.40352zM636.44928 385.37216c-141.2096 25.7536-239.19872 132.91776-233.57184 256.06656 7.40096 164.89472 200.71168 278.56896 386.32448 227.65312l21.90592-5.92128 46.1824 24.8704c25.4592 13.9136 46.77376 23.97696 47.36512 22.79168 0.59392-1.47968-4.43648-19.24352-10.95168-39.6672-14.79936-45.59104-15.09632-42.33472 4.73856-56.54272C1121.64864 654.464 925.67552 332.97408 636.44928 385.37216zM630.82496 518.28992c12.4288 8.28928 18.944 29.01248 13.61408 44.1088-11.24864 32.26624-59.49952 34.63424-72.52992 3.55328C557.10976 530.13248 597.9648 496.97536 630.82496 518.28992zM828.57472 521.84576c19.53792 18.64704 16.2816 50.32448-6.51264 62.16448-34.93376 17.76128-71.63904-17.76128-53.58336-51.80416C780.32128 510.2976 810.81344 504.97024 828.57472 521.84576z" p-id="2570" data-spm-anchor-id="a313x.7781069.0.i0" class="" fill="#07c160"></path>
            </svg>
        </div>
    </div>
</body>

</html>