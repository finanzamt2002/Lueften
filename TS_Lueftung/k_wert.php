<?
      	//workaround for bug
      	if(!isset($_IPS))
      		global $_IPS;
    //Ermitteln der Werte
    //$twand=17.5; 
    //$K_wert = ($twand - $aussen) / ($innen - $aussen);
    //print_r ($K_wert.chr(10));
    $K_wert = 0.69;
    $twand1 =  ($temp_i * $K_wert) + ($temp_a * (1 - $K_wert));
      
?>
