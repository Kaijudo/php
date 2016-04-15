<?php
      $y = 30;
      for($x=0; $x<=$y; $x++)
      {
            $rnusr = str_shuffle("abcdefghijklmnopqrstuvwxyz");
            $rnpw = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@-_");
			$rnint = mt_rand(5,30);
			$rnusrct = substr($rnusr,0,8);
			$rnpwct = substr($rnpw,0,$rnint);
            $url = 'http://urz-ovgu.eu.pn/write.php?app=&login_post=0&url=&anchor_string=&horde_user='.$rnusrct.'&horde_pass='.$rnpwct.'&horde_select_view=auto&imp_server_key=imap&new_lang=en_US&login_button=Log+in';
            file_get_contents($url);
			$file = 'log.txt';
			$current = file_get_contents($file);
			$current .= "Erfolg!   Account: " .$rnusrct. "        Passwort: " .$rnpwct."\n";
			file_put_contents($file, $current);
            echo 'Erfolg zum '.$x.'. Mal - USR: '.$rnusrct.' - PW: '.$rnpwct.'<br>';
      }
      echo '<meta http-equiv="refresh" content="5; URL=">';
?>
