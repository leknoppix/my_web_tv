<?php
function redirection_token()
{
	if(isset($_REQUEST['code']) && $_REQUEST['page'] == 'admin_youtube_token')
	{
		$options = $newoptions = get_option('my_webtvparams');
		$link = admin_url('admin.php?page=admin_youtube_token');
		$clientId = $newoptions['youtube_oauth_client'];
		$clientSecret = $newoptions['youtube_password_oauth_client'];
		$client = GetClient($clientId, $clientSecret);
		$client->setRedirectUri($link);
		$youtube = new Google_Service_YouTube($client);
		$client->authenticate($_GET['code']);
		$token = $client->getAccessToken();
		$newoptions['youtube_token']=$token;
		update_option('my_webtvparams', $newoptions);
		wp_redirect($link, 302);
		die();
	}
}
function admin_youtube()
{
}
// Fonction pour lister les vidéos
function admin_youtube_listing()
{
	$newoptions = get_option('my_webtvparams');
	$clientId = $newoptions['youtube_oauth_client'];
	$clientSecret = $newoptions['youtube_password_oauth_client'];
	$client = GetClient($clientId, $clientSecret);
	$client->setAccessToken($newoptions['youtube_token']);
	debug($client);
	//$youtube = new Google_Service_YouTube($client);
}
//fonction getclient
function GetClient($clientId, $clientSecret)
{
	$client = new Google_Client();
	$client->setClientId($clientId);
	$client->setClientSecret($clientSecret);
	$client->setScopes('https://www.googleapis.com/auth/youtube');
	return $client;
}