<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use League\Csv\Writer;


$host       = "localhost";
$username   = "root";
$password   = "rootutech";
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


$sql = "SELECT p.name, p.apartament, v.model, v.color, v.plate FROM people AS p, vehicles AS v WHERE p.id = v.person_id AND p.is_active = 1";

$statement = $stmt->query($sql);
$result = $statement->fetchAll(\PDO::FETCH_ASSOC);


$final = array();

foreach($result as $r) {

	$morador = utf8_decode( trim(preg_replace("/[0-9]/", "",$r['name'])) ); // tudo exceto numero
	$telefone = utf8_decode( trim(preg_replace('/\D/', '', $r['name'])) );  // somente numeros

	$unidade = utf8_decode(trim($r['apartament']));
	$unidadeNome = 'Casa '. utf8_decode($r['apartament']);

	$unit = (int)utf8_decode(preg_replace('/\D/', '', $r['apartament']));

	if($unit == 0) {
		continue;	
	}

	if($unit > 0 && $unit <= 326) {
		$bloco = 'Etapa 01';
	}
	elseif($unit > 326 && $unit <= 707) {
		$bloco = 'Etapa 02';
	}
	elseif($unit > 707 && $unit <= 1274) {
		$bloco = 'Etapa 03';
	}

	$carroModelo = utf8_decode(trim($r['model']));
	$carroCor = utf8_decode(trim($r['color']));
	$carroPlate = utf8_decode(trim($r['plate']));

	$final[] = array($morador, $telefone, $unidade, $unidadeNome, $bloco, $carroModelo, $carroCor, $carroPlate, 1);

}

$csv = Writer::createFromString('');

$csv->insertAll($final);

$csv->output('final.csv');
die;
