<?php
	define("DEF_PASS_FILE",dirname(__FILE__)."/htpasswd");
    function http_authenticate($user,$pass,$pass_file=DEF_PASS_FILE,$crypt_type='DES'){
        if(!ctype_alnum($user)) return false;
        if(!ctype_alnum($pass)) return false;
        
        // get the information from the htpasswd file
		if(file_exists($pass_file) && is_readable($pass_file)){
			// the password file exists, open it
			if($fp=fopen($pass_file,'r')){
				while($line=fgets($fp)){
					// for each line in the file remove line endings
					$line=preg_replace('`[\r\n]$`','',$line);
					list($fuser,$fpass)=explode(':',$line);
					if($fuser==$user){ // the submitted user name matches this line in the file
						switch($crypt_type){
							case 'DES':
								// the salt is the first 2 characters for DES encryption
								$salt=substr($fpass,0,2);
								// use the salt to encode the submitted password
								$test_pw=crypt($pass,$salt);
								break;
							case 'PLAIN':
								$test_pw=$pass;
								break;
							case 'SHA':
							case 'MD5':
							default:
								// unsupported crypt type
								fclose($fp);
								return FALSE;
						}
						if($test_pw == $fpass){
							// authentication success.
							fclose($fp);
							return true;
						} else return false;
                    }
                }
            } else return false; // could not open the password file
        }else return false;
	}
?>
