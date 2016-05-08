<?php

	$hostname="localhost";
	$username = "root";
	$password="";
	$dbh = new PDO("mysql:host=$hostname;dbname=databasename", $username, $password);

$data = $_POST['data'];
add_post($dbh,$data);
	function add_post($dbh,$data){

			global $wpdb;	
			$custom_field_array = array('_Property_1_Left','_Property_2_Left','_Property_3_Left','_Property_4_Left','_Property_5_Left','_Property_6_Left','_Property_7_Left','_Property_8_Left','_Property_9_Left','_Property_10_Left','_Property_1_Right','_Property_2_Right','_Property_3_Right','_Property_4_Right','_Property_5_Right','_Property_6_Right','_Property_7_Right','_Property_8_Right','_Property_9_Right','_Property_10_Right','_Partner_1_Left','_Partner_2_Left','_Partner_3_Left','_Partner_4_Left','_Partner_5_Left','_Partner_6_Left','_Partner_7_Left','_Partner_8_Left','_Partner_9_Left','_Partner_10_Left','_Partner_1_Right','_Partner_2_Right','_Partner_3_Right','_Partner_4_Right','_Partner_5_Right','_Partner_6_Right','_Partner_7_Right','_Partner_8_Right','_Partner_9_Right','_Partner_10_Right','_Region_1_Left','_Region_2_Left','_Region_3_Left','_Region_4_Left','_Region_5_Left','_Region_6_Left','_Region_7_Left','_Region_8_Left','_Region_9_Left','_Region_10_Left','_Region_1_Right','_Region_2_Right','_Region_3_Right','_Region_4_Right','_Region_5_Right','_Region_6_Right','_Region_7_Right','_Region_8_Right','_Region_9_Right','_Region_10_Right'
			);
			
			$row = $data;
					 
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
			
			$sth2 = $dbh->prepare("UPDATE wpprefixt_postsset
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

					$sql = "INSERT INTO wpprefix_postmeta (`post_id`, `meta_key`, `meta_value`) VALUES ";
					$final_array =array_combine($custom_field_array, $row);
				
					foreach($final_array as $key => $value){
						
						$sql .= "(".mysql_real_escape_string($post_id).",'".mysql_real_escape_string($key)."','".mysql_real_escape_string($value)."'),";
						
						
					}
					
				$sql = rtrim($sql,',');
				$sth3 = $dbh->prepare($sql);
				$sth3->execute();
					
			
			

		}


?>
