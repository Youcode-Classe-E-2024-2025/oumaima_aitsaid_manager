create database gestion_restaurants
use gestion_restaurants 

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_role INT,
   is_archifed boolean DEFAULT false,
   is_approved boolean DEFAULT false,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES roles(id)  ON DELETE CASCADE  ON UPDATE CASCADE 
);


CREATE TABLE roles{
id INT AUTO_INCREMENT PRIMARY KEY,
role_name VARCHAR(20)  UNIQUE DEFAULT 'user' ,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
id_user INT,

}



CREATE TABLE actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE 
);


CREATE TABLE archived_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    name VARCHAR(100),
    email VARCHAR(100),
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE  ON UPDATE CASCADE 
);

CREATE TABLE Plat(

id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL ,
prix FLOAT NOT NULL  ,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
CREATE TABLE salade(

id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL ,
prix FLOAT NOT NULL  ,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
CREATE TABLE Dessert(

id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL ,
prix FLOAT NOT NULL  ,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
CREATE TABLE menu (
id INT PRIMARY KEY AUTO_INCREMENT ,
id_plat INT,
id_salade INT ,
id_dessert INT ,
id_user INT ,
FOREIGN KEY (id_plat) REFERENCES Plat(id) ON DELETE CASCADE  ON UPDATE CASCADE ,
FOREIGN KEY (id_salade) REFERENCES salade(id) ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (id_dessert) REFERENCES Dessert(id) ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE  ON UPDATE CASCADE,
)
CREATE TABLE commande (
id INT PRIMARY KEY AUTO_INCREMENT ,
id_menu INT ,
id_user INT ,
FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (id_menu) REFERENCES menu(id) ON DELETE CASCADE  ON UPDATE CASCADE,
)