$(
    function ()
    {
        var playerUnit = 'X';
        var botUnit = 'O';

        /**
         *
         * @param className
         */
        var incrementPoints = function (className)
        {
            var botPoints = new Number($(className).html());

            $(className).html(botPoints + 1);
        }

        var resetBoard = function ()
        {
            $('.position').html('');
            $('.position').removeClass('winner-game');
            $('.position').removeClass('tied-game');
            $('.status-message').fadeOut();
        }

        /**
         * @param message
         */
        var showMessage = function (message)
        {
            $('.status-message .message').html(message);
            $('.status-message').fadeIn();
        };

        /**
         * @param xPosition
         * @param yPosition
         * @param playerUnit
         */
        var markBoard = function (xPosition, yPosition, playerUnit)
        {
            $('.p-' + xPosition + '-' + yPosition).html(playerUnit);
        }

        /**
         * @param winnerPositions
         */
        var handleWinnerPositions = function (winnerPositions)
        {
            for (var i in winnerPositions) {
                var x = winnerPositions[i][0];
                var y = winnerPositions[i][1];

                $('.p-' + x + '-' + y).addClass('winner-game');
            }
        };

        /**
         * @param xPosition
         * @param yPosition
         * @param unit
         * @returns {*[]}
         */
        var makeMove = function (xPosition, yPosition, unit)
        {
            markBoard(xPosition, yPosition, unit);

            return [
                [
                    $('.p-0-0').html(),
                    $('.p-1-0').html(),
                    $('.p-2-0').html()
                ],
                [
                    $('.p-0-1').html(),
                    $('.p-1-1').html(),
                    $('.p-2-1').html()
                ],
                [
                    $('.p-0-2').html(),
                    $('.p-1-2').html(),
                    $('.p-2-2').html()
                ]
            ];
        };

        /**
         * @param xPosition
         * @param yPosition
         * @param unit
         * @param isPlayerMove
         */
        var submitMove = function (xPosition, yPosition, unit, isPlayerMove)
        {
            var boardState = makeMove(xPosition, yPosition, unit);
            var requestBody = { playerUnit : playerUnit, boardState : boardState };

            $.ajax(
                {
                    dataType: 'json',
                    method: 'POST',
                    url: '/move',
                    data: JSON.stringify(requestBody)
                }
            ).done(
                function(data)
                {
                    if (data.winnerPositions !== null && typeof data.winnerPositions == "object") {
                        handleWinnerPositions(data.winnerPositions);
                    }

                    if (data.tiedGame) {
                        $('.position').addClass('tied-game');

                        return showMessage('Tied game! :|');
                    }

                    if (data.playerWins) {
                        incrementPoints('.user-points');

                        return showMessage('Player wins! :)');
                    }

                    if (data.botWins) {
                        incrementPoints('.bot-points');

                        return showMessage('Bot wins! :(');
                    }

                    if (isPlayerMove &&
                        data.nextMove !== null &&
                        typeof data.nextMove == 'object') {
                        xPosition = data.nextMove[0];
                        yPosition = data.nextMove[1];

                        markBoard(xPosition, yPosition, botUnit);
                        submitMove(xPosition, yPosition, botUnit, false);
                    }
                }
            );
        };

        $('.position').on(
            'click',
            function (event)
            {
                event.preventDefault();

                var button = $(this);
                var xPosition = button.data('x');
                var yPosition = button.data('y');
                var isPlayerMove = true;

                if (button.html().length > 0) {
                    return;
                }

                submitMove(xPosition, yPosition, playerUnit, isPlayerMove);
            }
        );

        $('.status-message button').on(
            'click',
            function (event)
            {
                event.preventDefault();

                resetBoard();
            }
        );
    }
);