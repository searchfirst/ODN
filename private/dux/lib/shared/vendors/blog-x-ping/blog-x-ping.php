<?php 
/*  blog-x-ping
		http://system-x.info/?pageid=26&menutree=61
			A lightweight yet powerful library to automatically ping blog
			aggregator services to notify them of updated syndication	feeds,
			using Weblogs.Com XML-RPC compatible pinging services.
		                   
  Copyright (C) 2007  Daniel G. Davies
  
  This library is free software; you can redistribute it and/or
  modify it under the terms of the GNU Lesser General Public
  License as published by the Free Software Foundation; either
  version 2.1 of the License, or (at your option) any later version.
  
  This library is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
  Lesser General Public License for more details.
  
  You should have received a copy of the GNU Lesser General Public
  License along with this library; if not, write to the Free Software
  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
  
  Daniel Davies can be contacted via the web form at 
  http://system-x.info/?pageid=10&menutree=34,35
*/

function log_ping($result,$loglevel,$logfile) {
	if ($loglevel!='none') {
		ignore_user_abort(true);    ## prevent refresh from aborting file operations and hosing file
		clearstatcache();
		$fp = fopen($logfile, 'a+');
		flock($fp, 1);

		if ($loglevel=='short') {
			$message=$result['short'];
		} else if ($loglevel=='detailed'){
			$message=$result['short']."\n".$result['detailed'];
		} else if ($loglevel=='verbose') {
			$message=$result['short']."\n".$result['detailed']."\n".$result['verbose'];
		};
		fputs($fp, $message."\n");
		flock($fp, 3);
		fclose($fp);
		ignore_user_abort(false);    ## put things back to normal		
	};
	return true;

};

// ping for server that supports the weblogs.com xml-rpc interface.

function ping($host, $port, $path, $method, $blogname, $blogurl, $changesurl='', $feedurl='', $timeout=16) {
	
	$client = new xmlrpc_client($path, $host, $port);
	if (strstr($method,'extendedPing') and strlen(trim($changesurl)) and strlen(trim($feedurl))) {
		$args=array(new xmlrpcval($blogname, 'string'),	new xmlrpcval($blogurl, 'string'), new xmlrpcval($changesurl, 'string'),	new xmlrpcval($feedurl, 'string'));
		$result['detailed']=$host.':'.$port.$path.' - '.$method.'("'.$blogname.'", "'.$blogurl.'", "'.$changesurl.'", "'.$feedurl.'")';
	} else {
		$args=array(new xmlrpcval($blogname, 'string'),	new xmlrpcval($blogurl, 'string'));
		$result['detailed']=$host.':'.$port.$path.' - '.$method.'("'.$blogname.'", "'.$blogurl.'")';
	};
	$response = $client->send(new xmlrpcmsg($method,$args), $timeout);

	$result['verbose']='';
	if (!$response) {
		$result['result']=false;
		$result['detailed'].="\n".'Error : '.$client->errno.' - '.$client->errstring;
		return $result;
	};
	if ($response->faultCode() != 0)  {
		$result['result']=false;
		$result['detailed'].="\n".'Error : '.$response->faultCode().' - '.$response->faultString();
		return $result;
	};
	$value=$response->value();
	$fl_error = $value->structmem('flerror');
	$message = $value->structmem('message');
	if (is_object($fl_error) and ($fl_error->kindof() != 'undef') and $fl_error->scalarval() != false) {
		$result['result']=false;
		$result['detailed'].="\n".'Error : '.$host.' - '.$message->scalarval();
		$result['message'] = $message->scalarval();
		return $result;
	} else if (is_object($message) and ($message->kindof() != 'undef'))	 {
		$result['message'] = $message->scalarval();
		$result['detailed'].="\n".'Success : '.$host.' - '.$message->scalarval();
	} else if (!is_object($fl_error) and !is_object($message)){
		$result['result']=false;
		$result['detailed'].="\n".'Error : '.$host.' - server did not return a valid object';
		return $result;
	};

	$result['verbose'] = $value->serialize();
	$result['result']=true;
	return $result;
};

function do_ping($blogname,$blogurl,$changesurl,$feedurl,$serverlistfile='ping_services.csv',$loglevel='none',$logfile='ping.log', $display=false, $timeout=16) {

	$pingcount = 0;
	$tstimer = explode( ' ', microtime() );
	$tstimer = $tstimer[1] + $tstimer[0];
	$done_service=array();
	if ($display==true) {
		echo '<ol>';
	};

	$ping_services=file($serverlistfile);

	for ($x=0;$x<sizeof($ping_services);$x++) {
		if ((strlen(trim($ping_services[$x]))>0) and (substr($ping_services[$x],0,2)!='##')){
			unset($properties);
			if (strstr($ping_services[$x],',')) {
				$properties=explode(',',$ping_services[$x]);
			} else {
				$properties[0]=trim($ping_services[$x]);
			};

			if (!isset($properties[1])) {
				$pingmethod='weblogUpdates.ping';
			} else {
				$pingmethod=trim($properties[1]);
			};

			$values=parse_url($properties[0]);

			if (!isset($values['port']) or (strlen(trim($values['port'])==0))) {
				$values['port']=80;
			}

			$service=$values['host'].':'.$values['port'].$values['path'];

			if (!isset($done_service[$service])) {
				$done_service[$service]=true;
				$r1='Pinging - '.$values['host'];

				if ($display==true) {
					echo '<li>'.$r1;
				};

				$stimer = explode( ' ', microtime() );
				$stimer = $stimer[1] + $stimer[0];
				$pingresult=ping($values['host'],$values['port'],$values['path'],$pingmethod,$blogname,$blogurl, $changesurl, $feedurl, $timeout);
				$etimer = explode( ' ', microtime() );
				$etimer = $etimer[1] + $etimer[0];

				if ($pingresult['result']) {
					$r2=' - succeeded in - '.number_format(($etimer-$stimer),4).' seconds';
				} else {
					$r2=' - failed after - '.number_format(($etimer-$stimer),4).' seconds';
				};

				if (isset($pingresult['message']) and strlen(trim($pingresult['message']))) {
					$r2.=' - '.$pingresult['message'];
				};

				if ($display==true) {
					echo substr($r2,0,128).'</li>';
				};
				
				$pingresult['short']=$r1.$r2;
		
				log_ping($pingresult,$loglevel,$logfile);
				$pingcount++;
			};
		};
	};
	
	$tetimer = explode( ' ', microtime() );
	$tetimer = $tetimer[1] + $tetimer[0];

	$log['short']='pinged '.$pingcount.' services in '.number_format(($tetimer-$tstimer),4).' seconds'."\n";
	
	if ($display==true) {
		echo '</ol>';
		echo $log['short'];
	};

	log_ping($log,$loglevel,$logfile);
};
?>