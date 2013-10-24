<?php
	/**
     * Array containing the valid file types
     * @var  array
     * @access   global
     */
     $GLOBALS['VALID_TYPES'] = 
     			array('jpg', 'jpeg', 'png', 'doc', 'txt', 'gif',
				      'rtf', 'pdf', 'xls', 'xlsx', 'tif',
				      'rar', 'tar', 'zip', 'tgz', 'gz');

/**
* Upload files to the server
*
* @author      Andrey Yasinecky <andrey@yasinecky.com>
* @version     1.0
*/
class Upload
{

	/**
     * Max file size
     * @var  	 integer
     * @access   private
     */
	//var $max_file_size = 1572864; //1,5 Mb
	var $max_file_size = 8388608; // 8,0 Mb
	
	/**
     * Max image width
     * @var  	 integer
     * @access   private
     */
	var $max_image_width = 1024; //pixels
	
	/**
	 * an array which contains uploaded files
	 * @var array
	 * @access private
	 */
	var $_files = array();

	/**
	 * path to files's icons folder
	 * @var string
	 * @access private
	 */
	var $icons_path = 'ico/';

	/**
     * Class constructor
     * @param    integer    $file_size    max file size
     * @param    string     $target_dir   dir to upload
     * @access   public
     */

	
	// }}}
    // {{{ setMaxWidth()

    /**
     * Setting max image width
     */
    function setMaxWidth($width)
    {
    	$this->max_image_width = $width;
    }
    

    // }}}
    // {{{ setMaxSize()

    /**
     * Setting max file size
     */
    function setMaxSize($size)
    {
    	$this->max_file_size = $size;
    }


    // }}}
    // {{{ checkDir()

    /**
     * Check destination dir. If dir not exists, create new one.
     *
     * @param string  $dir  destination folder
     * @return absolute path to dir
     * @access  public
     */
	function checkDir($dir)
	{
	    $dir = preg_replace("/(.*)(\/)$/","\\1", $dir);
		$absolute_dir = $_SERVER['DOCUMENT_ROOT'].''.$dir;
		if (!is_dir($absolute_dir)) {
			mkdir($absolute_dir, 0777);
			//eval("chmod(\"".$absolute_dir."\", 0777);"); // if php script has special rights to execute chmod
			chmod($absolute_dir, 0777);
		}

		return $absolute_dir;
	} // end checkDir()


	// }}}
    // {{{ checkGd()

    /**
     * Check GD library version.
     *
     * @return  GD version or false
     * @access  public
     */
	function checkGd()
	{
		$gd_content = get_extension_funcs('gd'); // Grab function list
		if (!$gd_content) { 
			$this->message_err('Upload class: GD libarary is not installed!', '', '', E_USER_ERROR); 
			
			return false; 
		} else {
			ob_start();
			phpinfo(8);
			$buffer = ob_get_contents();
			ob_end_clean(); 

			if (strpos($buffer, '2.0')) {
			    return 'gd2';
			} else {
			    return 'gd';
			}
		}
	}	
	

	// }}}
    // {{{ deleteFile()

    /**
     * Delete file from server
     *
     * @param string  $filename  filename
     * @param string  $source_dir   the dir in which is file
     * @return bool true|falses
     * @access  public
     */
	function deleteFile($filename, $source_dir)
	{
	    $source_dir = $this->checkDir($source_dir);
		if (file_exists($source_dir.'/'.$filename)) {
			if (unlink($source_dir.'/'.$filename)) {
				return true;
			}
		}
		return false;
	}


	// }}}
    // {{{ lantinEncode()

    /**
     * Encode string from russian to latin
     *
     * @param string  $str
     * @return string $new_string
     * @access  public
     */
	function lantinEncode($str)
	{
	    $accordance = array('a','b','v','g','d','e','zh','z','i','y','k','l','m','n','o','p',
							'r','s','t','u','f','h','ch','c','sh','sh','','i','','e','u','ya');
		$cirilyc = array('a','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï',
						 'ð','ñ','ò','ó','ô','õ','÷','ö','ø','ù','ú','û','ü','ý','þ','ÿ');
		
		$new_str = '';
		for ($i=0; $i<strlen($str); $i++) {
		    $symbol = substr($str, $i, 1);
			for($j=0; $j<count($cirilyc); $j++) {
			    if($symbol == $cirilyc[$j]) {
				    $symbol = $accordance[$j];
				}
			}
			$new_str.= $symbol;
		}
		
		return $new_str;
	}


    // }}}
	// {{{ uploadFile()
	
