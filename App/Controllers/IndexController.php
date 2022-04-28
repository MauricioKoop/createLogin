<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

//modelo Usuarios
use App\Models\Usuarios;
//modelo Authenticate
use App\Models\Authenticate;

class IndexController extends Action {

	public function index() {
		$this->view->loginErr = null;
		
		$this->render('index');
	}

	public function inscreverse() {
		$this->view->emailExi = null;

		$this->view->validaForm = array(
			'name' => null,
			'email' => null,
			'senha' => null,
			'i' => null,
		);

		$this->render('inscreverse');

	}

	public function registrar() {
		
		$usuarios = Container::getModel('Usuarios');
		$usuarios->__set('nome', $_POST['nome']);
		$usuarios->__set('email', $_POST['email']);

		$senha_cripto = password_hash($_POST['senha'], PASSWORD_DEFAULT);

		$usuarios->__set('senha', $senha_cripto);

		$valida = $usuarios->validaForm();

		$this->view->validaForm = $valida;

		if ($valida['i'] == 3 && count($usuarios->getEmail()) == 0) {
			$usuarios->salvar();
			$this->render('finalizado');
		}else if (count($usuarios->getEmail()) == 1){
			$this->view->emailExi = 'E-mail já existe no DB!';
			$this->render('inscreverse');
		}else{
			$this->render('inscreverse');
		}

	}

	public function autenticar()
	{

		$usuarioAuth = Container::getModel('Authenticate');
		$usuarioAuth->__set('email', $_POST['email']);
		$usuarioAuth->__set('senha', $_POST['senha']);
		
		$userPorEmail = $usuarioAuth->autenticar();

		if ($usuarioAuth->__get('email') == '' || $usuarioAuth->__get('senha') == '') {
			$this->view->loginErr = 'E-mail ou senha incorretos.';
			$this->render('index');
		}else{
			if ($usuarioAuth->__get('email') == $userPorEmail['email'] && password_verify($usuarioAuth->__get('senha'), $userPorEmail['senha'])) {
				echo 'Login realizado com sucesso';
				echo '<br>';
				echo '<pre>';
				print_r($userPorEmail);
				echo '</pre>';
			}else{
				$this->view->loginErr = 'E-mail ou senha incorretos.';
				$this->render('index');
			}
		}
	}

}


?>