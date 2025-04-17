CREATE TABLE offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    listing_id INT,
    offer_amount DECIMAL(10,2),
    offer_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
