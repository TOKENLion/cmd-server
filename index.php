<!-- -*- mode:javascript -*--->
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"/>
    <title>GIT Terminal</title>
    <meta name="Description" content="This is demonstration of JQuery Terminal Emulator Plugin. To run terminal type tilda on you keyboard."/>
    <script src="/resource/jquery/jquery-1.9.1.min.js"></script>
    <script src="/resource/mousewheel/jquery.plugin-mousewheel.min.js"></script>
    <script src="/resource/terminal/js/jquery.terminal.js"></script>
    <link href="/resource/terminal/css/jquery.terminal.css" rel="stylesheet"/>
    <link href="/resource/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="/resource/bootstrap/js/bootstrap.min.js"></script>
    <script src="/resource/jsCookie/js.cookie.js"></script>
<!--    <script src="/resource/md5/md5.script.js"></script>-->
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

</head>
<body>
<div id="tilda"></div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Authorisation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="login" class="col-sm-2 col-form-label">Login</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="login" value="" name="login">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary confirm-login">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="alert alert-primary text-center" role="alert" style="font-size: 26px; margin-bottom: 0px">
    Commands
</div>
<div id="command-teminal-execute"></div>
<script>
    $(document).ready(function () {
        var formModal = $("#exampleModal");
        if (!Cookies.get('is_login')) {
            formModal.modal({backdrop: false, keyboard: false});//backdrop: false, keyboard: false,
        }

        $(".confirm-login").on("click", function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: "/login.php",
                data: {login : $("#login").val()},
                cache: false,
                dataType: "JSON",
                success: function (output) {
                    if (output.status == "success") {
                        $("#exampleModal").modal('hide');
                        $("#exampleModal").after().html(output.connect);
                    } else {
                        alert(output.message);
                    }
                },
                error: function () {
                    alert("Internal server error");
                }
            });
        });
    });
</script>
<?php if ($_COOKIE['is_login']) { ?>
<script src="/resource/terminal/functional.js"></script>
<?php } ?>
</body>
</html>
