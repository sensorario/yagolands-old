<?php

session_start();


if ($_SERVER['REQUEST_URI'] == '/status') {
    echo json_encode([ ]); die;
}


if ($_SERVER['REQUEST_URI'] == '/village' && isset($_POST['village-name'])) {
    $villageName = $_POST['village-name'];
    setcookie('village', $_POST['village-name'], time()+3600);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}


if ($_SERVER['REQUEST_URI'] == '/' && isset($_POST['username'])) {
    $usernameIsCorrect = 'sensorario' == $_POST['username'];
    $passwordIsCorrect = 'sensorario' == $_POST['password'];
    if ($passwordIsCorrect && $usernameIsCorrect) {
        setcookie('username', $_POST['username'], time()+3600);
        Header("HTTP/1.1 301 Moved Permanently");
        Header("Location: http://localhost:8000");
    }
}


?>


<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script>
var handlePoll = function(data) { console.log(data); }

function poll() {
    $.post('http://localhost:8000/status', {}, handlePoll);
    setTimeout('poll()', 3000);
}

$(function(){ poll(); });
</script>


<?php if (!isset($_COOKIE['username'])) { ?>
<style> input { padding: 10px; } button { padding: 12px; } </style>
<form method="post">
    <label for="username">Username: </label>
    <input type="text" name="username" placeholder="username" />
    <label for="password">Password: </label>
    <input type="password" name="password" placeholder="password" />
    <button>accedi</button>
</form>
<?php } ?>


<?php if (isset($_COOKIE['username'])) { ?>
    <?php if (!isset($_COOKIE['village'])) { ?>
    <style> input { padding: 10px; } button { padding: 12px; } </style>
    <form method="post" action="/village">
        <label for="village">Village</label>:
        <input type="text" name="village-name" placeholder="Mordor" />
        <button>accedi</button>
    </form>
    <?php } ?>
<?php } ?>


<?php var_dump($_COOKIE); ?>
