-- INNODB bruges, til at konvertere databasen til INNODB, da den ellers er iSam. Den skal konverteres, ellers virker designer ikke.
-- INNODB er sat in på alle tabeller

DROP DATABASE IF EXISTS ResturantDB; -- Fjerner indholdet i databasen, for at indhente ny tilgængelig data
CREATE DATABASE ResturantDB; -- Opretter databasen

USE ResturantDB; -- Angiver at det er denne database, der skal bruges

-- Fjerner indholdet i de forskellige tabeller, for at indhente ny tilgængelig data
DROP TABLE IF EXISTS PostalCode; 
DROP TABLE IF EXISTS rLocation;
DROP TABLE IF EXISTS Customer;
DROP TABLE IF EXISTS rTable;
DROP TABLE IF EXISTS Menu;
DROP TABLE IF EXISTS rTableMenu;
DROP TABLE IF EXISTS Food;
DROP TABLE IF EXISTS MenuFood;
DROP TABLE IF EXISTS Reservation;

CREATE TABLE PostalCode -- Opretter en tabel med det givne navn
(
    PostalID INT(4) NOT NULL PRIMARY KEY, -- PostalID er postkode, Jeg har valgt INT(4), da jeg tager udgangspunkt i DK, 
    --med 4 cifret postnummer. Der skal være en værdi da det er ID'et i tabellen der bruges af andre tabeller, derfor NOT NULL og primary key
    City VARCHAR(100) -- VARCHAR(100) bruger jer, for at gøre det muligt at bruge alle nødvendige tegn til bynavnet.
    --Jeg sætter værdien til 100, for at sikre, at man ikke løber tør for plads til bynavnet
)   Engine=InnoDB;  -- Jeg bruger denne kommando, da min database er sat til MyISam, og jeg ønsker Inno

CREATE TABLE rLocation 
(
    rLocationID INT AUTO_INCREMENT NOT NULL PRIMARY KEY, -- Her har jeg sat til ID, da det ikke har en betydning, hvad ID'et hedder
    StreetName VARCHAR(100),
    StreetNr INT(50),
    PostalID INT,
    FOREIGN KEY (PostalID) REFERENCES PostalCode (PostalID) -- Jeg har lavet et foreign key udfra PostalCode ID'et, da dette er relevant
    -- i forhold til resturantens lokation
)   Engine=InnoDB;

CREATE TABLE Customer
(
    CustomerID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    FirstName VARCHAR(100),
    LastName VARCHAR(100),
    PhoneNumber INT (50)
)   Engine=InnoDB;

CREATE TABLE rTable 
(
    rTableID int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    TableSize INT(100),
    rLocationID INT,
    FOREIGN KEY (rLocationID) REFERENCES rLocation (rLocationID)
)   Engine=InnoDB;

CREATE TABLE Menu
(
    MenuID int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    MenuName VARCHAR(100),
    mDescription TEXT(3000)
)   Engine=InnoDB;

CREATE TABLE rTableMenu -- Jeg har lavet et junction tabel, da rTable og Menu har en mange til mange relation
(
    rTableID int NOT NULL,
    MenuID int NOT NULL,
    CONSTRAINT rTableMenu_PK PRIMARY KEY (rTableID, MenuID), -- Denne kommando skaber den nye primærenøgle, for denne tabel
    FOREIGN KEY (rTableID) REFERENCES rTable (rTableID),
    FOREIGN KEY (MenuID) REFERENCES Menu (MenuID)
    -- Det er vigtigt at rækkefølgen i brugen af rTableID og MenuID er ens - hvis rTable står først, skal den gøre det hele vejen igennem
    -- Ellers har Databasen svært ved at finde ud af, hvad der er hvad
)   Engine=InnoDB;

CREATE TABLE Food
(
    FoodID INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    FoodName VARCHAR(100),
    Price DECIMAL(5,2)
)   Engine=InnoDB;

