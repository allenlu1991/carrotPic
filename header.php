<?php
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="initial-scale=1.0,user-scalable=no"> 
		<?php wp_head(); ?>
		<!--[if lt IE 9]> 
		<script src="http://apps.bdimg.com/libs/html5shiv/3.7/html5shiv.min.js"></script> 
		<![endif]--> 
	</head>
	<body class="home blog">
		<div class="index_header">
			<div class="header_inner">
				<div class="logo">
					<a href="<?php echo home_cx;?>"><img src="<?php echo CX_THEMES_URL;?>/images/logo.png" alt="<?php bloginfo('name'); ?>" /></a>
				</div>

				<div class="header_menu">
					<ul>
						<?php 
						if(function_exists('wp_nav_menu')) 
							wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'left-nav'));
						?>
					</ul>
				</div>
				<div class="login_text pc" style="padding-top: 25px;">
				<?php if ( is_user_logged_in() ) {?>
					<a class="rlogin reg_hre_btn" href="<?php echo wp_logout_url( get_permalink() ); ?>">退出</a>
					<a class="rlogin login_hre_btn" href="<?php echo admin_url();?>">管理</a>
				<?php }else{ ?>
					<a class="rlogin reg_hre_btn" href="<?php echo home_cx;?>/wp-login.php?action=register">注册</a>
					<a class="rlogin login_hre_btn logint" href="javascript:;">登录</a>
				<?php } ?>
				</div>
				<div class="login_text mobie">
					<a href="javascript:;" class="slide-menu"><i class="fa fa-list-ul"></i></a>
				</div>
				<div class="header_search_bar">
					<form action="<?php echo home_cx;?>">
						<button class="search_bar_btn" type="submit"><i class="fa fa-search"></i></button>
						<input class="search_bar_input" type="text" name="s" placeholder="输入关键字">
					</form>
				</div>
			</div>
		</div>
		<!--移动端菜单-->
		<div class="slide-mask"></div>
		<nav class="slide-wrapper">
				<div class="header-info">
				<?php if ( is_user_logged_in() ) {?>
             	     <div class="header-logo">
	        			<a href="<?php echo home_cx;?>">
							<?php global $current_user; $email=$current_user->user_email ;echo get_avatar($email, 100 );?>						
						</a>
	        		</div>
        			<div class="header-info-content">
	        			<a href="<?php echo admin_url();?>">管 理</a>
	        		</div>
				<?php }else{ ?>
             	     <div class="header-logo">
	        			<a href="<?php echo home_cx;?>">	                     
							<img src="<?php echo CX_THEMES_URL;?>/images/avatar.jpg" alt="默认头像" />
						</a>
	        		</div>
        			<div class="header-info-content">
	        			<a href="<?php echo wp_login_url( get_permalink() ); ?>">登 陆</a>
	        		</div>
				<?php } ?>	
	        	</div>
				<ul class="menu_slide">
					<?php 
						if(function_exists('wp_nav_menu')) 
							wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'mini-nav'));
					?>
				</ul>
		</nav>
<!-- 头部代码end -->