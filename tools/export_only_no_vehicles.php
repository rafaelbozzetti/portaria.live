<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

$host       = "localhost";
$username   = "root";
$password   = "sawa";
$dbname     = "rapid";
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
              );

try {
    $stmt = new PDO("$dsn", $username, $password, $options);

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

$host       = "localhost";
$username   = "root";
$password   = "sawa";
$dbname     = "rapidt";
$dsn        = "mysql:host=$host;dbname=$dbname";
$options    = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
              );

try {
    $stmt2 = new PDO("$dsn", $username, $password, $options);

} catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}


$sql = "SELECT  p.name, p.apartament FROM    people as p LEFT JOIN vehicles as v ON p.id = v.person_id WHERE   v.person_id IS NULL AND p.is_active = 1";

$statement = $stmt->query($sql);
$result = $statement->fetchAll(\PDO::FETCH_ASSOC);

foreach($result as $r) {

	$morador = preg_replace( '/[^a-zA-Z0-9 ]+/', '', utf8_decode( trim(preg_replace("/[0-9]/", "",$r['name'])) ) );  // tudo exceto numero
	$telefone = utf8_decode( trim(preg_replace('/\D/', '', $r['name'])) );  // somente numeros

	$unidade = utf8_decode(trim($r['apartament']));
	$unidadeNome = 'Casa '. utf8_decode($r['apartament']);

	$unit = utf8_decode(preg_replace('/\D/', '', $r['apartament']));
	if($unit == 0) {
		continue;	
	}

	// consulta unidade no banco
	$sql = " SELECT id FROM units WHERE `number` = :unit "; 
	$st2 = $stmt2->prepare($sql);
	$st2->bindValue(':unit', $unit, PDO::PARAM_STR);
	$st2->execute();
	$result = $st2->fetch(\PDO::FETCH_ASSOC);


	// unidade existente
	if($result) {

		$sql =  " INSERT INTO people (name, active, type, unit_id ) 
				  VALUES ('$morador',1,1,:value) ";

		$stmt22 = $stmt2->prepare($sql);
		$stmt22->execute(array('value'=>$result['id']));

		if(trim($telefone) != '') {

			$sql = " SELECT id FROM people ORDER BY id DESC LIMIT 1 "; 

			$st = $stmt2->prepare($sql);
			$st->execute();
			$result = $st->fetch(\PDO::FETCH_ASSOC);

			$sql = " INSERT INTO phones (type, `number`, people_id )
					 VALUE ('phone', '$telefone', :value) ";

			$stmt22= $stmt2->prepare($sql);
			$stmt22->bindValue(':value', $result['id'], PDO::PARAM_STR);
			$stmt22->execute();

		}
	}

	// unidade nao existente
	else{

		$unit = (int)utf8_decode(preg_replace('/\D/', '', $r['apartament']));

		if($unit > 0 && $unit <= 326) {

			$sql = "SELECT id FROM blocks WHERE name = :name ";
			$st = $stmt2->prepare($sql);
			$st->bindValue(':name', 'Etapa 01', PDO::PARAM_STR);
			$st->execute();
			$block = $st->fetch(\PDO::FETCH_ASSOC);
		}
		elseif($unit > 326 && $unit <= 707) {
			
			$sql = "SELECT id FROM blocks WHERE name = :name ";
			$st = $stmt2->prepare($sql);
			$st->bindValue(':name', 'Etapa 02', PDO::PARAM_STR);
			$st->execute();
			$block = $st->fetch(\PDO::FETCH_ASSOC);
		}
		elseif($unit > 707 && $unit <= 1274) {
			
			$sql = "SELECT id FROM blocks WHERE name = :name ";
			$st = $stmt2->prepare($sql);
			$st->bindValue(':name', 'Etapa 03', PDO::PARAM_STR);
			$st->execute();
			$block = $st->fetch(\PDO::FETCH_ASSOC);
		}

		$sql = " INSERT INTO units (name, `number`, block_id)
				 VALUES ('$unidadeNome', '$unidade', :value) ";
		$stmt222= $stmt2->prepare($sql);
		$stmt222->execute(array('value'=>$block['id']));

		$sql = " SELECT id FROM units ORDER BY id DESC LIMIT 1 "; 
		$st = $stmt2->prepare($sql);
		$st->execute();
		$result = $st->fetch(\PDO::FETCH_ASSOC);


		$sql =  " INSERT INTO people (name, active, type, unit_id ) 
				  VALUES ('$morador',1,1,:value) ";
		$stmt223= $stmt2->prepare($sql);
		$stmt223->execute(array('value'=>$result['id']));

		if(trim($telefone) != '') {

			$sql = " SELECT id FROM people ORDER BY id DESC LIMIT 1 "; 
			$st = $stmt2->prepare($sql);
			$st->execute();
			$result = $st->fetch(\PDO::FETCH_ASSOC);

			$sql = " INSERT INTO phones (type, `number`, people_id )
					 VALUE ('phone', '$telefone', :value) ";
			$stmt224= $stmt2->prepare($sql);
			$stmt224->execute(array('value'=>$result['id']));
		}

	}
}


exit;