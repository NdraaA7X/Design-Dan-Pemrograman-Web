-- Jobsheet 10 Latihan Tambahan 2: Tabel remember_tokens untuk fitur "Ingat Saya"
-- Jalankan: psql -d simpus_mini -f sql/04_remember_tokens.sql

CREATE TABLE IF NOT EXISTS remember_tokens (
    id         SERIAL PRIMARY KEY,
    user_id    INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    token      VARCHAR(255) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL
);

-- Indeks untuk mempercepat pencarian token dan pembersihan token kadaluarsa
CREATE INDEX IF NOT EXISTS idx_remember_tokens_token ON remember_tokens(token);
