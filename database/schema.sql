-- Drop existing tables if they exist
DROP TABLE IF EXISTS milestones;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS bids;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'client',
    avatar VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    bio TEXT,
    company VARCHAR(255),
    website VARCHAR(255),
    social_links TEXT,
    skills TEXT,
    hourly_rate DECIMAL(10,2),
    availability VARCHAR(50),
    email_verified_at DATETIME,
    remember_token VARCHAR(100),
    google_id VARCHAR(255),
    linkedin_id VARCHAR(255),
    two_factor_secret TEXT,
    two_factor_recovery_codes TEXT,
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME
);

-- Create categories table
CREATE TABLE categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    parent_id INTEGER,
    icon VARCHAR(255),
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Create jobs table
CREATE TABLE jobs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category_id INTEGER NOT NULL,
    budget DECIMAL(10,2) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    skills_required TEXT NOT NULL,
    experience_level VARCHAR(50) NOT NULL,
    status VARCHAR(20) DEFAULT 'open',
    attachments TEXT,
    location VARCHAR(255),
    is_remote BOOLEAN DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Create bids table
CREATE TABLE bids (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    job_id INTEGER NOT NULL,
    freelancer_id INTEGER NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    duration VARCHAR(50) NOT NULL,
    proposal TEXT NOT NULL,
    attachments TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create messages table
CREATE TABLE messages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_id INTEGER NOT NULL,
    receiver_id INTEGER NOT NULL,
    job_id INTEGER,
    message TEXT NOT NULL,
    attachments TEXT,
    read_at DATETIME,
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL
);

-- Create milestones table
CREATE TABLE milestones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    job_id INTEGER NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    amount DECIMAL(10,2) NOT NULL,
    due_date DATE,
    status VARCHAR(20) DEFAULT 'pending',
    attachments TEXT,
    created_at DATETIME,
    updated_at DATETIME,
    deleted_at DATETIME,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
); 