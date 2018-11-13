-- <Dolibase dictionary table example>
-- Copyright (C) <2018>  <AXeL>

-- 'module' should be replaced with the name of module

CREATE TABLE llx_module_dict(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	label VARCHAR(255) NOT NULL,
	active INTEGER DEFAULT 1
);
