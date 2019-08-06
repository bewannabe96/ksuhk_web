<?php

	class BuyandsellDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_bas";
		private static $PASSWORD = "J6h40gr2rI7U";

		public static $CONNECT_ERROR = -1;
		public static $INSERT_ERROR = -2;
		public static $SELECT_ERROR = -3;
		public static $DELETE_ERROR = -4;
		public static $UPDATE_ERROR = -5;

		public static $INVALID_SIZE = 1;
		public static $INVALID_TYPE = 2;
		public static $IMAGE_NULL = 3;
		public static $VALID_AUTH = 4;
		public static $INVALID_AUTH = 5;

		public static $ALLOWED_TYPES = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
		public static $MAX_SIZE = 2097152; // 2MB

		private $conn;
		
		private function __construct() {
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_BUYANDSELL" );
			$this->conn->set_charset('utf8');
		}

		static function createBuyandsellDBManager() {
			$rtn_obj = new BuyandsellDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
		}

		function get_num_bases() {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM BuyAndSell" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->bind_result( $rtn_val );
				$stmt->fetch();
				$stmt->close();
			}
			return $rtn_val;
		}

		function get_num_bases_bysearch( $category, $search_word ) {
			$type = "";
			$params = array();
			$query = "SELECT COUNT(*) FROM BuyAndSell WHERE 1=1";

			if( $category != 0 ) {
				$query .= " AND BASType=?";
				$type .= "i";
				$params[] = &$category;
			}

			if( $search_word != "" ) {
				$query .= " AND BASTitle LIKE ?";
				$type .= "s";
				$params[] = &$search_word;
			}

			$stmt = $this->conn->prepare( $query );
			if( $stmt ) {
				if( $type != "" )
					call_user_func_array( array( $stmt, 'bind_param' ), array_merge( array($type), $params ) );
				$stmt->execute();
				$stmt->bind_result( $rtn_val );
				$stmt->fetch();
				$stmt->close();
			}
			return $rtn_val;
		}

		function get_num_report_bases() {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM BuyAndSell WHERE BASReportStatus=2" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->bind_result( $rtn_val );
				$stmt->fetch();
				$stmt->close();
			}
			return $rtn_val;
		}

		function get_num_tempimages( $user_id ) {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM TempImage WHERE UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $user_id );
				$stmt->execute();
				$stmt->bind_result( $rtn_val );
				$stmt->fetch();
				$stmt->close();
			}
			return $rtn_val;
		}

		function select_image_byhash( $bas_id, $image_hash ) {
			if( $bas_id == 0 )
				$stmt = $this->conn->prepare( "SELECT ImageType, ImageData FROM TempImage WHERE ImageHash=?" );
			else
				$stmt = $this->conn->prepare( "SELECT ImageType, ImageData FROM Image$bas_id WHERE ImageHash=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $image_hash );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["ImageType"], $rtn_row["ImageData"] );
					$stmt->fetch();
				}
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_row;
		}

		function select_images_byid( $bas_id ) {
			$stmt = $this->conn->prepare( "SELECT ImageHash FROM Image$bas_id" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result( $hash );
				$rtn_rows = array();
				while( $stmt->fetch() )
					$rtn_rows[] = $hash;
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_rows;
		}

		function select_bas_byid( $bas_id ) {
			$stmt = $this->conn->prepare( "SELECT BASID, UserName, UserUsername, UserEmail, UserSchool, UserPhoneNo, UserKakaoID, "
											. "BASTitle, BASPostdate, BASUpdate, BASType, BASPrice, BASContent, BASStatus, "
											. "BASImages, BASReportStatus, BASView FROM BuyAndSell JOIN ksuhkcom_USER.Users "
											. "WHERE BuyAndSell.UserID = ksuhkcom_USER.Users.UserID AND BASID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $bas_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["BASID"], $rtn_row["UserName"], $rtn_row["UserUsername"], $rtn_row["UserEmail"], 
								$rtn_row["UserSchool"], $rtn_row["UserPhoneNo"], $rtn_row["UserKakaoID"], $rtn_row["BASTitle"],
								$rtn_row["BASPostdate"], $rtn_row["BASUpdate"], $rtn_row["BASType"], $rtn_row["BASPrice"], $rtn_row["BASContent"],
								$rtn_row["BASStatus"], $rtn_row["BASImages"], $rtn_row["BASReportStatus"], $rtn_row["BASView"] );
					$stmt->fetch();
				} else {
					$rtn_row = FALSE;
				}
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_row;
		}

		function select_bases( $limit, $offset ) {
			$stmt = $this->conn->prepare( "SELECT BASID, UserName, UserUsername, BASTitle, BASPrice, BASStatus, "
								. "BASTopImage, BASView FROM BuyAndSell JOIN ksuhkcom_USER.Users "
								. "WHERE BuyAndSell.UserID = ksuhkcom_USER.Users.UserID ORDER BY BASID DESC LIMIT ? OFFSET ?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $limit, $offset );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $username, $title, $price, $status, $top_image, $view );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "BASID"=>$id, "UserName"=>$name, "UserUsername"=>$username, "BASTitle"=>$title,  
									"BASPrice"=>$price, "BASStatus"=>$status, "BASTopImage"=>$top_image, "BASView"=>$view ];
					}
				} else {
					$rtn_rows = FALSE;
				}
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_rows;
		}

		function search_bases( $category, $search_word, $limit, $offset ) {
			$type = "";
			$params = array();
			$count_query = "SELECT COUNT(*) FROM BuyAndSell WHERE 1=1";
			$select_query = "SELECT BASID, UserName, UserUsername, BASTitle, DATE(BASPostdate), BASPrice, BASStatus, BASTopImage, "
								. "BASView FROM BuyAndSell JOIN ksuhkcom_USER.Users WHERE BuyAndSell.UserID = ksuhkcom_USER.Users.UserID";

			if( $category != 0 ) {
				$select_query = $select_query . " AND BASType=?";
				$count_query = $count_query . " AND BASType=?";
				$type .= "i";
				$params[] = &$category;
			}

			if( $search_word !== "" && $search_word !== "%%" ) {
				$select_query = $select_query . " AND BASTitle LIKE ?";
				$count_query = $count_query . " AND BASTitle LIKE ?";
				$type .= "s";
				$params[] = &$search_word;
			}

			$stmt = $this->conn->prepare( $count_query );
			if( $stmt ) {
				if( $type != "" )
					call_user_func_array( array( $stmt, 'bind_param' ), array_merge( array($type), $params ) );
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result( $count );
				$stmt->fetch();
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}

			$select_query = $select_query . " ORDER BY BASID DESC LIMIT ? OFFSET ?";	
			$type .= "ii";
			$params[] = &$limit;
			$params[] = &$offset;

			$stmt = $this->conn->prepare( $select_query );
			if( $stmt ) {
				call_user_func_array( array( $stmt, 'bind_param' ), array_merge( array($type), $params ) );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $username, $title, $postdate, $price, $status, $top_image, $view );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "BASID"=>$id, "UserName"=>$name, "UserUsername"=>$username, "BASTitle"=>$title, "BASPostdate"=>$postdate, 
									"BASPrice"=>$price, "BASStatus"=>$status, "BASTopImage"=>$top_image, "BASView"=>$view ];
					}
				} else {
					$rtn_rows = FALSE;
				}
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return array( $count, $rtn_rows );
		}

		function select_report_bases( $limit, $offset ) {
			$stmt = $this->conn->prepare( "SELECT BASID, UserName, UserUsername, BASTitle, DATE(BASPostdate) "
								. "FROM BuyAndSell JOIN ksuhkcom_USER.Users WHERE BuyAndSell.UserID = ksuhkcom_USER.Users.UserID "
								. "AND BASReportStatus=2 ORDER BY BASID DESC LIMIT ? OFFSET ?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $limit, $offset );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $username, $title, $postdate );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "BASID"=>$id, "UserName"=>$name, "UserUsername"=>$username, "BASTitle"=>$title, "BASPostdate"=>$postdate ];
					}
				} else {
					$rtn_rows = FALSE;
				}
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_rows;
		}

		function select_report_byid( $bas_id ) {
			$stmt = $this->conn->prepare( "SELECT UserName, UserUsername, BASReport FROM BuyAndSell JOIN ksuhkcom_USER.Users "
							. "WHERE BuyAndSell.BASReportUser = ksuhkcom_USER.Users.UserID AND BASID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $bas_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["UserName"], $rtn_row["UserUsername"], $rtn_row["BASReport"] );
					$stmt->fetch();
				} else {
					$rtn_row = FALSE;
				}
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_row;
		}

		function insert_tempimage( $user_id, $image_assoc ) {
			$image_size = $image_assoc["size"];
			if( $image_size == 0 ) {
				return self::$IMAGE_NULL;

			} else if( $image_size > 0 && $image_size < self::$MAX_SIZE ) {
				$image_type = exif_imagetype( $image_assoc["tmp_name"] );
				if( in_array( $image_type, self::$ALLOWED_TYPES ) ) {
					switch( $image_type ) {
						case IMAGETYPE_PNG:
							$image_stype = "PNG";
							break;
						case IMAGETYPE_JPEG:
							$image_stype = "JPEG";
							break;
					}
					$image_hash = hash( "md5", time().$image_size );
		
					$stmt = $this->conn->prepare( "INSERT INTO TempImage (UserID, ImageHash, ImageType, ImageSize, ImageData) VALUES (?, ?, ?, ?, ?)" );
					if( $stmt ) {
						$null = NULL;
						$stmt->bind_param( "issib", $user_id, $image_hash, $image_stype, $image_size, $null );
						$stmt->send_long_data( 4, file_get_contents( $image_assoc["tmp_name"] ) );
						$stmt->execute();
						$stmt->close();
					} else {
						return self::$INSERT_ERROR;
					}
				} else {
					return self::$INVALID_TYPE;
				}
			} else {
				return self::$INVALID_SIZE;
			}
			return $image_hash;
		}

		function insert_new_bas( $bas_title, $user_id, $bas_type, $bas_price, $bas_content, $bas_images ) {
			$postdate = date('Y-m-d H:i:s');
			
			$stmt = $this->conn->prepare( "INSERT INTO BuyAndSell (BASTitle, UserID, BASPostdate, BASUpdate, BASType, "
							. "BASPrice, BASContent, BASImages) VALUES (?, ?, ?, ?, ?, ?, ?, ?)" );
			if( $stmt ) {
				$stmt->bind_param( "sissiisi", $bas_title, $user_id, $postdate, $postdate, $bas_type, $bas_price,
									$bas_content, $bas_images );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}

			$stmt = $this->conn->prepare( "SELECT BASID FROM BuyAndSell WHERE BASPostdate=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $postdate );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $bas_id );
					$stmt->fetch();
				}
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}
			return $bas_id;
		}

		function check_authority( $bas_id, $user_id ) {
			$stmt = $this->conn->prepare( "SELECT BASID FROM BuyAndSell WHERE BASID=? AND UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $bas_id, $user_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 )
					$rtn_stat = self::$VALID_AUTH;
				else
					$rtn_stat = self::$INVALID_AUTH;
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_stat;
		}

		function relocate_tempimages( $bas_id, $images_hash )
		{
			$stmt = $this->conn->prepare( "CREATE TABLE Image$bas_id ( "
							. "ImageID INT unsigned NOT NULL AUTO_INCREMENT, "
							. "ImageUploaddate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, "
							. "ImageHash char(64) DEFAULT NULL, "
							. "ImageType varchar(16) NOT NULL DEFAULT '', "
							. "ImageSize INT unsigned NOT NULL, "
							. "ImageData MEDIUMBLOB NOT NULL, "
							. "PRIMARY KEY (ImageID) ) ENGINE = INNODB "
							. "DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}

			$stmt = $this->conn->prepare( "INSERT INTO Image$bas_id (ImageUploaddate, ImageHash, ImageType, "
								. "ImageSize, ImageData) SELECT ImageUploaddate, ImageHash, ImageType, ImageSize, "
								. "ImageData FROM TempImage WHERE ImageHash=?" );
			if( $stmt ) {
				foreach( $images_hash as $hash ) {
					$stmt->bind_param( "s", $hash );
					$stmt->execute();
				}
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}

			$stmt = $this->conn->prepare( "UPDATE BuyAndSell SET BASTopImage=? WHERE BASID=?" );
			if( $stmt ) {
				$stmt->bind_param( "si", $images_hash[0], $bas_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}
		}

		function increase_view_byid( $bas_id ) {
			$stmt = $this->conn->prepare( "UPDATE BuyAndSell SET BASView=BASView+1 WHERE BASID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $bas_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}
		}

		function close_bas_byid( $bas_id ) {
			$stmt = $this->conn->prepare( "UPDATE BuyAndSell SET BASStatus=2 WHERE BASID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $bas_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function update_reportstatus_byid( $bas_id, $bas_report_status, $bas_report_user=0, $bas_report="") {
			$stmt = $this->conn->prepare( "UPDATE BuyAndSell SET BASReportStatus=?, BASReportUser=?, BASReport=? WHERE BASID=?" );
			if( $stmt ) {
				$stmt->bind_param( "iisi", $bas_report_status, $bas_report_user, $bas_report, $bas_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function delete_tempimage_byhash( $image_hash ) {
			$stmt = $this->conn->prepare( "DELETE FROM TempImage WHERE ImageHash=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $image_hash );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function delete_tempimages_byuserid( $user_id ) {
			$stmt = $this->conn->prepare( "DELETE FROM TempImage WHERE UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $user_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function delete_bas_byid( $bas_id ) {
			$stmt = $this->conn->prepare( "DELETE FROM BuyAndSell WHERE BASID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $bas_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}

			$stmt = $this->conn->prepare( "DROP TABLE IF EXISTS Image$bas_id" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function __destruct() {
			$this->conn->close();
		}
	}

?>
