<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");


//rate limiting by IP, don't allow more than 3 created games per 3 min
$ip = $_SERVER['REMOTE_ADDR'];
$log_file = dirname(__DIR__) . '/data/rate_limit_log.json';
$limit_seconds = 180; // time window
$max_creations = 3;  // max allowed per window

$log = file_exists($log_file) ? json_decode(file_get_contents($log_file), true) : [];

$log[$ip] = array_filter($log[$ip] ?? [], fn($t) => $t > time() - $limit_seconds);
if (count($log[$ip]) >= $max_creations) {
    http_response_code(429);
    die("<span style='color: red;'>You've created too many games, try again later.</span>");
}

$log[$ip][] = time();
file_put_contents($log_file, json_encode($log));


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

// delete 1 hour old games that never got another player
$now = time();
$expiration_seconds = 3600; // 1 hour
foreach ($games as $gid => $game) {
    if (!$game['started'] && isset($game['created']) && $game['created'] < $now - $expiration_seconds) {
        unset($games[$gid]);
    }
}

// delete games 24 hours old
$now = time();
$expiration_seconds = 86400; // 24 hours
foreach ($games as $gid => $game) {
    if (isset($game['created']) && $game['created'] < $now - $expiration_seconds) {
        unset($games[$gid]);
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
