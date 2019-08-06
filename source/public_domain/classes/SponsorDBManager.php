<?php

	class SponsorDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_sponsor";
		private static $PASSWORD = "9NnpFpde5Ux0";

		public static $CONNECT_ERROR = -1;
		public static $INSERT_ERROR = -2;
		public static $SELECT_ERROR = -3;
		public static $DELETE_ERROR = -4;
		public static $UPDATE_ERROR = -5;

		public static $INVALID_SIZE = 1;
		public static $INVALID_TYPE = 2;

		private $conn;

		private function __construct() {
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_SPONSOR" );
			$this->conn->set_charset('utf8');
		}

		static function createSponsorDBManager() {
			$rtn_obj = new SponsorDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
		}

		function select_all_sponsors() {
			$stmt = $this->conn->prepare( "SELECT SponsorID, SponsorTitle, SponsorImage FROM Sponsor" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $title, $image );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "SponsorID"=>$id, "SponsorTitle"=>$title, "SponsorImage"=>$image ];
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

		function insert_new_sponsor( $sponsor_title, $sponsor_image_assoc ) {
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				$image_result = $image_db->insert_image( $sponsor_image_assoc );
				switch( $image_result ) {
					case ImageDBManager::$INSERT_ERROR :
						return self::$INSERT_ERROR;
					case ImageDBManager::$INVALID_SIZE :
						return self::$INVALID_SIZE;
					case ImageDBManager::$INVALID_TYPE :
						return self::$INVALID_TYPE;
					default :
						$stmt = $this->conn->prepare( "INSERT INTO Sponsor (SponsorTitle, SponsorImage) VALUES (?, ?)" );
						if( $stmt ) {
							$stmt->bind_param( "ss", $sponsor_title, $image_result );
							$stmt->execute();
							$stmt->close();
						} else {
							return self::INSERT_ERROR;
						}
				}
			} else {
				return self::$CONNECT_ERROR;
			}
		}

		function delete_sponsor_byid( $sponsor_id ) {
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				$stmt = $this->conn->prepare( "SELECT SponsorImage FROM Sponsor WHERE SponsorID=?" );
				if( $stmt ) {
					$stmt->bind_param( "i", $sponsor_id );
					$stmt->execute();
					$stmt->store_result();
					if( $stmt->num_rows == 1 ) {
						$stmt->bind_result( $sponsor_image );
						$stmt->fetch();
						if( $image_db->delete_image_byhash( $sponsor_image ) === ImageDBManager::$DELETE_ERROR ) { 
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

			$stmt = $this->conn->prepare( "DELETE FROM Sponsor WHERE SponsorID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $sponsor_id );
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
