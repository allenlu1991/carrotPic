<?php
/***************************************

## Theme URI: http://www.chenxingweb.com/wp-theme-cx-udy.html
## Author: 晨星博客
## Author URI: http://www.chenxingweb.com
## Description: 简洁时尚自适应图片主题，适合各种图片展示类网站，有问题请加QQ群565616228请求帮助。
## Theme Name: CX-UDY
## Version: 0.1

****************************************/
if ( post_password_required() ) {
	return;
}
?>

<?php
  $numPingBacks = 0;
  $numComments  = 0;
  foreach ($comments as $comment)
  if (get_comment_type() != "comment") $numPingBacks++; else $numComments++;
?><!-- 引用 -->
 <section class="single-post-comment">
	<?php if ( have_comments() ) : ?>
	<h2>评论<?php echo comments_num($post->ID,1);?></h2>
<?php else:?>
<h2>评论</h2>
	<?php endif; ?>
	<?php if ( comments_open() ) : ?>
	<div class="single-post-comment-reply" id="respond" >
			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" class="comment_form" method="post" id="commentform" >
				<div class="single-post-comment__form cf">  
					<textarea class="textarea form-control" data-widearea="enable" id="comment" name="comment" placeholder="你怎么看？"></textarea>
					<?php comment_id_fields(); do_action('comment_form', $post->ID); ?>		
				</div>
				<?php if ( $user_ID ) : ?>
				<div class="bottom">
					<div class="meta">
						<span class="avatar"><?php echo get_avatar( get_the_author_meta('email'), '32' ); ?></span>
						<span><a href="javascript:void(0)"><?php echo $user_identity; ?></a></span>
						<span id="error_msg" style="color:#f00; margin-left:15px;"></span>
					</div>
					<button type="submit" class="ladda-button comment-submit-btn"><span class="ladda-label">提交评论</span></button>
					<div id="cancel_comment_reply"><?php cancel_comment_reply_link( '取消回复' ); ?></div>
				</div>
					<?php elseif ( '' != $comment_author ): ?>
				<div class="bottom">
					<div class="meta">
						<span class="avatar"><?php echo get_avatar($comment_author_email, $size = '32');  ?></span>
						<span>您好 <a href="javascript:void(0)"><?php printf ('%s', $comment_author); ?></a> 欢迎您再次来访！</span>
						<a href="javascript:toggleCommentAuthorInfo();" id="toggle-comboxinfo"> 修改信息</a>
						<script type="text/javascript" charset="utf-8">
							//<![CDATA[
							var changeMsg = " 修改信息";
							var closeMsg = " 关闭";
							function toggleCommentAuthorInfo() {
								jQuery('#comboxinfo').slideToggle('slow', function(){
									if ( jQuery('#comboxinfo').css('display') == 'none' ) {
									jQuery('#toggle-comboxinfo').text(changeMsg);
									} else {
									jQuery('#toggle-comboxinfo').text(closeMsg);
									}
								});
							}
							jQuery(document).ready(function(){
								jQuery('#comboxinfo').hide();
								jQuery('#comboxinfo .ladda-button').hide();
							});
							//]]>
						</script>
						<span id="error_msg" style="color:#f00; margin-left:15px;"></span>
					</div>
					<button type="submit" class="ladda-button comment-submit-btn"><span class="ladda-label">提交评论</span></button>
					<div id="cancel_comment_reply"><?php cancel_comment_reply_link( '取消回复' ); ?></div>
				</div>
					<?php endif; ?>

				<?php if ( ! $user_ID ): ?>
		
				<div id="comboxinfo" class="comboxinfo cl">
					<div class="cominfodiv cominfodiv-author ">
					<p for="author" class="nicheng">
					<input type="text" name="author" id="author" class="texty" value="<?php echo $comment_author; ?>" tabindex="1" /> <span class="required">昵称*</span>
					</p>
					</div>
					<div class="cominfodiv cominfodiv-email">
					<p for="email">	<input type="text" name="email" id="email" class="texty" value="<?php echo $comment_author_email; ?>" tabindex="2" /> <span class="required">邮箱*</span>
						</p>
					</div>
					<div class="cominfodiv cominfodiv-url">
					 	<p for="url"><input type="text" name="url" id="url" class="texty" value="<?php echo $comment_author_url; ?>" tabindex="3" /><span>网址 </span>
						</p>
					</div>
					
					<button type="submit" class="ladda-button comment-submit-btn"><span class="ladda-label">提交评论</span></button>
					<div id="cancel_comment_reply"><?php cancel_comment_reply_link( '取消回复' ); ?></div>
				</div>

<?php endif; ?>		
				
			</form>
			
			
			<script type="text/javascript">
				document.getElementById("comment").onkeydown = function (moz_ev){
				var ev = null;
				if (window.event){
				ev = window.event;
				}else{
				ev = moz_ev;
				}
				if (ev != null && ev.ctrlKey && ev.keyCode == 13){
				document.getElementById("submit").click();}
				}
			</script></div>
	 		<?php endif; ?>

<?php if ( ! comments_open() ) : ?>
		<p class="no-comments">评论已关闭！</p>
	<?php endif; ?>
<!-- 显示正在加载新评论 -->
	<ul>
	<hr>
		<?php wp_list_comments( 'type=comment&callback=mytheme_comment' ); ?>
	</ul><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	
		<div class="comment-pagenav"><?php paginate_comments_links('prev_text=上一页&next_text=下一页'); ?></div>
	
	<?php endif;?>	
</section>
<!-- #comments -->