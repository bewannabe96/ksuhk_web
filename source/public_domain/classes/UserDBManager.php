<?php

	class UserDBManager {
		private static $SERV_ADDR = "localhost";
		private static $USERNAME = "ksuhkcom_user";
		private static $PASSWORD = "0hyRHnQ7buEl";

		public static $CONNECT_ERROR = -1;
		public static $INSERT_ERROR = -2;
		public static $SELECT_ERROR = -3;
		public static $DELETE_ERROR = -4;
		public static $UPDATE_ERROR = -5;

		public static $USER_NOT_EXIST = 1;
		public static $PASSWORD_INCORRECT = 2;
		public static $VERIFIED = 3;
		public static $WAITING_ACCEPT = 4;
		public static $REFUSED_ACCEPT = 5;

		private $conn;
		
		private function __construct() {
			$this->conn = new mysqli( self::$SERV_ADDR, self::$USERNAME, self::$PASSWORD, "ksuhkcom_USER" );
			$this->conn->set_charset('utf8');
		}

		static function createUserDBManager() {
			$rtn_obj = new UserDBManager();
			if( $rtn_obj->conn->connect_error )
				return self::$CONNECT_ERROR;
			else
				return $rtn_obj;
		}

		function insert_new_user( $user_name, $user_username, $user_password, $user_school, $user_grade, $user_major,
										$user_phone_no, $user_email, $user_birth, $user_kakao_id ) {
			$signupdate = date('Y-m-d H:i:s');
			$stmt = $this->conn->prepare( "INSERT INTO Users (UserName, UserUsername, UserPassword, UserSchool, UserGrade, "
					. "UserMajor, UserPhoneNo, UserEmail, UserBirth, UserRegister) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" );
			if( $stmt ) {
				$stmt->bind_param( "sssiiissss", $user_name, $user_username, $user_password, $user_school, $user_grade, $user_major,
							$user_phone_no, $user_email, $user_birth, $signupdate );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$INSERT_ERROR;
			}

			if( $user_kakao_id != "" ) {
				$stmt = $this->conn->prepare( "UPDATE Users SET UserKakaoID=? WHERE UserUsername=?" );
				if( $stmt ) {
					$stmt->bind_param( "ss", $user_kakao_id, $user_username );
					$stmt->execute();
					$stmt->close();
				} else {
					return self::$INSERT_ERROR;
				}
			}
		}

		function select_user_byusername( $user_username ) {
			$stmt = $this->conn->prepare( "SELECT UserName, UserUsername, UserSchool, UserGrade, UserMajor, UserPhoneNo, UserEmail, "
					. "UserBirth, UserKakaoID FROM Users WHERE UserUsername=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $user_username );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_row["UserName"], $rtn_row["UserUsername"], $rtn_row["UserSchool"], $rtn_row["UserGrade"],
						$rtn_row["UserMajor"], $rtn_row["UserPhoneNo"], $rtn_row["UserEmail"], $rtn_row["UserBirth"], $rtn_row["UserKakaoID"] );
					$stmt->fetch();
				} else {
					$rtn_row = self::$USER_NOT_EXIST;
				}
				$stmt->close();

			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_row;
		}

		function select_username_byemail( $user_name, $user_email ) {
			$stmt = $this->conn->prepare( "SELECT UserUsername FROM Users WHERE UserName=? AND UserEmail=?" );
			if( $stmt ) {
				$stmt->bind_param( "ss", $user_name, $user_email );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows == 1 ) {
					$stmt->bind_result( $rtn_val );
					$stmt->fetch();
				} else {
					$rtn_val = self::$USER_NOT_EXIST;
				}
				$stmt->close();

			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_val;
		}

		function update_user_byusername( $user_username, $user_school, $user_grade, $user_major, $user_phone_no, $user_kakao_id ) {
			$stmt = $this->conn->prepare( "UPDATE Users SET UserSchool=?, UserGrade=?, UserMajor=?, UserPhoneNo=?, UserKakaoID=?, "
							. "UserAcceptance=1 WHERE UserUsername=?" );
			if( $stmt ) {
				$stmt->bind_param( "iiisss", $user_school, $user_grade, $user_major, $user_phone_no, $user_kakao_id, $user_username );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function update_user_password( $user_username, $user_password ) {
			$stmt = $this->conn->prepare( "UPDATE Users SET UserPassword=? WHERE UserUsername=?" );
			if( $stmt ) {
				$stmt->bind_param( "ss", $user_password, $user_username );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function update_acceptance( $user_id, $user_acceptance ) {
			$stmt = $this->conn->prepare( "UPDATE Users SET UserAcceptance=? WHERE UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "ii", $user_acceptance, $user_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$UPDATE_ERROR;
			}
		}

		function delete_user_byid( $user_id ) {
			$stmt = $this->conn->prepare( "DELETE FROM Users WHERE UserID=?" );
			if( $stmt ) {
				$stmt->bind_param( "i", $user_id );
				$stmt->execute();
				$stmt->close();
			} else {
				return self::$DELETE_ERROR;
			}
		}

		function check_user_login( $user_username, $user_password ) {
			$stmt = $this->conn->prepare( "SELECT UserID, UserName, UserUsername, UserPassword, UserAcceptance FROM Users WHERE UserUsername=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $user_username );
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result( $rtn_row["UserID"], $rtn_row["UserName"], $rtn_row["UserUsername"],
							$rtn_row["UserPassword"], $rtn_row["UserAcceptance"] );
				if( $stmt->num_rows == 1 ) {
					$stmt->fetch();
					if( $rtn_row["UserPassword"] !== $user_password )
						$rtn_row = self::$PASSWORD_INCORRECT;
					else if( $rtn_row["UserAcceptance"] == 1 )
						$rtn_row = self::$WAITING_ACCEPT;
					else if( $rtn_row["UserAcceptance"] == 3 )
						$rtn_row = self::$REFUSED_ACCEPT;
					else
						unset( $rtn_row["UserPassword"] );
				} else {
					$rtn_row = self::$USER_NOT_EXIST;
				}
				$stmt->close();

			} else {
				return self::$SELECT_ERROR;
			}

			return $rtn_row;
		}

		function verify_admin_auth( $admin_username, $admin_password ) {
			$stmt = $this->conn->prepare( "SELECT UserUsername, UserPassword FROM Users WHERE UserID=1" );
			if( $stmt ) {
				$stmt->execute();
				$stmt->store_result();
				$stmt->bind_result( $row["UserUsername"], $row["UserPassword"] );
				$stmt->fetch();
				if( $row["UserUsername"] == $admin_username ) {
					if( $row["UserPassword"] == $admin_password )
						$rtn_stat = self::$VERIFIED;
					else
						$rtn_stat = self::$PASSWORD_INCORRECT;
				} else {
					$rtn_stat = self::$USER_NOT_EXIST;
				}
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_stat;
		}

		function user_exist( $user_username ) {
			$stmt = $this->conn->prepare( "SELECT UserID FROM Users WHERE UserUsername=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $user_username );
				$stmt->execute();
				$stmt->store_result();
				$rtn_stat = $stmt->num_rows == 1 ? TRUE : FALSE;
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_stat;
		}

		function email_exist( $user_email ) {
			$stmt = $this->conn->prepare( "SELECT UserID FROM Users WHERE UserEmail=?" );
			if( $stmt ) {
				$stmt->bind_param( "s", $user_email );
				$stmt->execute();
				$stmt->store_result();
				$rtn_stat = $stmt->num_rows == 1 ? TRUE : FALSE;
				$stmt->close();
			} else {
				return self::$SELECT_ERROR;
			}
			return $rtn_stat;
		}

		function search_users( $user_name, $user_username, $user_school, $user_grade, $user_major, $user_phone_no,
					$user_email, $user_birth, $user_kakao_id, $user_acceptance, $limit, $offset ) {
			$type = "";
			$params = array();
			$count_query = "SELECT COUNT(*) FROM Users WHERE UserID!=1";
			$select_query = "SELECT UserID, UserName, UserUsername, UserSchool, UserGrade, UserMajor, "
						. "UserPhoneNo, UserEmail, UserBirth, UserKakaoID, DATE(UserRegister), UserAcceptance FROM Users WHERE UserID!=1";

			if( $user_name !== "" ) {
				$select_query = $select_query . " AND UserName LIKE ?";
				$count_query = $count_query . " AND UserName LIKE ?";
				$type .= "s";
				$user_name = "%$user_name%";
				$params[] = &$user_name;
			}
			if( $user_username !== "" ) {
				$select_query = $select_query . " AND UserUsername LIKE ?";
				$count_query = $count_query . " AND UserUsername LIKE ?";
				$type .= "s";
				$user_username = "%$user_username%";
				$params[] = &$user_username;
			}
			if( $user_school != 0 ) {
				$select_query = $select_query . " AND UserSchool=?";
				$count_query = $count_query . " AND UserSchool=?";
				$type .= "i";
				$params[] = &$user_school;
			}
			if( $user_grade != 0 ) {
				$select_query = $select_query . " AND UserGrade=?";
				$count_query = $count_query . " AND UserGrade=?";
				$type .= "i";
				$params[] = &$user_grade;
			}
			if( $user_major != 0 ) {
				$select_query = $select_query . " AND UserMajor=?";
				$count_query = $count_query . " AND UserMajor=?";
				$type .= "i";
				$params[] = &$user_major;
			}
			if( $user_phone_no !== ""  ) {
				$select_query = $select_query . " AND UserPhoneNo LIKE ?";
				$count_query = $count_query . " AND UserPhoneNo LIKE ?";
				$type .= "s";
				$user_phone_no = "%$user_phone_no%";
				$params[] = &$user_phone_no;
			}
			if( $user_email !== ""  ) {
				$select_query = $select_query . " AND UserEmail LIKE ?";
				$count_query = $count_query . " AND UserEmail LIKE ?";
				$type .= "s";
				$user_email = "%$user_email%";
				$params[] = &$user_email;
			}
			if( $user_birth !== ""  ) {
				$select_query = $select_query . " AND UserBirth=?";
				$count_query = $count_query . " AND UserBirth=?";
				$type .= "s";
				$params[] = &$user_birth;
			}
			if( $user_kakao_id !== ""  ) {
				$select_query = $select_query . " AND UserKakaoID LIKE ?";
				$count_query = $count_query . " AND UserKakaoID LIKE ?";
				$type .= "s";
				$user_kakao_id = "%$user_kakao_id%";
				$params[] = &$user_kakako_id;
			}
			if( $user_acceptance != 0  ) {
				$select_query = $select_query . " AND UserAcceptance=?";
				$count_query = $count_query . " AND UserAcceptance=?";
				$type .= "i";
				$params[] = &$user_acceptance;
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

			$select_query = $select_query . " LIMIT ? OFFSET ?";	
			$type .= "ii";
			$params[] = &$limit;
			$params[] = &$offset;

			$stmt = $this->conn->prepare( $select_query );
			if( $stmt ) {
				call_user_func_array( array( $stmt, 'bind_param' ), array_merge( array($type), $params ) );
				$stmt->execute();
				$stmt->store_result();
				if( $stmt->num_rows > 0 ) {
					$stmt->bind_result( $id, $name, $username, $school, $grade, $major, $phone_no, $email, $birth, $kakao_id, $register, $acceptance );
					$rtn_rows = array();
					while( $stmt->fetch() ) {
						$rtn_rows[] = [ "UserID"=>$id, "UserName"=>$name, "UserUsername"=>$username, "UserSchool"=>$school,
								"UserGrade"=>$grade, "UserMajor"=>$major, "UserPhoneNo"=>$phone_no, "UserEmail"=>$email,
								"UserBirth"=>$birth, "UserKakaoID"=>$kakao_id, "UserRegister"=>$register, "UserAcceptance"=>$acceptance ];
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

		function __destruct() {
			$this->conn->close();
		}
	}

?>
