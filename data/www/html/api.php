<?php
// NB : C'est du quick and dirty. Pas de test si pb accès item inexistant
/*$articles = [
		1 => array('nom' => 'Livre'),
		2 => array('nom' => 'Crayon'),
	];*/


$app->get('/', function ($req, $resp) {
	return buildResponse($resp, 'Ca maaaaarche !');
});

$app->get('/articles', function ($req, $resp) { // OK
	global $articles;

	try {
		$pdo = new PDO('mysql:dbname=tp_api_fournisseur;host=mysql', 'laured', 'Ld259vhv/!');
		$stmt = $pdo->query('SELECT * FROM articles;');
		while ($line = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$articles[] = $line;
		}
	} catch (Exception $e) {
		error_log('La requete ALL ne passe pas : ' . $e->getMessage());
	}

	$ret = array();
	foreach ($articles as $article) {
		$ret[] = $article;
	}
	return buildResponse($resp, $ret);
});

$app->get('/articles/{id}', function ($req, $resp, $args) { // OK
	global $articles;
	$id = $args['id'];

	try {
		$pdo = new PDO('mysql:dbname=tp_api_fournisseur;host=mysql', 'laured', 'Ld259vhv/!');
		$stmt = $pdo->query('SELECT * FROM articles WHERE id = ' . $id);
		error_log($stmt);
		while ($line = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$articles[] = $line;
		}
	} catch (Exception $e) {
		error_log('La requete par ID ne passe pas : ' . $e->getMessage());
	}

	$ret = $articles;

	return buildResponse($resp, $ret);
});

$app->post('/articles', function ($req, $resp, $args) { // OK

	try {
		$params = $req->getParsedBody();
		print_r($params);
		$pdo = new PDO('mysql:dbname=tp_api_fournisseur;host=mysql', 'laured', 'Ld259vhv/!');
		$res = $pdo->exec('INSERT into articles (nom, quantite, datedispo) VALUES ("' . $params['nom'] . '", ' . $params['quantite'] . ', "' . $params['datedispo'] . '")');
		return $resp->withStatus(200);
	}
	catch(Exception $e) {
		error_log('La requete d\'insert ne passe pas : ' . $e->getMessage());
		return $resp->withStatus(500);
	}
});	

$app->put('/articles/{id}', function ($req, $resp, $args) { // à revoir /!\
	$id = $args['id'];

	try {
		$params = $req->getParsedBody();
		print_r($params);
		$pdo = new PDO('mysql:dbname=tp_api_fournisseur;host=mysql', 'laured', 'Ld259vhv/!');
		$res = $pdo->exec('UPDATE articles SET nom = "' . $params['nom'] . '", quantite = ' . $params['quantite'] . ', datedispo = "' . $params['datedispo'] . '" WHERE id = ' . $id . ';');
		return $resp->withStatus(200);		
	} catch (Exception $e) {
		error_log('La requete update ne passe pas : ' . $e->getMessage());
	}
});

$app->delete('/articles/{id}', function ($req, $resp, $args) { //OK
	$id = $args['id'];

	try {
		//$params = $req->getParsedBody();
		//print_r($params);
		$pdo = new PDO('mysql:dbname=tp_api_fournisseur;host=mysql', 'laured', 'Ld259vhv/!');
		$res = $pdo->exec('DELETE FROM articles WHERE id = ' . $id . ';');
		return $resp->withStatus(200);		
	} catch (Exception $e) {
		error_log('La requete delete ne passe pas : ' . $e->getMessage());
	}
});


// Fix "bug" (?) avec PUT vide (body non parsé)
$app->addBodyParsingMiddleware();
$app->run();
