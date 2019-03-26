/* Creates database */
CREATE TABLE users ( 
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    token VARCHAR(10) NOT NULL, 
    hwid VARCHAR(10) NOT NULL,  
    ban INT(1), 
    date TIMESTAMP 
)

/* Add user */
INSERT INTO users (token, hwid, ban) VALUES (69, 0123456789, 0)