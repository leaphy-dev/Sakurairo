<?php
include(get_stylesheet_directory().'/layouts/all_opt.php');
$text_logo = iro_opt('text_logo');
$print_social_zone = function() use ($all_opt,$social_display_icon):void{
    // 左箭头
    if (iro_opt('cover_random_graphs_switch', 'true')):?>
        <li id="bg-pre"><img src="<?=$social_display_icon?>pre.png" loading="lazy" alt="<?=__('Previous','sakurairo')?>"/></li>
    <?php
    endif;
    // 微信
    if (iro_opt('wechat')):?>
        <li class="wechat"><a href="#" title="WeChat"><img loading="lazy" src="<?=$social_display_icon?>wechat.png" /></a>
            <div class="wechatInner">
                <img class="wechat-img" style="height: max-content;width: max-content;" loading="lazy" src="<?=iro_opt('wechat', '')?>" alt="WeChat">
            </div>
        </li>
    <?php
    endif;
    // 大体(all_opt.php)
    foreach ($all_opt as $key => $value):
        if (!empty($value['link'])):
            // 显然 这里的逻辑可以看看all_opt的结构（
            $img_url = $value['img'] ?? ($social_display_icon . ($value['icon'] ?? $key) . '.png');
            $title = $value['title'] ?? $key;
            ?>
            <li><a href="<?=$value['link'];?>" target="_blank" class="social-<?=$value['class'] ?? $key?>" title="<?=$title?>"><img alt="<?=$title?>" loading="lazy" src="<?=$img_url?>" /></a></li>
        <?php
        endif;
    endforeach;
    // 邮箱
    if (iro_opt('email_name') && iro_opt('email_domain')):?>
        <li><a onclick="mail_me()" class="social-wangyiyun" title="E-mail"><img loading="lazy"
        alt="E-mail"
                    src="<?=iro_opt('vision_resource_basepath')?><?=iro_opt('social_display_icon')?>/mail.png" /></a></li>
    <?php
    endif;
    // 右箭头
    if (iro_opt('cover_random_graphs_switch', 'true')):?>
        <li id="bg-next"><img loading="lazy" src="<?=$social_display_icon?>next.png" alt="<?=__('Next','sakurairo')?>"/></li>
    <?php endif;
}
?>
<?php
/*未定义的伪类 */
/* <style>
.header-info::before {
    display: none !important;
    opacity: 0 !important;
}
</style> */
?>
<div id="banner_wave_1"></div>
<div id="banner_wave_2"></div>
<figure id="centerbg" class="centerbg">
    <?php if (iro_opt('infor_bar')) { ?>
        <div class="focusinfo">
            <?php if (isset($text_logo['text']) && iro_opt('text_logo_options', 'true')) : ?>
                <h1 class="center-text glitch is-glitching Ubuntu-font" data-text="<?=$text_logo['text']; ?>">
                    <?php echo $text_logo['text']; ?></h1>
            <?php else : ?>
                <div class="header-tou"><a href="<?php bloginfo('url'); ?>"><img alt="avatar" loading="lazy" src="<?=iro_opt('personal_avatar', '') ?: iro_opt('vision_resource_basepath','https://s.nmxc.ltd/sakurairo_vision/@2.7/').'series/avatar.webp'?>"></a>
            </div>
            <?php endif; ?>
            <div class="header-container">
                <div class="header-info">
                    <!-- 首页一言打字效果 -->
                    <?php if (iro_opt('signature_typing', 'true')) : ?>
                    <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa-solid fa-quote-left"></i><?php endif; ?>
                    <span class="element"><?=iro_opt('signature_typing_placeholder','疯狂造句中......')?></span>
                    <?php if (iro_opt('signature_typing_marks', 'true')) : ?><i class="fa-solid fa-quote-right"></i><?php endif; ?>
                    <span class="element"></span>
                    <script type="application/json" id="typed-js-initial">
                    <?= iro_opt('signature_typing_json', ''); ?>
                    </script>
                    <!-- var typed = new Typed('.element', {
                            strings: ["给时光以生命，给岁月以文明", ], //输入内容, 支持html标签
                            typeSpeed: 140, //打字速度
                            backSpeed: 50, //回退速度
                            loop: false, //是否循环
                            loopCount: Infinity,
                            showCursor: true //是否开启光标
                        }); -->
                    <?php endif; ?>
                    <p><?php echo iro_opt('signature_text', 'Hi, Mashiro?'); ?></p>
                    <?php if (iro_opt('infor_bar_style') === 'v2') : ?>
                        <div class="top-social_v2">
                            <?php $print_social_zone(); ?>
                        </div>
                    <?php endif; ?>
                </div>               
            </div>

            <?php if (iro_opt('infor_bar_style') === 'v1') : ?>
                <div class="top-social">
                    <?php $print_social_zone(); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php } ?>
    <div class="homepage-widget">
    <?php if (iro_opt('bulletin_board')) : ?>
    <div class="homepage-widget-card">
        <div class="homepage-widget-card-info">
            <i class="fa-solid fa-bullhorn"></i><?php esc_attr_e('Bulletin', 'sakurairo'); ?>
        </div>
        <div class="hwcard-content">
            <?php $text = iro_opt('bulletin_text'); ?>
            <?php if (mb_strlen($text, 'UTF-8') > 80) { ?>
                <?php $text = mb_substr($text, 0, 80, 'UTF-8'); ?>
            <?php } ?>
            <?php if (mb_strlen($text, 'UTF-8') < 20) { ?>
                <div class="short-bulletin"><?php echo esc_html($text); ?></div>
            <?php } else { ?>
                <?php echo esc_html($text); ?>
            <?php } ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="homepage-widget-card">
    <div class="homepage-widget-card-info">
        <i class="fa-solid fa-at"></i><?php esc_attr_e('Author', 'sakurairo'); ?>
    </div>
    <div class="hwcard-content">
        <?php
        $args = array(
            'role__in' => array('Administrator', 'Editor', 'Author', 'Contributor'),
            'has_published_posts' => true,
            'orderby' => 'post_count',
            'order' => 'DESC'
        );
        $user_query = new WP_User_Query($args);
        $authors = $user_query->get_results();
        $author_count = count($authors);

        if ($author_count == 1) {
            $author = $authors[0];
            $post_count = count_user_posts($author->ID, 'post');
            
            // 获取该作者所有文章的ID
            $author_posts = get_posts(array(
                'author' => $author->ID,
                'post_type' => 'post',
                'posts_per_page' => -1,
                'fields' => 'ids'
            ));
            
            // 统计这些文章的评论数量
            $comment_count = get_comments(array(
                'post__in' => $author_posts,
                'count' => true
            ));
            ?>
            <div class="hwcard-author">
                <div class="hwcard-author-info">
                <a href="<?php echo get_author_posts_url($author->ID); ?>">
                    <?php echo get_avatar($author->ID, 40); ?>
                    </a>
                    <a href="<?php echo get_author_posts_url($author->ID); ?>">
                    <span class="hwcard-author-name"><?php echo esc_html($author->display_name); ?></span>
                </a>
                </div>
                <div class="hwcard-author-data">
                    <span class="hwcard-author-posts"><i class="fa-regular fa-pen-to-square"></i> <?php printf(esc_html__('Posts: %d', 'sakurairo'), $post_count); ?></span>
                    <span class="hwcard-author-comments"><i class="fa-regular fa-comment"></i> <?php printf(esc_html__('Comments: %d', 'sakurairo'), $comment_count); ?></span>
                </div>
            </div>
        <?php } elseif ($author_count == 2) {
            foreach ($authors as $author) {
                $post_count = count_user_posts($author->ID, 'post');
                ?>
            <div class="hwcard-author">
                <div class="hwcard-author-info">
                <a href="<?php echo get_author_posts_url($author->ID); ?>">
                    <?php echo get_avatar($author->ID, 40); ?>
                    </a>
                    <a href="<?php echo get_author_posts_url($author->ID); ?>">
                    <span class="hwcard-author-name"><?php echo esc_html($author->display_name); ?></span>
                </a>
                <span class="hwcard-author-posts-2"><?php printf(esc_html__('Posts: %d', 'sakurairo'), $post_count); ?></span>
            </div>
            </div>
            <?php }
        } elseif ($author_count >= 3) {
            foreach ($authors as $author) {
                ?>
                <div class="hwcard-author-avatar">
                    <a href="<?php echo get_author_posts_url($author->ID); ?>">
                        <?php echo get_avatar($author->ID, 48); ?>
                    </a>
                </div>
            <?php }
        } ?>
    </div>
</div>
    <div class="homepage-widget-card">
        <div class="homepage-widget-card-info">
            <i class="fa-solid fa-paperclip"></i><?php esc_attr_e('Links', 'sakurairo'); ?>
        </div>
    </div>
</figure>
<?php
echo bgvideo(); //BGVideo 
?>
<!-- 首页下拉箭头 -->
<?php if (iro_opt('drop_down_arrow', 'true')) : ?>
<div class="headertop-down" onclick="headertop_down()"><span><svg t="1682342753354" class="homepage-downicon" viewBox="0 0 1843 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="21355" width="60px" height="60px"><path d="M1221.06136021 284.43250057a100.69380037 100.69380037 0 0 1 130.90169466 153.0543795l-352.4275638 302.08090944a100.69380037 100.69380037 0 0 1-130.90169467 0L516.20574044 437.48688007A100.69380037 100.69380037 0 0 1 647.10792676 284.43250057L934.08439763 530.52766665l286.97696258-246.09516608z" fill="<?php echo iro_opt('drop_down_arrow_color'); ?>" p-id="21356"></path></svg></span></div>
<?php endif; ?>