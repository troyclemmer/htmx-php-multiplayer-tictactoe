<ul style="list-style-type: none; padding-inline-start: 0; padding:0 1rem;">
<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");
$games = json_decode(file_get_contents(GAMES_FILE), true) ?? [];

uasort($games, function($a, $b) { 
	if ($a['started'] === $b['started']) {
		if (array_key_exists('winner',$a) === array_key_exists('winner',$b)) {
			return $b['created'] - $a['created'];
		} 
		
		return array_key_exists('winner',$a) - array_key_exists('winner',$b); 
	} 
	
	return $a['started'] - $b['started']; 
}); 


foreach ($games as $id => $g) {
    //if (!$g['started']) {
		$created = new DateTime("@{$g['created']}"); // "@" tells DateTime to interpret as timestamp in UTC
		$created->setTimezone(new DateTimeZone('America/New_York')); // Replace with user's timezone
		$completed = !empty($g['winner']);
        echo  "<li style='margin-bottom: .2rem; white-space: nowrap;'>";
			if (!$g['started']) {
				echo "<sl-badge style='display: inline-block; width: 80px; margin-right:.75rem;' variant='warning' pill>Waiting</sl-badge>";
			} else {
				if ($completed) {
					echo "<sl-badge style=' display: inline-block;width: 80px; margin-right:.75rem;' variant='neutral' pill>Completed</sl-badge>";
				} else {
					echo "<sl-badge style=' display: inline-block;width: 80px; margin-right:.75rem;' variant='primary' pulse pill>In Progress</sl-badge>";
				}
			}
			echo "<div style='".($completed?"opacity:.6; ":"")."display:inline-block;'>";
			echo "Game $id";
			echo " &bull; ";
			echo "<small title='".$created->format('M jS, Y \\a\\t g:i A')."'><i><sl-relative-time date='".$created->format(DateTime::ATOM)."'></sl-relative-time></i></small>";
			echo " &bull; ";
			echo "<a href='join_game.php?game=$id'>";
			if (!$g['started']) {
				echo "Join";
			} else {
				if ($completed) {
					echo "View Results";
				} else {
					echo "Spectate";
				}
			}
			echo "</a>";
			echo "</div>";
		echo "</li>";
    //}
}
?>
</ul>
