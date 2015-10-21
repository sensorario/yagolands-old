<?php


require_once 'vendor/autoload.php';

use Sensorario\ValueObject\ValueObject;
use Symfony\Component\Yaml\Yaml;
use Yago\Building;
use Yago\Json;
use Yago\Queue;
use Yago\Status;
use \Twig_Environment;
use \Twig_Loader_Filesystem;


session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SERVER['REQUEST_URI'] == '/windmill')) {
    $yaml = file_get_contents('app/config/buildings.yml');
    $conf = Yaml::parse($yaml);

    $building = Building::box($conf['buildings']['windmill']);
    setcookie('building-in-progress', 'windmill');

    $secondsToBuildBuilding = $building->secondsToBuild();
    $dateTimeModifier = "+{$secondsToBuildBuilding} seconds";
    $buildingBuiltAt = (new DateTime($dateTimeModifier))
        ->setTimezone(new DateTimezone('UTC'))
        ->format('Y-m-dTH:i:s');
    setcookie('building-built-at', $buildingBuiltAt);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}


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
    echo Json::toJson();
    die;
}


if ($_SERVER['REQUEST_URI'] == '/logout') {
    setcookie('village', null);
    setcookie('username', null);
    setcookie('building-built-at', null);
    setcookie('building-in-progress', null);
    setcookie('temple-built', null);
    setcookie('castle-built', null);
    setcookie('windmill-built', null);
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


$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates');
$twig = new Twig_Environment($loader, [
//    'cache' => __DIR__ . '/app/cache',
]);

?>

<?php if ($_SERVER['REQUEST_URI'] == '/') { ?>
<?php echo $twig->render('base.twig', [
    'cookie' => $_COOKIE
]); ?>
<?php } ?>


<?php if (!isset($_COOKIE['username']) && $_SERVER['REQUEST_URI'] == '/login') { ?>
<?php echo $twig->render('login.twig', [
    'cookie' => $_COOKIE
]); ?>
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


<?php if(isset($_ENV['debug'])) { ?>
    cookie:
    <?php var_dump($_COOKIE); ?>

    post:
    <?php var_dump($_POST); ?>

    server:
    <?php var_dump($_SERVER); ?>
<?php } ?>
