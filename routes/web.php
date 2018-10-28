<?php

use App\Controllers\HomeController;
$app->get('/', HomeController::class . ':index')->setName('home');

use App\Controllers\FilesController;
$app->group('/file', function() {

  $this->post('/check-name', FilesController::class . ':ajaxCheckName');
  $this->post('/update-text', FilesController::class . ':ajaxUpdateText'); // TODO: Change to put

  $this->get('/{name}', FilesController::class . ':load')->setName('load');
  $this->get('/{name}#modal-check-password', FilesController::class . ':load')->setName('check_password_modal'); // file.index view with modal-check-password
  $this->get('/{name}/edit', FilesController::class . ':edit')->setName('edit');
  $this->get('/{name}/raw', FilesController::class . ':raw')->setName('raw');
  $this->get('/{name}/delete', FilesController::class . ':delete')->setName('delete'); // TODO: Change to delete
  $this->get('/{name}/password-isset', FilesController::class . ':passwordIsset')->setName('password_isset');

  $this->post('', FilesController::class . ':index')->setName('file');
  $this->post('/{name}/check-password', FilesController::class . ':checkPassword')->setName('check_password'); // TODO: '/{name}[/edit]'
  $this->post('/{name}/change-password', FilesController::class . ':changePassword')->setName('change_password'); // TODO: '/{name}[/edit]'

});

// TODO: Padronizar (-) para (_). Ex: check-password para check_password

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
  $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
  return $handler($req, $res);
});

?>
