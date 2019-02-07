-- <one line to give the program's name and a brief idea of what it does.>
-- Copyright (C) <year>  <name of author>

CREATE TABLE llx_books(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
        ref VARCHAR(30) NOT NULL,
        name VARCHAR(100) NOT NULL,
        `desc` VARCHAR(255) NULL,
        type INTEGER NOT NULL,
        qty DOUBLE NOT NULL,
        price DOUBLE DEFAULT NULL,
        publication_date DATETIME NULL,
        creation_date DATETIME NOT NULL,
        created_by INTEGER NOT NULL,
        model_pdf VARCHAR(255) DEFAULT NULL
);
