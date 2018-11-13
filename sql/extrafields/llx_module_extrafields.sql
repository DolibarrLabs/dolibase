-- <Dolibase extrafields table example>
-- Copyright (C) <2018>  <AXeL>

-- 'module' should be replaced with the name of module

CREATE TABLE llx_module_extrafields(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	tms TIMESTAMP,
	fk_object INTEGER NOT NULL,
	import_key VARCHAR(14)
);
