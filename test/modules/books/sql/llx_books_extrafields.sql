-- <Dolibase extrafields table>
-- Copyright (C) <2018>  <AXeL>

CREATE TABLE llx_books_extrafields(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	tms TIMESTAMP,
	fk_object INTEGER NOT NULL,
	import_key VARCHAR(14)
);
