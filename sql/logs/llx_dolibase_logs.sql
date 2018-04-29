-- <Dolibase logs table>
-- Copyright (C) <2018>  <AXeL>
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see <http://www.gnu.org/licenses/>.

CREATE TABLE llx_dolibase_logs(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	module_id INTEGER NOT NULL,
	module_name VARCHAR(255) NOT NULL,
	object_id INTEGER NOT NULL,
	action VARCHAR(255) NOT NULL,
	datec DATETIME NOT NULL,
	fk_user INTEGER NOT NULL
);
