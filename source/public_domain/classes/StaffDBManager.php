<?php

	class StaffDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_staff";
		private static $PASSWORD = "y7TLyFJ1h23G";

		public static $CONNECT_ERROR = -1;
		public static $INSERT_ERROR = -2;
		public static $SELECT_ERROR = -3;
		public static $DELETE_ERROR = -4;
		public static $UPDATE_ERROR = -5;

		public static $INVALID_SIZE = 1;
		public static $INVALID_TYPE = 2;

		private $conn;
		
		private function __construct() {
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_STAFF" );
			$this->conn->set_charset('utf8');
		}

		static function createStaffDBManager() {
			$rtn_obj = new StaffDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
		}

		function get_num_staffs() {
			$select_query = "SELECT COUNT(*) FROM Staffs";
			$result = $this->conn->query( $select_query );
			$rtn_value = $result->fetch_array()[0];
			$result->free();
			return $rtn_value;
		}

		function insert_new_staff( $staff_name, $staff_eng_name, $staff_position, $staff_school, $staff_phone_no, $staff_email, $priority ) {
			$stmt = $this->conn->prepare( "UPDATE Staffs SET priority = priority+1 WHERE priority >= ?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $priority );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}

			$stmt = $this->conn->prepare( "INSERT INTO Staffs (StaffName, StaffEngName, StaffPosition, StaffSchool, StaffPhoneNo, "
								. "StaffEmail, priority) VALUES (?, ?, ?, ?, ?, ?, ?)" );
			if( $stmt ) {
				$stmt->bind_param( "sssissi", $staff_name, $staff_eng_name, $staff_position, $staff_school, $staff_phone_no,
								$staff_email, $priority );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}

			$stmt = $this->conn->prepare( "SELECT StaffID FROM Staffs WHERE priority=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $priority );
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result( $rtn_val );
				if( $stmt->num_rows == 1 )
					$stmt->fetch();
				else
					$rtn_val= self::$INSERT_ERROR;
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}
			return $rtn_val;
		}

		function select_all_staffs() {
			$stmt = $this->conn->prepare( "SELECT StaffID, StaffName, StaffEngName, StaffPosition, StaffSchool, StaffPhoneNo, "
							. "StaffEmail, StaffImage, priority FROM Staffs ORDER BY priority" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $eng_name, $position, $school, $phone_no, $email, $image, $priority );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "StaffID"=>$id, "StaffName"=>$name, "StaffEngName"=>$eng_name, "StaffPosition"=>$position,
									"StaffSchool"=>$school, "StaffPhoneNo"=>$phone_no, "StaffEmail"=>$email,
									"StaffImage"=>$image, "priority"=>$priority ];
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

		function select_staff_byid( $staff_id ) {
			$stmt = $this->conn->prepare( "SELECT StaffID, StaffName, StaffEngName, StaffPosition, StaffSchool, StaffPhoneNo, "
							. "StaffEmail, StaffImage, priority FROM Staffs WHERE StaffID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $staff_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["StaffID"], $rtn_row["StaffName"], $rtn_row["StaffEngName"], $rtn_row["StaffPosition"],
						$rtn_row["StaffSchool"], $rtn_row["StaffPhoneNo"], $rtn_row["StaffEmail"], $rtn_row["StaffImage"], $rtn_row["priority"] );
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

		function update_staff_image_byid( $staff_id, $staff_image_assoc, $prev_image_hash ) {
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				$image_result = $image_db->insert_image( $staff_image_assoc );
				switch( $image_result ) {
					case ImageDBManager::$INSERT_ERROR :
						return self::$UPDATE_ERROR;
					case ImageDBManager::$INVALID_SIZE :
						return self::$INVALID_SIZE;
					case ImageDBManager::$INVALID_TYPE :
						return self::$INVALID_TYPE;
					default :
						$stmt = $this->conn->prepare( "UPDATE Staffs SET StaffImage=? WHERE StaffID=?" );
						if( $stmt ) {
							$stmt->bind_param( "si", $image_result, $staff_id );
							$stmt->execute();
							$stmt->close();
						} else {
							return self::$UPDATE_ERROR;
						}
				}
				if( isset( $prev_image_hash ) )
					if( $image_db->delete_image_byhash( $prev_image_hash ) === ImageDBManager::$DELETE_ERROR ) { return self::$UPDATE_ERROR; }
			} else {
				return self::$CONNECT_ERROR;
			}
		}

		function update_staff_byid( $staff_id, $staff_name, $staff_eng_name, $staff_position, $staff_school, $staff_phone_no, $staff_email ) {
			$stmt = $this->conn->prepare( "UPDATE Staffs SET StaffName=?, StaffEngName=?, StaffPosition=?, StaffSchool=?, "
						. "StaffPhoneNo=?, StaffEmail=? WHERE StaffID=?" );
			if( $stmt ) {
				$stmt->bind_param( "sssissi", $staff_name, $staff_eng_name, $staff_position, $staff_school, $staff_phone_no, $staff_email, $staff_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function delete_staff_byid( $staff_id, $image_hash, $priority ) { 
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				if( isset( $image_hash ) )
					if( $image_db->delete_image_byhash( $image_hash ) === ImageDBManager::$DELETE_ERROR ) { return self::$DELETE_ERROR; }
			} else {
				return self::$CONNECT_ERROR;
			}

			$stmt = $this->conn->prepare( "DELETE FROM Staffs WHERE StaffID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $staff_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}

			$stmt = $this->conn->prepare( "UPDATE Staffs SET priority = priority-1 WHERE priority > ?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $priority );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function flipflop_priority_bypriority( $priority_1, $priority_2 ) {
			$stmt = $this->conn->prepare( "UPDATE Staffs AS staffs1 JOIN Staffs AS staffs2 ON staffs1.priority=? AND staffs2.priority=? "
								. "SET staffs1.priority=staffs2.priority, staffs2.priority=staffs1.priority" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $priority_1, $priority_2 );
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
