<?php
use Dto\ResutltDto as resutltDto;
use Ranking\Upload;

class ImagesController extends ControllerBase
{

	public function indexAction($uuid)
	{
		
		echo $uuid;
   
	}
	public function viewAction($uuid)
	{
		$Hashmap = new Hashmap();
		$Att = new Attachment();
		$Upload = new Upload($Hashmap,$Att);
		$Upload->view($uuid);

	}
	
	

	public function minAction($uuid,$size=50){
		#print_r($_SERVER);
		#exit;
		$this->ickAction($uuid,$size);
		/*
		$dir = "/snapshoot/$size";
		$file = "$dir/$uuid";
		
		$localfile = $_SERVER['DOCUMENT_ROOT']."$file";
		if(!$this->checkFile($localfile)){
			$this->writeFile($uuid,$localfile);
			$image = new Phalcon\Image\Adapter\Imagick($localfile);
			$image->resize($size,$size);
			if(!$image->save($localfile,100)){
			}

		}

		$this->setCompressionQuality($file,100);

		#$this->response->redirect($file);
		exit;
		*/

	}
	public function ickAction($uuid,$size=50){
		$dir = "/snapshoot/$size";
		$file = "$dir/$uuid";
		$p = $_SERVER['DOCUMENT_ROOT'].$file;
		$localfile = $_SERVER['DOCUMENT_ROOT']."$file";
		#$this->writeImageBlob($uuid,$p,80,$size);

		if(!$this->checkFile($localfile)){
			$this->writeImageBlob($uuid,$p,80,$size);
			#$this->writeFile($uuid,$localfile);
			#$image = new Phalcon\Image\Adapter\Imagick($localfile);
			#$image->resize($size,$size);
			#if(!$image->save($localfile,100)){
			#}

		}
		#echo $file;exit;
		#$this->setCompressionQuality($file,100);exit;

		$this->response->redirect($file);
		#exit;

	}



	private function setCompressionQuality($imagePath, $quality) {
		
		#$p = "/var/www/html/legal_mobile/Ranking/Source/CMS/api/public".$imagePath;
		$p = $_SERVER['DOCUMENT_ROOT'].$imagePath;
		#$backgroundImagick = new \Imagick(realpath($imagePath));
		$backgroundImagick = new \Imagick($p);
		#echo $backgroundImagick;exit;
		$imagick = new \Imagick();
		$imagick->setCompressionQuality($quality);
		$imagick->newPseudoImage(
			$backgroundImagick->getImageWidth(),
	   		 $backgroundImagick->getImageHeight(),
			'canvas:white'
		);

		$imagick->compositeImage(
			$backgroundImagick,
			\Imagick::COMPOSITE_ATOP,
			0,
			0
		);
	
		$imagick->setFormat("jpg");	
		header("Content-Type: image/jpg");
		echo $imagick->getImageBlob();
	}

	private function writeImageBlob($uuid,$f,$quality=80,$size=0){
		$uid = explode(".",$uuid)[0];
		$Hashmap = new Hashmap();
		$Att = new Attachment();
		$Upload = new Upload($Hashmap,$Att);
		$att = $Upload->get($uid);

		$imagick = new Imagick();
		$imagick->readImageBlob($att->fileBody);
		$imagick->setFormat("jpg");
		$imagick->stripImage();
		if($size>0){
			$imagick->thumbnailImage($size,$size,true);
		}
		#$imagick->gaussianBlurImage(0.05,1);
		$imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
		$imagick->setImageCompressionQuality($quality); 
		$imagick->writeImage($f);
		#echo $imagick->getImageBlob();
	

	}

	private function readImageBlob($uuid,$f,$quality=80){
		$uid = explode(".",$uuid)[0];
		$Hashmap = new Hashmap();
		$Att = new Attachment();
		$Upload = new Upload($Hashmap,$Att);
		$att = $Upload->get($uid);

		$imagick = new Imagick();
		#$imagick->setCompressionQuality($quality);
		#$imagick->setCompression(Imagick::COMPRESSION_JPEG);
		$imagick->setFormat("jpg");
		$imagick->readImageBlob($att->fileBody);
		$imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
		$imagick->setImageCompressionQuality($quality); 
		header("Content-Type: image/jpg");
		echo $imagick->getImageBlob();
		exit;
	

	}

	private function checkFile($f){
		if(!file_exists($f)){
			return False;
		}else{
			return True;
		}

	}

	private function writeFile($uuid,$f){
		$uid = explode(".",$uuid)[0];
		$Hashmap = new Hashmap();
		$Att = new Attachment();
		$Upload = new Upload($Hashmap,$Att);
		$att = $Upload->get($uid);
		#echo $f;
		$fp = fopen($f,"w+");
		#echo $fp;
		fwrite($fp,$att->fileBody);
		fclose($fp);
	
	}

	private function readFile($f){

	}

	private function viewBody($body,$f,$t,$updatetime="20160808"){
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', strtotime($updatetime)).' GMT', true, 200);
	   		header('Content-Length:'.strlen($body));
		header("Content-type:".$t);
		header('Content-Disposition: inline; filename="'.$f.'"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes'); 
		echo $body;

	}



}

