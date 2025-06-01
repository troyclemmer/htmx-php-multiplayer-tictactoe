[Multiplayer TicTacToe](https://troyclemmer.dev/games/dodge) — browser multiplayer TicTacToe game
==================================================
* Author: `Troy Clemmer`
* Start Date: `2025-05-30`
* Type: `game`, `SPA`, `proof of concept`
* Platform: `web`, `browser`
* Tech: `CSS`, `HTML`, `HTMX`, `PHP`
* Playable on [troyclemmer.dev](https://troyclemmer.dev/games/tictactoe/)
* Repository at [github](https://github.com/troyclemmer/htmx-php-multiplayer-tictactoe)

## introduction

This is just a proof of concept game I made in a few days.  I wanted to try making a turn-based multiplayer game using `HTMX` and `PHP`.  

### concepts
- Multiplayer (multi screen) 2 player game
- Lobby joining system
- JSON for server data
- Spectating full games support
- Viewing results of past games support
- Automatic purging of old game data (24 hours)

## improvement ideas
- On results board, highlight the winning line
- Keep track of order of moves, and can either display turn numbers on the moves themselves (or just in results board), or allow the ability to "playback" a game, showing the moves in order with delay.
- Add the option to add a computer player as the second player
- Randomize X or O for first player
- Allow player names
- Password protected games

## development
- Can run locally using `php -S localhost:82` on the `/public' folder
