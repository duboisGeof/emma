<?php
for ($i = 0; $i<$length; $i++){
			echo"<pre>";
			echo $cij_arr1[$i]['EMPLOY']."<br>";
			echo"</pre>";
			if($wemploy != $cij_arr1[$i]['EMPLOY'] ){
				if($wemploy != '0'){
					/**$insert_IJ_ARRETS_3202="
						insert into IJ_ARRETS_3202 values  ('".$cij_arr1[$i]['EMPLOY'].
						"','".$cij_arr1[$i]['ASU'].
						"','".$cij_arr1[$i]['MAT'].
						"','".$cij_arr1[$i]['DEB'].
						"','".$cij_arr1[$i]['FIN'].
						"','".$cij_arr1[$i]['NBRIJ'].
						"','".$cij_arr1[$i]['MONTTOT'].
						"') ";**/
					
					$insert_IJ_ARRETS_3202 = "insert into ij_arrets_3202 values ('".$wemploy."', '".$wasu."', '".$wmat."', '".$wdeb."','".$wfin."','".$wnbrij."', '".$wmonttot."')";
					ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
					
				}
				$wnbrij = 0;
				$wmonttot = 0;
				$wtoprev = 0;
				$wemploy = $cij_arr1[$i]['EMPLOY'];
				$wasu = $cij_arr1[$i]['ASU'];
				$wmat = $cij_arr1[$i]['MAT'];
				
				if($cij_arr1[$i]['MONTIJ'] > 0){
					if (is_null($cij_arr1[$i]['REV'])){
						$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
						$wtoprev = 1;
					}
					else{
						if ($wtoprev == 0){
							$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
						}
				$wmonttot = $wmonttot + ($cij_arr1[$i]['NBRIJ'] * $cij_arr1[$i]['MONTTOT'] );
				}
				$wdeb = $cij_arr1[$i]['DEB'];
				$wfin = $cij_arr1[$i]['FIN'];
			}
			else{
				if( $wmat != $cij_arr1[$i]['MAT']){
					/**$insert_IJ_ARRETS_3202="
						insert into IJ_ARRETS_3202 values  ('".$cij_arr1[$i]['EMPLOY'].
						"','".$cij_arr1[$i]['ASU'].
						"','".$cij_arr1[$i]['MAT'].
						"','".$cij_arr1[$i]['DEB'].
						"','".$cij_arr1[$i]['FIN'].
						"','".$cij_arr1[$i]['NBRIJ'].
						"','".$cij_arr1[$i]['MONTTOT'].
						"') ";**/
					
					$insert_IJ_ARRETS_3202 = "insert into ij_arrets_3202 values ('".$wemploy."', '".$wasu."', '".$wmat."', '".$wdeb."','".$wfin."','".$wnbrij."', '".$wmonttot."')";
					ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
					
					$wnbrij = 0;
					$wmonttot = 0;
					$wtoprev = 0;
					$wmat = $cij_arr1[$i]['MAT'];
					if( is_null($cij_arr1[$i]['REV'])){
						$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
						$wtoprev = 1;
					}
					else{
						if($wtoprev == 0){
							$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
						}
						$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
					}
					$wdeb = $cij_arr1[$i]['DEB'];
					$wfin = $cij_arr1[$i]['FIN'];
				}
				else{
					if( $cij_arr1[$i]['DEB'] > $wfin){
						if( $cij_arr1[$i]['DEB'] = $wfin +1){
							if( $cij_arr1[$i]['MONTIJ'] > 0){
								if( is_null($cij_arr1[$i]['REV'])){
									$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
									$wtoprev = 1;
								}
								else{
									if($wtoprev == 0){
										$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
									}
								$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
								}
								$wfin = $cij_arr1[$i]['FIN'];
							}
							else{
								$insert_IJ_ARRETS_3202 = "insert into ij_arrets_3202 values ('".
									$wemploy."', '".
									$wasu."', '".
									$wmat."', '".
									$wdeb."','".
									$wfin."','".
									$wnbrij."', '".
									$wmonttot."')";
								ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);
								$wnbrij = 0;
								$wmonttot = 0;
								$wtoprev = 0;
								if($cij_arr1[$i]['MONTIJ'] > 0){
									if( is_null($cij_arr1[$i]['REV'])){
										$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
										$wtoprev = 1;
									}
									else{
										if($wtoprev == 0){
											$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
										}
									$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
									}
									$wdeb = $cij_arr1[$i]['DEB'];
									$wfin = $cij_arr1[$i]['FIN'];
								}
								else{
									if($cij_arr1[$i]['MONTIJ'] > 0){
										if( is_null($cij_arr1[$i]['REV'])){
											$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
											$wtoprev = 1;
										}
										else{
											if($wtoprev == 0){
												$wnbrij = $wnbrij + $cij_arr1[$i]['NBRIJ'];
											}
										$wmonttot = $wmonttot + ($cij_arr1[$i]['MONTIJ'] * $cij_arr1[$i]['NBRIJ']);
										}
									}
								}
							}
						}
					}
					
				}
			}
		}
	}

$insert_IJ_ARRETS_3202 = "insert into ij_arrets_3202 values ('".
	$wemploy."', '".
	$wasu."', '".
	$wmat."', '".
	$wdeb."','".
	$wfin."','".
	$wnbrij."', '".
	$wmonttot."')";
	ExeQuery($insert_IJ_ARRETS_3202,$cnxDistante);