-- PharmaFEFO Seed Data
-- Run after schema.sql

USE pharmafefo;

-- Default users (password: admin123, prep123, pharma123)
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@pharmafefo.com', '$2y$10$XRdPPFh3K4wrakTm3GEYduecyMjoDb6bBuRNVq0NdmmzFQ.FFo6qa', 'ADMIN'),
('Jean Préparateur', 'preparateur@pharmafefo.com', '$2y$10$oTA.HdO8aEM.7ktoonMQZepu.tSe2FWSA.IeTG27VJk4ue8Otj4KW', 'PREPARATEUR'),
('Marie Pharmacien', 'pharmacien@pharmafefo.com', '$2y$10$A.XBcXbNEDAc/P0wfkHa4OgI/CYlLO3IE5vK1dqBlwQntnTk4sNge', 'PHARMACIEN');

-- Sample products
INSERT INTO products (name, description, category, unit) VALUES
('Paracétamol 500mg', 'Antidouleur et antipyrétique', 'Antalgiques', 'boîte'),
('Ibuprofène 400mg', 'Anti-inflammatoire non stéroïdien', 'Antalgiques', 'boîte'),
('Amoxicilline 1g', 'Antibiotique à large spectre', 'Antibiotiques', 'boîte'),
('Vitamine D3', 'Complément alimentaire', 'Vitamines', 'flacon'),
('Sirop Toux', 'Antitussif pour adultes', 'Toux', 'flacon'),
('Oméprazole 20mg', 'Inhibiteur de la pompe à protons', 'Digestif', 'boîte'),
('Doliprane 1000mg', 'Paracétamol haute dose', 'Antalgiques', 'boîte'),
('Bétadine', 'Antiseptique cutané', 'Dermatologie', 'flacon');

-- Sample stock batches (various expiry dates for FEFO demo)
INSERT INTO stock_batches (product_id, batch_number, quantity, expiry_date, status) VALUES
(1, 'PARA-2025-001', 150, '2026-12-31', 'ACTIVE'),
(1, 'PARA-2025-002', 80, '2026-06-15', 'ACTIVE'),
(1, 'PARA-2024-003', 45, '2026-03-20', 'ACTIVE'),
(2, 'IBU-2025-001', 200, '2027-01-15', 'ACTIVE'),
(2, 'IBU-2024-002', 30, '2026-04-10', 'ACTIVE'),
(3, 'AMOX-2025-001', 100, '2026-08-30', 'ACTIVE'),
(3, 'AMOX-2024-002', 15, '2026-02-28', 'ACTIVE'),
(4, 'VITD-2025-001', 75, '2027-06-01', 'ACTIVE'),
(5, 'TOUX-2024-001', 40, '2026-01-15', 'ACTIVE'),
(6, 'OME-2025-001', 120, '2026-11-20', 'ACTIVE'),
(7, 'DOLI-2024-001', 5, '2026-03-01', 'ACTIVE'),
(8, 'BETA-2023-001', 10, '2025-12-01', 'EXPIRED');

-- Sample stock movements
INSERT INTO stock_movements (batch_id, user_id, type, quantity, notes) VALUES
(1, 2, 'IN', 150, 'Réception initiale'),
(2, 2, 'IN', 80, 'Réception initiale'),
(3, 2, 'IN', 45, 'Réception initiale'),
(4, 2, 'IN', 200, 'Réception initiale'),
(1, 2, 'OUT', 20, 'Vente comptoir - FEFO appliqué');

-- Update quantity after OUT movement on batch 1
UPDATE stock_batches SET quantity = 130 WHERE id = 1;

-- Sample alerts
INSERT INTO alerts (batch_id, level, message, is_read) VALUES
(12, 'expired', 'Lot BETA-2023-001 expiré - action requise', 0),
(7, 'red', 'Amoxicilline lot AMOX-2024-002 expire dans moins de 30 jours', 0),
(9, 'orange', 'Sirop Toux lot TOUX-2024-001 expire dans moins de 90 jours', 0),
(11, 'red', 'Doliprane lot DOLI-2024-001 - stock critique et expiration proche', 0),
(4, 'green', 'Ibuprofène lot IBU-2025-001 - expiration dans plus de 6 mois', 1);

-- Sample return
INSERT INTO returns (batch_id, quantity, reason, status) VALUES
(12, 10, 'Lot expiré - retour fournisseur', 'PENDING');

-- Sample loss report
INSERT INTO loss_reports (batch_id, quantity, reason, reported_by) VALUES
(12, 10, 'Perte due à expiration', 1);
