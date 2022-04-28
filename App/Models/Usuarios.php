<?php
namespace App\Models;

use MF\Model\Model;

class Usuarios extends Model
{
	protected $id;
	protected $nome;
	protected $email;
	protected $senha;

	public function __get($att)
	{
		return $this->$att;
	}

	public function __set($att, $value)
	{
		$this->$att = $value;
	}

	public function salvar()
	{
		$query = 'insert into usuarios(nome, email, senha)value(:nome, :email, :senha)';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha'));
		$stmt->execute();

		return $this;
	}

	public function getEmail()
	{
		$query = '
			select id, nome, email 
			from usuarios
			where :email = email
		';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function validaForm()
	{	
		$valida = [];

		if ($_SERVER["REQUEST_METHOD"] == 'POST') {
			$valida['i'] = null;

			if (empty($_POST['nome']) || strlen($_POST['nome']) < 3) {
				$valida['name'] = false;
			}else
			{
				$nome = $this->verify_input($_POST['nome']);
				$valida['name'] = $_POST['nome'];
				$valida['i']++;
			}
			if(empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			{
				$valida['email'] = false;
			}else
			{
				$email = $this->verify_input($_POST['email']);
				$valida['email'] = $_POST['email'];
				$valida['i']++;
			}
			if (empty($_POST['senha']) || strlen($_POST['senha']) <= 6) {
				$valida['senha'] = false;
			}else
			{
				$senha = $this->verify_input($_POST['senha']);
				$valida['senha'] = $_POST['senha'];
				$valida['i']++;
			}
		}
		return $valida;
	}

	public function verify_input($data)
	{
		$data = trim($data);
		$data = stripcslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

}

?>