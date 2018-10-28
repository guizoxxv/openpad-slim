<?php

namespace App\Controllers;

use PDO;
use App\Models\File;

class FilesController extends Controller {

  public function index($request, $response) {

    $params = $request->getParams();
    $name = $params['name'];

    if(isset($params['submit-load'])) {

      return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('load', ['name' => $name])); // Redirect. {name} on route is set to $name

    }

    if(isset($params['submit-create'])) {

      $sql = "INSERT INTO files (name) VALUES (:name)";
      $result = $this->c->db->prepare($sql);
      $result->bindParam(':name', $name, PDO::PARAM_STR);
      $result->execute();

      return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('edit', ['name' => $name])); // Redirect. {name} on route is set to $name

    }

  }

  public function load($request, $response, $args) {

    $name = $args['name'];

    $sql = "SELECT name, text FROM files WHERE name = :name";
    $result = $this->c->db->prepare($sql);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->execute();

    $file = $result->fetch(PDO::FETCH_OBJ);

    if($file !== false) {

      $msg = $this->c->flash->getMessage('msg');

      return $this->c->view->render($response, 'file/index.twig', compact('file', 'msg'));

    } else {

      $this->c->flash->addMessage('msg', '"' . $name . '"' . ' not found'); // Use slim flash to pass a message to next page

      return $response->withStatus(404)->withHeader('Location', $this->c->router->pathFor('home')); // Redirect

    }

  }

  public function edit($request, $response, $args) {

    $name = $args['name'];

    $sql = "SELECT name, text, password FROM files WHERE name = :name";
    $result = $this->c->db->prepare($sql);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->execute();

    $file = $result->fetch(PDO::FETCH_OBJ);

    if($file !== false) {

      if($file->password === NULL || isset($_SESSION['password']) && $file->password === $_SESSION['password']) {

        $msg = $this->c->flash->getMessage('msg');

        return $this->c->view->render($response, 'file/edit.twig', compact('file', 'msg'));

      } else {

        // echo 'Add password'; // TODO: Open modal-check-Password
        return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('check_password_modal', ['name' => $name])); // Redirect

      }

    } else {

      $this->c->flash->addMessage('msg', '"' . $name . '"' . ' not found'); // Use slim flash to pass a message to next page

      return $response->withStatus(404)->withHeader('Location', $this->c->router->pathFor('home')); // Redirect

    }

  }

  public function raw($request, $response, $args) {

    $name = $args['name'];

    $sql = "SELECT text FROM files WHERE name = :name";
    $result = $this->c->db->prepare($sql);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->execute();

    $file = $result->fetch(PDO::FETCH_OBJ);

    if($file !== false) {

      return $this->c->view->render($response, 'file/raw.twig', compact('file'));

    } else {

      $this->c->flash->addMessage('msg', '"' . $name . '"' . ' not found'); // Use slim flash to pass a message to next page

      return $response->withStatus(404)->withHeader('Location', $this->c->router->pathFor('home')); // Redirect

    }

  }

  public function delete($request, $response, $args) {

    $name = $args['name'];

    $sql = "DELETE FROM files WHERE name = :name";
    $result = $this->c->db->prepare($sql);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->execute(); // Executes the prepared statment and returns true (sucess) or false (fail)

    $this->c->flash->addMessage('msg', '"' . $name . '"' . ' deleted'); // Use slim flash to pass a message to next page

    return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('home')); // Redirect

  }

  public function passwordIsset($request, $response, $args) {

    $name = $args['name'];

    $sql = "SELECT password FROM files WHERE name = :name";
    $result = $this->c->db->prepare($sql);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->execute();

    $file = $result->fetch(PDO::FETCH_OBJ);

    if($file !== false) {

      if($file->password === NULL || isset($_SESSION['password']) && $file->password === $_SESSION['password']) {

        return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('edit', ['name' => $name])); // Redirect

      } else {

        return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('check_password_modal', ['name' => $name])); // Redirect

      }

    } else {

      $this->c->flash->addMessage('msg', '"' . $name . '"' . ' not found'); // Use slim flash to pass a message to next page

      return $response->withStatus(404)->withHeader('Location', $this->c->router->pathFor('home')); // Redirect

    }

  }

  public function checkPassword($request, $response, $args) {

    $params = $request->getParams();

    if(isset($params['submit-pass'])) {

      $name = $args['name'];
      $password = sha1($params['password']);

      $sql = "SELECT password FROM files WHERE name = :name";
      $result = $this->c->db->prepare($sql);
      $result->bindParam(':name', $name, PDO::PARAM_STR);
      $result->execute();

      $file = $result->fetch(PDO::FETCH_OBJ);

      if($file->password === NULL || $file->password === $password) {

        $_SESSION['password'] = $password;

        // echo 'Password match';
        return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('edit', ['name' => $name])); // Redirect. {name} on route is set to $name

      } else {

        $this->c->flash->addMessage('msg', 'Password did not match'); // Use slim flash to pass a message to next page

        // echo 'Password did not match'; // TODO: Ajax password check with submit btn
        return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('check_password_modal', ['name' => $name])); // Redirect. {name} on route is set to $name

      }

    }

  }

  public function changePassword($request, $response, $args) {

    $params = $request->getParams();

    if(isset($params['submit-pass'])) {

      $password1 = sha1($params['password1']);
      $password2 = sha1($params['password2']);

      if($password1 === $password2) {

        $name = $args['name'];

        $sql = "UPDATE files SET password = :password WHERE name = :name";
        $result = $this->c->db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password1, PDO::PARAM_STR);
        $result->execute();

        $_SESSION['password'] = $password1;

        $this->c->flash->addMessage('msg', 'Password changed'); // Use slim flash to pass a message to next page

        return $response->withStatus(302)->withHeader('Location', $this->c->router->pathFor('edit', ['name' => $name])); // Redirect

      }

    }

  }

  public function ajaxCheckName($request, $response) {

    $name = $request->getParam('name');

    $sql = "SELECT id FROM files WHERE name = :name";
    $result = $this->c->db->prepare($sql);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->execute();

    if($result->rowCount() > 0) {

      echo 'true';

    } else {

      echo 'false';

    }

  }

  public function ajaxUpdateText($request, $response) {

    $params = $request->getParams();
    $name = $params['name'];
    $text = $params['text']; // TODO: Necessary htmlentities?

    $sql = "UPDATE files SET text = :text WHERE name = :name";
    $result = $this->c->db->prepare($sql);
    $result->bindParam(':text', $text, PDO::PARAM_STR);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->execute();

  }

}

?>
