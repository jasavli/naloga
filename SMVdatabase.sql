CREATE DATABASE SpletnaUcilnica
USE SpletnaUcilnica

CREATE TABLE Administrator
(
 id_administratorja int NOT NULL PRIMARY KEY,
 ime         nvarchar(50) NOT NULL ,
 priimek     nvarchar(50) NOT NULL ,
 tel_st    int NOT NULL ,
 mail        nvarchar(50) NOT NULL ,
 kabinet     nvarchar(50),
 geslo password(20) NOT NULL,
);

CREATE TABLE Ucitelj
(
 id_ucitelja int NOT NULL PRIMARY KEY,
 ime         nvarchar(50) NOT NULL ,
 priimek     nvarchar(50) NOT NULL ,
 tel_st    int NOT NULL ,
 mail        nvarchar(50) NOT NULL ,
 kabinet     nvarchar(50) NOT NULL ,
 geslo password(20) NOT NULL,
);


CREATE TABLE Razred
(
 id_razreda  nvarchar(3) NOT NULL PRIMARY KEY,
 id_ucitelja int NOT NULL FOREIGN KEY REFERENCES Ucitelj(id_ucitelja),
 st_ucencev int NOT NULL ,
);

CREATE TABLE Ucenec
(
 id_ucenca  int NOT NULL PRIMARY KEY,
 ime        nvarchar(50) NOT NULL ,
 priimek    nvarchar(50) NOT NULL ,
 mail       nvarchar(50) NULL ,
 tel_st   int NULL ,
 emso       int NOT NULL ,
 id_razreda nvarchar(3) NOT NULL FOREIGN KEY REFERENCES Razred(id_razreda),
 geslo password(20) NOT NULL,
);


CREATE TABLE Predmet
(
 id_predmeta  int NOT NULL PRIMARY KEY,
 ime_predmeta nvarchar(50) NOT NULL ,
 id_ucenca    int NOT NULL FOREIGN KEY REFERENCES Ucenec(id_ucenca),
 id_ucitelja  int NOT NULL FOREIGN KEY REFERENCES Ucitelj(id_ucitelja),
 st_ur       int NOT NULL ,
 ocena        float NULL ,
);


CREATE TABLE Gradiva
(
 id_gradiva     int NOT NULL PRIMARY KEY,
 naslov_gradiva nvarchar(150) NULL ,
 navodilo       nvarchar(500) NULL ,
 datoteke       nvarchar(100)  NULL ,
 id_ucenca    int NOT NULL FOREIGN KEY REFERENCES Ucenec(id_ucenca),
 id_ucitelja  int NOT NULL FOREIGN KEY REFERENCES Ucitelj(id_ucitelja),
 rok_oddaje     datetime NULL ,
);


CREATE TABLE OddaneNaloge
(
 id_naloge    int NOT NULL PRIMARY KEY,
 id_gradiva   int NOT NULL FOREIGN KEY REFERENCES Gradiva(id_gradiva),
 datum_oddaje datetime NULL ,
 komentarji   nvarchar(500) NULL ,
);

/*
CREATE TABLE Administrator
(
 id_administratorja int NOT NULL PRIMARY KEY,
 ime         varchar(50) NOT NULL,
 priimek     varchar(50) NOT NULL,
 tel_st      varchar(20) NOT NULL,
 mail        varchar(50) NOT NULL,
 kabinet     varchar(50),
 geslo       varchar(255) NOT NULL -- Use a hashed password with enough length
);

CREATE TABLE Ucitelj
(
 id_ucitelja int NOT NULL PRIMARY KEY,
 ime         varchar(50) NOT NULL,
 priimek     varchar(50) NOT NULL,
 tel_st      varchar(20) NOT NULL,
 mail        varchar(50) NOT NULL,
 kabinet     varchar(50) NOT NULL,
 geslo       varchar(255) NOT NULL -- Use a hashed password with enough length
);

CREATE TABLE Razred
(
 id_razreda  varchar(3) NOT NULL PRIMARY KEY,
 id_ucitelja int NOT NULL,
 st_ucencev int NOT NULL,
 FOREIGN KEY (id_ucitelja) REFERENCES Ucitelj(id_ucitelja)
);

CREATE TABLE Ucenec
(
 id_ucenca  int NOT NULL PRIMARY KEY,
 ime        varchar(50) NOT NULL,
 priimek    varchar(50) NOT NULL,
 mail       varchar(50) NULL,
 tel_st     varchar(20) NULL,
 emso       varchar(13) NOT NULL, -- EMŠO is typically stored as a string
 id_razreda varchar(3) NOT NULL,
 geslo      varchar(255) NOT NULL, -- Use a hashed password with enough length
 FOREIGN KEY (id_razreda) REFERENCES Razred(id_razreda)
);

CREATE TABLE Predmet
(
 id_predmeta  int NOT NULL PRIMARY KEY,
 ime_predmeta varchar(50) NOT NULL,
 id_ucenca    int NOT NULL,
 id_ucitelja  int NOT NULL,
 st_ur       int NOT NULL,
 ocena        float NULL,
 FOREIGN KEY (id_ucenca) REFERENCES Ucenec(id_ucenca),
 FOREIGN KEY (id_ucitelja) REFERENCES Ucitelj(id_ucitelja)
);

CREATE TABLE Gradiva
(
 id_gradiva     int NOT NULL PRIMARY KEY,
 naslov_gradiva varchar(150) NULL,
 navodilo       varchar(500) NULL,
 datoteke       varchar(100) NULL,
 id_ucenca      int NOT NULL,
 id_ucitelja    int NOT NULL,
 rok_oddaje     datetime NULL,
 FOREIGN KEY (id_ucenca) REFERENCES Ucenec(id_ucenca),
 FOREIGN KEY (id_ucitelja) REFERENCES Ucitelj(id_ucitelja)
);

CREATE TABLE OddaneNaloge
(
 id_naloge    int NOT NULL PRIMARY KEY,
 id_gradiva   int NOT NULL,
 datum_oddaje datetime NULL,
 komentarji   varchar(500) NULL,
 FOREIGN KEY (id_gradiva) REFERENCES Gradiva(id_gradiva)
);

*/