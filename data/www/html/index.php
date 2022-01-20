<?php
	// header('Access-Control-Allow-Origin:*');

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	require __DIR__ . '/../slim/vendor/autoload.php';

	function buildResponse($resp, $ret) {
		$newResponse = $resp->withHeader('Content-type', 'application/json');
		$newResponse = $newResponse->withAddedHeader('Access-Control-Allow-Origin', '*');
		$newResponse->getBody()->write(json_encode($ret));
		return $newResponse;
	}

	$app = AppFactory::create();
	
	require __DIR__ . '/api.php';
?>
