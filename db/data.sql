CREATE TABLE user
(   
    user_id     INTEGER NOT NULL AUTO_INCREMENT,
    email       VARCHAR(100),
    password    VARCHAR(100),
    first_name  VARCHAR(100),
    surname     VARHCAR(100),
    phone       VARCHAR(20),
    address     VARCHAR(100),
    active      BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (user_id )
)