<?php
/*
   Plugin Name: Retell
   Plugin URI: https://retell.cc
   description: Turn your stories into audio, in real time.
   Version: 1.0.2
   Author: Retell
   Author URI: https://retell.cc
   License: MIT
*/

function register_retell($content)
{
    $link = get_permalink($content->ID);
    // TODO: get player options from settings
    $script = "<script data-voiced='player'> Retell.init({
           url: '{$link}'
      }) </script>";
    return $script . $content;
}

function init_retell_rss()
{
    wp_enqueue_script('js-file', 'https://widget.retell.cc/js/common.min.js');
    add_feed('retell', 'add_retell_rss');
}

function add_retell_rss()
{
    header('Content-Type: application/rss+xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    // TODO: move numberposts to settings
    $posts = get_posts(['numberposts' => 20])
?>
<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
    <channel>
        <title><?php bloginfo_rss('title') ?></title>
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss('description') ?></description>
        <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
        <?php foreach ($posts as $post): ?>
        <item>
            <title> <![CDATA[ <?php echo wp_kses($post->post_title, 'default'); ?> ]]> </title>
            <link><?php echo get_permalink($post->ID) ?></link>
            <content:encoded>
                <![CDATA[ <?php echo wp_kses($post->post_content, 'default') ?> ]]>
            </content:encoded>
        </item>
        <?php endforeach; ?>
    </channel>
</rss>
<?php
}

add_action('init', 'init_retell_rss');
add_filter('the_content', 'register_retell');
?>
