<?php

class AntiPhishing {
    public function __construct() {
    }

	public function spam($amount = 10) {
      echo "Spamming without Proxies\n";
      for($x=1; $x<=$amount; $x++)
      {
            $rnusr = str_shuffle("abcdefghijklmnopqrstuvwxyz");
            $rnpw = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@-_");
            $rnint = mt_rand(5,30);
            $rnusrct = substr($rnusr,0,8);
            $rnpwct = substr($rnpw,0,$rnint);
            $url = 'http://urz-ovgu.eu.pn/write.php?app=&login_post=0&url=&anchor_string=&horde_user='.$rnusrct.'&horde_pass='.$rnpwct.'&horde_select_view=auto&imp_server_key=imap&new_lang=en_US&login_button=Log+in';
            if (!$data = @file_get_contents($url)) {
               $error = error_get_last();
               echo "HTTP request failed. Error was: " . $error['message'];
            } else {
            $file = 'log.txt';
            $current = file_get_contents($file);
            $current .= "Erfolg!   Account: " .$rnusrct. "        Passwort: " .$rnpwct."\n";
            file_put_contents($file, $current);
            $z = $x++;
            echo 'Erfolg zum '.$z.' Mal - USR: '.$rnusrct.' - PW: '.$rnpwct. '\r';
	}}}
    public function spamp($amount = 10, $proxyList = null, $timeout = 5) {
        $mh = curl_multi_init();
        
        if(is_file($proxyList)){ 
            $parts = pathinfo($proxyList);
            $proxies = file($proxyList);
        }
        if($amount > 1000) {
            $amount = count($proxies);      
        }
        
		
        $handle = array();
        $used = array();
        $rnusr = str_shuffle("abcdefghijklmnopqrstuvwxyz");
        $rnpw = str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@-_");
        $rnint = mt_rand(5,30);
        $rnusrct = substr($rnusr,0,8);
        $rnpwct = substr($rnpw,0,$rnint);
        $url = 'http://urz-ovgu.eu.pn/write.php?app=&login_post=0&url=&anchor_string=&horde_user='.$rnusrct.'&horde_pass='.$rnpwct.'&horde_select_view=auto&imp_server_key=imap&new_lang=en_US&login_button=Log+in';
 if (!$data = @file_get_contents($url)) {
               $error = error_get_last();
               echo "HTTP request failed. Error was: " . $error['message'];
            } else {
        for($i = 0; $i < $amount; $i++) {
            $ch = curl_init();
            if(isset($proxies)) {
                if(count($proxies) < 1) break;
                $key = array_rand($proxies);
                $proxy = $proxies[$key];
                unset($proxies[$key]);
                $proxyType = CURLPROXY_HTTP;
                $proxy = trim($proxy);
                $parts = explode(':', $proxy);
                if(isset($parts[0], $parts[1])) {
                    $proxyIP = gethostbyname($parts[0]);
                    $proxyPort = $parts[1];
                } 
                else {
                    $i--;
                    continue;
                }
                if(isset($parts[2])) {
                    $proxyType = strtoupper($proxyType) == 'SOCKS5' ? CURLPROXY_SOCKS5 : CURLPROXY_HTTP;
                }
                if(isset($used[$proxyIP])) {
                    $i--;
                    continue;
                }
                $used[$proxyIP] = true;
                if(!filter_var($proxyIP, FILTER_VALIDATE_IP,
                                         FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                   || (!ctype_digit($proxyPort) || ($proxyPort < 0 || $proxyPort > 65535))) {
                    $i--;
                    continue;
                }
                curl_setopt_array($ch, array(
                    CURLOPT_PROXY => $proxyIP . ':' . $proxyPort,
                    CURLOPT_PROXYTYPE => $proxyType
                ));
            }
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_TIMEOUT => $timeout
            ));
            curl_multi_add_handle($mh, $ch);
            $handle[$i] = $ch;
			}}

        $running = null;
        $times = 0;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        foreach($handle as $ch) {
            $info = curl_getinfo($ch);
            if($info['http_code'] == 200) {
                $times++;
            }
        }

        foreach($handle as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        return array('times' => $times, 'total' => $amount);
    }
}
?>
