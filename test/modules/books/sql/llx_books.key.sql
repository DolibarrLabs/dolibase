-- <one line to give the program's name and a brief idea of what it does.>
-- Copyright (C) <year>  <name of author>

ALTER TABLE llx_books ADD UNIQUE INDEX uk_ref (ref);
ALTER TABLE llx_books ADD CONSTRAINT fk_book_created_by FOREIGN KEY (created_by) REFERENCES llx_user (rowid);

ALTER TABLE llx_books ADD CONSTRAINT fk_book_type FOREIGN KEY (type) REFERENCES llx_books_dict (rowid);
