<?php

	set_time_limit(0);
	$instance = new PDO('mysql:host=127.0.0.1;dbname=wikiblind', 'root', '');
	$tempName = mt_rand();
	
	### GENERATE IMAGE ###
	{
		$query = $instance->prepare("SELECT * FROM sys_page_list WHERE upload = 0 ORDER BY `views` DESC LIMIT 1");
		$query->execute();
		
		$queryInfo = $query->fetch();
		
		exec("IECapt.exe --url=https://en.wikipedia.org/wiki/{$queryInfo['title']} --out=images/{$tempName}.jpg --min-width=720");
		
	}
	###
	
	### GENERATE AUDIO ###
	{
		include 'speech.class.php'; 
		$t2s = new PHP_Text2Speech; 
		
		$data = file_get_contents("https://en.wikipedia.org/wiki/{$queryInfo['title']}");
		
		$doc = new DOMDocument();
		$e = $doc->loadHTML($data);
		$xpath = new DOMXPath($doc);

		$query = "//div[@id='mw-content-text']/p"; // paragraph
		$query .= " | //div[@id='mw-content-text']/h2"; // h2
		$query .= " | //div[@id='mw-content-text']/h3"; // h3
		$query .= " | //div[@id='mw-content-text']/ul"; // ul
		
		$dataz = null;
		
		$paraIndex = 0;
		$paraValue = 0;
		
		foreach ($xpath->query($query) as $entry)
		{
			if($entry->nodeValue == "See also[edit]" || $entry->nodeValue == "References[edit]" || $entry->nodeValue == "External links[edit]")
			{
				break;
			}
			
			$paraValue = preg_replace("/(\[(edit)\])+/",".",$entry->nodeValue);
			$paraValue = preg_replace("/(\[.+\])+/","",$paraValue);
			
			if($entry->nodeName === "p")
			{
				$dataz[$paraIndex] = $paraValue;
			}
			elseif($entry->nodeName === "ul")
			{
				$dataz[$paraIndex] = str_replace(array("\r\n","\n"),",\r\n",$entry->nodeValue);
			}
			elseif($entry->nodeName === "h2" || $entry->nodeName === "h3")
			{
				$dataz[$paraIndex] = "Chapter : " . $paraValue;
			}
			else
			{
			}
			
			$paraIndex++;
		}
		
		$dataz = str_replace('"',"'",implode($dataz));
		$dataz = preg_replace("/((?:[\.]?)Chapter\s\:)/",".Chapter :",$dataz);
		file_put_contents("texts/{$tempName}.txt",$dataz);
		
		$command = "tts.exe -f 10 -v 1 -t -o sounds/{$tempName} -i texts/{$tempName}.txt -s 60";
		$ret = shell_exec($command);
		
		$output = shell_exec('ffprobe -v quiet -print_format json -show_format -show_streams "sounds/'. $tempName .'0.mp3"');
		$parsed = json_decode($output, true);
		$duration = round(floatval($parsed['format']['duration']),0,PHP_ROUND_HALF_UP);
	}
	###
	
	### GENERATE VIDEO ###
	//$size = getimagesize('images/'. $tempName .'.jpg');
	//$test = $duration / $size[1];
	//$bis = $duration / 480;
	{
		$create = 'ffmpeg.exe -threads 0 -f lavfi -i color=s=720x480 -loop 1 -i images/'. $tempName .'.jpg -i sounds/'. $tempName .'0.mp3 -filter_complex "[1:v]scale=720:-2[fg];[0:v][fg]overlay=y=\'-((h-1)*t/'.$duration.')\':shortest=1[v]" -map "[v]" -map 2:a -codec:v libx264 -preset ultrafast -crf 27 -acodec libmp3lame -ac 2 -ab 160k -t '.$duration.' videos/'. $tempName .'.mkv';
		$info = exec($create);
		var_dump($create);
		var_dump($info);
	}
	###