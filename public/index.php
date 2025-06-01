<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Multiplayer TicTacToe</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/themes/light.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
<style>
sl-badge::part(base) {
width:100%;
}
</style>
</head>
<body>
<h1>Lobby</h1>
<form action="create_game.php" method="POST">
    <button>Create Game</button>
</form>
<form action="join_game.php" method="GET">
    <input style="display:inline-block" name="game" placeholder="Game ID">
    <button>Join Game</button>
</form>
<div hx-get="lobby.php"
     hx-trigger="load, every 5s"
     hx-swap="innerHTML"
	 style="overflow-x: auto;">
</div>
<script src="https://unpkg.com/htmx.org@2.0.4" integrity="sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+" crossorigin="anonymous"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/components/relative-time/relative-time.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/@shoelace-style/shoelace@2.20.1/cdn/components/badge/badge.js"></script>
</body>
</html>
