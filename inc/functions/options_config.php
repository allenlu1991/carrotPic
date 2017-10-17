<?php
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/

$tab_meta = array();
$tab_conf = array('title' => '文章扩展字段', 'id'=>'tab_box', 'page'=>array('post'), 'context'=>'normal', 'priority'=>'low');
$tab_meta[] = array(
  'name' => '附加字段',
  'id'   => 'seobox',
  'type' => 'open'
);

$tab_meta[] = array(
  'name'    => '模块选择',
  'id'      => '_id_radio',
  'desc'    => '',
  'std'     => '',
  'buttons' => array(
    'bg'      => '编辑推荐',
	'qx'      => '取消设置',
  ),
  'type'    => 'radio'
);

$tab_meta[] = array(
  'name' => '文本介绍',
  'id'   => '_post_txt',
  'desc' => '填写图集的描述文本！',
  'std'  => '',
  'size' => array(60,3),
  'type' => 'textarea'
);

$tab_meta[] = array(
  'type' => 'close'
);

$tab_box = new ashuwp_postmeta_feild($tab_meta, $tab_conf);
$ashu_feild = array();
$taxonomy_cof = array('category');

$ashu_feild[] = array(
  'name' => '封面图片',
  'id'   => '_feng_images',
  'desc' => '适用于分类封面显示。<style>.ashuwp_field_upload{width:300px!important;}</style>',
  'std'  => '',
  'button_text' => 'Upload',
  'type' => 'upload'
);

$ashu_feild[] = array(
  'name'      => '分类自定义title',
  'id'        => '_fl_title',
  'desc'      => '填写分类页面title标题一般在80个字符内',
  'std'       => '',
  'edit_only' => false,
  'size'      => 40,
  "type"      => "text"
);

$ashu_feild[] = array(
  'name'      => '分类自定义keywords',
  'id'        => '_fl_keywords',
  'desc'      => '填写分类页面keywords关键词一般在5个左右',
  'std'       => '',
  'edit_only' => false,
  'size'      => 40,
  "type"      => "text"
);

$ashu_feild[] = array(
  'type' => 'close'
);

$ashuwp_termmeta_feild = new ashuwp_termmeta_feild($ashu_feild, $taxonomy_cof);

/**
自定义幻灯片
**/
$slider_boxinfo = array('title' => '填写幻灯片信息', 'id'=>'sliderbox', 'page'=>array('slider_type'), 'context'=>'normal', 'priority'=>'low', 'callback'=>'');
$slider_metas[] = array(
  'name' => '幻灯片链接',
  'desc' => '以<code>http://</code>开头 例：http://www.chenxingweb.com',
  'id' => '_slider_link',
  'size'=> 40,
  'std'=>'',
  'type' => 'text'
);
$slider_metas[] = array(
  'name' => '幻灯片图片',
  'desc' => '上传一张幻灯片显示图像',
  'std'=>'',
  'size'=>60,
  'button_label'=>'Upload',
  'id' => '_slider_pic',
  'type' => 'upload'
);
$ashuwp_slider = new ashuwp_postmeta_feild($slider_metas, $slider_boxinfo);

/**
自定义专题
**/
$zhuanti_boxinfo = array('title' => '填写专题信息', 'id'=>'sliderbox', 'page'=>array('zhuanti_type'), 'context'=>'normal', 'priority'=>'low', 'callback'=>'');
$zhuanti_metas[] = array(
  'name' => '专题链接',
  'desc' => '以<code>http://</code>开头 例：http://www.chenxingweb.com',
  'id' => '_slider_link',
  'size'=> 40,
  'std'=>'',
  'type' => 'text'
);
$zhuanti_metas[] = array(
  'name' => '专题封面图片',
  'desc' => '上传一张专题封面显示图像',
  'std'=>'',
  'size'=>60,
  'button_label'=>'Upload',
  'id' => '_slider_pic',
  'type' => 'upload'
);
$zhuanti_slider = new ashuwp_postmeta_feild($zhuanti_metas, $zhuanti_boxinfo);

/**
标签页面自定义字段
**/
$tag_feild = array();
$taxonomy_cof = array('post_tag');

$tag_feild[] = array(
  'name' => '封面图片',
  'id'   => '_feng_images',
  'desc' => '适用于标签封面显示。',
  'std'  => '',
  'button_text' => 'Upload',
  'type' => 'upload'
);

