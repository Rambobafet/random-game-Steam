<?php
/*
 * Projet : Random Game Steam
 * Description : Trop de jeu sur Steam ? Lance la roulette du gamer et laisse l'ordinateur choisir a quoi tu joues la maintenant. Ouai, ca n'a aucun intérêt. Bisous.
 * Auteur : Bob
 * Version : 0.1
 */

include('lib/steam-condenser/steam-condenser.php');
require('config.php');

/* * ************
  FUNCTIONS
 * ************ */

function getJsonCurl($url) {
    /** Etape 1 : initialisation de la session * */
    $ch = curl_init();
    /** Etape 2 : définition des options * */
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /** Etape 3 : exécution de la requête * */
    $result = curl_exec($ch);
    /** Etape 4 : fermeture de la session * */
    curl_close($ch);
    /** Si on veut afficher le contenu * */
    $result = json_decode($result, true);
    return $result;
}

function uniteTemps($time) {
    $time = intval($time);
    if ($time < 60) {
        return $time . " mn";
    } else if (60 < $time && $time < 1440) {
        return floor($time / 60) . "h";
    } else {
        return floor($time / 1440) . "+ jours";
    }
}

// Recently played : http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=811BEC3E2DF3C86F55B61315005DCA5C&steamid=76561198032821242&format=json

$url = "http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=" . $keyDev . "&steamid=" . $steamId64 . "&format=json";
$result = getJsonCurl($url);
$array_games = array();
foreach ($result['response']['games'] as $key => $value) {
    //echo '<li><img src="http://media.steampowered.com/steamcommunity/public/images/apps/' . $value['appid'] . '/' . $value['img_icon_url'] . '.jpg" alt="' . $value['name'] . '" />' . $value['name'] . ' --> ' . uniteTemps($value['playtime_forever']) . '</li>';
    array_push($array_games, $value);
}

$url = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . $keyDev . "&steamid=" . $steamId64 . "&include_appinfo=1&format=json";
$result = getJsonCurl($url);
foreach ($result['response']['games'] as $key => $value) {
    array_push($array_games, $value);
    //echo '<li><img src="http://media.steampowered.com/steamcommunity/public/images/apps/' . $value['appid'] . '/' . $value['img_icon_url'] . '.jpg" alt="' . $value['name'] . '" />' . $value['name'] . ' --> ' . uniteTemps($value['playtime_forever']) . '</li>';
}

$rand_keys = array_rand($array_games, 2);

?>
<h1>Hurray! Le Dieu Machine a choisi pour toi ces jeux :</h1>
<ul>
    <?php 
        echo '<li><img src="http://media.steampowered.com/steamcommunity/public/images/apps/' . $array_games[$rand_keys[0]]['appid'] . '/' . $array_games[$rand_keys[0]]['img_icon_url'] . '.jpg" alt="' . $array_games[$rand_keys[0]]['name'] . '" />' . $array_games[$rand_keys[0]]['name'] . ' --> ' . uniteTemps($array_games[$rand_keys[0]]['playtime_forever']) . '</li>';
        echo '<li><img src="http://media.steampowered.com/steamcommunity/public/images/apps/' . $array_games[$rand_keys[1]]['appid'] . '/' . $array_games[$rand_keys[1]]['img_icon_url'] . '.jpg" alt="' . $array_games[$rand_keys[1]]['name'] . '" />' . $array_games[$rand_keys[1]]['name'] . ' --> ' . uniteTemps($array_games[$rand_keys[1]]['playtime_forever']) . '</li>';
    ?>
</ul>