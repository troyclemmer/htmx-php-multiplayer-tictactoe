<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");

$games = file_exists(GAMES_FILE)
    ? json_decode(file_get_contents(GAMES_FILE), true)
    : [];
	
// delete old games
$now = time();
$expiration_seconds = 86400; // 24 hours
foreach ($games as $id => $game) {
    if (isset($game['created']) && $game['created'] < $now - $expiration_seconds) {
        unset($games[$id]);
    }
}
	
$id = uniqid();
$games[$id] = [
    "board" => array_fill(0, 3, array_fill(0, 3, "")),
    "turn" => "X",
    "started" => false,
    "players" => ["X" => true],
	"created" => time()
];
file_put_contents(GAMES_FILE, json_encode($games));
header("Location: play.php?game=$id&player=X");
