-- <Dolibase logs table>
-- Copyright (C) <2018>  <AXeL>

CREATE TABLE llx_dolibase_logs(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	module_id INTEGER NOT NULL,
	module_name VARCHAR(255) NOT NULL,
	object_id INTEGER NOT NULL,
	object_element VARCHAR(100) DEFAULT NULL,
	action VARCHAR(255) NOT NULL,
	datec DATETIME NOT NULL,
	fk_user INTEGER NOT NULL
);
