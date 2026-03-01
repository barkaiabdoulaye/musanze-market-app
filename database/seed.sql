-- Seed data for Musanze Market
USE musanze_market;

-- Clear existing data (optional)
-- SET FOREIGN_KEY_CHECKS = 0;
-- TRUNCATE TABLE orders;
-- TRUNCATE TABLE farmers;
-- TRUNCATE TABLE users;
-- SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- Additional Admin Users
-- =====================================================
-- Password: aggregator123
INSERT INTO users (username, password) VALUES 
('aggregator1', '$2y$12$K8H5pJ9xQ3rL7mN2bVcXdYz6TtXqJ8hN4rK5wB2vF1gH7jK9lZ'),
('manager', '$2y$12$M9N4pK2xR5tL8mN3bVcXdYz7TtXqJ9hN5rK6wB3vF2gH8jK0lA');

-- =====================================================
-- More Sample Farmers
-- =====================================================
INSERT INTO farmers (full_name, phone, location, created_at) VALUES
('Emmanuel Ndayisaba', '0789678901', 'Muko', DATE_SUB(NOW(), INTERVAL 30 DAY)),
('Chantal Mukamana', '0789789012', 'Gahunga', DATE_SUB(NOW(), INTERVAL 28 DAY)),
('Theogene Habimana', '0789890123', 'Nkotsi', DATE_SUB(NOW(), INTERVAL 25 DAY)),
('Beatrice Uwimana', '0790901234', 'Bushoki', DATE_SUB(NOW(), INTERVAL 22 DAY)),
('Francois Nsengiyumva', '0791012345', 'Remera', DATE_SUB(NOW(), INTERVAL 20 DAY)),
('Grace Uwase', '0792123456', 'Cyabararika', DATE_SUB(NOW(), INTERVAL 18 DAY)),
('Olivier Gasana', '0793234567', 'Gataraga', DATE_SUB(NOW(), INTERVAL 15 DAY)),
('Patricia Uwamahoro', '0794345678', 'Musanze', DATE_SUB(NOW(), INTERVAL 12 DAY)),
('Sebastien Ntawuyamara', '0795456789', 'Ruhengeri', DATE_SUB(NOW(), INTERVAL 10 DAY)),
('Veronique Mukashema', '0796567890', 'Kinigi', DATE_SUB(NOW(), INTERVAL 8 DAY));

-- =====================================================
-- Additional Sample Orders (spread over last 30 days)
-- =====================================================

-- Function to generate random dates
DELIMITER $$
CREATE PROCEDURE GenerateSampleOrders()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE farmer_id INT;
    DECLARE random_qty DECIMAL(10,2);
    DECLARE random_price INT;
    DECLARE random_status VARCHAR(20);
    DECLARE random_days_ago INT;
    
    WHILE i <= 50 DO
        SET farmer_id = FLOOR(1 + RAND() * 15);
        SET random_qty = 20 + RAND() * 180;
        SET random_price = FLOOR(500 + RAND() * 200);
        SET random_days_ago = FLOOR(RAND() * 30);
        SET random_status = ELT(FLOOR(1 + RAND() * 3), 'pending', 'completed', 'cancelled');
        
        INSERT INTO orders (
            farmer_id, 
            quantity, 
            unit_price, 
            pickup_location, 
            status, 
            created_at,
            notes
        ) VALUES (
            farmer_id,
            random_qty,
            random_price,
            ELT(FLOOR(1 + RAND() * 4), 'Musanze Market', 'Ruhengeri', 'Kinigi', 'Cyuve'),
            random_status,
            DATE_SUB(NOW(), INTERVAL random_days_ago DAY),
            ELT(FLOOR(1 + RAND() * 5), 
                'Early delivery requested', 
                'Need receipt', 
                'Call before pickup', 
                'Quality check required', 
                '')
        );
        
        SET i = i + 1;
    END WHILE;
END$$
DELIMITER ;

-- Run the procedure
CALL GenerateSampleOrders();

-- Clean up
DROP PROCEDURE GenerateSampleOrders;

-- =====================================================
-- Update some orders with notes
-- =====================================================
UPDATE orders SET notes = 'Farmer requested morning pickup' 
WHERE id IN (1, 3, 5);

UPDATE orders SET notes = 'Quality inspection needed' 
WHERE id IN (2, 7);

-- =====================================================
-- Verify data
-- =====================================================
SELECT 'Users' as table_name, COUNT(*) as count FROM users
UNION ALL
SELECT 'Farmers', COUNT(*) FROM farmers
UNION ALL
SELECT 'Orders', COUNT(*) FROM orders;

-- Show sample data
SELECT 'Sample Farmers' as '';
SELECT id, full_name, phone, location FROM farmers LIMIT 5;

SELECT 'Sample Orders' as '';
SELECT o.id, f.full_name, o.quantity, o.total_amount, o.status, o.created_at 
FROM orders o
JOIN farmers f ON o.farmer_id = f.id
ORDER BY o.created_at DESC
LIMIT 5;