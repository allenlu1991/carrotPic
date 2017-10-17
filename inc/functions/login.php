<style>
#win {
    display: block;
    position: relative;
    margin: 0 auto;
    top: 20%;
    width: 100%;
    height: 400px;
    max-width: 400px;
    background: rgb(255, 255, 255);
    color: rgb(18,126,61);
    padding: 0px;
    box-shadow: rgba(0,0,0,.8) 0 0 8px;
	-webkit-animation:bounce 1s .2s ease both;
	-moz-animation:bounce 1s .2s ease both;
}
.titles {
    text-align: center;
    font-size: 2.5em;
    height: 80px;
    line-height: 80px;
    color: #F3F3F3;
    background-color: #FB9498;
    position: relative;
}	
.close {
    color: #FDFDFD;
    cursor: pointer;
    font-size: .7em;
    display: block;
    position: absolute;
    width: 80px;
    height: 80px;
    background-color: #F35D5D;
    right: 0;
    top: 0;
}
.inputs {
    margin-top: 30px;
    font-size: 1.2em;
    height: 40px;
    background-color: #F5F4F4;
    border: none;
	margin-left: 10%;
    width: 80%;
    padding: 5px 10px;
    color: #BBB8B8;
}
.jilu {
    margin-top: 30px;
    margin-left: 10%;
    color: #615F5F;
}
input#wp-submit {
    margin: 30px 0 0 25%;
    padding: 10px 40px;
    background-color: #FB9498;
    color: #fff;
    border: none;
    font-weight: 600;
    font-size: 1.5em;
}
span#zuce {
    color: #8E8A8A;
    margin-left: 20px;
    font-size: 1.2em;
}
#back {
	z-index:2;
	POSITION:fixed;
	left:0;
	top:0;
	width:100%;
	height:100%;
	background:rgba(0,0,0,.8);
}
</style>
<div id="win">
	<div class="titles">登 陆
		<a href="javascript:closeLogin();" class="close"><i class="fa fa-close"></i></a>
	</div>
	<form name="loginform" id="loginform" action="<?php echo wp_login_url(); ?>" method="post">
		<input type="text" name="log" class="inputs" id="user_login" size="20" placeholder="用户名" value="" onfocus="if (this.value == '请输入用户名') {this.value = '';}" onblur="if (this.value == '') {this.value = '请输入用户名';}" />
		<input type="password" name="pwd" class="inputs" id="user_pass" size="20" placeholder="密码" value="" />
		<div for="rememberme" class="jilu">
			<input name="rememberme" type="checkbox" id="rememberme" value="forever" checked> 记住登录信息&nbsp;|&nbsp;
			<a href="/wp-login.php?action=lostpassword">忘记密码？</a>
		</div>	
		<input type="submit" name="wp-submit" id="wp-submit" value="登 录">
		<span id="zuce">注册</span>
		<input type="hidden" name="redirect_to" value="/">
		<input type="hidden" name="testcookie" value="1">
	</form>
</div>
<script>
function openLogin(){
document.getElementById("win").style.display="";
document.getElementById("back").style.display="";
}
function closeLogin(){
document.getElementById("win").style.display="none";
document.getElementById("back").style.display="none";
}
</script>