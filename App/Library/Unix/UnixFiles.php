<?php
//
//
//    22 - 07 - 2014
//  11-07-2013
//  30-04-2013
//					dsphinxX


	class Unixfile
	{

		static $_instance;
		private $info = array();

		// Μην ασχολήσε με τα παρακάτω directories
		static $dirs_do_not_scanned = array( // Not Show this files
			".DS_Store"            => 1,
			".AppleDouble"         => 2,
			".git"                 => 3,
			".AppleDesktop"        => 4,
			".AppleDB"             => 5,
			"Network Trash Folder" => 6,
			"Temporary Items"      => 7,
			"thumbs"               => 8,
			"thumbs.db"            => 9
		);


		// Μην ασχολήσε με τα παρακάτω αρχεια
		static $filess_do_not_scanned = array( // Not Show this files
			"thumbs.db" => 1
		);


		function __construct()
		{

			self::$_instance = $this;
		}

		public function OS_identify()
		{

			$os = strtoupper(PHP_OS);

			$osis = $os;

			if ($os == "DARWIN")
				$osis = "OSX";
			if (substr($os, 0, 3) == "WIN")
				$osis = "WIN";

			//		echo PHP_OS." OS = $osis <br>".php_uname("s");

			$this->info = array("os"      => $osis, //	exec('uname -o '),
								"version" => php_uname("r"), // exec('uname -r'),
								"name"    => php_uname("n") //exec('uname -n'),	// node name
			);

			return $this->info;
		}

		static function    desanitize($sanitizedname)
		{

			$sanitizedname = str_replace('_', ' ', $sanitizedname);
			$sanitizedname = str_replace('amp;', '&', $sanitizedname);

			return $sanitizedname;
		}


		static function sanitize($dangerous_filename, $platform = 'Unix')
		{

			$dangerous_filename = trim($dangerous_filename);
			if (in_array(strtolower($platform), array('unix', 'linux'))) {
				// our list of "dangerous characters", add/remove characters if necessary
				$dangerous_characters = array(" ", '"', "'", "&", "/", "\\", "?", "#");
			} else {
				// no OS matched? return the original filename then...
				return $dangerous_filename;
			}

			//	return mb_strtolower(str_replace($dangerous_characters, '_', $dangerous_filename), 'UTF-8');
			return str_replace($dangerous_characters, '_', $dangerous_filename);
		}


		// done
		static function del($file)
		{

			if (!is_file("$file") && !is_writable("$file"))
				$file = str_replace(' ', '\ ', $file);

			if (!(file_exists("$file") && unlink("$file")))
				trigger_error(" UnixFile error removing file [ $file ] :");

		}


		//  deltree done
		static function deltree($path)
		{

			// $flags =  FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS;
			if (!is_dir("$path")) {
				$path = str_replace(' ', '\ ', $path);
			}

			if (!is_dir("$path") && !is_writable("$path")) {
				echo " Directory $path cant be removed !";

				return FALSE;
			}

			$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("$path", FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

			foreach ($it as $file) {
				$file->isFile() ? self::del((string)$file) : NULL;
				$file->isDir() ? rmdir((string)$file) : trigger_error(" UnixFile error removing directory [ " . (string)$file . " ] :");
			}

			return rmdir("$path") ? TRUE : FALSE;
		}

		//  show size in human KB MB GiB
		static function show_human($a_bytes)
		{

			if ($a_bytes < 1024) {
				return $a_bytes . ' B';
			} elseif ($a_bytes < 1048576) {
				return round($a_bytes / 1024, 2) . ' KB';
			} elseif ($a_bytes < 1073741824) {
				return round($a_bytes / 1048576, 2) . ' MB';
			} elseif ($a_bytes < 1099511627776) {
				return round($a_bytes / 1073741824, 2) . ' GB';
			} elseif ($a_bytes < 1125899906842624) {
				return round($a_bytes / 1099511627776, 2) . ' TB';
			} elseif ($a_bytes < 1152921504606846976) {
				return round($a_bytes / 1125899906842624, 2) . ' PB';
			} elseif ($a_bytes < 1180591620717411303424) {
				return round($a_bytes / 1152921504606846976, 2) . ' EB';
			} elseif ($a_bytes < 1208925819614629174706176) {
				return round($a_bytes / 1180591620717411303424, 2) . ' Zi';
			} else {
				return round($a_bytes / 1208925819614629174706176, 2) . ' YB';
			}
		}

		// done
		static function create_dir_writeable($path, $perms = "0777")
		{

			$old = umask(0);
			$ret = FALSE;

			if (!mkdir("$path", 0777) && !chmod("$path", 0777)) {
				trigger_error(" UnixFile error creating writeable dir  file [ $path ] chmod 7777  :");
			}

			umask($old);
			if (is_writable("$path"))
				$ret = TRUE;

			return $ret;
		}


		/**
		 *   create file
		 *
		 * @param        $path
		 * @param string $perms
		 *
		 * @return bool
		 */
		static function create_file($path, $contents = NULL, $perms = "0777")
		{

			$old = umask(0);
			$ret = TRUE;

			$urlh = @fopen($path, 'w');
			if (!$urlh && !chmod("$path", $perms)) {
				trigger_error(" UnixFile error creating file     [ $path ] chmod $perms  :");
				$ret = FALSE;
			}

			if ($contents) {
				if (fwrite($urlh, $contents) === FALSE) {
					trigger_error(" Cannot write to file       [ $path ] chmod $perms  :");
					$ret = FALSE;
				}
			}


			fclose($urlh);
			umask($old);

			return $ret;
		}



		static function getRecursiveFiles($dir, $fullpath = false)
		{

			$ignore = array(".","..");
			foreach (self::$dirs_do_not_scanned as $key=>$val){
				$ignore[] = $key;
			}

			if (isset($dir) && is_readable($dir)){
				$dlist = array();
				$dir = realpath($dir);
				$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir,RecursiveDirectoryIterator::KEY_AS_PATHNAME),RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);

				foreach($objects as $entry){
					if(!in_array(basename($entry), $ignore)){




							if (!$fullpath){
							$entry = str_replace($dir, '', $entry);
						}

  							$dlist[] = $entry;

					}
				}
				return $dlist;
			}




		}


		// done
		static function get_files_from_dir($workdir, $files_dont_show_or_extensions = NULL)
		{

			$ret = array();
			//    $files_dont_show_or_extensions = $files_dont_show_or_extensions ? $files_dont_show_or_extensions : self::$filess_do_not_scanned;


			if (is_dir("$workdir")) {

				$iterator = new DirectoryIterator("$workdir");
				foreach ($iterator as $fileinfo) {
					$file_extension = strtolower(substr(strrchr($fileinfo->getFilename(), "."), 1));
					$_flag          = TRUE;
					if (!$fileinfo->isdot() && !$fileinfo->isdir()) {

						if (is_array($files_dont_show_or_extensions) && !array_key_exists(strtolower($fileinfo->getFilename()), $files_dont_show_or_extensions))
							$_flag = FALSE;
						elseif ($files_dont_show_or_extensions && $files_dont_show_or_extensions == $file_extension)
							$_flag = FALSE;


						if ($_flag)
							$ret[] = $fileinfo->getFilename();
					}
				}
			}

			//	echo " Done at $workdir  </br>";
			return $ret;
		}


		// done
		static function get_dirs_from_dir($workdir, $files_dont_show_or_extensions = NULL)
		{

			$ret = array();

			if (is_dir("$workdir")) {

				$iterator = new DirectoryIterator("$workdir");
				foreach ($iterator as $fileinfo) {
					$file_extension = strtolower(substr(strrchr($fileinfo->getFilename(), "."), 1));
					$_flag          = TRUE;
					if (!$fileinfo->isdot() && $fileinfo->isdir() && !array_key_exists($fileinfo->getFilename(), self::$dirs_do_not_scanned)) {

						if (is_array($files_dont_show_or_extensions) && !array_key_exists(strtolower($fileinfo->getFilename()), $files_dont_show_or_extensions))
							$_flag = FALSE;
						elseif ($files_dont_show_or_extensions && $files_dont_show_or_extensions == $file_extension)
							$_flag = FALSE;


						if ($_flag)
							$ret[] = $fileinfo->getFilename();
					}
				}
			}

			return $ret;
		}

		static function smartCopy($source, $dest, $options = array('folderPermission' => 0777, 'filePermission' => 0777))
		{
			$result = FALSE;

			if (is_file($source)) {
				if ($dest[strlen($dest) - 1] == '/') {
					if (!file_exists($dest)) {
						cmfcDirectory::makeAll($dest, $options['folderPermission'], TRUE);
					}
					$__dest = $dest . "/" . basename($source);
				} else {
					$__dest = $dest;
				}
				$result = copy($source, $__dest);
				chmod($__dest, $options['filePermission']);

			} elseif (is_dir($source)) {
				if ($dest[strlen($dest) - 1] == '/') {
					if ($source[strlen($source) - 1] == '/') {
						//Copy only contents
					} else {
						//Change parent itself and its contents
						$dest = $dest . basename($source);
						@mkdir($dest);
						chmod($dest, $options['filePermission']);
					}
				} else {
					if ($source[strlen($source) - 1] == '/') {
						//Copy parent directory with new name and all its content
						@mkdir($dest, $options['folderPermission']);
						chmod($dest, $options['filePermission']);
					} else {
						//Copy parent directory with new name and all its content
						@mkdir($dest, $options['folderPermission']);
						chmod($dest, $options['filePermission']);
					}
				}

				$dirHandle = opendir($source);
				while ($file = readdir($dirHandle)) {
					if ($file != "." && $file != "..") {
						if (!is_dir($source . "/" . $file)) {
							$__dest = $dest . "/" . $file;
						} else {
							$__dest = $dest . "/" . $file;
						}
						//echo "$source/$file ||| $__dest<br />";
						$result = smartCopy($source . "/" . $file, $__dest, $options);
					}
				}
				closedir($dirHandle);

			} else {
				$result = FALSE;
			}

			return $result;
		}


		/**
		 * @param      $docx
		 * @param null $dir
		 *
		 * @return string
		 *
		 *    play with Libreoffice
		 *
		 */
		static function libreOfficePDF($docx, $dir = NULL)
		{

			$path   = str_replace(' ', '\ ', $docx);
			$cmd    = '/usr/bin/libreoffice --headless --convert-to pdf --outdir ' . $dir . ' ' . $path;
			$output = system('export HOME=/tmp && ' . $cmd);

			// echo "[ $cmd $output ]";
			return $output;
		}


	}

