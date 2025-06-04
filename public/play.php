<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");
$game_id = $_GET['game'] ?? '';
$player = $_GET['player'] ?? '';
$ai_opponent = isset($_GET['ai']) ?? 'false';

if (!file_exists(GAMES_FILE)) {
    die("<span style='color:red;'>Error: Game data file not found.</span>");
}

$games = json_decode(file_get_contents(GAMES_FILE), true);

if (!$games || !isset($games[$game_id])) {
    die("<span style='color:red;'>Error: Game not found.</span>");
}

$game = $games[$game_id];
$spectating = (empty($player) || (strtoupper($player) !== "X" && strtoupper($player) !== "O") || (strtoupper($player)==='O' && $ai_opponent));

if (!$game['started']) {
	//you clicked the ai opponent button and we need to set the game file to using an ai opponent and start the game
	if ($ai_opponent && strtoupper($player)==="X") {
		$games[$game_id]['ai_opponent'] = true;
		$games[$game_id]['players']['O'] = true;
		$games[$game_id]['started'] = true;
		file_put_contents(GAMES_FILE, json_encode($games));
	//if you are player O in an unstarted game somehow (manually copying URL and editing querystring), just start the game otherwise it will say You are Player O, waiting for Player O
	} else if (strtoupper($player)==="O") {
		//this will set up player O and start the game
		header("Location: join_game.php?game=$game_id");
	}
}
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Game <?= $game_id; ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/themes/light.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
<style>
button.cell {
    width: 100px;
    height: 100px;
	font-size: 3rem;
	text-align: center;
}
</style>
</head>
<body>

<a style="position: absolute; top: 5; left:5;" href='./'>Back to Lobby</a>

<?php
if (!$spectating):
?>
<h2 style="padding-top: 1rem;">You are Player <?= htmlspecialchars($player) ?></h2>
<?php else: ?>
<h2 style="padding-top: 1rem;">You are Spectating</h2>
<?php endif; ?>
<p>Game <?= $game_id; ?></p>
<?php include "board.php"; ?>
<script src="https://unpkg.com/htmx.org@2.0.4" integrity="sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+" crossorigin="anonymous"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/components/badge/badge.js"></script>
</body>
</html>
