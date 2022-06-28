-- All secrets set to 1234

INSERT INTO "client" (id, name, secret, callback_url) VALUES 
    ('4bc7dba9-46cc-41c2-802a-dcb5a76120c7', 'zufms.web', '$2a$10$QhgAK.zhQlgS03quwJYxg.RpmRpHrzjACA28yQFIlnXvb64U3O6g2', 'https://localhost/auth/cb');

INSERT INTO "client_allowed_scope" (client_id, scope_id) VALUES 
    ('4bc7dba9-46cc-41c2-802a-dcb5a76120c7', 5);

INSERT INTO "user" (id, email) VALUES
    ('08f0a407-7afb-4ffd-ae4f-8f8121c7680a', 'm.david@ufms.br'),
    ('e99382b9-c091-4e4c-8eea-9c16befab88d', 'leonardo.piassi@ufms.br'),
    ('0e259ef3-c3f8-4177-8de9-32529d4b8e37', 'lima.barbosa@ufms.br'),
    ('135572b3-3082-456c-aa05-fd787bee393a', 'murilo.mamede@ufms.br'),
    ('dbbd76c9-e151-45c9-a67b-d48bff26c2dc', 'yan.ajiki@ufms.br'),
    ('12a679c0-b068-4343-b948-5e2a2d5d307f', 'thomaz.sinani@ufms.br'),
    ('0c600d15-47c0-483a-ac29-d3b63b6fc2f2', 'liliana.piatti@ufms.br'),
    ('5521c5b8-8a76-44da-ae56-b9b0bfc72929', 'francisco.severo@ufms.br');

INSERT INTO "user_allowed_scope" (user_id, scope_id) VALUES 
    ('08f0a407-7afb-4ffd-ae4f-8f8121c7680a', 4),
    ('e99382b9-c091-4e4c-8eea-9c16befab88d', 4),
    ('0e259ef3-c3f8-4177-8de9-32529d4b8e37', 4),
    ('135572b3-3082-456c-aa05-fd787bee393a', 4),
    ('dbbd76c9-e151-45c9-a67b-d48bff26c2dc', 4),
    ('12a679c0-b068-4343-b948-5e2a2d5d307f', 4),
    ('0c600d15-47c0-483a-ac29-d3b63b6fc2f2', 4),
    ('5521c5b8-8a76-44da-ae56-b9b0bfc72929', 4);
