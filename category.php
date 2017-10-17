<?php
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/
wp_get_header();
?>


 <?php cat_meta_information();?>
    <div class="fl_title">
      <div class="fl01"> <?php single_cat_title(); ?></div>
    </div>
  </div>

  
<?php 
/** 调用分类列表 **/
cx__template('archive');
/** 掉用公共底部 **/
get_footer();