-- <Dolibase logs table>
-- Copyright (C) <2018>  <AXeL>

ALTER TABLE llx_dolibase_logs ADD CONSTRAINT fk_dolibase_logs_user FOREIGN KEY (fk_user) REFERENCES llx_user (rowid);
