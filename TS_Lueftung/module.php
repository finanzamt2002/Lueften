<?

	class TS_Lueftung extends IPSModule
	{

		public function __construct($InstanceID)
		 {
			//Never delete this line!
			parent::__construct($InstanceID);

			//These lines are parsed on Symcon Startup or Instance creation
			//You cannot use variables here. Just static values.
      $this->RegisterPropertyInteger("var1id", 34046);			
      $this->RegisterPropertyInteger("var2id", 24558);			
      $this->RegisterPropertyInteger("var3id", 28310);			
      $this->RegisterPropertyInteger("var4id", 50245);			
      $this->RegisterPropertyInteger("var5id", 54406);			

		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

$sid = $this->RegisterScript("_berechnung_i", "_berechnung_i", '<?
$Parent           					= IPS_GetParent($_IPS[\'SELF\']);
$temp_ID = IPS_GetObjectIDByName(\'Temperatur_innen\', $Parent);
$feuchte_ID = IPS_GetObjectIDByName(\'Feuchte_innen\', $Parent);
$absfeuchte_ID = IPS_GetObjectIDByName(\'AbsoluteFeuchte_innen\', $Parent);
$tau_ID = IPS_GetObjectIDByName(\'Taupunkt_innen\', $Parent);
$wandfeuchte_ID = IPS_GetObjectIDByName(\'Wand_Feuchte\', $Parent);
$Wand_temp_ID = IPS_GetObjectIDByName(\'Wand_temp\', $Parent);
$norm_ID = IPS_GetObjectIDByName(\'norm_Feuchte_innen\', $Parent);
$aw_wert = GetValue($wandfeuchte_ID);
$twand = GetValue($Wand_temp_ID);
$temp = GetValue($temp_ID);
$rel = GetValue($feuchte_ID);
include("../modules/Ts/TS_Lueftung/berechnung.php");
SetValue($absfeuchte_ID, $abs);
SetValue($tau_ID, $tp);
SetValue($wandfeuchte_ID, $aw_wert);
SetValue($norm_ID, $normfeu);
?>');

$sid2 = $this->RegisterScript("_berechnung_a", "_berechnung_a", '<?
$Parent           					= IPS_GetParent($_IPS[\'SELF\']);
$temp_a_ID = IPS_GetObjectIDByName(\'Temperatur_aussen\', $Parent);
$feuchte_a_ID = IPS_GetObjectIDByName(\'Feuchte_aussen\', $Parent);
$absfeuchte_a_ID = IPS_GetObjectIDByName(\'AbsoluteFeuchte_aussen\', $Parent);
$tau_a_ID = IPS_GetObjectIDByName(\'Taupunkt_aussen\', $Parent);
$wandfeuchte_ID = IPS_GetObjectIDByName(\'Wand_Feuchte\', $Parent);
$Wand_temp_ID = IPS_GetObjectIDByName(\'Wand_temp\', $Parent);
$norm_ID = IPS_GetObjectIDByName(\'norm_Feuchte_aussen\', $Parent);

$aw_wert = GetValue($wandfeuchte_ID);
$twand = GetValue($Wand_temp_ID);
$temp = GetValue($temp_a_ID);
$rel = GetValue($feuchte_a_ID);
include("../modules/Ts/TS_Lueftung/berechnung.php");
SetValue($absfeuchte_a_ID, $abs);
SetValue($tau_a_ID, $tp);
//SetValue($wandfeuchte_ID, $aw_wert); //Darf nur innen gesetzt werden
SetValue($norm_ID, $normfeu);

?>');
$sid3 = $this->RegisterScript("_lueften", "_lueften", '<?
$Parent           					= IPS_GetParent($_IPS[\'SELF\']);
$absfeuchte_ID = IPS_GetObjectIDByName(\'AbsoluteFeuchte_innen\', $Parent);
$absfeuchte_a_ID = IPS_GetObjectIDByName(\'AbsoluteFeuchte_aussen\', $Parent);
$lueften_ID = IPS_GetObjectIDByName(\'Lueften\', $Parent);
$feuchte_ID = IPS_GetObjectIDByName(\'Feuchte_innen\', $Parent);

    $aussen = GetValue($absfeuchte_a_ID);
    $innen = GetValue($absfeuchte_ID );
    $feuchte_innen =GetValue($feuchte_ID);

    if ($innen > $aussen xor $feuchte_innen < 40)
    {
        SetValueBoolean($lueften_ID ,true);
    }
    else
    {
        SetValueBoolean($lueften_ID ,false);
    }
?>');
$sid4 = $this->RegisterScript("_berechnen_wand", "_berechnen_wand", '<?
$Parent           					= IPS_GetParent($_IPS[\'SELF\']);
$taussen_ID = IPS_GetObjectIDByName(\'Temperatur_aussen\', $Parent);
$tinnen_ID = IPS_GetObjectIDByName(\'Temperatur_innen\', $Parent);
$Wand_temp_ID = IPS_GetObjectIDByName(\'Wand_temp\', $Parent);
$aussen = GetValue($taussen_ID);
$innen = GetValue($tinnen_ID );
//Ermitteln der Werte
//$twand=17.5;
//$K_wert = ($twand - $aussen) / ($innen - $aussen);
//print_r ($K_wert.chr(10));
$K_wert = 0.78;//0.69;
$twand =  ($innen * $K_wert) + ($aussen * (1 - $K_wert));
SetValue($Wand_temp_ID, $twand);
?>');
   
      $var1_id = $this->ReadPropertyInteger("var1id");
      $var2_id = $this->ReadPropertyInteger("var2id");
      $var3_id = $this->ReadPropertyInteger("var3id");
      $var4_id = $this->ReadPropertyInteger("var4id");
      $var5_id = $this->ReadPropertyInteger("var5id");
      
			$this->RegisterProfileFloat("TS_absolute_Feuchte", "Drops", "", " g/m".chr(179), 0, 50, 0.5);
			$this->RegisterProfileBoolean("TS_lueften", "Drops");
             
 			$temp_id=$this->RegisterVariableFloat("Temperatur_innen", "Temperatur_innen", "~Temperature");
 			$feuchte_id=$this->RegisterVariableFloat("Feuchte_innen", "Feuchte_innen", "~Humidity.F");
 			$ab_id=$this->RegisterVariableFloat("AbsoluteFeuchte_innen", "AbsoluteFeuchte_innen", "TS_absolute_Feuchte");
 			$this->RegisterVariableFloat("Wand_Feuchte", "Wand_Feuchte", "~Humidity.F");
 			$wand_temp_id=$this->RegisterVariableFloat("Wand_temp", "Wand_temp", "~Temperature");
 			$this->RegisterVariableFloat("norm_Feuchte_innen", "norm_Feuchte_innen", "~Humidity.F");
 			$this->RegisterVariableFloat("norm_Feuchte_aussen", "norm_Feuchte_aussen", "~Humidity.F");
			$this->RegisterVariableFloat("Taupunkt", "Taupunkt_innen", "~Temperature");
			$luefter_id = $this->RegisterVariableBoolean("Lueften", "Lueften", "TS_lueften");
 			$this->RegisterVariableFloat("Raumluftfeuchte_Soll", "Raumluftfeuchte_Soll", "~Humidity.F");
      $this->Registerevent1($temp_id,$sid); 
      $this->Registerevent2($feuchte_id,$sid); 
      $this->Registerevent11($var1_id,$temp_id); 
      $this->Registerevent12($var2_id,$feuchte_id); 
      $this->Registerevent121($var5_id,$wand_temp_id); 

      $this->Registerevent20($ab_id,$sid3); 


 			$temp_a_id=$this->RegisterVariableFloat("Temperatur_aussen", "Temperatur_aussen", "~Temperature");
 			$feuchte_a_id=$this->RegisterVariableFloat("Feuchte_aussen", "Feuchte_aussen", "~Humidity.F");
 			$ab_a_id=$this->RegisterVariableFloat("AbsoluteFeuchte_aussen", "AbsoluteFeuchte_aussen", "TS_absolute_Feuchte");
			$this->RegisterVariableFloat("Taupunkt_aussen", "Taupunkt_aussen", "~Temperature");
      $this->Registerevent3($temp_a_id,$sid2); 
      $this->Registerevent4($feuchte_a_id,$sid2); 
      $this->Registerevent13($var3_id,$temp_a_id); 
      $this->Registerevent14($var4_id,$feuchte_a_id); 
      $this->Registerevent21($ab_a_id,$sid3); 
      $this->Registerevent22($sid4); 

    }	


		private function Registerevent1($temp_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_Temp",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Temp", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Temp", $TargetID));
      }
      } 
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Temp");
      IPS_SetEventTrigger($eid, 1, $temp_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	
		private function Registerevent11($var1_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;
      $EreignisID = @IPS_GetEventIDByName("E_Ti",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Ti", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Ti", $TargetID));
      }
      }
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Ti");
      IPS_SetEventTrigger($eid,0,$var1_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventScript($eid, 'SetValue($_IPS[\'TARGET\'], $_IPS[\'VALUE\']);');
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	

		private function Registerevent2($feuchte_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_Feuchte",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Feuchte", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Feuchte", $TargetID));
      }
      }      
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Feuchte");
      IPS_SetEventTrigger($eid, 1, $feuchte_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	
		private function Registerevent12($var2_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_Fi",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Fi", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Fi", $TargetID));
      }
      }
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Fi");
      IPS_SetEventTrigger($eid, 0, $var2_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventScript($eid, 'SetValue($_IPS[\'TARGET\'], $_IPS[\'VALUE\']);');
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	

		private function Registerevent3($feuchte_a_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_Feuchte_a",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Feuchte_a", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Feuchte_a", $TargetID));
      }
      }
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Feuchte_a");
      IPS_SetEventTrigger($eid, 1, $feuchte_a_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	
		private function Registerevent13($var3_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_Ta",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Ta", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Ta", $TargetID));
      }
      }
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Ta");
      IPS_SetEventTrigger($eid, 0, $var3_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventScript($eid, 'SetValue($_IPS[\'TARGET\'], $_IPS[\'VALUE\']);');
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	

		private function Registerevent4($temp_a_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_Temp_a",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Temp_a", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Temp_a", $TargetID));
      }
      }       
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Temp_a");
      IPS_SetEventTrigger($eid, 1, $temp_a_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	
		private function Registerevent14($var4_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_Fa",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_Fa", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_Fa", $TargetID));
      }
      }
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_Fa");
      IPS_SetEventTrigger($eid, 0, $var4_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventScript($eid, 'SetValue($_IPS[\'TARGET\'], $_IPS[\'VALUE\']);');
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	

		private function Registerevent20($temp_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_lu_i",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_lu_i", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_lu_i", $TargetID));
      }
      }       
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_lu_i");
      IPS_SetEventTrigger($eid, 1, $temp_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	

		private function Registerevent21($temp_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_lu_a",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_lu_a", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_lu_a", $TargetID));
      }
      } 
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_lu_a");
      IPS_SetEventTrigger($eid, 1, $temp_id);        //Bei Änderung von Variable mit ID 15754
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	

		private function Registerevent22($TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_wand",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_wand", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_wand", $TargetID));
      }
      } 
      $eid = IPS_CreateEvent(1);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_wand");
      IPS_SetEventCyclic($eid, 2 /* Täglich */, 1 /* Jeden Tag */, 0, 0, 3 /* Stündlich */, 1 /* Alle 1 Stunden */);   
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	
		private function Registerevent121($var5_id,$TargetID)
		{ 
      if(!isset($_IPS))
      global $_IPS;  
      $EreignisID = @IPS_GetEventIDByName("E_TW",  $TargetID);
      if ($EreignisID == true){
      if (IPS_EventExists(IPS_GetEventIDByName ( "E_TW", $TargetID)))
      {
       IPS_DeleteEvent(IPS_GetEventIDByName ( "E_TW", $TargetID));
      }
      }
      $eid = IPS_CreateEvent(0);                  //Ausgelöstes Ereignis
      IPS_SetName($eid, "E_TW");
      IPS_SetEventTrigger($eid, 0, $var5_id);        //Bei Änderung von Variable Wandtemp
      IPS_SetParent($eid, $TargetID);         //Ereignis zuordnen
      IPS_SetEventScript($eid, 'SetValue($_IPS[\'TARGET\'], $_IPS[\'VALUE\']);');
      IPS_SetEventActive($eid, true);             //Ereignis aktivieren
    }	

		protected function RegisterProfileFloat($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize) {
			if(!IPS_VariableProfileExists($Name)) {
				IPS_CreateVariableProfile($Name, 2);
			} else {
				$profile = IPS_GetVariableProfile($Name);
				if($profile['ProfileType'] != 2)
					throw new Exception("Variable profile type does not match for profile ".$Name);
			}
			IPS_SetVariableProfileIcon($Name, $Icon);
			IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
			IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
      IPS_SetVariableProfileDigits($Name, 2);
		}
		protected function RegisterProfileBoolean($Name, $Icon) {
			if(!IPS_VariableProfileExists($Name)) {
				IPS_CreateVariableProfile($Name, 0);
			} else {
				$profile = IPS_GetVariableProfile($Name);
				if($profile['ProfileType'] != 0)
					throw new Exception("Variable profile type does not match for profile ".$Name);
			}
			IPS_SetVariableProfileIcon($Name, $Icon);
      IPS_SetVariableProfileAssociation("$Name", 1, "Lueften erlaubt", "", 0x00FF00);
      IPS_SetVariableProfileAssociation("$Name", 0, "Lueften verboten", "", 0xFF0000);
		}



		}
?>
