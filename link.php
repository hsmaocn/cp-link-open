<?php
// 加载 WordPress 环境
require_once( dirname(__FILE__) . '/../../../wp-load.php' ); // 修改路径，确保指向 WordPress 根目录下的 wp-load.php

if (isset($_GET['a'])) {
    $link = base64_decode($_GET['a']);
}
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>即将离开<?php echo get_bloginfo('name'); ?></title>
    <!-- MDUI CSS -->
    <link rel="stylesheet" href="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/mdui/1.0.2/css/mdui.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
        }
        body {
            background-image: url('https://image.hsmao.cn/blog/img/4e47c17f37bb8c8c.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .glassmorphism {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: backdrop-filter 0.3s ease;
        }
        #box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            max-width: 500px;
            width: 90%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        #box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.45);
        }
        .note {
            font-size: 24px;
            font-weight: 500;
            color: #333;
            margin-bottom: 20px;
        }
        .link {
            padding: 16px 0;
            border-bottom: 2px solid #e0e0e0;
            color: #666;
            font-size: 16px;
            word-break: break-all;
            margin-bottom: 20px;
        }
        .btn-plane {
           text-align: right;
        }
        .mdui-btn {
            text-transform: none;
            font-weight: 500;
        }
    </style>
</head>
<body class="mdui-theme-primary-indigo mdui-theme-accent-pink">
    <div class="glassmorphism">
        <div id="box" class="mdui-card mdui-card-raised">
            <div class="mdui-card-content">
                <p class="note mdui-typo-headline-opacity">即将离开<?php echo get_bloginfo('name'); ?>，请注意您的设备安全！</p>
                <p class="link mdui-typo-subheading-opacity"><?php echo htmlspecialchars($link ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="btn-plane">
                    <a href="<?php echo htmlspecialchars($link ?? '', ENT_QUOTES, 'UTF-8'); ?>" rel="nofollow" class="mdui-btn mdui-btn-raised mdui-ripple mdui-color-theme-accent">继续访问</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MDUI JavaScript -->
    <script src="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/mdui/1.0.2/js/mdui.min.js"></script>
    <script>
    document.addEventListener('mousemove', function(e) {
        const glassmorphism = document.querySelector('.glassmorphism');
        const box = document.getElementById('box');
        const rect = box.getBoundingClientRect();
        const x = e.clientX;
        const y = e.clientY;
        
        if (x >= rect.left && x <= rect.right && y >= rect.top && y <= rect.bottom) {
            glassmorphism.style.backdropFilter = 'blur(0px)';
            glassmorphism.style.webkitBackdropFilter = 'blur(0px)';
        } else {
            glassmorphism.style.backdropFilter = 'blur(10px)';
            glassmorphism.style.webkitBackdropFilter = 'blur(10px)';
        }
    });
    </script>
</body>
</html>