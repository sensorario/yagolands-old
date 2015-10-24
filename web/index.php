<?php

require_once '../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use Yago\Building;
use Yago\BuildingTree;
use Yago\Configuration;
use Yago\DataLayer;
use Yago\Json;
use Yago\Player;
use Yago\Status;
use \Twig_Environment;
use \Twig_Loader_Filesystem;


session_start();

?>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $yaml = file_get_contents('../app/config/buildings.yml');
    $conf = Yaml::parse($yaml);

    foreach($conf['buildings']['resources'] as $building => $resourcess) {
        if ($_SERVER['REQUEST_URI'] == '/' . $building) {
            $buildingValue = Building::box($resourcess);
            setcookie('building-in-progress', $building);
            $secondsToBuildBuilding = $buildingValue->secondsToBuild();
            $dateTimeModifier = "+{$secondsToBuildBuilding} seconds";
            $buildingBuiltAt = (new DateTime($dateTimeModifier))
                ->setTimezone(new DateTimezone('UTC'))
                ->format('Y-m-dTH:i:s');
            setcookie('building-built-at', $buildingBuiltAt);
            Header("HTTP/1.1 301 Moved Permanently");
            Header("Location: http://localhost:8000");
        }
    }
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
$twig = new Twig_Environment(
    new Twig_Loader_Filesystem(__DIR__ . '/../templates'), [
        //    'cache' => __DIR__ . '/app/cache',
    ]
);
?>


<?php if ($_SERVER['REQUEST_URI'] == '/') { ?>
<?php echo $twig->render('base.twig', [
    'cookie' => $_COOKIE,
    'dataLayer' => new DataLayer(),
    'buildingTree' => new BuildingTree(
        new Configuration(),
        new Player(
            new DataLayer()
        )
    )
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
