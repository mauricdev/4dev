<?php 

// Limite de post a mostrar, si no se indica se muestran 4 por default
$limit            = 4;

function getImages( $limit = 4 ){

    $user_id       = '2977856455';
    $access_token  = '2977856455.1677ed0.896abe14792c42a39e9a40ae2402936f';
    $item_resource = 'userid';
    $hashtag       = '';
    $limit         = $limit;

    if (!$user_id || !$access_token) {
        echo '<p class="alert alert-warning">NO HAY INFORMACIÓN COMPLETA</p>';
        return;
    }

    if( $item_resource == 'hashtag' && $hashtag) {
        $api = "https://api.instagram.com/v1/tags/". $hashtag  ."/media/recent/?access_token=" . $access_token . "&count=". $limit;
    } else {
        $api = "https://api.instagram.com/v1/users/". $user_id  ."/media/recent/?access_token=" . $access_token . "&count=". $limit;
    }

    if( ini_get('allow_url_fopen') ) {
        $images = @file_get_contents($api);
        @file_put_contents($cache_file, $images, LOCK_EX);
    } else {
        $images = curl($api);
    }

    $json = json_decode($images);
    if(isset($json->data)) {
        return $json->data;
    }
    
    return array();

}
	
function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}
?>