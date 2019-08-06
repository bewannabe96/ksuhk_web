<?php

	class NoticeDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_notice";
		private static $PASSWORD = "4tOp1wTRAwsd";

		public static $CONNECT_ERROR = -1;
		public static $INSERT_ERROR = -2;
		public static $SELECT_ERROR = -3;
		public static $DELETE_ERROR = -4;
		public static $UPDATE_ERROR = -5;

		public static $INVALID_SIZE = 1;
		public static $INVALID_TYPE = 2;

		private $conn;
		
		private function __construct() {
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_NOTICE" );
			$this->conn->set_charset('utf8');
		}

		static function createNoticeDBManager() {
			$rtn_obj = new NoticeDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
		}

		function get_num_posts_bycategory( $post_category ) {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM Notice WHERE PostCategory=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $post_category );
				$stmt->execute();
				$stmt->bind_result( $rtn_val );
				$stmt->fetch();
				$stmt->close();
			}
			return $rtn_val;
		}

		function select_post_byid( $post_id ) {
			$stmt = $this->conn->prepare( "SELECT PostID, PostTitle, PostPostdate, PostUpdate, PostCategory, PostContent, "
								. "PostImage FROM Notice WHERE PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $post_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["PostID"], $rtn_row["PostTitle"], $rtn_row["PostPostdate"], $rtn_row["PostUpdate"],
							$rtn_row["PostCategory"], $rtn_row["PostContent"], $rtn_row["PostImage"] );
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

		function select_posts_bycategory( $post_category, $limit, $offset ) {
			$stmt = $this->conn->prepare( "SELECT PostID, PostTitle, DATE(PostPostdate), DATE(PostUpdate) FROM Notice WHERE PostCategory=? "
								. "ORDER BY PostID DESC LIMIT ? OFFSET ?" );
			if( $stmt ) {
				$stmt->bind_param( "iii", $post_category, $limit, $offset );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $title, $postdate, $update );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "PostID"=>$id, "PostTitle"=>$title, "PostPostdate"=>$postdate, "PostUpdate"=>$update ];
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

		function insert_new_post( $post_title, $post_category, $post_content, $post_image_assoc ) {
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				$image_result = $image_db->insert_image( $post_image_assoc );
				switch( $image_result ) {
					case ImageDBManager::$INSERT_ERROR :
						return self::$INSERT_ERROR;
					case ImageDBManager::$INVALID_SIZE :
						return self::$INVALID_SIZE;
					case ImageDBManager::$INVALID_TYPE :
						return self::$INVALID_TYPE;
					case ImageDBManager::$IMAGE_NULL :
						$stmt = $this->conn->prepare( "INSERT INTO Notice (PostTitle, PostPostdate, PostUpdate, PostCategory, "
											. "PostContent) VALUES (?, ?, ?, ?, ?)" );
						if( $stmt ) {
							$stmt->bind_param( "sssis", $post_title, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $post_category,
										$post_content );
							$stmt->execute();
							$stmt->close();
						} else {
							return self::$INSERT_ERROR;
						}
						break;
					default :
						$stmt = $this->conn->prepare( "INSERT INTO Notice (PostTitle, PostPostdate, PostUpdate, PostCategory, "
											. "PostContent, PostImage) VALUES (?, ?, ?, ?, ?, ?)" );
						if( $stmt ) {
							$stmt->bind_param( "sssiss", $post_title, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $post_category,
										$post_content, $image_result );
							$stmt->execute();
							$stmt->close();
						} else {
							return self::$INSERT_ERROR;
						}
				}
			} else {
				return self::$CONNECT_ERROR;
			}
		}

		function delete_post_byid( $post_id ) {
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				$stmt = $this->conn->prepare( "SELECT PostImage FROM Notice WHERE PostID=?" );
				if( $stmt ) {
					$stmt->bind_param( "i", $post_id );
					$stmt->execute();
					$stmt->store_result();
					if( $stmt->num_rows == 1 ) {
						$stmt->bind_result( $post_image );
						$stmt->fetch();
						if( $image_db->delete_image_byhash( $post_image ) === ImageDBManager::$DELETE_ERROR ) { 
							$stmt->close();
							return self::$DELETE_ERROR;
						}
					}
					$stmt->close();
				} else {
					return self::$DELETE_ERROR;
				}
			} else {
				return self::$CONNECT_ERROR;
			}

			$stmt = $this->conn->prepare( "DELETE FROM Notice WHERE PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $post_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function update_post_byid( $post_id, $post_title, $post_content ) {
			$stmt = $this->conn->prepare( "UPDATE Notice SET PostTitle=?, PostContent=?, PostUpdate=? WHERE PostID=?" );
			if( $stmt ) {
				$stmt->bind_param( "sssi", $post_title, $post_content, date('Y-m-d H:i:s'), $post_id );
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
