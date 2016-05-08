<?php
		//require('wp-blog-header.php');

		$hostname="localhost";
		$username = "root";
		$password="";
		
		$dbh = new PDO("mysql:host=$hostname;dbname=database_name", $username, $password);
		
		//delete all post 
		delete_post($dbh);
		$fileName=$_FILES["file"]["name"];
		$fileSize=$_FILES["file"]["size"]/1024;
		$fileType=$_FILES["file"]["type"];
		$fileTmpName=$_FILES["file"]["tmp_name"];

		//count the cell 
			$linecount = 0;
			$handle = fopen($fileTmpName, "r");
			while(!feof($handle)){
			  $line = fgets($handle);
			  $linecount++;
			}

			fclose($handle);
			$final_count = $linecount-2;
		//end count the cell

		$complete_data = array();	
		$k = 1;
		if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) 
		{
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			{
				if($k>1)
				{
					$complete_data[] = $data;
					
				}
				$k++; 
			}
		   
		}
		fclose($handle);
	
		$list = glob("csv/temp/*.txt");
		foreach ($list as $file) {
			unlink($file);
		}
		unset($complete_data[0]);
	
		$final_aray = array_chunk($complete_data, count($complete_data));
		$total_data = array();
		$file_list_count = array();
		$file_data_count=0;
		foreach ($final_aray as $chunk) {
			$file = 'csv/temp/file'.substr( md5(rand()), 0, 7).'.txt';
			$myfile = fopen($file, "w");
			fwrite($myfile, json_encode($chunk));
			$my_array = array("file"=>$file,'count'=>count($chunk));
			$file_data_count +=count($chunk);
			array_push($file_list_count, $my_array);
		}
		
		$total_data = array($file_list_count,$file_data_count);
		echo json_encode($total_data);

die;



		delete_post($dbh);
		$after_uoload_list = glob("csv/temp/*.txt");
		$total = 0;
		foreach($after_uoload_list as $text_file){
			
			$raw_data = file_get_contents($text_file);
			$data = json_decode($raw_data);
			add_post($dbh,$data);
			sleep(5);

		}


		function add_post($dbh,$data){
			
			global $wpdb;	
			$custom_field_array = array('_Property_1_Left','_Property_2_Left','_Property_3_Left','_Property_4_Left','_Property_5_Left','_Property_6_Left','_Property_7_Left','_Property_8_Left','_Property_9_Left','_Property_10_Left','_Property_1_Right','_Property_2_Right','_Property_3_Right','_Property_4_Right','_Property_5_Right','_Property_6_Right','_Property_7_Right','_Property_8_Right','_Property_9_Right','_Property_10_Right','_Partner_1_Left','_Partner_2_Left','_Partner_3_Left','_Partner_4_Left','_Partner_5_Left','_Partner_6_Left','_Partner_7_Left','_Partner_8_Left','_Partner_9_Left','_Partner_10_Left','_Partner_1_Right','_Partner_2_Right','_Partner_3_Right','_Partner_4_Right','_Partner_5_Right','_Partner_6_Right','_Partner_7_Right','_Partner_8_Right','_Partner_9_Right','_Partner_10_Right','_Region_1_Left','_Region_2_Left','_Region_3_Left','_Region_4_Left','_Region_5_Left','_Region_6_Left','_Region_7_Left','_Region_8_Left','_Region_9_Left','_Region_10_Left','_Region_1_Right','_Region_2_Right','_Region_3_Right','_Region_4_Right','_Region_5_Right','_Region_6_Right','_Region_7_Right','_Region_8_Right','_Region_9_Right','_Region_10_Right'
			);
			
			foreach($data as $row){
					 
								$sth1 = $dbh->prepare("INSERT INTO `wpprefix_posts` (
										`post_author`
										,`post_title`
										,`post_type`
										,`post_date`
										,`post_date_gmt`
										,`post_modified`
										,`post_modified_gmt`
										,`post_excerpt`
										,`post_content`
										,`to_ping`
										,`pinged`
										,`post_content_filtered`
										)
									VALUES (
										1
										,'".$row[0]."'
										,'villa'
										,now()
										,now()
										,now()
										,now()
										,''
										,''
										,''
										,''
										,''
										)");
			$sth1->execute();	
			$post_id = $dbh->lastInsertId();
			
			$sth2 = $dbh->prepare("UPDATE wpprefix_postsset
						SET post_name = CONCAT (
								REPLACE(lower(post_title), ' ', '-')
								,'-'
								,id
								)
							,guid = CONCAT (
								'server_url'
								,lower(post_title)
								,'-'
								,id
								)
						FROM
						WHERE id =".$post_id);
			$sth2->execute();
			unset($row[0]);

					$sql = "INSERT INTO ".$wpdb->prefix."postmeta (`post_id`, `meta_key`, `meta_value`) VALUES ";
					$final_array =array_combine($custom_field_array, $row);
					foreach($final_array as $key => $value){
						
						$sql .= "(".wp_strip_all_tags($post_id).",'".wp_strip_all_tags($key)."','".wp_strip_all_tags($value)."'),";
						
						
					}
					
				$sql = rtrim($sql,',');
					$sth3 = $dbh->prepare($sql);
					$sth3->execute();
					
			}
			

		}	
		
		function delete_post($dbh){
			// $count = $dbh->exec("INSERT INTO animals(animal_type, animal_name) VALUES ('kiwi', 'troy')");

     		$sth = $dbh->prepare("select id from wpprefix_posts where post_type='post_type'");
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
			$id =  implode(",",$result);
			
			$sth1 = $dbh->prepare("delete from wpprefix_posts where id in(".$id.")");
			$sth1->execute();	

			$sth2 = $dbh->prepare("delete from wpprefix_postmeta where post_id in(".$id.")");
			$sth2->execute();
			$dbh = null;
		}
		

	




?>
