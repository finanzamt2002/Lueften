<?
      	//workaround for bug
      	if(!isset($_IPS))
      		global $_IPS;
//http://www.bauphysik-zimmer.de/feuchterechner/

//$feuchte_ID = IPS_GetObjectIDByName('Feuchte_innen', $Parent);
//$temp_ID = IPS_GetObjectIDByName('Temperatur_innen', $Parent);
//      $rel =GetValue($feuchte_ID);
//      $temp =GetValue($temp_ID);


      if ($temp >= 0)
      {
          $a = 7.5;
          $b = 237.3;
      }
      elseif ($temperatur < 0)
      {
          $a = 7.6;
          $b = 240.7;
      }
		$wdsd = 611 * pow(10,$a*$temp/($b+$temp));//Wasserdampfsättigungsdruck in Pa
		$wdd = $rel/100 * $wdsd;  //Wasserdampfdruck in Pa
    $wdsdbauteil = 611*pow(10,7.5*$twand/(237.3+$twand));//Wasserdampfsättigungsdruck Bauteil(Temperatur der Oberfläche Wand)
		$tp = 237.3*log($wdd/610.78)/log(10)/(7.5 - log($wdd/610.78)/log(10));//Taupunkt
		$tf80 = 237.3*log($wdd/(610.78*0.8))/log(10)/(7.5 - log($wdd/(610.78*0.8)) / log(10));//TF80-Wert
		$tf70 = 237.3*log($wdd/(610.78*0.7))/log(10)/(7.5 - log($wdd/(610.78*0.7)) / log(10));//TF70-Wert
		$tf60 = 237.3*log($wdd/(610.78*0.6))/log(10)/(7.5 - log($wdd/(610.78*0.6)) / log(10));//TF60-Wert
		$abs = 1000*18.016/8314.3*$wdd/($temp+273.15);//Absolute Luftfeuchtigkeit
		$normfeu = $wdsd*$rel/2340;//normierte Feuchte (20 °C)
    $aw_wert = ($wdd / $wdsdbauteil); // rel Luftfeuchte an der Wand
    $aw_wert = ($aw_wert *100); //auf 100%
  	if ($aw_wert > 100) {
  		$aw_wert = 100;
		}
/*
    print_r ("Wandtemp : ".$twand.chr(10));
		print_r ($rel.chr(10));
		print_r ($temp.chr(10));
		print_r ($wdsd.chr(10));
		print_r ($wdd.chr(10));
		print_r ("Tau :".$tp.chr(10));
		print_r ("TF80 ".$tf80.chr(10));
		print_r ("TF70 ".$tf70.chr(10));
		print_r ("TF60 ".$tf60.chr(10));
		print_r ("ABS :".$abs.chr(10));
		print_r ("Wand r.F. : :".$aw_wert.chr(10));
*/
?>