$tag_feild[] = array(
  'name'      => '标签自定义title',
  'id'        => '_fl_title',
  'desc'      => '填写分类页面title标题一般在80个字符内',
  'std'       => '',
  'edit_only' => false,
  'size'      => 40,
  "type"      => "text"
);

$tag_feild[] = array(
  'name'      => '标签自定义keywords',
  'id'        => '_fl_keywords',
  'desc'      => '填写分类页面keywords关键词一般在5个左右',
  'std'       => '',
  'edit_only' => false,
  'size'      => 40,
  "type"      => "text"
);

$tag_feild[] = array(
  'type' => 'close'
);

$ashuwp_tag_feild = new ashuwp_termmeta_feild($tag_feild, $taxonomy_cof);


$child_info = array(
   'full_name' => '主题选项',
  'optionname'=>'general',
  'child'=>false,
  'filename' => 'generalpage'
);

$child_option = array();
$child_option[] = array(
  'name' => '全局扩展',
  'id'   => 'option_tab1',
  'type' => 'open',
);

$child_option[] = array(
  'name'    => '列表布局样式',
  'id'      => '_tags_themes',
  'desc'    => '设置站内列表缩略图尺寸。',
  'std'     => '1001',
  'subtype' => array(
    '1001'  => '4*3缩略图布局',
	'1002'  => '3*5缩略图布局',
  ),
  'type' => 'select'
);

$child_option[] = array(
  'name'    => '幻灯片功能',
  'id'      => '_cx_slider',
  'desc'    => '启用该项可在后台侧边显示一个自定义文章类型来发布幻灯片',
  'std'     => 'off',
  'subtype' => array(
    'off'  => '启用',
    'no' => '隐藏',
  ),
  'type' => 'select'
);

$child_option[] = array(
  'name'    => '幻灯片数量',
  'id'      => '_cx_slider_num',
  'desc'    => '设置幻灯片显示数量',
  'std'  => '3',
  'size' => 40,
  'type' => 'text'
);

$child_option[] = array(
  'name'    => '底部站内统计',
  'id'      => '_cx_tongji',
  'desc'    => '启用该项将在网站底部显示站内数据统计',
  'std'     => 'off',
  'subtype' => array(
    'off'  => '显示',
    'no' => '隐藏',
  ),
  'type' => 'select'
);


$child_option[] = array(
  'type' => 'close',
);

/**third tab**/
$child_option[] = array(
  'name' => 'SEO配置',
  'id'   => 'option_tab2',
  'type' => 'open',
);

$child_option[] = array(
  'name' => '网站统计代码',
  'id'   => '_wz_baidu',
  'desc' => '请在网站统计平台获取代码后放置到这里。适合百度统计',
  'size' => array(60,5),
  'type' => 'textarea'
);

$child_option[] = array(
  'name' => '首页标题title',
  'id'   => '_seo_title',
  'desc' => '一般在80个字符内，显示在首页title标题中',
  'std'  => '例：晨星博客-专注WordPress网站建设',
  'size' => 40,
  'type' => 'text'
);

$child_option[] = array(
  'name' => '首页关键词keywords',
  'id'   => '_seo_keywords',
  'desc' => '多个关键词请用英文逗号分隔',
  'std'  => '例：晨星博客,SEO博客',
  'size' => 40,
  'type' => 'text'
);

$child_option[] = array(
  'name' => '首页描述description',
  'id'   => '_seo_description',
  'desc' => '输入首页描述文本，一般在200字以内。。。',
  'std'  => '例：晨星博客（www.chenxingweb.com）网站内容以wordpress开发为主，为互联网贡献关于网站开发系列技术文章。',
  'size' => array(60,5),
  'type' => 'textarea'
);

$child_option[] = array(
  'name' => 'ICP备案号',
  'id'   => '_foot_ba',
  'desc' => '',
  'std'  => '例：沪ICP备88888888号',
  'size' => 40,
  'type' => 'text'
);
$child_option[] = array(
  'name'    => '备案号码链接',
  'id'      => '_foot_ba_url',
  'desc'    => '在办理备案手续时可以在这里快速设置是否加工信部的链接，链接已设置了新窗口打开和nofollow属性',
  'std'     => 'no',
  'subtype' => array(
    'off'  => '显示',
    'no' => '隐藏',
  ),
  'type' => 'select'
);

$child_option[] = array(
  'type' => 'close',
);


$child_page = new ashuwp_options_feild($child_option, $child_info);