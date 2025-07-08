CREATE TABLE
    gabinete_tipo (
        id VARCHAR(36) NOT NULL PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO gabinete_tipo (id, nome) VALUES
    ('1', 'Gestor'),
    ('2', 'Deputado Federal'),
    ('3', 'Senador'),
    ('4', 'Deputado Estadual'),
    ('5', 'Vereador'),
    ('6', 'Prefeito'),
    ('7', 'Governador');

CREATE TABLE
    gabinete (
        id VARCHAR(36) NOT NULL PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE,
        estado VARCHAR(2) NOT NULL,
        cidade VARCHAR(100) DEFAULT NULL,
        partido VARCHAR(100) DEFAULT NULL,
        tipo VARCHAR(36) NOT NULL,
        ativo BOOLEAN NOT NULL DEFAULT TRUE,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (tipo) REFERENCES gabinete_tipo (id) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO gabinete (id, nome, estado, cidade, partido, tipo) VALUES
    ('1', 'Gabinete Sistema', 'DF', 'Brasília', 'Sem partido', '1');

CREATE TABLE
    usuario_tipo (
        id VARCHAR(36) PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO usuario_tipo (id, nome) VALUES
    ('1', 'Administrador'),
    ('2', 'Comunicação'),
    ('3', 'Secretaria'),
    ('4', 'Legislativo'),
    ('5', 'Orçamento');

CREATE TABLE
    usuario (
        id VARCHAR(36) PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        senha VARCHAR(100) NOT NULL,
        token VARCHAR(36) DEFAULT NULL,
        telefone VARCHAR(20) NOT NULL,
        foto TEXT DEFAULT NULL,
        aniversario VARCHAR(5) NULL,
        ativo BOOLEAN NOT NULL DEFAULT TRUE,
        gabinete VARCHAR(36) NOT NULL,
        tipo_id VARCHAR(36) NOT NULL,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (gabinete) REFERENCES gabinete (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (tipo_id) REFERENCES usuario_tipo (id) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO usuario (id, nome, email, senha, telefone, aniversario, gabinete, tipo_id) VALUES
    ('1', 'Usuario Sistema', 'email@email', 'senha', '99999999999', '01/01', '1', '1');

CREATE TABLE
    orgao_tipo (
        id VARCHAR(36) PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE,
        gabinete VARCHAR(36) NOT NULL,
        criado_por VARCHAR(36) NOT NULL,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (gabinete) REFERENCES gabinete (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (criado_por) REFERENCES usuario (id) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    orgao (
        id VARCHAR(36) NOT NULL PRIMARY KEY,
        nome TEXT NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        telefone VARCHAR(255) DEFAULT NULL,
        endereco TEXT,
        municipio VARCHAR(255) NOT NULL,
        estado VARCHAR(255) NOT NULL,
        cep VARCHAR(255) DEFAULT NULL,
        tipo_id VARCHAR(36) NOT NULL,
        informacoes TEXT,
        site TEXT,
        instagram TEXT,
        twitter TEXT,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        criado_por VARCHAR(36) NOT NULL,
        gabinete VARCHAR(36) NOT NULL,
        FOREIGN KEY (tipo_id) REFERENCES orgao_tipo (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (criado_por) REFERENCES usuario (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (gabinete) REFERENCES gabinete (id) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    pessoa_tipo (
        id VARCHAR(36) PRIMARY KEY,
        nome VARCHAR(100) NOT NULL UNIQUE,
        gabinete VARCHAR(36) NOT NULL,
        criado_por VARCHAR(36) NOT NULL,
        criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (gabinete) REFERENCES gabinete (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (criado_por) REFERENCES usuario (id) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE
    pessoa (
        id VARCHAR(36) NOT NULL PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        aniversario DATE DEFAULT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        telefone VARCHAR(255) DEFAULT NULL,
        endereco TEXT DEFAULT NULL,
        bairro TEXT DEFAULT NULL,
        municipio VARCHAR(255) NOT NULL,
        estado VARCHAR(255) NOT NULL,
        cep VARCHAR(255) DEFAULT NULL,
        sexo VARCHAR(255) DEFAULT NULL,
        facebook VARCHAR(255) DEFAULT NULL,
        instagram VARCHAR(255) DEFAULT NULL,
        twitter VARCHAR(255) DEFAULT NULL,
        informacoes TEXT DEFAULT NULL,
        profissao VARCHAR(36) NOT NULL,
        importancia VARCHAR(20) DEFAULT 'Neutro',
        tipo_id VARCHAR(36) NOT NULL,
        orgao VARCHAR(36) NOT NULL,
        gabinete VARCHAR(36) NOT NULL,
        foto TEXT DEFAULT NULL,
        criado_por VARCHAR(36) NOT NULL,
        criado_em TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        atualizada_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (tipo_id) REFERENCES pessoa_tipo (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (orgao) REFERENCES orgao (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (gabinete) REFERENCES gabinete (id) ON DELETE RESTRICT ON UPDATE RESTRICT,
        FOREIGN KEY (criado_por) REFERENCES usuario (id) ON DELETE RESTRICT ON UPDATE RESTRICT
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;