<?php
	// note: $pdo_handler is internal object in pdo_connect()

	// Use raw SQL
	/*
	$pdo_handler->exec(''
	.	'CREATE TABLE cars'
	.	'('
	.		'id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,'
	.		'name TEXT,'
	.		'price INTEGER'
	.	')'
	);

	$pdo_handler->exec(''
	.	'INSERT INTO cars'
	.	'('
	.		'name,'
	.		'price'
	.	') VALUES'
	.		'("Audi", 52642),'
	.		'("Mercedes", 57127),'
	.		'("Skoda", 9000),'
	.		'("Volvo", 29000),'
	.		'("Bentley", 350000),'
	.		'("Citroen", 21000),'
	.		'("Hummer", 41400),'
	.		'("Volkswagen", 21600)'
	);

	$pdo_handler->exec(''
	.	'INSERT INTO cars'
	.	'('
	.		'name,'
	.		'price'
	.	') VALUES'
	.		'("Single row", 1234)'
	);
	*/

	// Use PDO CRUD builder
	if(!class_exists('pdo_crud_builder'))
		require TK_LIB.'/pdo_crud_builder.php';

	$seed_crud_builder=new pdo_crud_builder([
		'pdo_handler'=>$pdo_handler
	]);

	$seed_crud_builder->create_table(
		'cars',
		[
			'id'=>'INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL',
			'name'=>'TEXT',
			'price'=>'INTEGER'
		]
	)->exec();

	$seed_crud_builder->insert_into(
		'cars',
		'name,price',
		[
			['Bentley', '52642'],
			['Audo', '5712'],
			['Mercedes', '9000'],
			['Skoda', '29000'],
			['Lolvo', '29000'],
			['Citroen', '41400'],
			['Hummer', '21600']
		]
	)->exec();

	$seed_crud_builder->insert_into(
		'cars',
		'name,price',
		[
			['Single row', '12354']
		]
	)->exec();

	unset($seed_crud_builder);
?>