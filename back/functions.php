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
	echo '<div class="wrap">';
	echo '<h1>';
	echo 'MyWebTv: Réglage du plugin';
	echo '</h1>';
	echo '<form method="post">';
	echo '<fieldset">';
	echo '<table class="wp-list-table widefat fixed posts" cellspacing="0"';
	echo '<tr>';
	echo '<th><label for="youtube_limit">Nombre de video de l\'utilisateur youtube à afficher</label></th>';
	echo '<td><input type="text" name="youtube_limit" id="youtube_limit" value="'.$options['youtube_limit'].'" class="regular-text code"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="">Clé Developpeur YT</label></th>';
	echo '<td><input type="text" name="youtube_devkey" id="youtube_devkey" value="'.$options['youtube_devkey'].'" class="regular-text code"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="youtube_oauth_client">Identifiant client Oauth 2.0</label></th>';
	echo '<td><input type="text" name="youtube_oauth_client" id="youtube_oauth_client" value="'.$options['youtube_oauth_client'].'" class="regular-text code"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="youtube_password_oauth_client">Password client Oauth 2.0</label></th>';
	echo '<td><input type="text" name="youtube_password_oauth_client" id="youtube_password_oauth_client" value="'.$options['youtube_password_oauth_client'].'" class="regular-text code"/>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="youtube_token">Clé Token</label></th>';
	echo '<td>';
	if($options['youtube_token'] != '')
	{
		echo 'Token valide<br /><input type="text" name="youtube_token" id="youtube_token" value="'.$options['youtube_token'].'" class="regular-text code"/></td>';
	}
	else
	{
		echo "Token invalide ou non créer";
	}
	echo '</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="youtube_playlist">Numéro de la Playlist YT (si compléter, ne pas compléter le champs suivant)</label></th>';
	echo '<td><input type="text" name="youtube_playlist" id="youtube_playlist" value="'.$options['youtube_playlist'].'" class="regular-text code"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="youtube_forusername">Nom de l\'utilisateur YT</label></th>';
	echo '<td><input type="text" name="youtube_forusername" id="youtube_forusername" value="'.$options['youtube_forusername'].'" class="regular-text code"/></td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="youtube_order">Ordre de visionnage</label></th>';
	echo '<td>
		<select name="youtube_order" id="youtube_order">
			<option value="0" ';
			if( $options['youtube_order'] == "0" )
			{
				echo 'selected="selected"';
			}
			echo '>Croissant</option>';
				echo '<option value="1" ';
				if( $options['youtube_order'] == "1" )
				{
					echo 'selected="selected"';
				}
				echo '>Décroissant</option>
			</select>
	</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<th><label for="youtube_champstri">Ordre de visionnage</label></th>';
	echo '<td>
		<select name="youtube_champstri" id="youtube_champstri">
			<option value="0" ';
			if( $options['youtube_champstri'] == "0" )
			{
				echo 'selected="selected"';
			}
			echo '>Titre</option>';
			echo '<option value="1" ';
			if( $options['youtube_champstri'] == "1" )
			{
				echo 'selected="selected"';
			}
			echo '>Durée</option>';
			echo '<option value="2" ';
			if( $options['youtube_champstri'] == "2" )
			{
				echo 'selected="selected"';
			}
			echo '>Date de publication</option>
		</select>
	</td>';
	echo '</tr>';
	echo '</table>';
	echo '</fieldset>';
	echo '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Enregistrer les modifications"  /></p></form>';    
	echo "</div>";
}
// Fonction pour récupérer le token de transaction avec Youtube
function admin_youtube_token(){
	$newoptions = get_option('my_webtvparams');
	if(!isset($newoptions['youtube_token']))
	{
		echo '<div class="wrap widefat fixed posts">';
		echo '<h1>';
		echo 'MyWebTv: génération du token';
		echo '</h1>';
		echo "<h3>Attention, le token est déjà connu. Pour le régénérer, veuiller cliquez sur <a href=\"admin.php?page=admin_youtube_params\">réglage du plugin</a>.</h3>";
		echo "</div>";
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
			wp_redirect($link);
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
			echo '<div class="wrap">';
			echo '<h1>';
			echo 'MyWebTv: génération du token';
			echo '</h1>';
			echo "<p>Ce page va vous permettre de créer le token d'identification qui permettra d'envoyer les videos vers la plateforme Youtube, sans avoir besoin d'utiliser les identifiants YT.<br />";
			echo "Pour cela, cliquez sur le <a href=\"".$client->createAuthUrl()."\">lien suivant</a> et suivez les instructions.</p>";
			echo "</div>";
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