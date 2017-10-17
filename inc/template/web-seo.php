<?php
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/

 if ( is_home() ) { ?>

<title><?php cx_options('_seo_title',1); ?></title>
<?php } if ( is_search() ){ ?>

<title><?php printf( __( '%s', 'chenxing' ), get_search_query() ); ?>的搜索结果 - <?php bloginfo('name'); ?></title>
<?php } if ( is_single() ) { ?>

<title><?php echo trim(wp_title('',0)); ?><?php if (get_query_var('page')) { echo '-第'; echo get_query_var('page'); echo '页';}?> - <?php bloginfo('name'); ?></title>
<?php } if ( is_page() ) { ?>

<title><?php echo trim(wp_title('',0)); ?> - <?php bloginfo('name'); ?></title>
<?php }if ( is_category() ) { ?>

<title><?php $currentterm = get_queried_object();$_fl_title = get_term_meta($currentterm->term_id , '_fl_title',true); if(isset($_fl_title) && $_fl_title !=''){ echo $_fl_title;}else {echo single_cat_title();}?> - <?php bloginfo('name'); ?></title> 
<?php } if ( is_year() ) { ?>

<title><?php the_time('Y年'); ?>所有文章 - <?php bloginfo('name'); ?></title>
<?php } if ( is_month() ) { ?>

<title><?php the_time('F'); ?>份所有文章 - <?php bloginfo('name'); ?></title>
<?php } if ( is_day() ) { ?>

<title><?php the_time('Y年n月j日'); ?>所有文章 - <?php bloginfo('name'); ?></title>
<?php } if (function_exists('is_tag')) { if ( is_tag() ) { ?>

<title><?php $currentterm = get_queried_object();$_fl_title = get_term_meta($currentterm->term_id , '_fl_title',true); if(isset($_fl_title) && $_fl_title !=''){ echo $_fl_title;}else {echo single_tag_title();}?> - <?php bloginfo('name'); ?></title> 
<?php } } if ( is_tax('notice') ) { ?>

<title><?php setTitle(); ?> - <?php bloginfo('name'); ?></title>
<?php } if ( is_author() ) {?>

<title><?php wp_title('');?>发表的所有文章 - <?php bloginfo('name'); ?></title>
<?php }
if (!function_exists('utf8Substr')) {
 function utf8Substr($str, $from, $len)
 {
     return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
          '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
          '$1',$str);
 }
}
if ( is_single() ){
    if ($post->post_excerpt) {
        $description  = $post->post_excerpt;
    } else {
   if(preg_match('/<p>(.*)<\/p>/iU',trim(strip_tags($post->post_content,"<p>")),$result)){
    $post_content = $result['1'];
   } else {
    $post_content_r = explode("\n",trim(strip_tags($post->post_content)));
    $post_content = $post_content_r['0'];
   }
         $description = utf8Substr($post_content,0,220);
  } 
    $keywords = "";
    $tags = wp_get_post_tags($post->ID);
    foreach ($tags as $tag ) {
        $keywords = $keywords . $tag->name . ",";
    }}?>
<?php if ( is_single() ) { ?>
<meta name="keywords" content="<?php echo rtrim($keywords,','); ?>" />
<meta name="description" content="<?php echo trim($description); ?> " />
<?php } if ( is_page() ) { ?>
<meta name="keywords" content="<?php $keywords = get_post_meta($post->ID, 'keywords', true);{echo $keywords;}?>" />
<?php } if ( is_category() ) { ?>
<meta name="keywords" content="<?php $currentterm = get_queried_object();$_fl_keywords = get_term_meta($currentterm->term_id , '_fl_keywords',true); echo $_fl_keywords;?>" />
<meta name="description" content="<?php echo trim(strip_tags(category_description())); ?> " />
<?php } if ( is_tag() ) { ?>
<meta name="keywords" content="<?php $currentterm = get_queried_object();$_fl_keywords = get_term_meta($currentterm->term_id , '_fl_keywords',true); echo $_fl_keywords;?>" />
<meta name="description" content="<?php echo trim(strip_tags(category_description())); ?> " />
<?php } if ( is_home() ) { ?>
<meta name="keywords" content="<?php echo cx_options('_seo_keywords'); ?>" />
<meta name="description" content="<?php echo cx_options('_seo_description'); ?>" />
<?php } ?>