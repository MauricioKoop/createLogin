<?php 

namespace App\Models;

use MF\Model\Model;

class Authenticate extends Model
{
	private $email;
	private $senha;

	public function __get($att)
	{
		return $this->$att;
	}

	public function __set($att, $value)
	{
		$this->$att = $value;
	}

	public function autenticar()
	{
		$query = 'select id, nome, email, senha from usuarios where email = :email';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->execute();

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

}

?>