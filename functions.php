<?php
	abstract class db_CRUD_sqlite {
		protected $db_file = "cc_db.db";
		protected $db;
		
		//creates database connection
		public function open() {
			$this->db = new SQLite3($this->db_file);
		}
		
		//removes database connection
		public function close() {
			$this->db->close();
		}
		
		//query backend database
		abstract protected function select($table, $col, $where, $order);
	}
	
	class card extends db_CRUD_sqlite {
		protected $id;
		protected $name;
		protected $issuer;
		protected $apr;
		
		//implementation of abstracted partent function
		protected function select($table, $col = '*', $where = null, $order = null) {
			parent::open();
			$q = "SELECT $col FROM $table";
			if($where != null) {
				$q .= " WHERE $where";
			}
			if($order != null) {
				$q .= " ORDER BY $order";
			}
			$query = $this->db->query($q);
			if($query) {
				$numResults = 0;
				while($query->fetchArray()) {
					$numResults++;
				}
				$query->reset();
				if($numResults > 1) {
					while($result[] = $query->fetchArray(SQLITE3_ASSOC));
					array_pop($result);
					$query->finalize();
					parent::close();
					return $result;
				} elseif($numResults == 1) {
					$result = $query->fetchArray(SQLITE3_ASSOC);
					$query->finalize();
					parent::close();
					return $result;
				} else {
					return $this->db->lastErrorMsg();
				}
			} else {
				return $this->db->lastErrorMsg();
			}
		}
		
		public function get_card($id) {
			if(ctype_digit($id) && strlen($id) == 16) {
				return json_encode($this->select("cc", "id, name, issuer, apr", "id = '$id'"));
			} else {
				return json_encode(array("error"=>"type"));
			}
		}
		
		public function get_issuers() {
			return json_encode($this->select("cc", "DISTINCT issuer", null, 1));
		}
		
		public function get_customers($issuer) {
			return json_encode($this->select("cc", "name, id, apr", "issuer = '$issuer'", 2));
		}
		
		public function temp1() {
			parent::open();
			$this->db->exec("drop table cc");
			$this->db->exec('CREATE TABLE cc (id CHAR(16) PRIMARY KEY, name VARCHAR(255), issuer VARCHAR(255), apr REAL)');
			$this->db->exec("INSERT INTO cc VALUES ('1234512345123451', 'Ken Belles', 'Visa', 12.525)");
			$this->db->exec("INSERT INTO cc VALUES ('6789067890678906', 'Sam Belles', 'Mastercard', 21.425)");
			$this->db->exec("INSERT INTO cc VALUES ('1234567887654321', 'Kenneth Skertchly', 'Visa', 2.425)");
			$this->db->exec("INSERT INTO cc VALUES ('1001100110011001', 'Kyle Putman', 'American Express', 1.011");
			parent::close();
		}
	}
?>