CREATE TABLE MenuFood
(
    MenuID int NOT NULL,
    FoodID int NOT NULL,
    CONSTRAINT MenuFood_PK PRIMARY KEY (MenuID, FoodID),
    FOREIGN KEY (MenuID) REFERENCES Menu (MenuID),
    FOREIGN KEY (FoodID) REFERENCES Food (FoodID)
)   Engine=InnoDB;

CREATE TABLE Reservation
(
    ReservationID int AUTO_INCREMENT NOT NULL PRIMARY KEY,
    ReservationDate INT(100),
    GroupSize INT(100),
    CheckIn INT (50),
    Checkout INT (50),
    rTableID INT,
    CustomerID INT,
    FOREIGN KEY (rTableID) REFERENCES rTable (rTableID),
    FOREIGN KEY (CustomerID) REFERENCES Customer (CustomerID)

)   Engine=InnoDB;

--Følgende er testdata

INSERT INTO PostalCode VALUE ('6760','Ribe'); -- INSERT INTO PostalCode angiver, hvor indholdet skal palceres. Herefter fortæller VALUE
-- at det er en "værdi", altså data, der skal sættes ind. Rækkefølgen er her relevant, da den sætter indholdet ind 1:1. Så hvis jeg i dette
-- tilfælde bytter rundt på 6760 og Ribe, Vil Ribe blive den primære nøgle. Udover at indholdet vil være ugyldigt, da den først culonne
-- Er sat til at have INT værdi - kun hele tal
INSERT INTO rLocation VALUE (NULL, 'Skolegade', 'a4', 6760); -- I tabeller hvor ID'et er sat til AI, skal denne markeres med en NULL værdi

INSERT INTO Customer VALUE (NULL,'Nanna', 'Møller', '51239423');
INSERT INTO Customer VALUE (NULL,'Sanne', 'Christensen', '32498512');
INSERT INTO Customer VALUE (NULL,'Lars', 'Andersen', '93439423');
INSERT INTO Customer VALUE (NULL,'Nynne', 'Amalie', '51232343');
INSERT INTO Customer VALUE (NULL,'Morten', 'Møller', '53465423');
INSERT INTO Customer VALUE (NULL,'Nina', 'Valentin', '51239938');
INSERT INTO Customer VALUE (NULL,'Rosa', 'Rulle', '54392423');
INSERT INTO Customer VALUE (NULL,'Nikolaj', 'Nulle', '51207423');
INSERT INTO Customer VALUE (NULL,'Liva', 'Odder', '51203923');


INSERT INTO rTable VALUE (NULL, '5', 1); -- Når der indhentes et ID i for af en foreign key, skal denne værdi ikke sættes i en string
INSERT INTO rTable VALUE (NULL, '2', 1);
INSERT INTO rTable VALUE (NULL, '2', 1);
INSERT INTO rTable VALUE (NULL, '4', 1);
INSERT INTO rTable VALUE (NULL, '6', 1);
INSERT INTO rTable VALUE (NULL, '2', 1);
INSERT INTO rTable VALUE (NULL, '4', 1);
INSERT INTO rTable VALUE (NULL, '4', 1);
INSERT INTO rTable VALUE (NULL, '8', 1);
INSERT INTO rTable VALUE (NULL, '2', 1);

INSERT INTO Menu VALUE (NULL, 'Alt godt fra havet', 'Frisk fisk og muslinger fra vesterhavet');
INSERT INTO Menu VALUE (NULL, 'Efterårs menu', 'Frisk kød med årstidens grønt');
INSERT INTO Menu VALUE (NULL, 'Vegetar', 'Årstidens grønt med tufo');
INSERT INTO Menu VALUE (NULL, 'Børnemenu', 'Håndgodter med dressign');


-- Junction tables fungere ved, at man tager ID's fra de brugte tabeller, og sætter sammen som vist nedenfor.
INSERT INTO rTableMenu VALUE (1,1);
INSERT INTO rTableMenu VALUE (1,4);

INSERT INTO rTableMenu VALUE (2,2);

