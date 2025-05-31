<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
$game_id = $_GET['game'] ?? '';
$player = $_GET['player'] ?? '';
$spectating = (empty($player) || (strtoupper($player) !== "X" && strtoupper($player) !== "O"));
?>
<title>Game <?= $game_id; ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/themes/light.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
<style>
button {
    width: 100px;
    height: 100px;
	font-size: 3rem;
	text-align: center;
}
</style>
</head>
<body>

<a style="position: absolute; top: 5; left:5;" href='/'>Back to Lobby</a>

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
