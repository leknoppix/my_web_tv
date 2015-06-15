<?php
function mywt_ListVideo(){
	$var=get_option('my_webtvparams');
	$youtubedevkey = $var['youtube_devkey'];
	$youtubeforusername = $var['youtube_forusername'];
	$maxResults = $var['youtube_limit'];
	$order = $var['youtube_order'];
	$champstri = $var['youtube_champstri'];
	$youtube_playlist = $var['youtube_playlist'];
	if( !wp_cache_get('listevideo') )
	{
		$client = new Google_Client();
		$client->setDeveloperKey($youtubedevkey);
		$youtube = new Google_Service_YouTube($client);
		if($youtube_playlist != ''){
			$uploadsListId=$youtube_playlist;
		}
		if($youtubeforusername != ''){
			$uploadsListId = getPlaylistId($youtube, $youtubeforusername);
		}
		$response2 = getListVideoInfo($youtube, $uploadsListId, $maxResults, $order, $champstri);
		wp_cache_add( 'listevideo', json_encode($response2) );
	}
	/* Lecture du cache */
	$response2 = wp_cache_get('listevideo');
	$result = json_decode($response2);
	parsearray($result);
}
/* Récupération le numéro de la playlist complete */
function getPlaylistId($youtube, $youtubeforusername){
	$response = $youtube->channels->listChannels('id,contentDetails', ['forUsername' => $youtubeforusername]);
	/* récupération de l'id de la chaine */
	$uploadsListId = $response['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
	return $uploadsListId;
}
/* Récupérer la liste des videos */
function getListVideoInfo($youtube, $uploadsListId, $maxResults, $order, $champstri){
		$getListVideoInfo = $youtube->playlistItems->listPlaylistItems('snippet', ['playlistId' => $uploadsListId, 'maxResults' => $maxResults]);
		foreach ($getListVideoInfo['items'] as $item){
			$title=$item["snippet"]['title'];
			$videoId = $item["snippet"]['resourceId']['videoId'];
			$duree=getVideoDuration($youtube, $videoId);
			if($champstri == "0"){
				$variable = $title;
			} else if($champstri == "1"){
				$variable = $duree;
			} else if($champstri == "2"){
				$variable = $item["snippet"]['publishedAt'];
			} else {
				$variable = $title;
			}
			$tableau[$variable]=array(
				'title'=> $title,
				'description'=> $item["snippet"]['description'],
				'videoId'=> $item["snippet"]['resourceId']['videoId'],
				'ImageUrl' => $item["snippet"]['thumbnails']['high']['url'],
				'duree' => $duree
			);
		}
		if($order=="0") {
			ksort($tableau);
		} else {
			krsort($tableau);
		}
		return $tableau;
}
/* Récupération de la durée d'un vidéo Clé */
function getVideoDuration($youtube, $VideoId){
	$id = $youtube->videos->listVideos('contentDetails',['id' => $VideoId]);
	$dureenonencode= $id['items'][0]['contentDetails']['duration'];
	$duree = (new DateInterval($dureenonencode))->format('%imin%s');
	return $duree;
}
function parsearray($arrays = null){
	$retourne='';
	foreach($arrays as $title => $key){
		$retourne.= "<h1>" . $key->title . "</h1>";
		$retourne.= "<p>" . nl2br( $key->description ) . "</p>";
		$retourne.= "<a href=\"http://www.youtube.com/watch?v=". $key->videoId ."\"><img src=\"".$key->ImageUrl . "\" title=\"" . $key->title . "\" alt=\"" . $key->title ."\" /></a>";
		$retourne.= "<hr />";
	}
	echo $retourne;
}
add_shortcode('ListVideo', 'mywt_ListVideo');