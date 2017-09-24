<?php

namespace App\Controllers;

use PDO;
use PDOException;

class HomeController extends Controller {

  public function index($request, $response, $args) {

    // require __DIR__ . '/../../config/db_const.php';
    // try {
    //   $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    //   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //   echo 'Connected';
    //   $result = $conn->query('Select * from files')->fetch();
    //   var_dump($result);
    // } catch (PDOException $e) {
    //   echo 'No connection';
    //   echo $e->getMessage();
    // }
    // die();

    if(isset($_SESSION['password'])) {
      unset($_SESSION['password']); // Clear password from session
    }

    $msg = $this->c->flash->getMessage('msg');

    return $this->c->view->render($response, 'home/index.twig', compact('msg'));

  }

}

?>
