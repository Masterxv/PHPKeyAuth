/* Creates users database */
CREATE TABLE users ( 
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    token VARCHAR(10) NOT NULL, 
    hwid VARCHAR(10) NOT NULL,  
    ban INT(1), 
    date TIMESTAMP 
)
/* ban isnt needed since its moved into a new table and deleted from the users table! */

/* Creates banned_users database */
CREATE TABLE banned_users ( 
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    token VARCHAR(10) NOT NULL, 
    hwid VARCHAR(10) NOT NULL,
    reason TEXT NULL DEFAULT NULL,  
    date TIMESTAMP 
)

/* Add banned user */
INSERT INTO banned_users (token, hwid) VALUES (69, 123)

/* return data from according row where hwid matches */
SELECT * FROM banned_users WHERE hwid = 123

/* Add user */
INSERT INTO users (token, hwid, ban) VALUES (69, 123, 0)

/* Add (fist time setup user) */
INSERT INTO users (token, hwid, ban) VALUES (1337, 0, 0)