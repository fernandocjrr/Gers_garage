CREATE TABLE user
(   
    user_id     INTEGER NOT NULL AUTO_INCREMENT,
    email       VARCHAR(100),
    password    VARCHAR(100),
    first_name  VARCHAR(100),
    surname     VARCHAR(100),
    phone       VARCHAR(20),
    address     VARCHAR(100),
    admin       BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (user_id )
)