<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

define('IMG_COMPRESSION',75);

class SimpleImage {
 
   var $image;
   var $image_type;
   var $filepath;
	
	
	
   function load($filename) {
	  $filepath=$filename;
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
 
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $compression=IMG_COMPRESSION, $permissions=null) {
  $image_type = $this->image_type;
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }

   
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   } 
	
	/* Function by 3magine ( http://www.daniweb.com/members/506079/3magine ). Slightly modified. */
	/* upload_food_pic.php is complete. Complete the function below */
	function cropImage($dest_width, $dest_height,$do_centering=false)
	{
		$width = imagesx($this->image);
		$height = imagesy($this->image);

		$original_aspect = $width / $height;
		$thumb_aspect = $dest_width / $dest_height;
		
		if ( $original_aspect >= $thumb_aspect )
		{
		   // Too wide
		   $src_height = $height;
		   $src_width = $height * $thumb_aspect;
		   
		   $org_prop_resized_height=$dest_height;
		   $org_prop_resized_width=$dest_height*$original_aspect;
		}
		else
		{
		   // Too tall
		   $src_width = $width;
		   $src_height = $width / $thumb_aspect;
		   
		   $org_prop_resized_width=$dest_width;
		   $org_prop_resized_height=$dest_width/$original_aspect;
		}

		$thumb = imagecreatetruecolor( $dest_width, $dest_height );
		
		// Resize and crop
		if(!$do_centering)
		{
			imagecopyresampled($thumb,$this->image,0,0,0,0,$dest_width, $dest_height,$src_width, $src_height);
		}
		else
		{
			//Do a centered crop
			imagecopyresampled($thumb,$this->image,0,0,($width-$src_width)/2,($height-$src_height)/2,$dest_width, $dest_height,$src_width, $src_height);
		}
		
		$this->image=$thumb;
	}
 
}



?>