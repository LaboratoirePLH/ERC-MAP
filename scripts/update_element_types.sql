UPDATE element SET type = 'theonym' WHERE id IN (1, 2, 3);
UPDATE element SET type = 'non_theonym' WHERE id IN (4, 5, 6);
UPDATE element SET type = 'mixed' WHERE id IN (7, 8, 9);

UPDATE element SET type_checked = 1 WHERE id IN (1, 2, 3);
UPDATE element SET type_checked = 0 WHERE id IN (4, 5, 6);