INSERT INTO rTableMenu VALUE (4,1);
INSERT INTO rTableMenu VALUE (4,3);
INSERT INTO rTableMenu VALUE (4,4);

INSERT INTO rTableMenu VALUE (5,4);
INSERT INTO rTableMenu VALUE (5,1);
INSERT INTO rTableMenu VALUE (5,2);

INSERT INTO rTableMenu VALUE (8,1);
INSERT INTO rTableMenu VALUE (8,2);
INSERT INTO rTableMenu VALUE (8,3);
INSERT INTO rTableMenu VALUE (8,4);

INSERT INTO Food VALUE (NULL, 'Fisk med grønt', '299,99');
INSERT INTO Food VALUE (NULL, 'Muslinger med pasta', '199,99');
INSERT INTO Food VALUE (NULL, 'Lam med grønstsags blanding', '399,99');
INSERT INTO Food VALUE (NULL, 'Oksemørbrad med rodfrugt blanding', '449,45');
INSERT INTO Food VALUE (NULL, 'Tofu med knust kartoffel og sovs', '249,99');
INSERT INTO Food VALUE (NULL, 'Svampe paté med stegte grøntsager', '299,99');
INSERT INTO Food VALUE (NULL, 'Fiskefilet med fritter', '99,99');
INSERT INTO Food VALUE (NULL, 'Hamburger med fritter', '149,99');

INSERT INTO MenuFood VALUE (1,1);
INSERT INTO MenuFood VALUE (1,2);
INSERT INTO MenuFood VALUE (2,3);
INSERT INTO MenuFood VALUE (2,4);
INSERT INTO MenuFood VALUE (3,5);
INSERT INTO MenuFood VALUE (3,6);
INSERT INTO MenuFood VALUE (4,7);
INSERT INTO MenuFood VALUE (4,8);


INSERT INTO Reservation VALUE (NULL, '10/10-2023', '4', '18.00', '20.00', 1, 1);
INSERT INTO Reservation VALUE (NULL, '11/10-2023', '4', '19.00', '22.00', 4, 1);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '2', '17.30', '21.00', 6, 2);
INSERT INTO Reservation VALUE (NULL, '11/10-2023', '4', '18.00', '21.00', 2, 2);
INSERT INTO Reservation VALUE (NULL, '12/10-2023', '6', '18.00', '19.00', 5, 3);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '4', '19.00', '22.00', 8, 3);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '2', '18.00', '20.00', 4, 4);
INSERT INTO Reservation VALUE (NULL, '11/10-2023', '4', '19.00', '22.00', 7, 4);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '4', '18.00', '20.00', 7, 5);
INSERT INTO Reservation VALUE (NULL, '15/10-2023', '4', '19.00', '22.00', 1, 5);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '2', '19.00', '22.00', 9, 6);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '4', '19.00', '22.00', 8, 7);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '6', '19.00', '22.00', 5, 8);
INSERT INTO Reservation VALUE (NULL, '10/10-2023', '4', '19.00', '22.00', 3, 9);
INSERT INTO Reservation VALUE (NULL, '13/10-2023', '4', '18.00', '22.00', 8, 9);

-- Få et overblik over alle borde i resturanten
SELECT * FROM rTable;

-- Få et overblik over alle reservationer fra en bestemt gæst, samt checkin og checkout, sorteret efter dato

SELECT * FROM reservation 
WHERE CustomerID = 1 -- Angiver med ID hvilken gæst der forspørges information om
ORDER BY ReservationDate; -- Sætter rækkefølgen på reservations datoerne fra mindste værdi til højeste

-- Information om et specifikt bord og dens gæster, for en specifik dato

SELECT * FROM reservation -- Vælger alt inhold fra tabellen "Reservation"
WHERE rTableID = 1 -- Angiver med ID hvilket bord der forspørges information om
AND ReservationDate = '10/10-2023' -- Fortæller hvilken dato, der forspørges om information fra

