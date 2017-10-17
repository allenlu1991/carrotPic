<?php
/***********
晨星博客开发主题扩展代码放置区
添加方法：
  1、 从下一行开始，放置您复制的代码；
  2、 注意代码中符号的格式，php代码全部用半角符号，全角会报错；
***********/

/* 发布文章自动设置字段
/* -------------------------------- */
add_action('publish_post', 'add_custom_field_automatically');
function add_custom_field_automatically($post_ID) {
	global $wpdb;
	$random = mt_rand(300, 2000);
	$random2 = mt_rand(0, 20);
	if(!wp_is_post_revision($post_ID)) {
		add_post_meta($post_ID, 'views', $random, true);
		add_post_meta($post_ID, 'bigfa_ding', $random2, true);
	}
}

/* 彻底禁止WordPress缩略图
/* -------------------------------- */
add_filter( 'add_image_size', create_function( '', 'return 1;' ) );


//扩展代码放到上面一行 .end