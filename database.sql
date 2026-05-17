CREATE DATABASE IF NOT EXISTS restaurant_system
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE restaurant_system;

CREATE TABLE IF NOT EXISTS branches (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    branch_name VARCHAR(100) NOT NULL,
    location    VARCHAR(150) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS employees (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    branch_id     INT NOT NULL,
    employee_name VARCHAR(100) NOT NULL,
    position      VARCHAR(100) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_branch
        FOREIGN KEY (branch_id)
        REFERENCES branches(id)
        ON DELETE CASCADE   
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Branches
INSERT INTO branches (branch_name, location) VALUES
('Bonifacio Global City',    'BGC, Taguig City'),
('Makati Central',           'Ayala Avenue, Makati City'),
('Ortigas Center',           'Ortigas, Pasig City'),
('Quezon City Main',         'Eastwood, Quezon City'),
('Alabang South',            'Filinvest, Muntinlupa City');

-- Employees
INSERT INTO employees (branch_id, employee_name, position) VALUES
-- BGC Branch (id=1)
(1, 'Miguel Santos',     'Branch Manager'),
(1, 'Clara Reyes',       'Head Chef'),
(1, 'Paolo Dela Cruz',   'Sous Chef'),
(1, 'Anna Villanueva',   'Cashier'),
(1, 'Jose Mendoza',      'Waiter'),

-- Makati Branch (id=2)
(2, 'Sofia Bautista',    'Branch Manager'),
(2, 'Luis Garcia',       'Head Chef'),
(2, 'Maria Torres',      'Cashier'),
(2, 'Rafael Cruz',       'Waiter'),
(2, 'Elena Ramos',       'Barista'),

-- Ortigas Branch (id=3)
(3, 'Carlos Fernandez',  'Branch Manager'),
(3, 'Diana Lopez',       'Head Chef'),
(3, 'Marco Castillo',    'Waiter'),
(3, 'Isabela Morales',   'Cashier'),

-- QC Branch (id=4)
(4, 'Antonio Rivera',    'Branch Manager'),
(4, 'Patricia Navarro',  'Head Chef'),
(4, 'Francis Tan',       'Sous Chef'),
(4, 'Christine Lim',     'Waiter'),

-- Alabang Branch (id=5)
(5, 'Roberto Aquino',    'Branch Manager'),
(5, 'Maricel Santos',    'Head Chef'),
(5, 'Jayson Reyes',      'Cashier'),
(5, 'Theresa Go',        'Waiter');
