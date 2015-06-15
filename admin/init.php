<?php
function add_menu()
{
	add_menu_page('Gestionnaire de video depuis YouTube', 'Gestionnaire de video depuis YouTube', 3, 'admin_youtube', 'admin_youtube', plugins_url( 'icon.png' , dirname(__FILE__)));
	add_submenu_page('admin_youtube', 'Lister les vidéos de sa chaine', 'Lister les vidéos de sa chaine', 1, 'admin_youtube_listing', 'admin_youtube_listing');
	add_submenu_page('admin_youtube', 'Ajouter une video dans youtube', 'Ajouter une video dans youtube', 2, 'admin_youtube_add_video', 'admin_youtube_add_video');
	add_submenu_page('admin_youtube', 'Réglage du plugin', 'Réglage du plugin', 3, 'admin_youtube_params', 'admin_youtube_params');
	add_submenu_page('admin_youtube', 'Générer le token pour youtube', 'Générer le token pour youtube', 4, 'admin_youtube_token', 'admin_youtube_token');
}