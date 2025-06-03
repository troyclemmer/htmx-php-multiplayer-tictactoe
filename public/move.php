<?php
define("GAMES_FILE", dirname(__DIR__) . "/data/games.json");

$game_id = $_POST['game'] ?? null;
$player = $_POST['player'] ?? null;
$r = $_POST['row'] ?? null;
$c = $_POST['col'] ?? null;

if (!$game_id || !$player || $r === null || $c === null) {
    die("<span style='color:red;'>Error: Invalid input.</span>");
}

$games = json_decode(file_get_contents(GAMES_FILE), true);

if (!isset($games[$game_id])) {
    die("<span style='color:red;'>Error: Game not found.</span>");
}

$game = &$games[$game_id];

// Only apply move if game is started and it's the player's turn and the cell is empty
if ($game['started'] && $game['board'][$r][$c] === "" && $game['turn'] === $player) {
    $game['board'][$r][$c] = $player;
	
	
	// Check for win
	$winningCells = checkWin($game['board'], $player);
    if ($winningCells) {
        $game['winner'] = $player;
		$game['winning_cells'] = $winningCells;
    }
    // Check for draw if no winner
    elseif (checkDraw($game['board'])) {
        $game['winner'] = "draw";
    } else {
        // Switch turns only if game continues
        $game['turn'] = $player === "X" ? "O" : "X";
    }
	
	
    $game['turn'] = $player === "X" ? "O" : "X";
	
	//check if we have an ai opponent, if so, ai makes move now
	if (!$game['winner'] && $game['ai_opponent'] && strtoupper($game['turn'])==="O") {
		
		$attempts = 0;
		do {
			$row = random_int(0, 2);
			$col = random_int(0, 2);
			$attempts++;
	
			if ($game['board'][$row][$col] === "") {
				$game['board'][$row][$col] = "O";
				break;
			}
		} while ($attempts < 1000);
			
		$game['turn'] = "X";
	}
	
    file_put_contents(GAMES_FILE, json_encode($games));
}

// Either way, reload the board view
$_GET['game'] = $game_id;
$_GET['player'] = $player;
include "board.php";


function checkWin(array $board, string $player): array|false {
    // Check rows and columns
    for ($i = 0; $i < 3; $i++) {
        if ($board[$i][0] === $player && $board[$i][1] === $player && $board[$i][2] === $player) {
            return [[$i, 0], [$i, 1], [$i, 2]];
        }
        if ($board[0][$i] === $player && $board[1][$i] === $player && $board[2][$i] === $player) {
            return [[0, $i], [1, $i], [2, $i]];
        }
    }

    // Check diagonals
    if ($board[0][0] === $player && $board[1][1] === $player && $board[2][2] === $player) {
        return [[0, 0], [1, 1], [2, 2]];
    }
    if ($board[0][2] === $player && $board[1][1] === $player && $board[2][0] === $player) {
        return [[0, 2], [1, 1], [2, 0]];
    }

    return false;
}


function checkDraw(array $board): bool {
    foreach ($board as $row) {
        foreach ($row as $cell) {
            if ($cell === "") {
                return false; // empty cell found, not a draw
            }
        }
    }
    return true; // no empty cells, it's a draw
}
?>