SET sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';

USE stacgatelms;

DROP TABLE IF EXISTS sg_role_permissions;
DROP TABLE IF EXISTS sg_user_roles;
DROP TABLE IF EXISTS sg_permissions;
DROP TABLE IF EXISTS sg_roles;
DROP TABLE IF EXISTS sg_users;

CREATE TABLE sg_roles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL,
    description TEXT NULL,
    level TINYINT UNSIGNED NOT NULL DEFAULT 1,
    color VARCHAR(7) NOT NULL DEFAULT '#6c757d',
    icon VARCHAR(50) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_system TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_name (name),
    INDEX idx_level (level),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sg_users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NULL,
    last_name VARCHAR(50) NULL,
    avatar_url VARCHAR(255) NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    email_verified TINYINT(1) NOT NULL DEFAULT 0,
    login_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
    last_login_at TIMESTAMP NULL,
    last_login_ip VARCHAR(45) NULL,
    password_reset_token VARCHAR(64) NULL,
    password_reset_expires TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sg_permissions (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    display_name VARCHAR(150) NOT NULL,
    description TEXT NULL,
    module VARCHAR(50) NOT NULL,
    resource VARCHAR(50) NOT NULL,
    action VARCHAR(30) NOT NULL,
    category VARCHAR(50) NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 999,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    is_system TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_module_resource_action (module, resource, action),
    INDEX idx_category_sort (category, sort_order),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sg_user_roles (
    user_id INT UNSIGNED NOT NULL,
    role_id INT UNSIGNED NOT NULL,
    granted_by INT UNSIGNED NULL,
    granted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    context VARCHAR(100) NULL,
    notes TEXT NULL,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES sg_users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES sg_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (granted_by) REFERENCES sg_users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_role_id (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sg_role_permissions (
    role_id INT UNSIGNED NOT NULL,
    permission_id INT UNSIGNED NOT NULL,
    granted TINYINT(1) NOT NULL DEFAULT 1,
    conditions JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES sg_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES sg_permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO sg_roles (name, display_name, description, level, color, icon, is_system) VALUES
('administrator', 'Administrateur', 'Accès total à la plateforme', 10, '#dc3545', 'fas fa-user-shield', 1),
('manager', 'Manager', 'Supervision pédagogique', 8, '#fd7e14', 'fas fa-users-cog', 1),
('formateur', 'Formateur', 'Création et animation de formations', 6, '#28a745', 'fas fa-chalkboard-teacher', 1),
('apprenant', 'Apprenant', 'Participation aux formations', 3, '#007bff', 'fas fa-graduation-cap', 1),
('guest', 'Invité', 'Accès en lecture seule', 1, '#6c757d', 'fas fa-eye', 1);

INSERT INTO sg_permissions (name, display_name, description, module, resource, action, category, sort_order, is_system) VALUES
('system_admin', 'Administration Système', 'Accès complet système', 'system', 'all', 'admin', 'Système', 1, 1),
('users_create', 'Créer Utilisateurs', 'Créer nouveaux comptes', 'users', 'user', 'create', 'Utilisateurs', 10, 0),
('users_read', 'Consulter Utilisateurs', 'Consulter profils', 'users', 'user', 'read', 'Utilisateurs', 11, 0),
('users_update', 'Modifier Utilisateurs', 'Modifier informations', 'users', 'user', 'update', 'Utilisateurs', 12, 0),
('users_delete', 'Supprimer Utilisateurs', 'Supprimer comptes', 'users', 'user', 'delete', 'Utilisateurs', 13, 0),
('formations_create', 'Créer Formations', 'Créer nouvelles formations', 'formations', 'formation', 'create', 'Formations', 20, 0),
('formations_read', 'Consulter Formations', 'Accéder aux formations', 'formations', 'formation', 'read', 'Formations', 21, 0),
('formations_update', 'Modifier Formations', 'Éditer contenu formations', 'formations', 'formation', 'update', 'Formations', 22, 0),
('sessions_participate', 'Participer Sessions', 'Rejoindre sessions', 'sessions', 'session', 'read', 'Sessions', 32, 0);

INSERT INTO sg_role_permissions (role_id, permission_id, granted)
SELECT 1, id, 1 FROM sg_permissions;

INSERT INTO sg_role_permissions (role_id, permission_id, granted)
SELECT 3, id, 1 FROM sg_permissions 
WHERE name IN ('formations_create', 'formations_read', 'formations_update', 'sessions_participate');

INSERT INTO sg_role_permissions (role_id, permission_id, granted)
SELECT 4, id, 1 FROM sg_permissions 
WHERE name IN ('formations_read', 'sessions_participate');

INSERT INTO sg_users (username, email, password_hash, first_name, last_name, status, email_verified) VALUES
('admin', 'admin@stacgate.local', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super', 'Administrateur', 1, 1);

INSERT INTO sg_user_roles (user_id, role_id, granted_by, notes) VALUES
(1, 1, 1, 'Compte administrateur initial');

SELECT 'Migration terminée avec succès!' as message;
SELECT 'Tables créées' as info, COUNT(*) as count 
FROM information_schema.tables 
WHERE table_schema = DATABASE() AND table_name LIKE 'sg_%';
