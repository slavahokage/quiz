;(function ($, window, document, undefined) {


    'use strict';


    $.quiz = function (el, options) {
        var base = this;
        base.$el = $(el);

        base.$el.data('quiz', base);


        base.options = $.extend($.quiz.defaultOptions, options);

        var questions = base.options.questions,
            numQuestions = questions.length,
            startScreen = base.options.startScreen,
            startButton = base.options.startButton,
            homeButton = base.options.homeButton,
            resultsScreen = base.options.resultsScreen,
            gameOverScreen = base.options.gameOverScreen,
            nextButtonText = base.options.nextButtonText,
            finishButtonText = base.options.finishButtonText,
            resultButtonText = base.options.resultButtonText,
            score = 0,
            currentQuestion = currentQuestionFromServer,
            startTime = 0,
            endTime = 0,
            baseTime = 0,
            flag = 0,
            answerLocked = false;

        $(window).on('beforeunload', function () {
            if (flag === 0) {
                if (endTime === 0) {
                    endTime = new Date();
                }
                if (startTime !== 0) {
                    var time = endTime - startTime + baseTime;
                    $.ajax({
                        url: '/questions/ajax',
                        type: "POST",
                        dataType: "json",
                        data: {
                            "time": time
                        },
                    });
                }
            }
        });


        base.methods = {
            init: function () {
                base.methods.setup();
                var start;
                var check3 = $.ajax({
                    url: '/questions/getTime',
                    type: "POST",
                    dataType: "json",
                    data: {
                        "giveMeTime": "giveMeTime"
                    },
                    success: function (data) {
                        start = data.output;
                    }
                });
                $.when(check3).done(function () {
                    baseTime = start;
                });

                $(document).on('click', startButton, function (e) {
                    startTime = new Date();
                    $.ajax({
                        url: '/questions/getTime',
                        type: "POST",
                        dataType: "json",
                        data: {
                            "giveMeTime": "giveMeTime"
                        },
                        success: function (data) {
                            start = data.output;
                        }
                    });

                    $('#description').hide();
                    e.preventDefault();
                    base.methods.start();
                });

                $(document).on('click', homeButton, function(e) {
                    e.preventDefault();
                    base.methods.home();
                });

                $(document).on('click', '.answers a', function(e) {
                    e.preventDefault();
                    base.methods.answerQuestion(this);
                });

                $(document).on('click', '#quiz-next-btn', function(e) {
                    e.preventDefault();
                    base.methods.nextQuestion();
                });

                $(document).on('click', '#quiz-finish-btn', function (e) {
                    endTime = new Date();
                    var time = endTime - startTime + baseTime;
                    $.ajax({
                        url: '/questions/ajax',
                        type: "POST",
                        dataType: "json",
                        data: {
                            "time": time,
                            "isOver": "isOver"
                        },
                    });

                    e.preventDefault();
                    base.methods.finish();
                });

                $(document).on('click', '#quiz-result-btn, #quiz-retry-btn', function(e) {
                    e.preventDefault();
                    base.methods.result();
                    });
            },
            setup: function() {

                var quizHtml = '';

                if (base.options.counter) {
                    quizHtml += '<div id="quiz-counter"></div>';
                }

                quizHtml += '<div id="questions">';
                $.each(questions, function (i, question) {
                    if (currentQuestion > numQuestions) {
                        flag = 1;
                        $(location).attr('href', window.location.href + '/showResults');
                    }
                    if (i + 1 < currentQuestion) {
                    } else {
                        quizHtml += '<div class="question-container">';
                        quizHtml += '<p class="question">' + question.q + '</p>';
                        quizHtml += '<ul class="answers">';
                        $.each(question.options, function (index, answer) {
                            quizHtml += '<li><a href="#" data-index="' + index + '">' + answer + '</a></li>';
                        });
                        quizHtml += '</ul>';
                        quizHtml += '</div>';
                    }
                });
                quizHtml += '</div>';

                if ($(resultsScreen).length === 0) {
                    quizHtml += '<div id="' + resultsScreen.substr(1) + '">';
                    quizHtml += '<p id="quiz-results"></p>';
                    quizHtml += '</div>';
                }

                quizHtml += '<div id="quiz-controls">';
                quizHtml += '<p id="quiz-response"></p>';
                quizHtml += '<div id="quiz-buttons">';
                quizHtml += '<a href="#" id="quiz-next-btn">' + nextButtonText + '</a>';
                quizHtml += '<a href="#" id="quiz-finish-btn">' + finishButtonText + '</a>';
                quizHtml += '<a href="#" id="quiz-result-btn">' + resultButtonText + '</a>';
                quizHtml += '</div>';
                quizHtml += '</div>';

                base.$el.append(quizHtml).addClass('quiz-container quiz-start-state');

                $('#quiz-counter').hide();
                $('.question-container').hide();
                $(gameOverScreen).hide();
                $(resultsScreen).hide();
                $('#quiz-controls').hide();
            },
            start: function() {
                base.$el.removeClass('quiz-start-state').addClass('quiz-questions-state');
                $(startScreen).hide();
                $('#quiz-controls').hide();
                $('#quiz-finish-btn').hide();
                if(currentQuestion === numQuestions){
                    $('#quiz-next-btn').hide();
                    $('#quiz-finish-btn').show();
                }else{
                    $('#quiz-next-btn').show();
                }
                $('#quiz-result-btn').hide();
                $('.question-container:first-child').show().addClass('active-question');
                $('#questions').show();
                $('#quiz-counter').show();
                base.methods.updateCounter();
            },
            answerQuestion: function(answerEl) {
                if (answerLocked) {
                    return;
                }
                answerLocked = true;

                var $answerEl = $(answerEl),
                    response = '',
                    selected = $answerEl.data('index'),
                    currentQuestionIndex = currentQuestion - 1,
                    correct = questions[currentQuestionIndex].correctIndex;

                if (selected === correct) {
                    $answerEl.addClass('correct');
                    response = questions[currentQuestionIndex].correctResponse;
                    score++;

                    $.ajax({
                        url: '/questions/ajax',
                        type: "POST",
                        dataType: "json",
                        data: {
                            "correct": "correct"
                        },
                        async: true,
                    });



                } else {
                    $answerEl.addClass('incorrect');
                    response = questions[currentQuestionIndex].incorrectResponse;
                    if (!base.options.allowIncorrect) {
                        base.methods.gameOver(response);
                        return;
                    }
                }

                $('#quiz-response').html(response);
                $('#quiz-controls').fadeIn();

                if (typeof base.options.answerCallback === 'function') {
                    base.options.answerCallback(currentQuestion, selected === correct);
                }
            },
            nextQuestion: function() {
                answerLocked = false;

                $('.active-question')
                    .hide()
                    .removeClass('active-question')
                    .next('.question-container')
                    .show()
                    .addClass('active-question');

                $('#quiz-controls').hide();


                if (currentQuestion === numQuestions) {
                    $('#quiz-next-btn').hide();
                    $('#quiz-finish-btn').show();
                }

                 if (++currentQuestion === numQuestions) {
                    $('#quiz-next-btn').hide();
                    $('#quiz-finish-btn').show();
                }

                base.methods.updateCounter();

                if (typeof base.options.nextCallback === 'function') {
                    base.options.nextCallback();
                }
            },
            gameOver: function(response) {
                if ($(gameOverScreen).length === 0) {
                    var quizHtml = '';
                    quizHtml += '<div id="' + gameOverScreen.substr(1) + '">';
                    quizHtml += '<p id="quiz-gameover-response"></p>';
                    quizHtml += '<p><a href="#" id="quiz-retry-btn">' + resultButtonText + '</a></p>';
                    quizHtml += '</div>';
                    base.$el.append(quizHtml);
                }
                $('#quiz-gameover-response').html(response);
                $('#quiz-counter').hide();
                $('#questions').hide();
                $('#quiz-finish-btn').hide();
                $(gameOverScreen).show();
            },
            finish: function () {
                    base.$el.removeClass('quiz-questions-state').addClass('quiz-results-state');
                    $('.active-question').hide().removeClass('active-question');
                    $('#quiz-counter').hide();
                    $('#quiz-response').hide();
                    $('#quiz-finish-btn').hide();
                    $('#quiz-next-btn').hide();
                    $('#quiz-result-btn').show();
                    $(resultsScreen).show();
                    var finishScore;
                    var checkScore = $.ajax({
                    url: '/questions/getScore',
                    type: "POST",
                    dataType: "json",
                    data: {
                        "giveMeScore": "giveMeScore"
                    },
                    success: function (data) {
                        finishScore = data.output;
                    }
                });

                $.when(checkScore).done(function () {
                    var resultsStr = base.options.resultsFormat.replace('%score', finishScore).replace('%total', numQuestions);
                    $('#quiz-results').html(resultsStr);

                    if (typeof base.options.finishCallback === 'function') {
                        base.options.finishCallback();
                    }
                });

            },
            result: function() {
                var quiz = document.cookie.match(new RegExp(
                    "(?:^|; )" + "quiz".replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
                ));

                document.location.replace("http://quiz/questions/"+decodeURIComponent(quiz[1])+"/showResults");
            },
            updateCounter: function () {
                var countStr = base.options.counterFormat.replace('%current', currentQuestion).replace('%total', numQuestions);
                $('#quiz-counter').html(countStr);
                $.ajax({
                    url: '/questions/ajax',
                    type: "POST",
                    dataType: "json",
                    data: {
                        "currentQuestion": currentQuestion
                    },
                });
            }
        };

        base.methods.init();
    };

    $.quiz.defaultOptions = {
        allowIncorrect: true,
        counter: true,
        counterFormat: '%current/%total',
        startScreen: '#quiz-start-screen',
        startButton: '#quiz-start-btn',
        homeButton: '#quiz-home-btn',
        resultsScreen: '#quiz-results-screen',
        resultsFormat: 'You got %score out of %total correct!',
        gameOverScreen: '#quiz-gameover-screen',
        nextButtonText: 'Next',
        finishButtonText: 'Finish',
        resultButtonText: 'Results'
    };

    $.fn.quiz = function(options) {
        return this.each(function() {
            new $.quiz(this, options);
        });
    };
}(jQuery, window, document));