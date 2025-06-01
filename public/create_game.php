<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");

$games = file_exists(GAMES_FILE)
    ? json_decode(file_get_contents(GAMES_FILE), true)
    : [];
	


// Shorter ID start  (lowest integer that isn't taken)

// Find the lowest available integer ID
$id = 1;
while (isset($games[(string)$id])) {
    $id++;
}

// Basic "random delay" to reduce race condition impact (optional)
usleep(random_int(10, 100) * 1000); // sleep 10–100 ms

// Check again after short delay in case another process created this ID
$games = json_decode(file_get_contents(GAMES_FILE), true);
if (isset($games[(string)$id])) {
    // Someone else grabbed it — re-run ID generation
    header("Location: create_game.php"); // or use recursion or loop
    exit;
}
//shorter ID end

//$id = uniqid(); //use this if you have more concurrent users, but makes the strings so much longer

// delete old games
$now = time();
$expiration_seconds = 86400; // 24 hours
foreach ($games as $id => $game) {
    if (isset($game['created']) && $game['created'] < $now - $expiration_seconds) {
        unset($games[$id]);
    }
}

$games[(string)$id] = [
    "board" => array_fill(0, 3, array_fill(0, 3, "")),
    "turn" => "X",
    "started" => false,
    "players" => ["X" => true],
	"created" => time()
];
file_put_contents(GAMES_FILE, json_encode($games));
header("Location: play.php?game=$id&player=X");
