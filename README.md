# Tic-tac-toe-php

A Tic Tac Toe game written in PHP

# Instructions

## 1. Once you have a webserver running in your machine and listen to 127.0.0.1:80, add this entry to your "/etc/hosts" file

```
127.0.0.1 tic-tac-toe-game
```

## 2. Configure your webserver to recognize the entry point "tic-tac-toe-game" to the "public" folder of this very project.
 In case of you are using NGinx and your public directory location is "/var/www/html/workspace/tic-tac-toe/public".
 Config like this:

## 2.1 Create the configuration file:
```
nano /etc/nginx/sites-available/tic-tac-toe.conf
```

## 2.2 Create the symbolic link

```
ln -s /etc/nginx/sites-available/tic-tac-toe.conf /etc/nginx/sites-enabled/tic-tac-toe.con
```

## 2.3 Restart nginx
```
service nginx restart
```
# API usage

Making a move without winner will provide a new Bot move:

```
POST http://tic-tac-toe-game/move
{
  "playerUnit" : "X",
  "boardState" : 
  	[
      ["X", "O", ""],
      ["X", "O", "O"],
      ["O",  "X", "X"]
    ]  
  
}

Response 200 OK
{
    "winner": null,
    "playerWins": false,
    "botWins": false,
    "tiedGame": false,
    "winnerPositions": [],
    "nextMove": [
        2,
        0,
        "O"
    ]
}
```

Making a move with winner:

```
POST http://tic-tac-toe-game/move
{
  "playerUnit" : "X",
  "boardState" : 
  	[
      ["X", "O", "O"],
      ["X", "O", "O"],
      ["O",  "X", "X"]
    ]  
  
}

Response 200 OK
{
    "winner": "O",
    "playerWins": false,
    "botWins": true,
    "tiedGame": false,
    "winnerPositions": [
        [
            0,
            2
        ],
        [
            1,
            1
        ],
        [
            2,
            0
        ]
    ],
    "nextMove": null
}
```

Making a move that will cause a tie:

```
{
  "playerUnit" : "X",
  "boardState" : 
  	[
      ["X", "O", "X"],
      ["X", "O", "O"],
      ["O",  "X", "X"]
    ]  
  
}

Response 200 OK
{
    "winner": null,
    "playerWins": false,
    "botWins": false,
    "tiedGame": true,
    "winnerPositions": [],
    "nextMove": null
}
```
