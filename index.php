<!-- -*- mode:javascript -*--->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"/>
    <title>GIT Terminal</title>
    <meta name="Description" content="This is demonstration of JQuery Terminal Emulator Plugin. To run terminal type tilda on you keyboard."/>
    <script src="../resource/jquery/jquery-1.7.1.min.js"></script>
    <script src="../resource/mousewheel/jquery.plugin-mousewheel.min.js"></script>
    <script src="../resource/terminal/js/jquery.terminal.js"></script>
    <link href="../resource/terminal/css/jquery.terminal.css" rel="stylesheet"/>
    <!--[if IE]>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .tilda {
            position: absolute;
        }
    </style>
    <script>
        jQuery(document).ready(function ($) {
            $(window).scroll(function () {
                $('.tilda').each(function () {
                    $(this).css({top: $('body').prop('scrollTop')});
                });
            });
        });
    </script><![endif]-->
    <script>

        String.prototype.strip = function (char) {
            return this.replace(new RegExp("^" + char + "*"), '').replace(new RegExp(char + "*$"), '');
        }


        $.extend_if_has = function (desc, source, array) {
            for (var i = array.length; i--;) {
                if (typeof source[array[i]] !== 'undefined') {
                    desc[array[i]] = source[array[i]];
                }
            }
            return desc;
        };

        (function ($) {
            $.fn.tilda = function (eval, options) {
                if ($('body').data('tilda')) {
                    return $('body').data('tilda').terminal;
                }
                this.addClass('tilda');
                options = options || {};
                eval = eval || function (command, term) {
                    term.echo("you don't set eval for tilda");
                };
                var settings = {
                    prompt: '$> ',
                    name: 'tilda',
                    height: 500,
                    enabled: true,
                    greetings: 'SERVER TERMINAL',
                    keypress: function (e) {
                        if (e.which === 96) {
                            return false;
                        }
                    }
                };
                if (options) {
                    $.extend(settings, options);
                }
                this.append('<div class="td"></div>');
                var self = this;
                self.terminal = this.find('.td').terminal(eval, settings);
                var focus = false;
                $(document.documentElement).keypress(function (e) {
                    if (e.which === 96) {
                        self.slideToggle('fast');
                        self.terminal.focus(focus = !focus);
                        self.terminal.attr({
                            scrollTop: self.terminal.attr("scrollHeight")
                        });
                    }
                });
                $('body').data('tilda', this);
                this.hide();
                return self;
            };
        })(jQuery);

        //--------------------------------------------------------------------------
        jQuery(document).ready(function ($) {
            $('#tilda').tilda(function (command, terminal) {
                if (command !== '') {
                    $.ajax({
                        type: "POST",
                        url: "/test.php",
                        async: false,
                        data: {
                            cmd: command,
                            pwd: terminal.get_prompt()
                        },
                        dataType: 'json'
                    }).done(function (response) {
                        if (response.status === 'success') {
                            terminal.echo(response['result']);
                            terminal.set_prompt(response['pwd']);
                        } else {
                            terminal.error(response.result);
                        }
                    });
                    // try {
                    //     var result = window.eval(command);
                    //     if (result !== undefined) {
                    //         this.echo(new String(result));
                    //     }
                    // } catch(e) {
                    //     this.error(new String(e));
                    // }
                }
                // else {
                //    this.echo('you type command "' + command + '"');
                // }
                // terminal.echo('you type command "' + command + '"');
            });
        });

    </script>
</head>
<body>
<div id="tilda"></div>
<h1>Type ~/`</h1>
</body>
</html>
