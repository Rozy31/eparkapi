<?php
require_once('./config/Config.php');
require_once('./modules/Procedural.php');
require_once('./modules/Get.php');
require_once('./modules/Post.php');
require_once('./modules/Auth.php');
require_once('./modules/Global.php');
require_once('./modules/Patch.php');

$db = new Connection();
$pdo = $db->connect();
$get = new Get($pdo);
$post = new Post($pdo);
$auth = new Auth($pdo);
$patch = new Patch($pdo);

if (isset($_REQUEST['request'])) {
    $req = explode('/', rtrim($_REQUEST['request'], '/'));
} else {
    $req = array("errorcatcher");
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $d = json_decode(file_get_contents("php://input"));
        switch ($req[0]) {
            case 'login-admin':
                echo json_encode($auth->loginAdmin($d));
                break;
            case 'register-admin':
                echo json_encode($auth->registerAdmin($d));
                break;
            case 'register-user':
                echo json_encode($auth->registerUser($d));
                break;
            case 'login-user':
                echo json_encode($auth->loginUser($d));
                break;
            case 'logout-admin':
                echo json_encode($auth->timeOutLog($d));
                break;

                //get -- new 02/06/2022 
            case 'getTodayBookings':
                echo json_encode($get->getTodayBookings());
                break;
            case 'getSlot':
                echo json_encode($get->getSlot());
                break;
            case 'getAllBookings':
                echo json_encode($get->getAllBookings());
                break;
            case 'getuserbook':
                echo json_encode($get->getUserBookings($d));
                break;
            case 'searchDate':
                echo json_encode($get->searchDate($d));
                break;
            case 'getRate':
                echo json_encode($get->getRate());
                break;
            case 'getAlluser':
                echo json_encode($get->getAlluser());
                break;
            case 'getForchart':
                echo json_encode($get->getForchart());
                break;
            case 'getEmployee':
                echo json_encode($get->getEmployee());
                break;

                //update
            case 'updateSlotstatus':
                echo json_encode($patch->updateSlotstatus($d));
                break;
            case 'updateProfileclerk':
                echo json_encode($patch->updateProfileclerk($d));
                break;
            case 'updateBookingstatus':
                echo json_encode($patch->updateBookingstatus($d));
                break;
            case 'updateProfileuser':
                echo json_encode($patch->updateProfileuser($d));
                break;
            case 'updateRates':
                echo json_encode($patch->updateRates($d));
                break;
            case 'updatePark':
                echo json_encode($patch->updatePark($d));
                break;
            case 'updateClerk':
                echo json_encode($patch->updateClerk($d));
                break;
            case 'scan':
                echo json_encode($patch->scan($d));
                break;

                //add 
            case 'addReserve':
                echo json_encode($post->addReserve($d));
                break;
            case 'add-slot':
                echo json_encode($post->addslot());
                break;

            default:
                echo responseError(400);
                break;
        }
        break;

    default:
        echo responseError(400);
        break;
}
