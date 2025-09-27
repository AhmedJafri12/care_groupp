USE care_group;

INSERT INTO users (name, username, email, password_hash, role)
VALUES (
  'Admin User',
  'admin',
  'admin@example.com',
  '$2y$10$0mXJf7mZC7Zb7oMt8LZbQOKj6k3w4Yc6o1o4wL1zT9f2f1rJ4XK1a',
  'Administrator'
)
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  email = VALUES(email),
  role = VALUES(role),
  password_hash = VALUES(password_hash);
