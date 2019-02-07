-- <Dolibase dictionary table example>
-- Copyright (C) <2018>  <AXeL>

CREATE TABLE llx_books_dict(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	label VARCHAR(255) NOT NULL,
	active INTEGER DEFAULT 1
);
