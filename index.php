<?php


require_once 'vendor/autoload.php';


session_start();


$temple = Yago\Building::box([
    'wood' => 23,
    'clay' => 22,
]);


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['REQUEST_URI'] == '/temple') {
    $secondsToBuildTemple = $temple->secondsToBuild();
    $dateTimeModifier = "+{$secondsToBuildTemple} seconds";
    $templeBuiltAt = (new DateTime($dateTimeModifier))
        ->setTimezone(new DateTimezone('UTC'))
        ->format('Y-m-dTH:i:s');
    setcookie('temple-built-at', $templeBuiltAt);

    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}


if ($_SERVER['REQUEST_URI'] == '/status') {
    $json = [];

    if (isset($_COOKIE['village'])) {
        $json['village'] = $_COOKIE['village'];
    }

    if (isset($_COOKIE['username'])) {
        $json['username'] = $_COOKIE['username'];
    }

    if (isset($_COOKIE['temple-built-at'])) {
        $now = (new DateTime('now'))->getTimestamp();
        $end = (new DateTime($_COOKIE['temple-built-at']))->getTimestamp();
        $json['seconds-left'] = $end - $now;
    }

    echo json_encode($json); die;
}


if ($_SERVER['REQUEST_URI'] == '/logout') {
    setcookie('village', null);
    setcookie('username', null);
    setcookie('temple-built-at', null);
    setcookie('temple-built', null);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}


if ($_SERVER['REQUEST_URI'] == '/village' && isset($_POST['village-name'])) {
    $villageName = $_POST['village-name'];
    setcookie('village', $villageName);
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
<h1>Yagolands</h1>
<?php if (isset($_COOKIE['village'])) { ?>
    <h2>Village: <?php echo $_COOKIE['village']; ?></h2>
<?php } ?>
<div id="seconds_left">0</div>


<script>
var handlePoll = function(data) {
    player.status.seconds_left = data['seconds-left'];
    console.log(data);
}

function updateServerInformations() {
    $.post('http://localhost:8000/status', {}, handlePoll, 'json');
    setTimeout('updateServerInformations()', 5000);
}

$(function(){ updateServerInformations(); });
</script>


<?php if (!isset($_COOKIE['username'])) { ?>
<style> input { padding: 10px; } button { padding: 12px; } </style>
<form method="post">
    <label for="username">Username: </label>
    <input type="text" name="username" placeholder="username" autofocus/>
    <label for="password">Password: </label>
    <input type="password" name="password" placeholder="password" />
    <button>accedi</button>
</form>
<?php } ?>


<?php if (isset($_COOKIE['username'])) { ?>
    <?php echo $_COOKIE['username']; ?> <a href="/logout">(logout)</a>
<?php } ?>


<?php if (isset($_COOKIE['username'])) { ?>
    <?php if (!isset($_COOKIE['village'])) { ?>
    <style> input { padding: 10px; } button { padding: 12px; } </style>
    <form method="post" action="/village">
        <label for="village">Village</label>:
        <input type="text" name="village-name" placeholder="Mordor" autofocus/>
        <button>accedi</button>
    </form>
    <?php } ?>
<?php } ?>


<?php if (isset($_COOKIE['temple-built-at'])) { ?>
<?php
$now = (new DateTime('now'))->setTimezone(new DateTimezone('UTC'))->format('Y-m-dTH:i:s');
$end = (new DateTime($_COOKIE['temple-built-at']))->setTimezone(new DateTimezone('UTC'))->format('Y-m-dTH:i:s');

if ($end <= $now) {
    setcookie('temple-built-at', null);
    setcookie('temple-built', true, time()+3600);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}

?>
    <script>
        function pollTemple() {
            var adesso = Math.floor(Date.now() / 1000);
            var fine = <?php echo (new DateTime($_COOKIE['temple-built-at']))->getTimestamp(); ?>;
            if (fine > adesso) {
                setTimeout('pollTemple()', 1000);
            } else {
                document.location.reload();
            }
        }
        pollTemple();
    </script>
<?php } ?>


<?php if (isset($_COOKIE['username']) && isset($_COOKIE['temple-built'])) { ?>
    <h2>Congratulations <?php echo $_COOKIE['username']; ?>, from the
    <?php echo $_COOKIE['village']; ?> village. You won the game.</h2>
<?php } ?>


<?php if (!isset($_COOKIE['temple-built-at']) && !isset($_COOKIE['temple-built']) && isset($_COOKIE['username'])) { ?>
    <?php if (isset($_COOKIE['village'])) { ?>
        <style> input { padding: 10px; } button { padding: 12px; } </style>
        <form method="post" action="/temple">
            <button>costruisci templio</button>
        </form>
    <?php } ?>
<?php } ?>

<script>
var player = {};
player.status = {
    seconds_left: 0
};
</script>

<script>
    function updateLocalStatus() {
        player.status.seconds_left--;
        if (player.status.seconds_left>0) {
            $('#seconds_left').html(player.status.seconds_left);
        } else {
            $('#seconds_left').html(0);
        }
        setTimeout('updateLocalStatus()', 1000);
    }
    updateLocalStatus();
</script>

<?php if(isset($_ENV['debug'])) { ?>
    cookie:
    <?php var_dump($_COOKIE); ?>

    post:
    <?php var_dump($_POST); ?>

    server:
    <?php var_dump($_SERVER); ?>
<?php } ?>
