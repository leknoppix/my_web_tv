<?php
function admin_youtube(){
}
// Fonction pour enregistrer les paramètres
function admin_youtube_params(){
	$options = $newoptions = get_option('my_webtvparams');
	if( isset($_POST['youtube_devkey']))
	{
		$newoptions['youtube_limit'] = strip_tags(stripslashes($_POST["youtube_limit"]));
		$newoptions['youtube_devkey'] = strip_tags(stripslashes($_POST["youtube_devkey"]));
		$newoptions['youtube_forusername'] = strip_tags(stripslashes($_POST["youtube_forusername"]));
		$newoptions['youtube_order'] = strip_tags(stripslashes($_POST["youtube_order"]));
		$newoptions['youtube_champstri'] = strip_tags(stripslashes($_POST["youtube_champstri"]));
		$newoptions['youtube_playlist'] = strip_tags(stripslashes($_POST["youtube_playlist"]));
		$newoptions['youtube_oauth_client'] = strip_tags(stripslashes($_POST["youtube_oauth_client"]));
		$newoptions['youtube_token'] = strip_tags(stripslashes($_POST["youtube_token"]));
		$newoptions['youtube_password_oauth_client'] = strip_tags(stripslashes($_POST["youtube_password_oauth_client"]));
	}
	// On enregistre si il y a une mise à jour de l'option
	if( $options != $newoptions )
	{
		$options = $newoptions;
		update_option('my_webtvparams', $options);
	}
	$retourne='';
	$retourne.= '<div class="wrap">';
	$retourne.= '<h1>';
	$retourne.= 'MyWebTv: Réglage du plugin';
	$retourne.= '</h1>';
	$retourne.= '<form method="post">';
	$retourne.= '<fieldset">';
	$retourne.= '<table class="wp-list-table widefat fixed posts" cellspacing="0"';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_limit">Nombre de video de l\'utilisateur youtube à afficher</label></th>';
	$retourne.= '<td><input type="text" name="youtube_limit" id="youtube_limit" value="'.$options['youtube_limit'].'" class="regular-text code"/></td>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="">Clé Developpeur YT</label></th>';
	$retourne.= '<td><input type="text" name="youtube_devkey" id="youtube_devkey" value="'.$options['youtube_devkey'].'" class="regular-text code"/></td>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_oauth_client">Identifiant client Oauth 2.0</label></th>';
	$retourne.= '<td><input type="text" name="youtube_oauth_client" id="youtube_oauth_client" value="'.$options['youtube_oauth_client'].'" class="regular-text code"/></td>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_password_oauth_client">Password client Oauth 2.0</label></th>';
	$retourne.= '<td><input type="text" name="youtube_password_oauth_client" id="youtube_password_oauth_client" value="'.$options['youtube_password_oauth_client'].'" class="regular-text code"/>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_token">Clé Token</label></th>';
	$retourne.= '<td>';
	if($options['youtube_token'] != '')
	{
		$retourne.= 'Token valide<br /><input type="text" name="youtube_token" id="youtube_token" value="'.$options['youtube_token'].'" class="regular-text code"/></td>';
	}
	else
	{
		$retourne.= "Token invalide ou non créer";
	}
	$retourne.= '</td>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_playlist">Numéro de la Playlist YT (si compléter, ne pas compléter le champs suivant)</label></th>';
	$retourne.= '<td><input type="text" name="youtube_playlist" id="youtube_playlist" value="'.$options['youtube_playlist'].'" class="regular-text code"/></td>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_forusername">Nom de l\'utilisateur YT</label></th>';
	$retourne.= '<td><input type="text" name="youtube_forusername" id="youtube_forusername" value="'.$options['youtube_forusername'].'" class="regular-text code"/></td>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_order">Ordre de visionnage</label></th>';
	$retourne.= '<td>
		<select name="youtube_order" id="youtube_order">
			<option value="0" ';
			if( $options['youtube_order'] == "0" )
			{
				$retourne.= 'selected="selected"';
			}
			$retourne.= '>Croissant</option>';
				$retourne.= '<option value="1" ';
				if( $options['youtube_order'] == "1" )
				{
					$retourne.= 'selected="selected"';
				}
				$retourne.= '>Décroissant</option>
			</select>
	</td>';
	$retourne.= '</tr>';
	$retourne.= '<tr>';
	$retourne.= '<th><label for="youtube_champstri">Ordre de visionnage</label></th>';
	$retourne.= '<td>
		<select name="youtube_champstri" id="youtube_champstri">
			<option value="0" ';
			if( $options['youtube_champstri'] == "0" )
			{
				$retourne.= 'selected="selected"';
			}
			$retourne.= '>Titre</option>';
			$retourne.= '<option value="1" ';
			if( $options['youtube_champstri'] == "1" )
			{
				$retourne.= 'selected="selected"';
			}
			$retourne.= '>Durée</option>';
			$retourne.= '<option value="2" ';
			if( $options['youtube_champstri'] == "2" )
			{
				$retourne.= 'selected="selected"';
			}
			$retourne.= '>Date de publication</option>
		</select>
	</td>';
	$retourne.= '</tr>';
	$retourne.= '</table>';
	$retourne.= '</fieldset>';
	$retourne.= '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications"  /></p></form>';    
	$retourne.= "</div>";
	echo $retourne;
}
// Fonction pour récupérer le token de transaction avec Youtube
function admin_youtube_token(){
	$retourne='';
	$newoptions = get_option('my_webtvparams');
	if(!isset($newoptions['youtube_token'])&&!empty($newoptions['youtube_token']))
	{
		$retourne.= '<div class="wrap widefat fixed posts">';
		$retourne.= '<h1>';
		$retourne.= 'MyWebTv: génération du token';
		$retourne.= '</h1>';
		$retourne.= "<h3>Attention, le token est déjà connu. Pour le régénérer, veuiller cliquez sur <a href=\"admin.php?page=admin_youtube_params\">réglage du plugin</a>.</h3>";
		$retourne.= "</div>";
		echo $retourne;
	}
	else
	{
		if(isset($_GET['code']))
		{
			$link = admin_url('admin.php?page=admin_youtube_token');
			$clientId = $newoptions['youtube_oauth_client'];
			$clientSecret = $newoptions['youtube_password_oauth_client'];
			$client = new Google_Client();
			$client->setClientId($clientId);
			$client->setClientSecret($clientSecret);
			$client->setRedirectUri($link);
			$client->setScopes('https://www.googleapis.com/auth/youtube');
			$youtube = new Google_Service_YouTube($client);
			$client->authenticate($_GET['code']);
			$token = $client->getAccessToken();
			$newoptions['youtube_token']=$token;
			update_option('my_webtvparams', $newoptions);
			wp_redirect('admin.php?page=admin_youtube_token');
			exit();
		}
		else
		{
			$link = admin_url('admin.php?page=admin_youtube_token');
			$clientId = $newoptions['youtube_oauth_client'];
			$clientSecret = $newoptions['youtube_password_oauth_client'];
			$client = new Google_Client();
			$client->setClientId($clientId);
			$client->setClientSecret($clientSecret);
			$client->setRedirectUri($link);
			$client->setScopes('https://www.googleapis.com/auth/youtube');
			$youtube = new Google_Service_YouTube($client);
			$retourne.= '<div class="wrap">';
			$retourne.= '<h1>';
			$retourne.= 'MyWebTv: génération du token';
			$retourne.= '</h1>';
			$retourne.= "<p>Ce page va vous permettre de créer le token d'identification qui permettra d'envoyer les videos vers la plateforme Youtube, sans avoir besoin d'utiliser les identifiants YT.<br />";
			$retourne.= "Pour cela, cliquez sur le <a href=\"".$client->createAuthUrl()."\">lien suivant</a> et suivez les instructions.</p>";
			$retourne.= "</div>";
			echo $retourne;
		}
	}
}
// Fonction pour lister les vidéos
function admin_youtube_listing(){
	$newoptions = get_option('my_webtvparams');
	debug($newoptions['youtube_token']);
	$clientId = $newoptions['youtube_oauth_client'];
	$clientSecret = $newoptions['youtube_password_oauth_client'];
	$client = new Google_Client();
	$client->setClientId($clientId);
	$client->setClientSecret($clientSecret);
	$client->setScopes('https://www.googleapis.com/auth/youtube');
	$youtube = new Google_Service_YouTube($client);
	$client->setAccessToken($newoptions['youtube_token']);
	$refreshToken= $client->refreshToken();
	debug($refreshToken);
}