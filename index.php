<?php

require_once 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Yago\Building;
use Yago\Json;
use Yago\Status;
use \Twig_Environment;
use \Twig_Loader_Filesystem;


session_start();

?>


<?php

if ($_SERVER['REQUEST_URI'] == '/windmill' && $_SERVER['REQUEST_METHOD'] == 'POST') {
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

?>


<?php

if ($_SERVER['REQUEST_URI'] == '/castle' && $_SERVER['REQUEST_METHOD'] == 'POST') {
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

?>


<?php

if ($_SERVER['REQUEST_URI'] == '/temple' && $_SERVER['REQUEST_METHOD'] == 'POST') {
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

?>


<?php

if ($_SERVER['REQUEST_URI'] == '/status') {
    echo Json::toJson();
    die;
}

?>


<?php

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

?>


<?php

if ($_SERVER['REQUEST_URI'] == '/village' && isset($_POST['village-name'])) {
    $villageName = $_POST['village-name'];
    setcookie('village', $villageName);
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: http://localhost:8000");
}

?>


<?php

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


<?php
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


<?php if(isset($_ENV['debug'])) { ?>
    cookie:
    <?php var_dump($_COOKIE); ?>

    post:
    <?php var_dump($_POST); ?>

    server:
    <?php var_dump($_SERVER); ?>
<?php } ?>
