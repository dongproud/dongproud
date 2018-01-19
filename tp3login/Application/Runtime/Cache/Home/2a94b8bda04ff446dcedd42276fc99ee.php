<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

    <link href="/tp32/Public/css/style.css" rel='stylesheet' type='text/css' />

    <script src="/tp32/Public/js/jquery.min.js"></script>
</head>
<body>
<script>$(document).ready(function(c) {
    $('.close').on('click', function(c){
        $('.login-form').fadeOut('slow', function(c){
            $('.login-form').remove();
        });
    });
});
</script>
<!--SIGN UP-->
<h1>用户登录</h1>
<div class="login-form">
    <div class="close"> </div>

    <div class="clear"> </div>
    <div class="avtar">
        <img src="/tp32/Public/images/avtar.png" />
    </div>
    <form id="forms" name="loginform" onsubmit="return false"  method="post">
        <input type="text" name="username" class="text" value="Username" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Username';}" >
        <div class="key">
            <input type="password" name="password" value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}">
        </div>
        <div>
            <input type="checkbox" name="ischecks" value="1"><font color="#fff">十天内免登录</font>
        </div>
        <div class="signin">
            <input type="submit"  id="subs" value="登录" >
        </div>
    </form>

</div>
<script type="text/javascript">
    $('#forms').submit(function(){
        $.ajax({
            url:"<?php echo U('Index/do_login');?>",
            type:'POST',
            data:$('#forms').serialize(),
            success:function(result){
                if(result.status)
                {
                    alert(result.msg);
                    window.location.href='/tp32/index.php/Home/Index/welcome'
                }
                else{
                    alert(result.msg);
                    window.location.href='/tp32/index.php/Home/Index/index'
                }
            },
            error: function () {
                
            },
        });
    });
</script>


</body>
</html>