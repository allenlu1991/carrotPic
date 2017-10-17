<?php
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/
function cx_themes_total() {
	$_index_images_10 = cx_options('_index_images_10');
	$pic_total = pic_total();
	if(isset($_index_images_10) && $_index_images_10 =='off'){
		echo '<div class="btns-sum"><span>'.$pic_total.'</span>张</div>';
	}
}

function cx_src() {
	$_ajax = cx_options('_ajax_off');
	if(is_category() && is_paged() && isset($_ajax) && $_ajax=='off'){
		echo 'src=';
	} else {
		echo 'src="'.cx_loading().'" data-original=';
	}
}
function cx_post_txtto() {
	$id = get_the_ID();
	$_post_txt = get_post_meta($id,'_post_txt',true);
	if(isset($_post_txt) && $_post_txt != ""){
		echo $_post_txt;
	}else{
		echo "小编比较懒，这个视频描述简介暂时没有更新，请稍后在看…";
	}
}
function cx_like($id = 0) {
	if($id == 0)
	$id = get_the_ID();
	echo '<span class="cx_like"><i class="fa fa-heart"></i>';
	$like_ding = get_post_meta($id,'bigfa_ding',true);
	if( isset($like_ding) && $like_ding != ""){
		echo $like_ding;
	} else {
		echo '0';
	}
	echo '</span>';
}

function cx_themes_switch($content=null,$post= 0,$mate='views',$i=0) {
	$theme = cx_options('_tags_themes');
	$cx_themes = (int)$content;
	switch ($cx_themes) {
	case 1001:	?>
		<li class="i_list list_n1"> 
			<a href="<?php the_permalink();?>" title="<?php the_title();?>"> 
				<img class="waitpic" src="<?php echo cx_loading();?>" data-original="<?php cx_timthumb(280,180,'280x180');?>" width="280" height="180" alt="<?php the_title();?>">
			</a>
			<div class="case_info">
				<div class="meta-title"> <?php the_title();?> </div>
				<div class="meta-post"><i class="fa fa-clock-o"></i> <?php the_time('Y-n-j');cx_like();?></div>
			</div>
		</li><!-- 4*3 缩略图模板-->
	<?php break;case 1002:?>
		<li class="i_list list_n2"> 
			<a href="<?php the_permalink();?>" title="<?php the_title();?>"> 
				<img class="waitpic" src="<?php echo cx_loading();?>" data-original="<?php cx_timthumb(270,370,'270x370');?>" width="270" height="370" alt="<?php the_title();?>">
			</a>
			<div class="case_info">
				<div class="meta-title"> <?php the_title();?> </div>
				<div class="meta-post"><i class="fa fa-clock-o"></i> <?php the_time('Y-n-j');cx_like();?></div>
			</div>
		</li><!-- 3*5 缩略图模板-->
		
	<?php break;case 1003:?>
		<li class="tuts_top3">
			<div class="tuts_top3_bg">
				<a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title(); ?>">
					<img src="<?php echo cx_loading();?>" data-original="<?php cx_timthumb(280,180,'280x180');?>" alt="<?php the_title(); ?>" width="250" height="160">
					<p><?php the_title(); ?></p>
				</a> 
			</div>
		</li>	 <!-- 相关文章模板-->	
		
	<?php break;case 1004:?>
		<li class="z-date">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_title(); ?>
			</a>
		</li><!-- 作者文章 -->
 
	<?php break;case 2000:?>
		<li class="i_list list_n3"> 
			<a href="<?php echo get_post_meta(get_the_ID(),'_slider_link',true);?>" title="<?php the_title();?>"> 
				<img class="waitpic" src="<?php echo cx_loading('zhuanti');?>" data-original="<?php cx_timthumb(380,170,'380x170');?>" width="270" height="120" alt="<?php the_title();?>" />
			<div class="case_info">
				<div class="meta-title"> <?php the_title();?> </div>
			</div>
			</a>
		</li><!-- .专题模板 2000-->
	<?php break;case 2001:?>
		<li>
			<a href="<?php echo get_post_meta(get_the_ID(),'_slider_link',true);?>" target="_blank">
				<img src="<?php cx_timthumb(380,170,'380x170');?>" alt="<?php the_title();?>" width="270" height="120">
				<span>
				<i class="fa fa-chevron-circle-right"></i>
				</span>
			</a>
		</li><!-- .侧边专题模板 2001-->
	<?php break;case 3000:
			if((int)$theme == 1001){
				$timthumb = cx_timthumb(280,180,'280x180',$post->ID,false);
				$class = ' list_n1';
				$width = 280;
				$height = 180;
			}else{
				$timthumb = cx_timthumb(270,370,'270x370',$post->ID,false);
				$class = ' list_n2';
				$width = 270;
				$height = 370;
			}
			$class_id = $i+1;
	?>
		<li class="i_list <?php echo $class;?>"> 
			<a href="<?php echo get_permalink($post->ID);?>" title="<?php echo $post->post_title;?>"> 
				<img class="waitpic" src="<?php echo cx_loading();?>" data-original="<?php echo $timthumb;?>" width="<?php echo $width;?>" height="<?php echo $height;?>" alt="<?php echo $post->post_title;?>">
			</a>
			<div class="case_info">
				<div class="meta-title"> <?php echo $post->post_title;?> </div>
				<div class="meta-post"><i class="fa fa-clock-o"></i> <?php echo date("Y-m-d",strtotime($post->post_date));cx_like($post->ID);?></div>
			</div>
			<div class="meta_zan xl_<?php echo $class_id;?>">
			<?php 
			if($mate=='views'){
				echo '<i class="fa fa-eye"></i>';
				Bing_get_views(true,$post->ID);
			}else{
				echo '<i class="fa fa-comment"></i>';
				echo $post->comment_count;
			}
			?>
			</div>
		</li><!-- 排行榜模板-->
	<?php break;default:?>	
<?php }
}
