<?php
	set_time_limit(0);
	
	if(isset($_GET['name']))
	{
		if(!empty($_GET['name']))
		{
			$instance = new PDO('mysql:host=127.0.0.1;dbname=wikiblind', 'root', '');
			$handle = @fopen($_GET['name'], "r");
			
			$split = null;
			
			$fetched = 0;
			$insert = 0;
			
			if ($handle) 
			{
				while (($buffer = fgets($handle, 4096)) !== false) 
				{
					if(preg_match("/^(en)\s.+/",$buffer))
					{
						$split = explode(" ",$buffer);
						
						if($split[1] != "")
						{
							$checkQuery = $instance->prepare("SELECT uid FROM sys_page_list WHERE title = :title");
							$checkQuery->execute(array('title' => $split[1]));
							
							$fetched++;
							
							if($checkQuery->fetch() == false)
							{
								$query = $instance->prepare("INSERT INTO sys_page_list VALUES (NULL,:title,:views,:size,FALSE)");
								$query->execute(array('title' => $split[1],'views' =>  $split[2],'size' => $split[3]));
								
								$insert++;
							}
						}
					}
				}
				fclose($handle);
			}
			var_dump($fetched);
			var_dump($insert);
			exit("work done.");
		}
		else
		{
			exit("Empty name input.");
		}
	}
	else
	{
		exit("No file name input.");
	}
