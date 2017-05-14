<?php
	class dbConnect {

		//	PDO APPLICATION - DB CONNECTION
		function db_connect($db_name, $db_user){
			//	---
			try{
					$db = new PDO("mysql:host=". DB_LOCATION .";dbname=". $db_name, $db_user, DB_PASSWORD);
					$db-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					
					return $db;
				}catch(PDOException $e){
					file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND);
			}

			return null;
		}
		
		//	PDO APPLICATION - SQL QUERY EXCUTE
		function db_query($db_name, $db_user, $sqltext, $sql_array){
			try{
					$connects = $this->db_connect($db_name, $db_user);
					$db_query = $connects->prepare($sqltext);
					$db_query-> execute($sql_array);
					unset($connects);
					
					return $db_query;
				}catch(PDOException $e){
					file_put_contents('PDOErrorexecute.txt', $e->getMessage(), FILE_APPEND);
			}
			
			return null;
		}

		function sqlQuery( $db_name, $db_user, $opt_case, $sql_texts, $sql_array ){

			try {
				$db_sql = $this->db_query($db_name, $db_user, $sql_texts, $sql_array);
				$sqlrow = ($db_sql != null)? $db_sql->rowCount() : 0 ;
				
				if($sqlrow > 0){
					switch($opt_case){
						case 'select':
							$result = $db_sql->fetchAll(PDO::FETCH_ASSOC);
						    return $result;
						break;
						case 'insert':
						case 'update':
						case 'delete':
						    return 1;
						break;
					}
				}
			} catch (Exception $e) {
				file_put_contents('PDOErrorquery.txt', $e->getMessage(), FILE_APPEND);
			}

			return 0;
		}
		
	}
?>