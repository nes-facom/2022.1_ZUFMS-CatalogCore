INSERT INTO "scope" (id, name, description) VALUES (0, 'users:read', ''), (1, 'users', ''), (2, 'occurrences', ''), (3, 'occurrences:read', ''), (4, 'admin', ''), (5, 'internal.client', ''), (6, 'client.auth:otp', '');

INSERT INTO "scope_closure_table" (ancestor, descendant) VALUES (0, 0), (1, 0), (4, 0), (1, 1), (4, 1), (2, 2), (4, 2), (3, 3), (2, 3), (4, 3), (4, 4), (5, 5), (6, 6), (5, 6);
