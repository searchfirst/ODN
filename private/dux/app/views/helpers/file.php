<?php
	class FileHandler extends Model 
	{
		var $name = 'FileHandler';
		var $useTable = false;
		var $error;
		
		function save($file=null, $filename=null, $directory=null, $overwrite=false)
		{
			// Check if required params are empty
			if(empty($file) || empty($directory) || empty($filename))
			{
				$this->error = 0;
				return false;
			}
			
			// Check if the temp file exists, the directory exists and is writable
			if(!file_exists($file) || !is_dir($directory)  || !is_writable($directory))
			{
				$this->error = 1;
				return false;
			}
			
			// Check the directory has a DS on the end
			if($directory[strlen($directory)-1] != DS)
			{
				$directory .= DS;
			}
			
			// Check if a file already exists with the same name
			if(file_exists($directory . $filename))
			{
				if($overwrite === false)
				{
					$this->error = 2;
					return false;
				}
				
				$current_count = 1;
				$orig_filename = $filename;
				
				while(file_exists($directory . $filename))
				{
					$split = explode('.', $orig_filename);
					$filename = '';
					
					for($i=0;$i<(count($split)-1);$i++)
					{
						$filename .= $split[$i];
					}
					
					$filename .= $current_count . '.' . $split[(count($split)-1)];
					$current_count++;
				}
			}
			
			// Move file
			if(!move_uploaded_file($file, $directory . $filename))
			{
				$this->error = 3;
				return false;
			}
			
			// Return the directory/filename where is was saved
			return $directory . $filename;
		}
		
	}
?>