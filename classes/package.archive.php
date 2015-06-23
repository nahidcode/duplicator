<?php
if ( ! defined( 'DUPLICATOR_VERSION' ) ) exit; // Exit if accessed directly

require_once (DUPLICATOR_PLUGIN_PATH . 'classes/package.archive.zip.php');

class DUP_Archive {
	
	//PUBLIC
	public $FilterDirs;
	public $FilterExts;
	public $FilterOn;
	public $File;
	public $Format;
	public $PackDir;		
	public $Size = 0;
	public $WarnFileSize = array();
	public $WarnFileName = array();
	public $Dirs  = array();
	public $Files = array();
	public $Links = array();
	public $OmitDirs  = array();
	public $OmitFiles = array();
	
	//PROTECTED
	protected $Package;
	
	//PRIVATE
	private $filterDirsArray = array();
	private $filterExtsArray = array();


	public function __construct($package) {
		$this->Package   = $package;
		$this->FilterOn  = false;
	}
	
	public function Build($package) {
		try {
			
			$this->Package = $package;
			
			if (!isset($this->PackDir) && ! is_dir($this->PackDir)) throw new Exception("The 'PackDir' property must be a valid diretory.");
			if (!isset($this->File)) throw new Exception("A 'File' property must be set.");
		
			$this->Package->SetStatus(DUP_PackageStatus::ARCSTART);
			switch ($this->Format) {
				case 'TAR':			break;
				case 'TAR-GZIP': 	break;
				default:
					if (class_exists(ZipArchive)) {
						$this->Format = 'ZIP';
						DUP_Zip::Create($this);
					} else {
						//TODO:PECL and SHELL FORMATS
					}
					break;
			}
			
			$storePath = "{$this->Package->StorePath}/{$this->File}";
			$this->Size   = @filesize($storePath);
			$this->Package->SetStatus(DUP_PackageStatus::ARCDONE);
		
		} catch (Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	public function GetFilterDirAsArray() {
		return array_map('DUP_Util::SafePath', explode(";", $this->FilterDirs, -1));
	}
	
	public function GetFilterExtsAsArray() {
		return explode(";", $this->FilterExts, -1);
	}
	
	/**
	 *  DIRSTATS
	 *  Get the directory size recursively, but don't calc the snapshot directory, exclusion diretories
	 *  @param string $directory		The directory to calculate
	 *  @returns array					An array of values for the directory stats
	 *  @link http://msdn.microsoft.com/en-us/library/aa365247%28VS.85%29.aspx Windows filename restrictions
	 */	
	public function Stats() {
		
		$this->filterDirsArray = array();
		$this->filterExtsArray = array();
		if ($this->FilterOn) {
			$this->filterDirsArray = $this->GetFilterDirAsArray();
			$this->filterExtsArray = $this->GetFilterExtsAsArray();
		}
		
		$this->getDirs();
		$this->getFiles();
		return $this;
	}
	
	//Get All Directories then filter
	private function getDirs() {
		
		$rootPath = DUP_Util::SafePath(rtrim(DUPLICATOR_WPROOTPATH, '//' ));
		array_push($this->filterDirsArray, DUPLICATOR_SSDIR_PATH);
		
		//If the root directory is a filter then we will only need the root files
		if (in_array($this->PackDir, $this->filterDirsArray)) {
			$this->Dirs = $this->PackDir;
		} else {
			$this->dirsToArray($rootPath);
			array_push($this->Dirs, $this->PackDir);
		}
		
		//Filter Directories
		//Invalid test contains checks for: characters over 250, invlaid characters, 
		//empty string and directories ending with period (Windows incompatable)
		foreach ($this->Dirs as $key => $val) {
			//Remove path filter directories
			foreach($this->filterDirsArray as $item) { 
				if (strstr($val, $item . '/') || $val == $item) {
					$this->OmitDirs[] = $val;
					unset($this->Dirs[$key]);
					continue 2;
				}
			}
			
			//Locate invalid directories and warn
			$name = basename($val); 
			$invalid_test = strlen($val) > 250 
							|| 	preg_match('/(\/|\*|\?|\>|\<|\:|\\|\|)/', $name) 
							|| 	trim($name) == "" 
							||  (strrpos($name, '.') == strlen($name) - 1  && substr($name, -1) == '.');
			
			if ($invalid_test) {
				$this->WarnFileName[] = $val;
				$this->OmitDirs[]     = $val;
			} 
		}
	}
	
	//Get all files and filter out error prone subsets
	private function getFiles() {
		foreach ($this->Dirs as $key => $val) {
			$files = DUP_Util::ListFiles($val);
			foreach ($files as $filePath) {
				$fileName = basename($filePath);
				$valid = true;
				if (!is_dir($filePath)){
					if (!in_array(@pathinfo($filePath, PATHINFO_EXTENSION), $this->filterExtsArray)  && is_readable($filePath)) {
						$fileSize = @filesize($filePath);
						$fileSize = empty($fileSize) ? 0 : $fileSize; 
						if (strlen($filePath) > 250 || preg_match('/(\/|\*|\?|\>|\<|\:|\\|\|)/', $fileName)|| trim($fileName) == "") {
							array_push($this->WarnFileName, $filePath);
							$valid = false;
						} 
						if ($fileSize > DUPLICATOR_SCAN_WARNFILESIZE) {
							array_push($this->WarnFileSize, $filePath . ' [' . DUP_Util::ByteSize($fileSize) . ']');
						}
						if ($valid) {
							$this->Size += $fileSize;
							$this->Files[] = $filePath;
						} 
						else {
							$this->OmitFiles[] = $filePath;
						}
					} else {
						$this->OmitFiles[] = $filePath;	
					} 
				}
			}
		}
	}
	
    //Recursive function to get all Directories in a wp install
    //Older PHP logic which is more stable on older version of PHP
	//NOTE RecursiveIteratorIterator is problematic on some systems issues include:
    // - error 'too many files open' for recursion
    // - $file->getExtension() is not reliable as it silently fails at least in php 5.2.17 
    // - issues with when a file has a permission such as 705 and trying to get info (had to fallback to pathinfo)
	// - basic conclusion wait on the SPL libs untill after php 5.4 is a requiremnt
	// - since we are in a tight recursive loop lets remove the utiltiy call DUP_PRO_Util::SafePath("{$path}/{$file}") and 
	//   squeeze out as much performance as we possible can
	private function dirsToArray($path) {
		$items = array();
		$handle = opendir($path);
		if ($handle) {
			while (($file = readdir($handle)) !== false ) {
				if ($file != '.' && $file != '..') {
					$fullPath = DUP_Util::SafePath($path. "/" . $file);
					if (is_dir($fullPath)) {
						$items = array_merge($items, $this->dirsToArray($fullPath));
						$items[] = $fullPath;
					}
				}
			}
			closedir($handle);
		}
		return $items;
	}
	

}
?>