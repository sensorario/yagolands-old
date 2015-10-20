<?php


require_once 'vendor/autoload.php';


use Symfony\Component\Yaml\Yaml;
use Yago\Building;
use Yago\Queue;


session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SERVER['REQUEST_URI'] == '/castle')) {
    $yaml = file_get_contents('app/config/buildings.yml');
    $conf = Yaml::parse($yaml);

    $building = Building::box($conf['buildings']['castle']);
    setcookie('building-in-progress', 'castle');

    $secondsToBuildBuilding = $building->secondsToBuild();
    $dateTimeModifier = "+{$secondsToBuildBuilding} seconds";
    $buildingBuiltAt = (new DateTime($dateTimeModifier))
        ->setTimezone(new DateTimezone('UTC'))
        ->format('Y-m-dTH:i:s');
    setcookie('building-built-at', $buildingBuiltAt);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SERVER['REQUEST_URI'] == '/temple')) {
    $yaml = file_get_contents('app/config/buildings.yml');
    $conf = Yaml::parse($yaml);

    $building = Building::box($conf['buildings']['temple']);
    setcookie('building-in-progress', 'temple');

    $secondsToBuildBuilding = $building->secondsToBuild();
    $dateTimeModifier = "+{$secondsToBuildBuilding} seconds";
    $buildingBuiltAt = (new DateTime($dateTimeModifier))
        ->setTimezone(new DateTimezone('UTC'))
        ->format('Y-m-dTH:i:s');
    setcookie('building-built-at', $buildingBuiltAt);
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

    if (isset($_COOKIE['building-built-at'])) {
        $now = (new DateTime('now'))->getTimestamp();
        $end = (new DateTime($_COOKIE['building-built-at']))->getTimestamp();
        $json['seconds-left'] = $end > $now
            ? $end - $now
            : 0;
    }

    echo json_encode($json); die;
}


if ($_SERVER['REQUEST_URI'] == '/logout') {
    setcookie('village', null);
    setcookie('username', null);
    setcookie('building-built-at', null);
    setcookie('building-in-progress', null);
    setcookie('temple-built', null);
    setcookie('castle-built', null);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}


if ($_SERVER['REQUEST_URI'] == '/village' && isset($_POST['village-name'])) {
    $villageName = $_POST['village-name'];
    setcookie('village', $villageName);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}


if ($_SERVER['REQUEST_URI'] == '/login' && isset($_POST['username'])) {
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



<script>
var player = {};

player.status = {
    seconds_left: 0
};

var handlePoll = function(data) {
    player.status.seconds_left = data['seconds-left'];
}

function updateServerInformations() {
    $.post('http://localhost:8000/status', {}, handlePoll, 'json');
    setTimeout('updateServerInformations()', 5000);
}

$(function(){ updateServerInformations(); });
</script>


<?php if (!isset($_COOKIE['username']) && $_SERVER['REQUEST_URI'] == '/login') { ?>
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
<?php } else { ?>
    <a href="/login">login</a>
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


<?php if (isset($_COOKIE['building-built-at'])) { ?>
<?php
$now = (new DateTime('now'))->setTimezone(new DateTimezone('UTC'))->format('Y-m-dTH:i:s');
$end = (new DateTime($_COOKIE['building-built-at']))->setTimezone(new DateTimezone('UTC'))->format('Y-m-dTH:i:s');

if ($end <= $now) {
    setcookie('building-built-at', null);
    setcookie($_COOKIE['building-in-progress'] . '-built', true, time()+3600);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}

?>
    <script>
        function updateLocalStatus() {
            var adesso = Math.floor(Date.now() / 1000);
            var fine = <?php echo (new DateTime($_COOKIE['building-built-at']))->getTimestamp(); ?>;
            if (fine < adesso) {
                document.location.reload();
            }
            player.status.seconds_left--;
            if (player.status.seconds_left>0) {
                var total   = player.status.seconds_left;
                var seconds = total % 60;
                var minutes = (total - seconds) / 60;

                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                $('#seconds_left').html(seconds);
                $('#minutes_left').html(minutes);
            } else {
                $('#seconds_left').html('00');
            }
            setTimeout('updateLocalStatus()', 1000);
        }
        updateLocalStatus();
    </script>
    <div id="seconds_left_container">Seconds left: <span id="minutes_left"></span>:<span id="seconds_left"></span></div>
<?php } ?>


<?php if (isset($_COOKIE['username']) && isset($_COOKIE['temple-built'])) { ?>
    <h2>Congratulations <?php echo $_COOKIE['username']; ?>, from the
    <?php echo $_COOKIE['village']; ?> village. You won the game.</h2>
<?php } ?>


<?php if (!isset($_COOKIE['building-built-at']) && !isset($_COOKIE['temple-built']) && isset($_COOKIE['username'])) { ?>
    <?php if (isset($_COOKIE['castle-built'])) { ?>
        <style> input { padding: 10px; } button { padding: 12px; } </style>
        <form method="post" action="/temple">
            <button>build temple</button>
        </form>
    <?php } ?>
    <?php if (!isset($_COOKIE['castle-built']) && isset($_COOKIE['village'])) { ?>
        <style> input { padding: 10px; } button { padding: 12px; } </style>
        <form method="post" action="/castle">
            <button>build castle</button>
        </form>
    <?php } ?>
<?php } ?>


<?php if(isset($_ENV['debug'])) { ?>
    cookie:
    <?php var_dump($_COOKIE); ?>

    post:
    <?php var_dump($_POST); ?>

    server:
    <?php var_dump($_SERVER); ?>
<?php } ?>
