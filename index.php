<?php

session_start();

if ($_SERVER['REQUEST_URI'] == '/status') {
    echo json_encode([
    ]);
    die;
}

if (isset($_POST['username'])) {
    $usernameIsCorrect = 'sensorario' == $_POST['username'];
    $passwordIsCorrect = 'sensorario' == $_POST['password'];

    if ($passwordIsCorrect && $usernameIsCorrect) {
        setcookie('username', $_POST['username'], time()+3600);
    }
}


?>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script>
var handlePoll = function(data) {
    console.log(data);
}

function poll() {
    $.post('http://localhost:8000/status', {}, handlePoll);
    setTimeout('poll()', 3000);
}

$(function(){
    poll();
});
</script>

<?php if (!isset($_COOKIE['username'])) { ?>
<style>
input {
    padding: 10px;
}
button {
    padding: 12px;
}
</style>

<form method="post">
    <label for="username">
        <input type="text" name="username" placeholder="username" />
        <input type="password" name="password" placeholder="password" />
    </label>
    <button>accedi</button>
</form>
<?php } ?>


<?php var_dump($_COOKIE); ?>
