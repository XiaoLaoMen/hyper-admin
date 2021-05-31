<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/admin/lib/layui-v2.5.5/css/layui.css">
    <link rel="stylesheet" href="/admin/js/lay-module/notice/notice.css">
    <!--[if lt IE 9]>
    <script src="/admin/js/html5.min.js"></script>
    <script src="/admin/js/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="/admin/css/login.css">
</head>
<body>
<div class="main-body">
    <div class="login-main">
        <div class="login-top">
            <span>后台登录</span>
            <span class="bg1"></span>
            <span class="bg2"></span>
        </div>
        <form class="layui-form login-bottom" onclick="return false;">
            <div class="center">
                <div class="item">
                    <span class="icon icon-2"></span>
                    <input type="text" name="emailormob" placeholder="邮箱" maxlength="60" autocomplete="off"/>
                </div>

                <div class="item">
                    <span class="icon icon-3"></span>
                    <input type="password" name="password" placeholder="密码" maxlength="60" autocomplete="off">
                    <span class="bind-password icon icon-4"></span>
                </div>

                <div id="validatePanel" class="item" style="width: 137px;">
                    <input type="text" name="captcha" placeholder="验证码" maxlength="4" autocomplete="off">
                    <img id="refreshCaptcha" class="validateImg"  src="/admin/login/captcha" onclick="this.src=this.src+'?d='+Math.random();">
                </div>
            </div>

            <div class="tip">
                <input type="checkbox" name="remember" lay-skin="primary">
                <span class="login-tip">保持七天登录</span>
            </div>
            <div class="layui-form-item" style="text-align:center; width:100%;height:100%;margin:0px;">
                <button class="login-btn" lay-submit lay-filter="login">立即登录</button>
            </div>
        </form>
    </div>
</div>
<script src="/admin/lib/layui-v2.5.5/layui.js" charset="utf-8"></script>
<script src="/admin/js/lay-config.js?v=2.0.0" charset="utf-8"></script>
<script>
    layui.use(['form','jquery','notice'], function () {
        var $ = layui.jquery,
            form = layui.form,
            notice = layui.notice,
            layer = layui.layer;
        notice.options = {
            debug:false,//启用debug
            positionClass:"toast-top-center",//弹出的位置,
        };

        $('.bind-password').on('click', function () {
            if ($(this).hasClass('icon-5')) {
                $(this).removeClass('icon-5');
                $("input[name='password']").attr('type', 'password');
            } else {
                $(this).addClass('icon-5');
                $("input[name='password']").attr('type', 'text');
            }
        });

        // 进行登录操作
        form.on('submit(login)', function (data) {
            data = data.field;
            if (data.emailormob == '') {
                notice.error("邮箱不能为空");
                return false;
            }
            if (data.password == '') {
                notice.error("密码不能为空");
                return false;
            }
            if (data.captcha == '') {
                notice.error("验证码不能为空");
                return false;
            }
            $.ajax({
                url : '/admin/login/index',
                type : "POST",
                data : data,
                dataType : "json",
                async: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success : function(result) {
                    if(result.code==0){
                        notice.success(result.message);
                        layer.load();
                        setTimeout(function(){
                            window.location='/admin/index/layout';
                        },2000);
                    }else{
                        notice.error(result.message);
                        $('#refreshCaptcha').attr('src','/admin/login/captcha'+'?d='+Math.random());
                    }
                },
                error:function(XMLHttpRequest, textStatus, errorThrow ){
                    notice.error(XMLHttpRequest['responseText']);
                }
            });

            return false;
        });
    });
</script>
</body>
</html>
