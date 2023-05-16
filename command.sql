--MySQL commands
CREATE TABLE privilege(
    id int PRIMARY KEY AUTO_INCREMENT,
    id_parent_privilege int,
    name varchar(100),
    active tinyint,
    asset_url varchar(200)
);

CREATE TABLE role(
    id smallint PRIMARY KEY AUTO_INCREMENT,
    role_name varchar(30),
    description text
);

CREATE TABLE role_privilege(
    id int PRIMARY KEY,
    id_role smallint,
    privilege_id int,
    issue_time date,
    expire_time date
);

CREATE TABLE user_role(
    id int PRIMARY KEY,
    id_user int,
    id_role smallint,
    issue_time date,
    expire_time date
);

CREATE TABLE user_privilege(
    id int PRIMARY KEY,
    id_user int,
    id_privilege int
);

CREATE TABLE user(
    id int PRIMARY KEY,
    name varchar(30),
    surname varchar(40),
    phone varchar(12),
);

--bot
-- Create foreign key relationship between user_privilege and user tables
ALTER TABLE user_privilege
ADD CONSTRAINT fk_user_privilege_user_id
FOREIGN KEY (id_user)
REFERENCES user(id);

-- Create foreign key relationship between user_role and user tables
ALTER TABLE user_role
ADD CONSTRAINT fk_user_role_user_id
FOREIGN KEY (id_user)
REFERENCES user(id);

-- Create foreign key relationship between privilege and user_privilege tables
ALTER TABLE user_privilege
ADD CONSTRAINT fk_user_privilege_privilege_id
FOREIGN KEY (id_privilege)
REFERENCES privilege(id);

-- Create foreign key relationship between privilege and role_privilege tables
ALTER TABLE role_privilege
ADD CONSTRAINT fk_role_privilege_privilege_id
FOREIGN KEY (privilege_id)
REFERENCES privilege(id);

-- Create foreign key relationship between role and user_role tables
ALTER TABLE user_role
ADD CONSTRAINT fk_user_role_role_id
FOREIGN KEY (id_role)
REFERENCES role(id);

-- Create foreign key relationship between role and role_privilege tables
ALTER TABLE role_privilege
ADD CONSTRAINT fk_role_privilege_role_id
FOREIGN KEY (id_role)
REFERENCES role(id);

-- Create foreign key relationship between privilege and privilege tables
ALTER TABLE privilege
ADD CONSTRAINT fk_privilege_privilege_id
FOREIGN KEY (id_parent_privilege)
REFERENCES privilege(id);

-- Add the three roles to the role table
INSERT INTO role (id, role_name, description)
VALUES (1, 'Administrator', 'Manages system settings and user accounts'),
       (2, 'Moderator', 'Moderates discussions and manages users'),
       (3, 'User', 'Standard user role with limited privileges'),
       (4, 'New', 'New user role with limited privileges');

-- Add the two privileges to the privilege table
INSERT INTO privilege (id, name, active, asset_url)
VALUES (1, 'add message', 1, '/privileges/add_message'),
       (2, 'delete message', 1, '/privileges/delete_message'),
       (3, 'edit message', 1, '/privileges/edit_message'),
       (4, 'dispay message', 1, '/privileges/display_message'),
       (5, 'create role', 1, '/privileges/create_role'),
       (6, 'delete role', 1, '/privileges/delete_role'),
       (7, 'edit role', 1, '/privileges/edit_role'),
       (8, 'display role', 1, '/privileges/display_role'),
       (9, 'create user', 1, '/privileges/create_user'),
       (10, 'delete user', 1, '/privileges/delete_user'),
       (11, 'edit user', 1, '/privileges/edit_user'),
       (12, 'display user', 1, '/privileges/display_user'),
       (13, 'create privilege', 1, '/privileges/create_privilege'),
       (14, 'delete privilege', 1, '/privileges/delete_privilege'),
       (15, 'edit privilege', 1, '/privileges/edit_privilege'),
       (16, 'display privilege', 1, '/privileges/display_privilege');



CREATE ROLE IF NOT EXIST admin, moderator, user, new;

GRANT ALL PRIVILEGES ON *.* TO 'root';

GRANT ALL PRIVILEGES ON *.* TO 'admin';

GRANT SELECT, DELETE, INSERT, UPDATE ON news.message TO 'moderator';

GRANT SELECT, INSERT, UPDATE ON news.message TO 'user';

GRANT SELECT ON news.message TO 'new';

CREATE USER IF NOT EXIST 'root'@'localhost' IDENTIFIED BY 'root';

Create USER IF NOT EXIST 'admin1'@'localhost';

Create USER IF NOT EXIST 'moderator1'@'localhost';

Create USER IF NOT EXIST 'user1'@'localhost';

Create USER IF NOT EXIST 'new1'@'localhost';

SET DEFAULT ROLE admin TO 'admin1'@'localhost';

SET DEFAULT ROLE moderator TO 'moderator1'@'localhost';

SET DEFAULT ROLE user TO 'user1'@'localhost';

SET DEFAULT ROLE new TO 'new1'@'localhost';

INSERT INTO role_privilege (id, id_role, id_privilege, issue_time, expire_time)
VALUES  (1, 1, 1, NOW(), NULL),
        (2, 1, 2, NOW(), NULL),
        (3, 1, 3, NOW(), NULL),
        (4, 1, 4, NOW(), NULL),
        (5, 1, 5, NOW(), NULL),
        (6, 1, 6, NOW(), NULL),
        (7, 1, 7, NOW(), NULL),
        (8, 1, 8, NOW(), NULL),
        (9, 1, 9, NOW(), NULL),
        (10, 1, 10, NOW(), NULL),
        (11, 1, 11, NOW(), NULL),
        (12, 1, 12, NOW(), NULL),
        (13, 1, 13, NOW(), NULL),
        (14, 1, 14, NOW(), NULL),
        (15, 1, 15, NOW(), NULL),
        (16, 1, 16, NOW(), NULL),
        (17, 2, 1, NOW(), NULL),
        (18, 2, 2, NOW(), NULL),
        (19, 2, 3, NOW(), NULL),
        (20, 2, 4, NOW(), NULL),
        (21, 3, 1, NOW(), NULL),
        (22, 3, 3, NOW(), NULL),
        (23, 3, 4, NOW(), NULL),
        (26, 4, 4, NOW(), NULL);

INSERT INTO user_role (id, id_user, id_role, issue_time, expire_time)
VALUES  (1, 42, 1, NOW(), NULL);

INSERT INTO user_privilege (id, id_user, id_privilege)
VALUES  (1, 42, 1),
        (2, 42, 2),
        (3, 42, 3),
        (4, 42, 4),
        (5, 42, 5),
        (6, 42, 6),
        (8, 42, 8),
        (9, 42, 9),
        (10, 42, 10),
        (11, 42, 11),
        (12, 42, 12),
        (13, 42, 13),
        (14, 42, 14),
        (15, 42, 15),
        (16, 42, 16);



Tabela role
id 	role_name 
1 	Administrator 
2 	Moderator 
3 	User 
4 	New

--role for username/login
SELECT r.role_name FROM role r 
INNER JOIN user_role ur ON ur.id_role = r.id
INNER JOIN user u ON u.id = ur.id_user 
WHERE u.login = "essa";