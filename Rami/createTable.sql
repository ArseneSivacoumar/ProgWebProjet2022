create table utilisateur (
    Username varchar(255) NOT NULL UNIQUE,
    Nom varchar(255) NOT NULL,
    Prenom varchar(255) NOT NULL,
    Password varchar(255) NOT NULL,
    Connected int NOT NULL,
    AdresseMail varchar(255) PRIMARY KEY
    );