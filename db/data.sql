CREATE TABLE db_garage.user
(   
    user_id     INTEGER NOT NULL AUTO_INCREMENT,
    email       VARCHAR(100),
    password    VARCHAR(100),
    first_name  VARCHAR(100),
    surname     VARCHAR(100),
    phone       VARCHAR(20),
    address     VARCHAR(100),
    admin       BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (user_id)
)

CREATE TABLE db_garage.own
(   
    own_id                      INTEGER NOT NULL AUTO_INCREMENT,
    user_id                     INTEGER NOT NULL,
    vehicle_id                 INTEGER NOT NULL,
    PRIMARY KEY (own_id),
    FOREIGN KEY (vehicle_id)    REFERENCES vehicle (vehicle_id),
    FOREIGN KEY (user_id)       REFERENCES user (user_id)
)

CREATE TABLE db_garage.vehicle
(   
    vehicle_id                     INTEGER NOT NULL AUTO_INCREMENT,
    licence_details                 VARCHAR(100),
    engine                          VARCHAR(100),
    vehicle_details_id              INTEGER NOT NULL,
    PRIMARY KEY (vehicle_id),
    FOREIGN KEY (vehicle_details_id) REFERENCES vehicle_details (vehicle_details_id)
)

CREATE TABLE db_garage.vehicle_details
(   
    vehicle_details_id     INTEGER NOT NULL AUTO_INCREMENT,
    manufacturer            VARCHAR(100),
    model                   VARCHAR(100),
    year                    INTEGER,
    type                     VARCHAR(100),
    PRIMARY KEY (vehicle_details_id)
)

CREATE TABLE db_garage.have
(   
    have_id                     INTEGER NOT NULL AUTO_INCREMENT,
    vehicle_id                 INTEGER NOT NULL,
    booking_id                  INTEGER NOT NULL,
    PRIMARY KEY (have_id),
    FOREIGN KEY (vehicle_id)        REFERENCES vehicle (vehicle_id),
    FOREIGN KEY (booking_id)        REFERENCES booking (booking_id)
    
)
CREATE TABLE db_garage.booking
(   
    booking_id                  INTEGER NOT NULL AUTO_INCREMENT,
    fix_type                    VARCHAR(100),
    details                     VARCHAR(100),
    date                        DATE,
    status                     VARCHAR(100) DEFAULT "booked",
    PRIMARY KEY (booking_id)
)

CREATE TABLE db_garage.cost
(   
    cost_id                     INTEGER NOT NULL AUTO_INCREMENT,
    quantity                    INTEGER,
    cost                        DOUBLE,
    description                 VARCHAR(100),
    booking_id                  INTEGER NOT NULL,
    part_id                     INTEGER,
    PRIMARY KEY (cost_id),
    FOREIGN KEY (booking_id)   REFERENCES booking (booking_id),
    FOREIGN KEY (part_id)       REFERENCES parts (part_id)
)

CREATE TABLE db_garage.parts
(   
    part_id                     INTEGER NOT NULL AUTO_INCREMENT,
    part                        VARCHAR(100),
    part_cost                   DOUBLE,
    PRIMARY KEY (part_id)
)

CREATE TABLE db_garage.asign
(   
    asign_id                    INTEGER NOT NULL AUTO_INCREMENT,
    staff_id                    INTEGER NOT NULL,
    booking_id                  INTEGER NOT NULL,
    PRIMARY KEY (asign_id),
    FOREIGN KEY (staff_id)      REFERENCES   staff (staff_id),
    FOREIGN KEY (booking_id)    REFERENCES   booking (booking_id)
)

CREATE TABLE db_garage.staff
(   
    staff_id                     INTEGER NOT NULL AUTO_INCREMENT,
    staff_fname                  VARCHAR(100),
    PRIMARY KEY (staff_id)
)