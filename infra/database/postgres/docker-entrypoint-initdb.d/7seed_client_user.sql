-- All secrets/passwords set to 1234

INSERT INTO "client" (id, name, secret, callback_url) VALUES 
    ('4bc7dba9-46cc-41c2-802a-dcb5a76120c7', 'zufms.web', '$2a$10$QhgAK.zhQlgS03quwJYxg.RpmRpHrzjACA28yQFIlnXvb64U3O6g2', 'https://localhost/auth/cb'),
    ('150ea34a-dcd7-4eae-a535-3be3beee9a6a', 'outside-client', '$2a$10$QhgAK.zhQlgS03quwJYxg.RpmRpHrzjACA28yQFIlnXvb64U3O6g2', 'https://localhost/auth/cb');

INSERT INTO "client_allowed_scope" (client_id, scope_id) VALUES 
    ('4bc7dba9-46cc-41c2-802a-dcb5a76120c7', 5),
    ('150ea34a-dcd7-4eae-a535-3be3beee9a6a', 6);

INSERT INTO "user" (id, email) VALUES
    ('08f0a407-7afb-4ffd-ae4f-8f8121c7680a', 'admin@zufms.ufms.br'),
    ('e99382b9-c091-4e4c-8eea-9c16befab88d', 'user@zufms.ufms.br');

INSERT INTO "user_allowed_scope" (user_id, scope_id) VALUES 
    ('08f0a407-7afb-4ffd-ae4f-8f8121c7680a', 4),
    ('e99382b9-c091-4e4c-8eea-9c16befab88d', 3);
