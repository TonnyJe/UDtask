INSERT INTO `setting` (`type`, `value`)
VALUES ('DEFAULT_RIGHTS', '11');

INSERT INTO `village` (`name`)
VALUES ('Praha'), ('Brno');

INSERT INTO `user_admin` (`name`)
VALUES 
('Adam'),
('Bob'),
('Cyril');

-- Adresář/Vyhledávač => AV (1/0 jestli má, či ne)
INSERT INTO `user_admin_x_village` (`user_admin_id`, `village_id`, `rights`)
VALUES 
('1', '1', '11'),  -- Adam v Praze vše
('1', '2', '00'),  -- Adam v Brně nic
('2', '1', '01'),  -- Bob v Praze Vyhledávač
('2', '2', '10'),  -- Bobo v Brně Adresář
('3', '1', '10'),  -- Cyril v Praze Adresář
('3', '2', '11');  -- Cyril v Brně vše

INSERT INTO `rights` (`name`)
VALUES 
('Adresář'),
('Vyhledávač');


