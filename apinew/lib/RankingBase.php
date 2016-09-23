<?php
namespace Ranking;

class RankingBase{

	public function guid()
	{
		$host = "10.35.28.39";
		$port = 8888;
		$msg = "0000000000000004guid";
		
		$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
		$result = socket_connect($socket,$host,$port);
		
		socket_write($socket,$msg,strlen($msg)+1);
		$str = socket_read($socket,100);
		$guid = substr($str,17);
		if(empty($str))
		{
			echo "Empty guid ! from $host : $port";
			exit;
		}
		#echo htmlspecialchars($guid);
		#echo "<br>";
		return substr($guid,6,-7);
	}
}		
