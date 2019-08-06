<?php

	class EventDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_event";
		private static $PASSWORD = "lhGfm72FmVbu";

		public static $CONNECT_ERROR = -1;
		public static $INSERT_ERROR = -2;
		public static $SELECT_ERROR = -3;
		public static $DELETE_ERROR = -4;
		public static $UPDATE_ERROR = -5;

		public static $INVALID_SIZE = 1;
		public static $INVALID_TYPE = 2;
		public static $JOINED_FULL = 3;
		public static $JOIN_INVALID = 4;
		public static $JOIN_CLOSED = 5;
		public static $ALREADY_JOINED = 6;

		private $conn;
		
		private function __construct() {
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_EVENT" );
			$this->conn->set_charset('utf8');
		}

		static function createEventDBManager() {
			$rtn_obj = new EventDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
		}

		function get_num_events_bystatus( $event_status ) {
			$select_query = "SELECT COUNT(*) FROM Event";
			if( $event_status != 0 )
				$select_query = $select_query . " WHERE EventStatus=?";
			else
				$select_query = $select_query . " WHERE EventStatus>?";
			$stmt = $this->conn->prepare( $select_query );
			$stmt->bind_param( "i", $event_status );
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result( $rtn_val );
			$stmt->fetch();
			$stmt->close();
			return $rtn_val;
		}

		function get_num_joins_byid( $event_id ) {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM EventJoin$event_id" );
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result( $rtn_val );
			$stmt->fetch();
			$stmt->close();
			return $rtn_val;
		}

		function insert_new_event( $event_title, $event_start, $event_end, $event_address, $event_latitude, $event_longitude,
						$event_currency, $event_cost, $event_max_invite, $event_invite_enable, $event_content, $event_image_assoc) {
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				$image_result = $image_db->insert_image( $event_image_assoc );
				switch( $image_result ) {
					case ImageDBManager::$INSERT_ERROR :
						return self::$INSERT_ERROR;
					case ImageDBManager::$INVALID_SIZE :
						return self::$INVALID_SIZE;
					case ImageDBManager::$INVALID_TYPE :
						return self::$INVALID_TYPE;
					case ImageDBManager::$IMAGE_NULL :
						$postdate = date('Y-m-d H:i:s');

						$stmt = $this->conn->prepare( "INSERT INTO Event (EventTitle, EventPostdate, EventUpdate, EventStart, EventEnd, "
								. "EventAddress, EventLatitude, EventLongitude, EventCurrency, EventCost, EventMaxInvite, "
								. "EventInviteEnable, EventContent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" );
						if( $stmt ) {
							$stmt->bind_param( "ssssssddiiiis", $event_title, $postdate, $postdate, $event_start, $event_end, 
										$event_address, $event_latitude, $event_longitude, $event_currency, $event_cost,
										$event_max_invite, $event_invite_enable, $event_content );
							$stmt->execute();
						} else {
							return self::$INSERT_ERROR;
						}

						if( $event_invite_enable == 2 ) {
							$stmt = $this->conn->prepare( "SELECT EventID FROM Event WHERE EventPostdate=?" );
							$stmt->bind_param( "s", $postdate );
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result( $event_id );
							$stmt->fetch();
							$stmt->close();
	
							$stmt = $this->conn->prepare( "CREATE TABLE EventJoin$event_id ( "
											. "JoinID INT unsigned NOT NULL AUTO_INCREMENT, "
											. "UserID INT unsigned NOT NULL, "
											. "PRIMARY KEY (JoinID) ) ENGINE = INNODB "
											. "DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci" );
							if( $stmt ) {
								$stmt->execute();
								$stmt->close();
							} else {
								return self::$INSERT_ERROR;
							}
						}
						break;
					default :
						$postdate = date('Y-m-d H:i:s');

						$stmt = $this->conn->prepare( "INSERT INTO Event (EventTitle, EventPostdate, EventUpdate, EventStart, EventEnd, "
								. "EventAddress, EventLatitude, EventLongitude, EventCurrency, EventCost, EventMaxInvite, "
								. "EventInviteEnable, EventContent, EventImage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" );
						if( $stmt ) {
							$stmt->bind_param( "ssssssddiiiiss", $event_title, $postdate, $postdate, $event_start, $event_end, 
										$event_address, $event_latitude, $event_longitude, $event_currency, $event_cost,
										$event_max_invite, $event_invite_enable, $event_content, $image_result );
							$stmt->execute();
						} else {
							return self::$INSERT_ERROR;
						}

						if( $event_invite_enable == 2 ) {
							$stmt = $this->conn->prepare( "SELECT EventID FROM Event WHERE EventPostdate=?" );
							$stmt->bind_param( "s", $postdate );
							$stmt->execute();
							$stmt->store_result();
							$stmt->bind_result( $event_id );
							$stmt->fetch();
							$stmt->close();
	
							$stmt = $this->conn->prepare( "CREATE TABLE EventJoin$event_id ( "
											. "JoinID INT unsigned NOT NULL AUTO_INCREMENT, "
											. "UserID INT unsigned NOT NULL, "
											. "PRIMARY KEY (JoinID) ) ENGINE = INNODB "
											. "DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci" );
							if( $stmt ) {
								$stmt->execute();
								$stmt->close();
							} else {
								return self::$INSERT_ERROR;
							}
						}
				}
			} else {
				return self::$CONNECT_ERROR;
			}
		}

		function select_event_byid( $event_id ) {
			$stmt = $this->conn->prepare( "SELECT EventID, EventTitle, EventPostdate, EventUpdate, EventStart, EventEnd, EventAddress, EventLatitude, "
							. "EventLongitude, EventCurrency, EventCost, EventMaxInvite, EventContent, EventImage, EventStatus, "
							. "EventInviteEnable FROM Event WHERE EventID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $event_id );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["EventID"], $rtn_row["EventTitle"], $rtn_row["EventPostdate"], $rtn_row["EventUpdate"],
							$rtn_row["EventStart"], $rtn_row["EventEnd"], $rtn_row["EventAddress"], $rtn_row["EventLatitude"],
							$rtn_row["EventLongitude"], $rtn_row["EventCurrency"], $rtn_row["EventCost"], $rtn_row["EventMaxInvite"],
							$rtn_row["EventContent"], $rtn_row["EventImage"], $rtn_row["EventStatus"], $rtn_row["EventInviteEnable"] );
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

		function select_events_bystatus( $event_status, $limit, $offset ) {
			$select_query = "SELECT EventID, EventTitle, EventStart, EventEnd, DATE(EventPostdate), DATE(EventUpdate), "
						. "EventCurrency, EventCost, EventStatus FROM Event";
			if( $event_status != 0 )
				$select_query = $select_query . " WHERE EventStatus=?";
			else
				$select_query = $select_query . " WHERE EventStatus>?";
			$select_query = $select_query . " ORDER BY EventID DESC LIMIT ? OFFSET ?";	

			$stmt = $this->conn->prepare( $select_query );
			if( $stmt ) {
				$stmt->bind_param( "iii", $event_status, $limit, $offset );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $title, $start, $end, $postdate, $update, $currency, $cost, $status );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "EventID"=>$id, "EventTitle"=>$title, "EventStart"=>$start, "EventEnd"=>$end,
									"EventPostdate"=>$postdate, "EventUpdate"=>$update, "EventCurrency"=>$currency,
									"EventCost"=>$cost, "EventStatus"=>$status ];
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

		function select_joins( $event_id, $limit, $offset ) {
			$stmt = $this->conn->prepare( "SELECT ksuhkcom_USER.Users.UserName, ksuhkcom_USER.Users.UserUsername, ksuhkcom_USER.Users.UserSchool, "
						. "ksuhkcom_USER.Users.UserPhoneNo, ksuhkcom_USER.Users.UserEmail, ksuhkcom_USER.Users.UserKakaoID "
						. "FROM EventJoin$event_id JOIN ksuhkcom_USER.Users ON EventJoin$event_id.UserID = Users.UserID "
						. "ORDER BY EventJoin$event_id.JoinID LIMIT ? OFFSET ?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $limit, $offset );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $name, $username, $school, $phone_no, $email, $kakao_id );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "UserName"=>$name, "UserUsername"=>$username, "UserSchool"=>$school, "UserPhoneNo"=>$phone_no,
									"UserEmail"=>$email, "UserKakaoID"=>$kakao_id ];
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

		function delete_event_byid( $event_id ) {
			include 'ImageDBManager.php';
			$image_db = ImageDBManager::createImageDBManager();

			if( $image_db !== ImageDBManager::$CONNECT_ERROR ) {
				$stmt = $this->conn->prepare( "SELECT EventImage FROM Event WHERE EventID=?" );
				if( $stmt ) {
					$stmt->bind_param( "i", $event_id );
					$stmt->execute();
					$stmt->store_result();
					if( $stmt->num_rows == 1 ) {
						$stmt->bind_result( $event_image );
						$stmt->fetch();
						if( $image_db->delete_image_byhash( $event_image ) === ImageDBManager::$DELETE_ERROR ) { 
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

			$stmt = $this->conn->prepare( "DELETE FROM Event WHERE EventID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $event_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}

			$stmt = $this->conn->prepare( "DROP TABLE IF EXISTS EventJoin$event_id" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function can_join( $event_id ) {
			$stmt = $this->conn->prepare( "SELECT EventMaxInvite, EventInviteEnable FROM Event WHERE EventID=?" );
			$stmt->bind_param( "i", $event_id );
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result( $max_invite, $invite_enable );
			$stmt->fetch();
			$stmt->close();

			if( $invite_enable == 1 )
				return self::$JOIN_INVALID;
			else if( $invite_enable == 3 )
				return self::$JOIN_CLOSED;
			else if( $this->get_num_joins_byid( $event_id ) >= $max_invite && $max_invite != 0 )
				return self::$JOINED_FULL;
		}

		function close_invite( $event_id ) {
			$stmt = $this->conn->prepare( "UPDATE Event SET EventInviteEnable=3 WHERE EventID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $event_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function close_event( $event_id ) {
			$stmt = $this->conn->prepare( "UPDATE Event SET EventStatus=2 WHERE EventID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $event_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}

			$stmt = $this->conn->prepare( "DROP TABLE IF EXISTS EventJoin$event_id" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function is_joined( $event_id, $user_id ) {
			$stmt = $this->conn->prepare( "SELECT COUNT(*) FROM EventJoin$event_id WHERE UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $user_id );
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result( $num_rows );
				$stmt->fetch();
				$stmt->close();
				if( $num_rows != 0 ) { return self::$ALREADY_JOINED; }
			} else {
				return self::$INSERT_ERROR;
			}
		}

		function join_event( $event_id, $user_id ) {
			$stmt = $this->conn->prepare( "INSERT INTO EventJoin$event_id (UserID) VALUES (?)" );
			if( $stmt ) {
				$stmt->bind_param( "i", $user_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}
		}

		function out_event( $event_id, $user_id ) {
			$stmt = $this->conn->prepare( "DELETE FROM EventJoin$event_id WHERE UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $user_id );
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
