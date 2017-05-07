# Tic-tac-toe-php

A Tic Tac Toe game written in PHP

# Requirements

- PHP >=7.1.0
- WebServer with rewrite mode activated (I provide a sample NGinx configuration file `tic-tac-toe.conf`.

# Installation Instructions

### 1. Install the package using composer

Package is available in https://packagist.org/packages/gabrielfs7/tic-tac-toe-php

### 2. Once you have a webserver running in your machine and listen to 127.0.0.1:80, add this entry to your "/etc/hosts" file

```
127.0.0.1 tic-tac-toe-game
```

### 3. Configure your webserver to recognize the entry point "tic-tac-toe-game" to the "public" folder of this very project.
 In case of you are using NGinx and your public directory location is "/var/www/html/workspace/tic-tac-toe/public".
 Config like this:

### 3.1 Create the configuration file:
```
nano /etc/nginx/sites-available/tic-tac-toe.conf
```

### 3.2 Create the symbolic link

```
ln -s /etc/nginx/sites-available/tic-tac-toe.conf /etc/nginx/sites-enabled/tic-tac-toe.con
```

### 3.3 Restart nginx
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
POST http://tic-tac-toe-game/move
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

# Quality Control

All the quality metrics bellow can be found by accessing the "Quality Control" menu.


Running unit tests

```
bin/generate-test.sh
```

Running Coding Standards

```
bin/generate-cs.sh
```

Running Mess Detection (Empty means no error)

```
bin/generate-md.sh
```
