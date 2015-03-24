<?php
$meta = get_post_meta($post->ID);
$director = get_post($meta['_igv_director'][0]);
$permalink = get_permalink($director->ID);

$redirect = $permalink . '#video-' . $post->post_name;

header('HTTP/1.1 301 Moved Permanently');
header('Location: ' . $redirect);
?>