	/**
	 * Upload file to server
	 * 
	 * @param string $target_dir destination dir
	 * @param string $encode encode type
	 * @param string $name_length filename length
	 * @access public
	 */ 	
	function uploadFile($target_dir = '', $encode = 'md5', $name_length = 10)
	{
	    $target_dir = $this->checkDir($target_dir); //print_r($_FILES); exit;
	    foreach ($_FILES as $varname => $array) {
		    if (!empty($array['name'])) {
			    if (is_uploaded_file($array['tmp_name'])) {
				    $filename = strtolower(str_replace(' ', '', $array['name']));
					$basefilename = preg_replace("/(.*)\.([^.]+)$/","\\1", $filename);
					$ext = preg_replace("/.*\.([^.]+)$/","\\1", $filename);
					
					if ($array['size'] > $this->max_file_size) {
					    //$this->message_err('Upload class: Impossible upload file size', '', '', E_USER_WARNING);
					    $this->message_err('Upload class: file troppo grande', '', '', E_USER_WARNING);
					
					} elseif (!in_array($ext, $GLOBALS['VALID_TYPES'])) {
					    //$this->message_err('Upload class: Impossible upload file type', '', '', E_USER_WARNING);
					    $this->message_err('Upload class: tipo di file non supportato', '', '', E_USER_WARNING);
					
					} else {
					    // encode filename
					    switch ($encode) {
						case 'md5':
						    $basefilename = substr(md5($basefilename), 0, $name_length);
							$filename = $basefilename.'.'.$ext;
						    break;
							
						case 'latin':
						    $basefilename = substr($this->lantinEncode($basefilename), 0, $name_length);
							$filename = $basefilename.'.'.$ext;
						    break;
						}
						
						if (!move_uploaded_file($array['tmp_name'], $target_dir.'/'.$filename)) {
						    //$this->message_err('Upload class: cannot upload file', '', '', E_USER_ERROR);
						    $this->message_err('Upload class: upload file fallito', '', '', E_USER_ERROR);
						}
						
						$this->_files[$varname] = $filename;
					}
				} else {
				    $this->message_err('Upload class: cannot create tmp file', '', '', E_USER_ERROR);
				}
			}
		}
	}
	
	
	// }}}
	// {{{ imgResize()
	
	/**
	 * Resize image on the server
	 * 
	 * @param string $filename the filename of image
	 * @param string $source_dir the dir where this image stored
	 * @param string $dest_width destination image width
	 * @param bool $duplicate set true if you want to duplicate image 
	 * @return bool true|false
	 * @access public
	 */ 	
	function imgResize($filename, $source_dir, $dest_width, $duplicate = false)
	{
	    $source_dir = $this->checkDir($source_dir);
		$full_path = $source_dir.'/'.$filename;
		$basefilename = preg_replace("/(.*)\.([^.]+)$/","\\1", $filename);
		$ext = preg_replace("/.*\.([^.]+)$/","\\1", $filename);

		switch ($ext) {
		case 'png':
		    $image = imagecreatefrompng($full_path);
		    break;
		
		case 'jpg':
		    $image = imagecreatefromjpeg($full_path);
		    break;
		
		case 'jpeg':
		    $image = imagecreatefromjpeg($full_path);
		    break;
		
		default:
		    $this->message_err('Upload class: the '.$ext.' format is not allowed in your GD version', '', '', E_USER_ERROR);
			break;
		}

		$image_width = imagesx($image);
    	$image_height = imagesy($image);
		
		// resize image pro rata
		$coefficient = ($image_width > $this->max_image_width) ? (real)($this->max_image_width / $image_width) : 1;
		$dest_width = (int)($image_width * $coefficient);
		$dest_height = (int)($image_height * $coefficient);

		// create copy of original image and next working with copy.
		// an original image still old
		if (false !== $duplicate) {
		    $filename = $basefilename.'_2.'.$ext;
		    copy($full_path, $source_dir.'/'.$filename);
		}
		
		if ('gd2' == $this->checkGd()) { 
		    $img_id = imagecreatetruecolor($dest_width, $dest_height);
    		imagecopyresampled($img_id, $image, 0, 0, 0, 0, $dest_width + 1, $dest_height + 1, $image_width, $image_height);
		} else {
		    $img_id = imagecreate($dest_width, $dest_height);
    		imagecopyresized($img_id, $image, 0, 0, 0, 0, $dest_width + 1, $dest_height + 1, $image_width, $image_height);
		}

        switch ($ext) {
		case 'png':
		    imagepng($img_id, $source_dir.'/'.$filename);
		    break;
		
		case 'jpg':
		    imagejpeg($img_id, $source_dir.'/'.$filename);
		    break;
		
		case 'jpeg':
		    imagejpeg($img_id, $source_dir.'/'.$filename);
		    break;
        }
		
		imagedestroy($img_id);
		
		return true;
	}

	
	// }}}
    // {{{ displayFiles()

    /**
     * Display files in destination folder 
     * @param $source_dir the dir where files stored
     * @access  public
     */
	function displayFiles($source_dir)
	{
	    $source_dir = $this->checkDir($source_dir);
		if ($contents = opendir($source_dir)) {
		    echo 'Directory contents:<br>';
			
			while (false !== ($file = readdir($contents))) {
			    if (is_dir($file)) continue;

			    $filesize = (real)(filesize($source_dir.'/'.$file) / 1024);
				$filesize = number_format($filesize, 3, ',', ' ');

			    $ext = preg_replace("/.*\.([^.]+)$/","\\1", $file);
			    echo '<p><img src="'.$this->icons_path.''.$ext.'.gif">&nbsp;'.$file.'&nbsp;('.$filesize.') Kb</p>';
			}
		}
	}

	
	// }}}
    // {{{ fileRename()

    /**
     * Rename target file
     * @param $source_dir the dir where files stored
	 * @param $filename the source filename
	 * @param $newname new filename
     * @access  public
     */
	function fileRename($source_dir, $filename, $newname)
	{
	    $source_dir = $this->checkDir($source_dir);
		//if (rename($source_dir.'/'.$filename, $newname)) {
		if (rename($source_dir.'/'.$filename, $source_dir.'/'.$newname)) {
		    return true;
		} else {
		    return false;
		}
	}



	// }}}
    // {{{ message_err()

    /**
     * Trigger error
	 */
	function message_err($error_msg, $err_line, $err_file, $error_type = E_USER_WARNING)
    {
	    $error_msg.= (!empty($err_line)) ? 'on line '.$err_line : '';
		$error_msg.= (!empty($err_file)) ? 'in - '.$err_file : '';
		
        //trigger_error($error_msg, $error_type);
        die($error_msg);
    }

	
}
?>
