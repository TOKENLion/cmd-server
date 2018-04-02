<?php
include_once ("function.php");

if (!is_ajax()) {
    redirect();
}

$login = "";
if (!empty($_POST['login'])) {
    $login = sha1($_POST['login']);
}

if ($login !== "596408f21f8342887e8097cba48e33f99dd49f50") {
    display_json("Login is not right");
}

setcookie("is_login", true, time()+(3600 * 2));

display_json("Login is right", "success", array("connect" => '<script src="/resource/terminal/functional.js"></script>'));
