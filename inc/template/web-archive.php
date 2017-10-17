<?php 
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/
if(is_home() || is_category() || is_tag() || is_search() || is_author()){	
$themes = cx_options('_tags_themes');
echo '<div class="update_area">';
	echo '<div class="update_area_content">';
	themes_if($themes ,1005,'<div class="blog_list cl"><ul class="update_area_list">','<ul class="update_area_lists cl">');		
		if ( have_posts() ) : 
		if(isset($_GET['ctag'])){
			if(isset($_GET['orderby']))
				$order = $_GET['orderby'];
			else{
				$order = 'date';
			}
			$args=array(
			'tag_id' => $_GET['ctag'],
			'orderby' => $order,
			);
		query_posts($args);
		}
		while ( have_posts() ) : the_post();
			cx_themes_switch($themes);
		endwhile;
		endif;
		echo "</ul>";
		wp_reset_query();
		/** 侧边调用 **/
		if($themes == 1005){
			get_sidebar();
		}
		/** 博客模板添加DIV **/
		themes_if($themes ,1005,'</div>','');
		/** 分页代码调用 **/
			the_posts_pagination( array(
				'prev_text'          =>'<i class="fa fa-chevron-left"></i>',
				'next_text'          =>'<i class="fa fa-chevron-right"></i>',
				'mid_size' => 3 ,
				'format' => '?paged=%#%&'.$_SERVER['QUERY_STRING'],
				'before_page_number' => '<span class="meta-nav screen-reader-text">第 </span>',
				'after_page_number' => '<span class="meta-nav screen-reader-text"> 页</span>',
			) );
			echo "</div>";
		echo "</div>";
		}