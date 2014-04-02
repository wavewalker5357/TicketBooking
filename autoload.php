<?php


// dumb autoloader

$base_dir = dirname(__FILE__);

$classes = array(

	// repositories
	'Repositories\\TicketRepositoryInterface' => $base_dir.'/repositories/TicketRepositoryInterface.php',
	'Repositories\\DBTicketRepository' => $base_dir.'/repositories/DBTicketRepository.php',

	// models
	'Models\\Input' => $base_dir.'/models/Input.php', 

	// controllers
	'Controllers\\FormFacade' => $base_dir.'/controllers/FormFacade.php', 
	


	);


foreach ( $classes as $class => $path ) {

	if ( !file_exists( $path ) ) {

		// soubor neni k dispozici, throw exception?

	} else {
		include_once($path);
	}

}