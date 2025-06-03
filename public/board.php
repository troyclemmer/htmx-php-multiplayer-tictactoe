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
    die("<span style='color:red;'>Error: Game not found or data is corrupted.</span>");
}

$game = $games[$game_id];
$spectating = (empty($player) || (strtoupper($player) !== "X" && strtoupper($player) !== "O") || (strtoupper($player)==='O' && $ai_opponent));

?>
<div id="board"
     hx-get="board.php?game=<?= urlencode($game_id) ?>&player=<?= urlencode($player) ?>"
     hx-trigger="every 2s"
     hx-swap="outerHTML"
>
<?php 
$winningLookup = [];
if (!empty($game['winning_cells'])) {
foreach ($game['winning_cells'] ?? [] as [$wr, $wc]) {
    $winningLookup["$wr,$wc"] = true;
}
}
for ($r = 0; $r < 3; $r++): ?>
    <div>
    <?php for ($c = 0; $c < 3; $c++):
        $cell = $game['board'][$r][$c];
        if ($game['started'] && !$spectating && (($cell === "") && empty($game['winner']) && empty($game['draw'])) && $game['turn']===$player): ?>
            <button class="cell"
                hx-post="move.php"
                hx-target="#board"
                hx-swap="outerHTML"
                hx-vals='<?= json_encode(["game" => $game_id, "player" => $player, "row" => $r, "col" => $c]) ?>'
            >&nbsp;</button>
        <?php else: ?>
			<?php
			$isWinning = isset($winningLookup["$r,$c"]);
			$style = $isWinning ? 'color: blue; font-weight:bold;' : '';
			?>
            <button class="cell" style="<?= $style ?>" disabled><?= $cell === "" ? "&nbsp;" : htmlspecialchars($cell) ?></button>
        <?php endif; ?>
    <?php endfor; ?>
    </div>
<?php endfor; ?>

<hr>
<div style="margin-top:1rem;">
<?php if (!empty($game['winner']) && $game['winner']!=="draw" ): ?>
	<?php if ($spectating): ?>
		<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='neutral' pill>Completed</sl-badge><span>🎉 Player <?= htmlspecialchars($game['winner']) ?> wins! 🎉</span>
	<?php elseif (htmlspecialchars($game['winner'])===$player): ?>
		<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='neutral' pill>Completed</sl-badge><span>🎉 You won! 🎉</span>
	<?php else: ?>
		<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='neutral' pill>Completed</sl-badge><span>😢 You lost! 😢</span>
<?php endif; ?>
<?php elseif (!empty($game['winner']) && $game['winner']==="draw" ): ?>
    <sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='neutral' pill>Completed</sl-badge><span>It's a draw! 🤝</span>
<?php elseif (!$game['started']): ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='warning' pill>Waiting</sl-badge><span>Waiting for Player O</span>
	<div style="margin-top:2rem;"><form action="play.php?game=<?= urlencode($game_id) ?>&player=X&ai=1" method="POST"><button>Play AI Opponent</button></form></div>
<?php elseif ($spectating): ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='primary' pulse pill>In Progress</sl-badge><span>Current Turn: <?= htmlspecialchars($game['turn']) ?></p>
<?php elseif (htmlspecialchars($game['turn'])===$player): ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='success' pulse pill>In Progress</sl-badge><span>It's your turn!</span>
<?php else: ?>
	<sl-badge style='font-size:1.25rem; display: inline-block; margin-right:.75rem;' variant='danger' pulse pill>In Progress</sl-badge><span>It's their turn!</span>
<?php endif; ?>
</div>


</div>