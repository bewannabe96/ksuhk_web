<?php

	class ImageDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_image";
		private static $PASSWORD = "H8cUzwbH4gIl";

		public static $CONNECT_ERROR = -1;
		public static $INSERT_ERROR = -2;
		public static $SELECT_ERROR = -3;
		public static $DELETE_ERROR = -4;
		public static $UPDATE_ERROR = -5;

		public static $INVALID_SIZE = 1;
		public static $INVALID_TYPE = 2;
		public static $IMAGE_NULL = 3;

		public static $ALLOWED_TYPES = array(IMAGETYPE_PNG, IMAGETYPE_JPEG);
		public static $MAX_SIZE = 2097152; // 2MB

		private $conn;
		
		private function __construct() {
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_IMAGE" );
			$this->conn->set_charset('utf8');
		}

		static function createImageDBManager() {
			$rtn_obj = new ImageDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
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
					$image_hash = hash( "sha256", time().$image_size );
		
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

		function __destruct() {
			$this->conn->close();
		}
	}

?>
