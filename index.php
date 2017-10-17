<?php
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/
get_header();
/** 调用首页幻灯片 **/
$slider = cx_options('_cx_slider');
if(isset($slider) && $slider == 'off')
cx__template('hdp');
?>

	<div class="home-filter">	
		<div class="h-screen-wrap">
			<ul class="h-screen">
				<?php 
				if(function_exists('wp_nav_menu')) 
				wp_nav_menu(array('container' => false, 'items_wrap' => '%3$s', 'theme_location' => 'home-nav'));
				?>				                            
			</ul>
		</div>
		<ul class="h-soup cl">
			<li class="open"><i class="fa fa-coffee"></i>最近一周新增 <em><?php echo get_week_post_count();?></em> 篇文章</li>                                                
		</ul>
	</div>
	<h2 class="btt mobies"> <i class="fa fa-gittip" style="color: #E53A40;"></i>为您推荐 <span>给您推荐一批更精彩的</span> </h2>
		
<?php 
/** 调用首页文章列表 **/
cx__template('archive');
/** 掉用公共底部 **/
get_footer();