<?php
/*
	PufferPanel - A Minecraft Server Management Panel
	Copyright (c) 2013 Dane Everitt

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see http://www.gnu.org/licenses/.
*/
namespace PufferPanel\Core;
use \ORM as ORM;
header('Content-Type: application/json');
require_once '../../src/core/core.php';
require_once '../../src/core/api/initalize.php';

$klein = new \Klein\Klein();
$base  = dirname($_SERVER['PHP_SELF']);
if(ltrim($base, '/'))
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));

// check all requests for a header
// $headers = getallheaders();
// if(array_key_exists('X-Access-Token', $headers)){
//
// 	$authenticate = ORM::forTable('api')->where('key', $headers['X-Access-Token'])->findOne();
//
// 	if(!$authenticate){
//
// 		http_response_code(401);
// 		exit();
//
// 	}
//
// }else{
//
// 	http_response_code(401);
// 	exit();
//
// }

$api = new API\Initalize();

$klein->respond('GET', '/users/[:uuid]?', function ($request, $response) use ($api) {

	$users = $api->loadClass('Users');

        if($request->param('uuid')){

            $data = $users->listUsers($request->param('uuid'));
            if(!$data){

                $response->code(404);
                return json_encode(array('message' => 'The requested UUID does not exist in the system.'));

            }else
                return json_encode($data, JSON_PRETTY_PRINT);


        }else
            return json_encode($users->listUsers(), JSON_PRETTY_PRINT);

});

$klein->respond(function($request, $response, $service, $app, $matched) {

	if ($matched < 1) {

		$response->code(404);
		return json_encode(array('message' => 'You have reached an invalid API point.'));

	}

});

$klein->dispatch();

?>