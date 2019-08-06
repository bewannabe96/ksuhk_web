<?php

	class FreeboardDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_free";
		private static $PASSWORD = "K9C9Td7VB1FZ";

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
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_FREE" );
			$this->conn->set_charset('utf8');
		}

		static function createFreeboardDBManager() {
			$rtn_obj = new FreeboardDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
		}

		function get_num_posts() {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM Freeboard" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->bind_result( $rtn_val );
				$stmt->fetch();
				$stmt->close();
			}
			return $rtn_val;
		}

		function get_num_report_posts() {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM Freeboard WHERE PostStatus=2" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->bind_result( $rtn_val );
				$stmt->fetch();
				$stmt->close();
			}
			return $rtn_val;
		}

		function select_image_byhash( $image_hash ) {
			$stmt = $this->conn->prepare( "SELECT ImageType, ImageData FROM Image WHERE ImageHash=?" );
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

		function select_post_byid( $post_id ) {
			$stmt = $this->conn->prepare( "SELECT PostID, UserName, UserUsername, PostTitle, PostPostdate, PostUpdate, PostContent, PostImage, PostStatus "
							. "FROM Freeboard JOIN ksuhkcom_USER.Users WHERE Freeboard.UserID = ksuhkcom_USER.Users.UserID AND PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $post_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["PostID"], $rtn_row["UserName"], $rtn_row["UserUsername"],
								$rtn_row["PostTitle"], $rtn_row["PostPostdate"], $rtn_row["PostUpdate"],
								$rtn_row["PostContent"], $rtn_row["PostImage"], $rtn_row["PostStatus"] );
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

		function select_posts( $limit, $offset ) {
			$stmt = $this->conn->prepare( "SELECT PostID, UserName, UserUsername, PostTitle, DATE(PostPostdate) FROM Freeboard JOIN ksuhkcom_USER.Users "
								. "WHERE Freeboard.UserID = ksuhkcom_USER.Users.UserID ORDER BY PostID DESC LIMIT ? OFFSET ?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $limit, $offset );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $username, $title, $postdate );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "PostID"=>$id, "UserName"=>$name, "UserUsername"=>$username,
									"PostTitle"=>$title, "PostPostdate"=>$postdate ];
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

		function select_report_posts( $limit, $offset ) {
			$stmt = $this->conn->prepare( "SELECT PostID, UserName, UserUsername, PostTitle, DATE(PostPostdate) FROM Freeboard JOIN ksuhkcom_USER.Users "
						. "WHERE Freeboard.UserID = ksuhkcom_USER.Users.UserID AND PostStatus=2 ORDER BY PostID DESC LIMIT ? OFFSET ?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $limit, $offset );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $username, $title, $postdate );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "PostID"=>$id, "UserName"=>$name, "UserUsername"=>$username,
									"PostTitle"=>$title, "PostPostdate"=>$postdate ];
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

		function select_report_byid( $post_id ) {
			$stmt = $this->conn->prepare( "SELECT UserName, UserUsername, PostReport FROM Freeboard JOIN ksuhkcom_USER.Users "
							. "WHERE Freeboard.PostReportUser = ksuhkcom_USER.Users.UserID AND PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $post_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["UserName"], $rtn_row["UserUsername"], $rtn_row["PostReport"] );
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

		function select_comments_byid( $post_id ) {
			$stmt = $this->conn->prepare( "SELECT CmtID, UserName, UserUsername, UserSchool, CmtPostdate, CmtContent FROM Comment$post_id "
						. "JOIN ksuhkcom_USER.Users WHERE Comment$post_id.UserID = ksuhkcom_USER.Users.UserID ORDER BY CmtID" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $username, $school, $postdate, $content );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "CmtID"=>$id, "UserName"=>$name, "UserUsername"=>$username, "UserSchool"=>$school,
									"CmtPostdate"=>$postdate, "CmtContent"=>$content];
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

		function insert_image( $image_assoc ) {
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
		
					$stmt = $this->conn->prepare( "INSERT INTO Image (ImageHash, ImageType, ImageSize, ImageData) VALUES (?, ?, ?, ?)" );
					if( $stmt ) {
						$null = NULL;
						$stmt->bind_param( "ssib", $image_hash, $image_stype, $image_size, $null );
						$stmt->send_long_data( 3, file_get_contents( $image_assoc["tmp_name"] ) );
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

		function insert_new_post( $post_title, $user_id, $post_content, $post_image_assoc ) {
			$postdate = date('Y-m-d H:i:s');

			$image_result = $this->insert_image( $post_image_assoc );
			switch( $image_result ) {
				case self::$INSERT_ERROR :
				case self::$INVALID_SIZE :
				case self::$INVALID_TYPE :
					return $image_result;

				case self::$IMAGE_NULL :
					$stmt = $this->conn->prepare( "INSERT INTO Freeboard (PostTitle, UserID, PostPostdate, PostUpdate, "
									. "PostContent) VALUES (?, ?, ?, ?, ?)" );
					if( $stmt ) {
						$stmt->bind_param( "sisss", $post_title, $user_id, $postdate, $postdate, $post_content );
						$stmt->execute();
						$stmt->close();
					} else {
						return self::$INSERT_ERROR;
					}
					$image_result = 1;
					break;

				default :
					$stmt = $this->conn->prepare( "INSERT INTO Freeboard (PostTitle, UserID, PostPostdate, PostUpdate, "
										. "PostContent, PostImage) VALUES (?, ?, ?, ?, ?, ?)" );
					if( $stmt ) {
						$stmt->bind_param( "sissss", $post_title, $user_id, $postdate, $postdate, $post_content, $image_result );
						$stmt->execute();
						$stmt->close();
					} else {
						return self::$INSERT_ERROR;
					}
					$image_result = 1;
			}

			if( $image_result == 1 ) {
				$stmt = $this->conn->prepare( "SELECT PostID FROM Freeboard WHERE PostPostdate=?" );
				$stmt->bind_param( "s", $postdate );
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result( $post_id );
				$stmt->fetch();
				$stmt->close();

				$stmt = $this->conn->prepare( "CREATE TABLE Comment$post_id ( "
								. "CmtID INT unsigned NOT NULL AUTO_INCREMENT, "
								. "UserID INT unsigned NOT NULL, "
								. "CmtPostdate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, "
								. "CmtContent TEXT DEFAULT NULL, "
								. "PRIMARY KEY (CmtID) ) ENGINE = INNODB "
								. "DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci" );
				if( $stmt ) {
					$stmt->execute();
					$stmt->close();
				} else {
					return self::$INSERT_ERROR;
				}
			}
		}

		function insert_comment_byid( $post_id, $user_id, $cmt_content ) {
			$stmt = $this->conn->prepare( "INSERT INTO Comment$post_id (UserID, CmtContent) VALUES (?, ?)" );
			if( $stmt ) {
				$stmt->bind_param( "is", $user_id, $cmt_content );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}
		}

		function check_authority( $post_id, $user_id ) {
			$stmt = $this->conn->prepare( "SELECT PostID FROM Freeboard WHERE PostID=? AND UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $post_id, $user_id );
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

		function check_comment_authority( $post_id, $cmt_id, $user_id ) {
			$stmt = $this->conn->prepare( "SELECT CmtID FROM Comment$post_id WHERE CmtID=? AND UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $cmt_id, $user_id );
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

		function delete_image_byhash( $image_hash ) {
			$stmt = $this->conn->prepare( "DELETE FROM Image WHERE ImageHash=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $image_hash );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}
		
		function delete_post_byid( $post_id ) {
			$stmt = $this->conn->prepare( "SELECT PostImage FROM Freeboard WHERE PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $post_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $post_image );
					$stmt->fetch();
					if( $this->delete_image_byhash( $post_image ) === self::$DELETE_ERROR ) { 
						$stmt->close();
						return self::$DELETE_ERROR;
					}
				}
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}

			$stmt = $this->conn->prepare( "DELETE FROM Freeboard WHERE PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $post_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}

			$stmt = $this->conn->prepare( "DROP TABLE IF EXISTS Comment$post_id" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function delete_comment_byid( $post_id, $cmt_id ) {
			$stmt = $this->conn->prepare( "DELETE FROM Comment$post_id WHERE CmtID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $cmt_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function update_status_byid( $post_id, $post_status, $post_report_user=0, $post_report="") {
			$stmt = $this->conn->prepare( "UPDATE Freeboard SET PostStatus=?, PostReportUser=?, PostReport=? WHERE PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "iisi", $post_status, $post_report_user, $post_report, $post_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function __destruct() {
			$this->conn->close();
		}
	}

?>
