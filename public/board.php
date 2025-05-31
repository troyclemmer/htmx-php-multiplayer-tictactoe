<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");

$game_id = $_GET['game'] ?? '';
$player = $_GET['player'] ?? '';

if (!file_exists(GAMES_FILE)) {
    die("Error: Game data file not found.");
}

$games = json_decode(file_get_contents(GAMES_FILE), true);

if (!$games || !isset($games[$game_id])) {
    die("Error: Game not found or data is corrupted.");
}

$game = $games[$game_id];
$spectating = (empty($player) || (strtoupper($player) !== "X" && strtoupper($player) !== "O"));

?>
<div id="board"
     hx-get="board.php?game=<?= urlencode($game_id) ?>&player=<?= urlencode($player) ?>"
     hx-trigger="every 2s"
     hx-swap="outerHTML"
>
<?php for ($r = 0; $r < 3; $r++): ?>
    <div>
    <?php for ($c = 0; $c < 3; $c++):
        $cell = $game['board'][$r][$c];
        if ($game['started'] && !$spectating && (($cell === "") && empty($game['winner']) && empty($game['draw']))): ?>
            <button
                hx-post="move.php"
                hx-target="#board"
                hx-swap="outerHTML"
                hx-vals='<?= json_encode(["game" => $game_id, "player" => $player, "row" => $r, "col" => $c]) ?>'
            >&nbsp;</button>
        <?php else: ?>
            <button disabled><?= $cell === "" ? "&nbsp;" : htmlspecialchars($cell) ?></button>
        <?php endif; ?>
    <?php endfor; ?>
    </div>
<?php endfor; ?>

<hr>
<div style="margin-top:1rem;">
<?php if (!empty($game['winner']) && $game['winner']!=="draw" ): ?>
    <sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='neutral' pill>Completed</sl-badge><span>🎉 Player <?= htmlspecialchars($game['winner']) ?> wins! 🎉</span>
<?php elseif (!empty($game['winner']) && $game['winner']==="draw" ): ?>
    <sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='neutral' pill>Completed</sl-badge><span>It's a draw! 🤝</span>
<?php elseif (!$game['started']): ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='warning' pill>Waiting</sl-badge><span>Waiting for Player O</span>
<?php elseif ($spectating): ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='primary' pulse pill>In Progress</sl-badge><span>Current Turn: <?= htmlspecialchars($game['turn']) ?></p>
<?php elseif (htmlspecialchars($game['turn'])===$player): ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='success' pulse pill>In Progress</sl-badge><span>It's your turn!</span>
<?php else: ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='danger' pulse pill>In Progress</sl-badge><span>It's their turn!</span>
<?php endif; ?>
</div>

</div>