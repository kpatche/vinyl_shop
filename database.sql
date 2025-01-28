CREATE TABLE users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vinyl_genres (
    genreID INT PRIMARY KEY AUTO_INCREMENT,
    genreName VARCHAR(50) NOT NULL
);

CREATE TABLE vinyl_records (
    recordID INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    artist VARCHAR(100) NOT NULL,
    genreID INT,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    image_url VARCHAR(255),
    description TEXT,
    FOREIGN KEY (genreID) REFERENCES vinyl_genres(genreID)
);

CREATE TABLE vinyl_orders (
    orderID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT,
    orderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totalAmount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'pending',
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE vinyl_order_items (
    orderItemID INT PRIMARY KEY AUTO_INCREMENT,
    orderID INT,
    recordID INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (orderID) REFERENCES vinyl_orders(orderID),
    FOREIGN KEY (recordID) REFERENCES vinyl_records(recordID)
);

INSERT INTO users (username, password, email) VALUES
('petar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petar@example.com'),
('petko', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petko@example.com'),
('jana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jana@example.com');

INSERT INTO vinyl_genres (genreName) VALUES
('Rock'),
('Jazz'),
('Classical'),
('Pop'),
('Hip Hop'),
('Electronic'),
('Blues'),
('Country');

INSERT INTO vinyl_records (title, artist, genreID, price, stock, description) VALUES
('Abbey Road', 'The Beatles', 1, 29.99, 50, 'Classic Beatles album from 1969'),
('Kind of Blue', 'Miles Davis', 2, 24.99, 30, 'Best-selling jazz record of all time'),
('The Four Seasons', 'Antonio Vivaldi', 3, 19.99, 20, 'Famous classical composition'),
('Thriller', 'Michael Jackson', 4, 27.99, 40, 'Best-selling album of all time'),
('To Pimp a Butterfly', 'Kendrick Lamar', 5, 25.99, 25, 'Modern hip-hop masterpiece'),
('Random Access Memories', 'Daft Punk', 6, 28.99, 35, 'Electronic music classic'),
('The Blues Brothers', 'The Blues Brothers', 7, 22.99, 15, 'Iconic blues album'),
('Red', 'Taylor Swift', 8, 26.99, 45, 'Popular country-pop crossover');

INSERT INTO vinyl_orders (userID, totalAmount, status) VALUES
(1, 54.98, 'completed'),
(2, 27.99, 'completed'),
(3, 48.98, 'pending');

INSERT INTO vinyl_order_items (orderID, recordID, quantity, price) VALUES
(1, 1, 1, 29.99),
(1, 2, 1, 24.99),
(2, 4, 1, 27.99),
(3, 3, 1, 19.99),
(3, 5, 1, 28.99);