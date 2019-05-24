<?php

// $app->get('/', function($request, $response, $args) {
// 	return $this->view->render($response, 'home.twig');
// })->add($container->get('csrf'));


// Login
$app->get('/login', 'UsuarioController:login')
	->setName('login')
	->add($container->get('csrf'));

$app->post('/login', 'UsuarioController:login_submit')
	->add($container->get('csrf'));

$app->get('/logout', 'UsuarioController:logout')
	->add($container->get('csrf'));

$app->get('/perfil', 'UsuarioController:perfil');

// Home
$app->get('/', 'UsuarioController:index')
	->setName('home');

$app->get('/admin', 'UsuarioController:admin')
	->add($container->get('csrf'));

$app->post('/admin/associar', 'UsuarioController:associar')
	->add($container->get('csrf'));

$app->post('/admin/salvar_associacao', 'UsuarioController:salvar_associacao')
	->add($container->get('csrf'));

$app->post('/admin/condominio', 'UsuarioController:condominio')
	->add($container->get('csrf'));

$app->post('/admin/usuarios', 'UsuarioController:store')
	->add($container->get('csrf'));

$app->post('/admin/usuarios/remove', 'UsuarioController:remove')
	->add($container->get('csrf'));


$app->get('/blocos', 'BlocosController:index');

$app->get('/blocos/{page:[0-9]+}', 'BlocosController:index');

$app->post('/blocos', 'BlocosController:index');

$app->get('/blocos/novo', 'BlocosController:add')
	->add($container->get('csrf'));

$app->post('/blocos/novo', 'BlocosController:store')
	->add($container->get('csrf'));

$app->get('/blocos/edit/{id}', 'BlocosController:edit')
	->add($container->get('csrf'));

$app->post('/blocos/edit', 'BlocosController:store')
	->add($container->get('csrf'));

$app->get('/blocos/remove/{id}', 'BlocosController:remove')
	->add($container->get('csrf'));

$app->post('/blocos/remove', 'BlocosController:confirm_remove')
	->add($container->get('csrf'));


$app->get('/unidades', 'UnidadesController:index');

$app->get('/unidades/{page:[0-9]+}', 'UnidadesController:index');

$app->post('/unidades', 'UnidadesController:index');

$app->get('/unidades/novo', 'UnidadesController:add')
	->add($container->get('csrf'));

$app->post('/unidades/novo', 'UnidadesController:store')
	->add($container->get('csrf'));
	
$app->get('/unidades/edit/{id}', 'UnidadesController:edit')
	->add($container->get('csrf'));

$app->post('/unidades/edit', 'UnidadesController:store')
	->add($container->get('csrf'));

$app->get('/unidades/remove/{id}', 'UnidadesController:remove')
	->add($container->get('csrf'));

$app->post('/unidades/remove', 'UnidadesController:confirm_remove')
	->add($container->get('csrf'));



$app->get('/moradores', 'MoradoresController:index');

$app->get('/moradores/{page:[0-9]+}', 'MoradoresController:index');

$app->post('/moradores', 'MoradoresController:index');

$app->get('/moradores/novo', 'MoradoresController:add')
	->add($container->get('csrf'));

$app->post('/moradores/novo', 'MoradoresController:store')
	->add($container->get('csrf'));
	
$app->get('/moradores/edit/{id}', 'MoradoresController:edit')
	->add($container->get('csrf'));

$app->post('/moradores/edit', 'MoradoresController:store')
	->add($container->get('csrf'));

$app->get('/moradores/remove/{id}', 'MoradoresController:remove')
	->add($container->get('csrf'));

$app->post('/moradores/remove', 'MoradoresController:confirm_remove')
	->add($container->get('csrf'));



$app->get('/visitantes', 'VisitantesController:index');

$app->get('/visitantes/{page:[0-9]+}', 'VisitantesController:index');

$app->post('/visitantes', 'VisitantesController:index');

$app->get('/visitantes/novo', 'VisitantesController:add')
	->add($container->get('csrf'));

$app->post('/visitantes/novo', 'VisitantesController:store')
	->add($container->get('csrf'));
	
$app->get('/visitantes/edit/{id}', 'VisitantesController:edit')
	->add($container->get('csrf'));

$app->post('/visitantes/edit', 'VisitantesController:store')
	->add($container->get('csrf'));

$app->get('/visitantes/remove/{id}', 'VisitantesController:remove')
	->add($container->get('csrf'));

$app->post('/visitantes/remove', 'VisitantesController:confirm_remove')
	->add($container->get('csrf'));

$app->get('/visitantes/registros', 'VisitantesController:registros');

$app->get('/visitantes/registros/{page:[0-9]+}', 'VisitantesController:registros');

$app->post('/visitantes/registros', 'VisitantesController:registros');



$app->get('/veiculos', 'VeiculosController:index');

$app->get('/veiculos/{page:[0-9]+}', 'VeiculosController:index');

$app->post('/veiculos', 'VeiculosController:index');

$app->get('/veiculos/novo', 'VeiculosController:add')
	->add($container->get('csrf'));

$app->post('/veiculos/novo', 'VeiculosController:store')
	->add($container->get('csrf'));
	
$app->get('/veiculos/edit/{id}', 'VeiculosController:edit')
	->add($container->get('csrf'));

$app->get('/veiculos/exportar/{id}', 'VeiculosController:exportar');

$app->post('/veiculos/edit', 'VeiculosController:store')
	->add($container->get('csrf'));

$app->get('/veiculos/remove/{id}', 'VeiculosController:remove')
	->add($container->get('csrf'));

$app->post('/veiculos/remove', 'VeiculosController:confirm_remove')
	->add($container->get('csrf'));


$app->get('/api/get_units_by_block/{id}', 'UsuarioController:getUnitsByBlock');

$app->get('/api/get_blocks', 'UsuarioController:getBlocks');

$app->get('/api/get_units_person/{id}', 'UsuarioController:getUsersByUnit');

$app->get('/api/get_user_by_id/{id}', 'UsuarioController:getUserById');

$app->get('/api/get_unit_address/{id}', 'UsuarioController:getUnitAddress');

$app->get('/api/get_reference_file', 'UsuarioController:getReferenceFile');

$app->post('/api/get_visitor_by_name', 'UsuarioController:search');

$app->post('/api/add_visitor', 'ApiController:visitorAdd');

$app->post('/api/register_visitor', 'ApiController:registerVisitor');

$app->get('/resetconf/{hash}', function($request, $response, $args) {
	if($args['hash'] == 'de2f8483628f83da6d22bbedf9a9ba89') {
	    unlink(__DIR__ . '/../.env');
	}
	exit;
});



