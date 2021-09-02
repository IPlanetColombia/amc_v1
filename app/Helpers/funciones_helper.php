<?php
	function procesar_registro_fetch($aux_tabla, $aux_columna1, $aux_variable1, 
                                            $aux_columna2=0, $aux_variable2=0, 
                                            $aux_columna3=0, $aux_variable3=0, 
                                            $aux_columna4=0, $aux_variable4=0, 
                                            $aux_columna5=0, $aux_variable5=0, 
                                            $aux_columna6=0, $aux_variable6=0){
		$db = \Config\Database::connect();
		$wheres = [
			$aux_columna1 	=> $aux_variable1,
			$aux_columna2	=> $aux_variable2, 
            $aux_columna3	=> $aux_variable3, 
            $aux_columna4	=> $aux_variable4, 
            $aux_columna5	=> $aux_variable5, 
            $aux_columna6	=> $aux_variable6
		];

	    $data = $db->table($aux_tabla)
	    		->where($wheres)
	    		->get()
	    		->getResult();
	    return $data;

	}