<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");

$id = trim(htmlspecialchars($_GET['game']));
$games = file_exists(GAMES_FILE) ? json_decode(file_get_contents(GAMES_FILE), true) : [];
if (isset($games[$id]) && !$games[$id]['started']) {
    $games[$id]['players']['O'] = true;
    $games[$id]['started'] = true;
    file_put_contents(GAMES_FILE, json_encode($games));
    header("Location: play.php?game=$id&player=O");
} else {
    //echo "Invalid or full game.";
	header("Location: play.php?game=$id");
}
