<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_edit
{		
	var $CI;
	var $mimes;
	
	// ------------------------------------------------------------------------
	
	public function __construct()
	{
		$this->CI = &get_instance();	
	}	
	
	// ------------------------------------------------------------------------
	
	public function imagecreatetruecolor( $width, $height, $fill_bg = FALSE ) 
	{
		$img = imagecreatetruecolor($width, $height);
		if ( is_resource($img)  ) 
		{
            if ( $fill_bg )
            {
                $white = imagecolorallocate($img, 255, 255, 255);
                imagefill($img, 0, 0, $white);
            }
            else
            {
                if ( function_exists('imagealphablending') && function_exists('imagesavealpha') )
                {
                    imagealphablending($img, FALSE);
                    imagesavealpha($img, TRUE);
                }
            }
			return $img;
		}	
		return FALSE;	
	}

	// ------------------------------------------------------------------------
	
	function _get_mime_by_ext($ext)
	{			
		$ext = trim($ext, '.');		
		$ext = strtolower($ext);
		switch ($ext) 
		{
			case 'gif': return 'image/gif';
			case 'jpeg':
			case 'jpg' :
			case 'jpe' : return 'image/jpeg';
			case 'png' : return 'image/png';
 		} 
 		
 		return FALSE;
	}
	
	function _get_mime($path)
	{
		$size = @getimagesize($path);
		return (isset($size['mime'])) ? $size['mime'] : FALSE;
	}
	
	// ------------------------------------------------------------------------
		
	public function load_image_to_edit( $path, $mime_type = '', $external_link = FALSE ) 
	{
		if ( !$external_link && !file_exists($path) ) return FALSE;
		
		if ($mime_type == '')
		{
			$mime_type = $this->_get_mime($path);
			if ($mime_type === FALSE) return FALSE;
		}
		
		switch ( $mime_type ) 
		{
			case 'image/jpeg':
                ini_set('gd.jpeg_ignore_warning', 1);
				$image = @imagecreatefromjpeg($path);
                if (!is_resource($image))
                    $image = $this->_load_image_to_edit_retry($path);
				break;
			case 'image/png':
				$image = @imagecreatefrompng($path);
                if (!is_resource($image))
                    $image = $this->_load_image_to_edit_retry($path);
				break;
			case 'image/gif':
				$image = @imagecreatefromgif($path);
                if (!is_resource($image))
                    $image = $this->_load_image_to_edit_retry($path);
				break;
			default:
				$image = FALSE;
				break;
		}
		
		if ( is_resource($image) ) 
		{			
			if ( function_exists('imagealphablending') && function_exists('imagesavealpha') ) 
			{
				imagealphablending($image, FALSE);
				imagesavealpha($image, TRUE);
			}
		}
		
		return $image;
	}
    
    private function _load_image_to_edit_retry( $path )
    {
        $mime_type = $this->_get_mime($path);
        if ($mime_type === FALSE) return FALSE;
        
        switch ( $mime_type ) 
        {
            case 'image/jpeg':
                ini_set('gd.jpeg_ignore_warning', 1);
                $image = @imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $image = @imagecreatefrompng($path);
                break;
            case 'image/gif':
                $image = @imagecreatefromgif($path);
                break;
            default:
                $image = FALSE;
                break;
        }
        
        return $image;
    }
    
	// ------------------------------------------------------------------------
	
	public function constrain_dimensions( $current_width, $current_height, $max_width=0, $max_height=0 ) 
	{
		if ( !$max_width and !$max_height )
			return array( $current_width, $current_height );
	
		$width_ratio = $height_ratio = 1.0;
		$did_width = $did_height = FALSE;
	
		if ( $max_width > 0 && $current_width > 0 && $current_width > $max_width ) 
		{
			$width_ratio = $max_width / $current_width;
			$did_width = TRUE;
		}
	
		if ( $max_height > 0 && $current_height > 0 && $current_height > $max_height ) 
		{
			$height_ratio = $max_height / $current_height;
			$did_height = TRUE;
		}
	
		// Calculate the larger/smaller ratios
		$smaller_ratio = min( $width_ratio, $height_ratio );
		$larger_ratio  = max( $width_ratio, $height_ratio );
	
		if ( intval( $current_width * $larger_ratio ) > $max_width || intval( $current_height * $larger_ratio ) > $max_height )
	 		// The larger ratio is too big. It would result in an overflow.
			$ratio = $smaller_ratio;
		else
			// The larger ratio fits, and is likely to be a more "snug" fit.
			$ratio = $larger_ratio;
	
		$w = intval( $current_width  * $ratio );
		$h = intval( $current_height * $ratio );
	
		// Sometimes, due to rounding, we'll end up with a result like this: 465x700 in a 177x177 box is 117x176... a pixel short
		// We also have issues with recursive calls resulting in an ever-changing result. Contraining to the result of a constraint should yield the original result.
		// Thus we look for dimensions that are one pixel shy of the max value and bump them up
		if ( $did_width && $w == $max_width - 1 )
			$w = $max_width; // Round it up
		if ( $did_height && $h == $max_height - 1 )
			$h = $max_height; // Round it up
	
		return array( $w, $h );
	}
	
	// ------------------------------------------------------------------------
	
	public function image_resize_dimensions($orig_w, $orig_h, $dest_w, $dest_h, $crop = FALSE, $orig_type = '', $image = NULL, $external_link = FALSE) 
	{	
		if ($orig_w <= 0 || $orig_h <= 0)
			return FALSE;
		// at least one of dest_w or dest_h must be specific
		if ($dest_w <= 0 && $dest_h <= 0)
			return FALSE;
	
		if ( $crop ) 
		{
			// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
			$aspect_ratio = $orig_w / $orig_h;
			$new_w = min($dest_w, $orig_w);
			$new_h = min($dest_h, $orig_h);
	
			if ( !$new_w ) 
			{
				$new_w = intval($new_h * $aspect_ratio);
			}
	
			if ( !$new_h ) 
			{
				$new_h = intval($new_w / $aspect_ratio);
			}
	
			$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
	
			$crop_w = round($new_w / $size_ratio);
			$crop_h = round($new_h / $size_ratio);
	
			$s_x = floor( ($orig_w - $crop_w) / 2 );
			$s_y = floor( ($orig_h - $crop_h) / 2 );
		} else {
			// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
			$crop_w = $orig_w;
			$crop_h = $orig_h;
	
			$s_x = 0;
			$s_y = 0;
	
			list( $new_w, $new_h ) = $this->constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
		}
	
		// if the resulting image would be the same size or larger we don't want to resize it
		if ( $new_w >= $orig_w && $new_h >= $orig_h )
			return FALSE;
	
		// the return array matches the parameters to imagecopyresampled()
		// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h, $orig_type, $image, $external_link );
	
	}

	// ------------------------------------------------------------------------

    /*
     * Rename an image with a full path to a new name in the same directory.
     */
    function rename_image($current_full_path, $new_name) {
        $info = pathinfo($current_full_path);
        $dir = $info['dirname'];
        $ext = $info['extension'];
        $new_path = $dir.'/'.$new_name.".$ext";
        return rename($current_full_path, $new_path)? $new_path : FALSE;
    }
	
	public function get_dims($file, $max_w, $max_h, $crop = FALSE)
	{
		$external_link = (substr($file, 0, 7) == 'http://') ? TRUE : FALSE;
		if (!$external_link) $external_link = (substr($file, 0, 8) == 'https://') ? TRUE : FALSE;
	
		$image = $this->load_image_to_edit( $file, '', $external_link );
		
		if ( !is_resource( $image ) )
			return FALSE;
	
		$size = @getimagesize( $file );
		
		if ( !$size )
			return FALSE;
			
		list($orig_w, $orig_h, $orig_type) = $size;
        
        if ($external_link && $orig_w <= $max_w && $orig_h <= $max_h)
            return array(0, 0, 0, 0, $orig_w, $orig_h, $orig_w, $orig_h, $orig_type, $image, $external_link);
        else
            return $this->image_resize_dimensions( $orig_w, $orig_h, $max_w, $max_h, $crop, $orig_type, $image, $external_link );
	}
	
	public function resize_image( $file, $max_w, $max_h, $crop = FALSE, $suffix = NULL, $dims = NULL, $dest_path = NULL, $return_resource = FALSE, $quality = 90 ) 
	{ 
		if ($dims == NULL)
			$dims = $this->get_dims($file, $max_w, $max_h, $crop);
		list($dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h, $orig_type, $image, $external_link) = $dims;
		
		if ( !$dims )
		{
			if (!$external_link)
				return FALSE;
			$newimage =& $image;
		}
		else
		{
            $fill_bg = IMAGETYPE_PNG == $orig_type && $external_link;
			$newimage = $this->imagecreatetruecolor( $dst_w, $dst_h, $fill_bg );
		
			imagecopyresampled( $newimage, $image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		
			// convert from full colors to index colors, like original PNG.
			if ( IMAGETYPE_PNG == $orig_type && function_exists('imageistruecolor') && !imageistruecolor( $image ) )
				imagetruecolortopalette( $newimage, false, imagecolorstotal( $image ) );
		
			// we don't need the original in memory anymore
			imagedestroy( $image );
		}
	
		// $suffix will be appended to the destination filename, just before the extension
		if ( !$suffix )
			$suffix = "{$dst_w}x{$dst_h}";
	
		$info = pathinfo($file);
		if ($external_link)
        {
            $this->CI->load->config('upload', TRUE);
			//$dir = $this->CI->config->item('external_path', 'upload') . date('W');
			$dir = $this->CI->config->item('external_path', 'upload').date('Y').'/'.date('m').'/'.date('d').'/';
        }
		else
			$dir = rtrim($info['dirname'], '/');
		$ext = $info['extension'];
		
		if ( !is_null($dest_path) and $_dest_path = realpath($dest_path) )
			$dir = $_dest_path;
			
		if ($external_link)
			$destfilename = "{$dir}/{$suffix}.";
		else
			$destfilename = "{$dir}/{$suffix}_{$dst_w}x{$dst_h}.";
	
		// if ( IMAGETYPE_GIF == $orig_type ) 
		// {
			// $mime_type = 'image/gif';
			// $destfilename .= 'gif';
			// if ( !imagegif( $newimage, $destfilename ) )
				// return FALSE;
		// } 
		// else
        if ( IMAGETYPE_PNG == $orig_type ) 
		{
			$mime_type = 'image/png';
            if ($external_link || (strcasecmp($ext, 'jpg') != 0 && strcasecmp($ext, 'jpeg') != 0 && strcasecmp($ext, 'png') != 0))
                $ext = 'png';
			$destfilename .= $ext;
			if ( !file_exists($destfilename) && !imagepng( $newimage, $destfilename, (10 - $quality / 10) ) )
				return FALSE;
		} 
		else // if ( IMAGETYPE_JPEG == $orig_type ) 
		{
			$mime_type = 'image/jpeg';
            if ($external_link || (strcasecmp($ext, 'jpg') != 0 && strcasecmp($ext, 'jpeg') != 0 && strcasecmp($ext, 'png') != 0))
                $ext = 'jpg';
			$destfilename .= $ext;
			if ( !file_exists($destfilename) && !imagejpeg( $newimage, $destfilename, $quality ) )
				return FALSE;
		}
		// else 
		// {
			// all other formats are converted to jpg
			// $mime_type = 'image/jpeg';
			// $destfilename .= 'jpg';
			// if ( !imagejpeg( $newimage, $destfilename, $quality ) )
				// return FALSE;
		// }
		
		// Set correct file permissions
		$stat = stat( dirname( $destfilename ));
		$perms = $stat['mode'] & 0000666; //same permissions as parent folder, strip off the executable bits
		@ chmod( $destfilename, $perms );
	
		if ($return_resource)
			return array('image' => $newimage, 'mime' => $mime_type);
		else
			return $destfilename;
	}	
	
	// ------------------------------------------------------------------------
	
	public function scale_image_resouce($img, $fwidth, $fheight) 
	{
		$sX = imagesx($img);
		$sY = imagesy($img);
		
		// check if it has roughly the same w / h ratio
		$diff = round($sX / $sY, 2) - round($fwidth / $fheight, 2);
		
		if ( -0.1 < $diff && $diff < 0.1 ) {
			// scale the full size image
			$dst = $this->imagecreatetruecolor($fwidth, $fheight);
			
			if ( imagecopyresampled( $dst, $img, 0, 0, 0, 0, $fwidth, $fheight, $sX, $sY ) ) 
			{
				imagedestroy($img);
				$img = $dst;
				return $img;
			}
		}		
		
		return FALSE;
	}
	
	// ------------------------------------------------------------------------
	
	function image_display($resource, $mime_type, $source_image = '' )
	{		
		if ($source_image != '')
			header("Content-Disposition: filename={$source_image};");
			
		header("Content-Type: {$mime_type}");
		header('Content-Transfer-Encoding: binary');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
		
		switch ($mime_type)
		{
			case 'image/jpeg':
				header('Content-Type: image/jpeg');
				return imagejpeg($resource, null, 90);
			case 'image/png':
				header('Content-Type: image/png');
				return imagepng($resource);
			case 'image/gif':
				header('Content-Type: image/gif');
				return imagegif($resource);
			default:
				return FALSE;
		}
	}
	
	// ------------------------------------------------------------------------
	
	public function save_image_file($image, $path, $mime_type = '' ) 
	{		
		if ($mime_type == '')
		{
			$file_ext = end( explode('.', $path) );
			if ( FALSE == ($mime_type = $this->_get_mime_by_ext($file_ext) ) ) 
				return FALSE;		
		}				
				
		switch ( $mime_type ) 
		{
			case 'image/jpeg':
				return imagejpeg( $image, $path, 90 ); break;
			case 'image/png':
				return imagepng($image, $path); break;
			case 'image/gif':
				return imagegif($image, $path); break;					
		}		
		
		return FALSE;
	}
		
	// ------------------------------------------------------------------------	
	
	public function rotate_image_resource($img, $angle) 
	{
		if ( function_exists('imagerotate') ) 
		{
			$rotated = imagerotate($img, $angle, 0);
			if ( is_resource($rotated) ) 
			{
				imagedestroy($img);
				$img = $rotated;
			}
		}
		return $img;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * crop_image_resource	
	 * Cat hinh tu toa do $x, $y chieu dai $w va rong $h.
	 * @param Resource $img
	 * @param int $x
	 * @param int $y
	 * @param int $w
	 * @param int $h
	 */
	public function crop_image_resource($img, $x, $y, $w, $h) 
	{
		$dst = $this->imagecreatetruecolor($w, $h);
		if ( is_resource($dst) ) 
		{
			if ( imagecopy($dst, $img, 0, 0, $x, $y, $w, $h) ) 
			{
				imagedestroy($img);
				$img = $dst;
			}
		}
		return $img;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * flip_image_resource
	 * Lap hinh
	 * @param Resource $img
	 * @param bool $horz
	 * @param bool $vert
	 */
	public function flip_image_resource($img, $horz, $vert) 
	{
		$w = imagesx($img);
		$h = imagesy($img);
		$dst = $this->imagecreatetruecolor($w, $h);
		
		if ( is_resource($dst) ) 
		{
			$sx = $vert ? ($w - 1) : 0;
			$sy = $horz ? ($h - 1) : 0;
			$sw = $vert ? -$w : $w;
			$sh = $horz ? -$h : $h;
	
			if ( imagecopyresampled($dst, $img, 0, 0, $sx, $sy, $w, $h, $sw, $sh) ) 
			{
				imagedestroy($img);
				$img = $dst;
			}
		}
		
		return $img;
	}
}