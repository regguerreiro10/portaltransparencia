CREATE TABLE api_error( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `classe` varchar  (255)   , 
      `metodo` varchar  (255)   , 
      `url` varchar  (500)   , 
      `dados` varchar  (3000)   , 
      `error_message` varchar  (3000)   , 
      `created_at` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE categoria( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `tipo_conta_id` int   NOT NULL  , 
      `nome` text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE categoria_cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cep_cache( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `cep` varchar  (10)   , 
      `rua` varchar  (10)   , 
      `cidade` varchar  (500)   , 
      `bairro` varchar  (500)   , 
      `codigo_ibge` varchar  (20)   , 
      `uf` varchar  (2)   , 
      `cidade_id` int   , 
      `estado_id` int   , 
      `created_at` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE cidade( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `estado_id` int   NOT NULL  , 
      `nome` varchar  (255)   NOT NULL  , 
      `codigo_ibge` varchar  (10)   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE conta( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `pessoa_id` int   NOT NULL  , 
      `tipo_conta_id` int   NOT NULL  , 
      `categoria_id` int   NOT NULL  , 
      `forma_pagamento_id` int   NOT NULL  , 
      `pedido_venda_id` int   , 
      `dt_vencimento` date   , 
      `dt_emissao` date   , 
      `dt_pagamento` date   , 
      `valor` double   , 
      `parcela` int   , 
      `obs` text   , 
      `mes_vencimento` int   , 
      `ano_vencimento` int   , 
      `ano_mes_vencimento` int   , 
      `mes_emissao` int   , 
      `ano_emissao` int   , 
      `ano_mes_emissao` int   , 
      `mes_pagamento` int   , 
      `ano_pagamento` int   , 
      `ano_mes_pagamento` int   , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `system_unit_id` int   , 
      `system_departamento_id` int   , 
      `system_entidade_id` int   , 
      `natureza_conta_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE conta_anexo( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `conta_id` int   NOT NULL  , 
      `tipo_anexo_id` int   NOT NULL  , 
      `descricao` text   , 
      `arquivo` text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE dotacao_orcamentaria_conta( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `conta_id` int   , 
      `system_saldo_empenho_departamento_id` int   , 
      `valor` double   , 
      `saldo_atual` double   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE error_log_crontab( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `classe` text   , 
      `metodo` text   , 
      `mensagem` text   , 
      `created_at` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE estado( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` varchar  (255)   NOT NULL  , 
      `sigla` char  (2)   NOT NULL  , 
      `codigo_ibge` varchar  (10)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE forma_pagamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE grupo_pessoa( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE natureza_conta( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `descricao` varchar  (40)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pessoa( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `tipo_cliente_id` int   NOT NULL  , 
      `categoria_cliente_id` int   , 
      `system_user_id` int   , 
      `nome` varchar  (500)   NOT NULL  , 
      `documento` varchar  (20)   NOT NULL  , 
      `obs` varchar  (1000)   , 
      `fone` varchar  (255)   , 
      `email` varchar  (255)   , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `login` varchar  (255)   , 
      `senha` varchar  (255)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pessoa_contato( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `pessoa_id` int   NOT NULL  , 
      `email` varchar  (255)   , 
      `nome` varchar  (255)   , 
      `telefone` varchar  (255)   , 
      `obs` varchar  (500)   , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pessoa_departamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `pessoa_id` int   , 
      `system_departamento_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pessoa_endereco( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `pessoa_id` int   NOT NULL  , 
      `cidade_id` int   NOT NULL  , 
      `nome` varchar  (255)   , 
      `principal` char  (1)   , 
      `cep` varchar  (10)   , 
      `rua` varchar  (500)   , 
      `numero` varchar  (20)   , 
      `bairro` varchar  (500)   , 
      `complemento` varchar  (500)   , 
      `data_desativacao` date   , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `longitude` varchar  (20)   , 
      `latitude` varchar  (20)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE pessoa_grupo( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `pessoa_id` int   NOT NULL  , 
      `grupo_pessoa_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_departamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `system_unit_id` int   , 
      `cidade_id` int   , 
      `nome` text   , 
      `rua` varchar  (500)   , 
      `cep` varchar  (10)   , 
      `bairro` varchar  (500)   , 
      `numero` varchar  (20)   , 
      `longitude` varchar  (20)   , 
      `latitude` varchar  (20)   , 
      `email` varchar  (500)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_documentos( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `system_unit_id` int   , 
      `data` date   , 
      `mes` int   , 
      `ano` int   , 
      `arquivo` varchar  (500)   , 
      `tipo_system_documentos_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_entidade( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `cnpj` varchar  (20)   , 
      `nome` text   , 
      `email` text   , 
      `cep` varchar  (10)   , 
      `numero` varchar  (10)   , 
      `rua` varchar  (500)   , 
      `bairro` varchar  (500)   , 
      `telefone01` varchar  (20)   , 
      `telefone02` varchar  (20)   , 
      `longitude` varchar  (20)   , 
      `latitude` varchar  (20)   , 
      `logo` varchar  (500)   , 
      `complemento` varchar  (500)   , 
      `cidade_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_group( 
      `id` int   NOT NULL  , 
      `name` text   NOT NULL  , 
      `uuid` varchar  (36)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_group_program( 
      `id` int   NOT NULL  , 
      `system_group_id` int   NOT NULL  , 
      `system_program_id` int   NOT NULL  , 
      `actions` text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_preference( 
      `id` varchar  (255)   NOT NULL  , 
      `preference` text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_program( 
      `id` int   NOT NULL  , 
      `name` text   NOT NULL  , 
      `controller` text   NOT NULL  , 
      `actions` text   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_saldo_empenho_departamento( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `system_departamento_id` int   , 
      `saldo` double   , 
      `historico` varchar  (500)   , 
      `numero_documento_empenho` varchar  (500)   , 
      `data_documento_empenho` date   , 
      `numero_processo` varchar  (500)   , 
      `arquivo_empenho` varchar  (500)   , 
      `system_users_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_unit( 
      `id` int   NOT NULL  , 
      `name` text   NOT NULL  , 
      `connection_name` text   , 
      `email` varchar  (500)   , 
      `cep` varchar  (10)   , 
      `rua` varchar  (500)   , 
      `numero` varchar  (10)   , 
      `bairro` varchar  (500)   , 
      `complemento` varchar  (500)   , 
      `cnpj` varchar  (20)   , 
      `telefone01` varchar  (50)   , 
      `telefone02` varchar  (50)   , 
      `logo` varchar  (500)   , 
      `longitude` varchar  (20)   , 
      `latitude` varchar  (20)   , 
      `system_entidade_id` int   , 
      `cidade_id` int   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_user_group( 
      `id` int   NOT NULL  , 
      `system_user_id` int   NOT NULL  , 
      `system_group_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_user_program( 
      `id` int   NOT NULL  , 
      `system_user_id` int   NOT NULL  , 
      `system_program_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_users( 
      `id` int   NOT NULL  , 
      `name` text   NOT NULL  , 
      `login` text   NOT NULL  , 
      `password` text   NOT NULL  , 
      `email` text   , 
      `frontpage_id` int   , 
      `system_unit_id` int   , 
      `active` char  (1)   , 
      `accepted_term_policy_at` text   , 
      `accepted_term_policy` char  (1)   , 
      `two_factor_enabled` char  (1)     DEFAULT 'N', 
      `two_factor_type` varchar  (100)   , 
      `two_factor_secret` varchar  (255)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE system_user_unit( 
      `id` int   NOT NULL  , 
      `system_user_id` int   NOT NULL  , 
      `system_unit_id` int   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tipo_anexo( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tipo_cliente( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` varchar  (255)   NOT NULL  , 
      `sigla` char  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tipo_conta( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `nome` text   NOT NULL  , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

CREATE TABLE tipo_system_documentos( 
      `id`  INT  AUTO_INCREMENT    NOT NULL  , 
      `created_at` datetime   , 
      `updated_at` datetime   , 
      `deleted_at` datetime   , 
      `descricao` varchar  (100)   , 
 PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; 

 
  
 ALTER TABLE categoria ADD CONSTRAINT fk_categoria_1 FOREIGN KEY (tipo_conta_id) references tipo_conta(id); 
ALTER TABLE cidade ADD CONSTRAINT fk_cidade_1 FOREIGN KEY (estado_id) references estado(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_1 FOREIGN KEY (tipo_conta_id) references tipo_conta(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_2 FOREIGN KEY (categoria_id) references categoria(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_3 FOREIGN KEY (forma_pagamento_id) references forma_pagamento(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_4 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_5 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_6 FOREIGN KEY (system_departamento_id) references system_departamento(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_7 FOREIGN KEY (system_entidade_id) references system_entidade(id); 
ALTER TABLE conta ADD CONSTRAINT fk_conta_8 FOREIGN KEY (natureza_conta_id) references natureza_conta(id); 
ALTER TABLE conta_anexo ADD CONSTRAINT fk_conta_anexo_1 FOREIGN KEY (conta_id) references conta(id); 
ALTER TABLE conta_anexo ADD CONSTRAINT fk_conta_anexo_2 FOREIGN KEY (tipo_anexo_id) references tipo_anexo(id); 
ALTER TABLE dotacao_orcamentaria_conta ADD CONSTRAINT fk_dotacao_conta_1 FOREIGN KEY (conta_id) references conta(id); 
ALTER TABLE dotacao_orcamentaria_conta ADD CONSTRAINT fk_dotacao_conta_2 FOREIGN KEY (system_saldo_empenho_departamento_id) references system_saldo_empenho_departamento(id); 
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_1 FOREIGN KEY (tipo_cliente_id) references tipo_cliente(id); 
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_2 FOREIGN KEY (categoria_cliente_id) references categoria_cliente(id); 
ALTER TABLE pessoa ADD CONSTRAINT fk_pessoa_3 FOREIGN KEY (system_user_id) references system_users(id); 
ALTER TABLE pessoa_contato ADD CONSTRAINT fk_pessoa_contato_1 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE pessoa_departamento ADD CONSTRAINT fk_pessoa_departamento_1 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE pessoa_departamento ADD CONSTRAINT fk_pessoa_departamento_2 FOREIGN KEY (system_departamento_id) references system_departamento(id); 
ALTER TABLE pessoa_endereco ADD CONSTRAINT fk_pessoa_endereco_1 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE pessoa_endereco ADD CONSTRAINT fk_pessoa_endereco_2 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE pessoa_grupo ADD CONSTRAINT fk_pessoa_grupo_1 FOREIGN KEY (pessoa_id) references pessoa(id); 
ALTER TABLE pessoa_grupo ADD CONSTRAINT fk_pessoa_grupo_2 FOREIGN KEY (grupo_pessoa_id) references grupo_pessoa(id); 
ALTER TABLE system_departamento ADD CONSTRAINT fk_system_departamento_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE system_departamento ADD CONSTRAINT fk_system_departamento_2 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE system_documentos ADD CONSTRAINT fk_balancos_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE system_documentos ADD CONSTRAINT fk_system_documentos_2 FOREIGN KEY (tipo_system_documentos_id) references tipo_system_documentos(id); 
ALTER TABLE system_entidade ADD CONSTRAINT fk_system_entidade_1 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE system_group_program ADD CONSTRAINT fk_system_group_program_1 FOREIGN KEY (system_program_id) references system_program(id); 
ALTER TABLE system_group_program ADD CONSTRAINT fk_system_group_program_2 FOREIGN KEY (system_group_id) references system_group(id); 
ALTER TABLE system_saldo_empenho_departamento ADD CONSTRAINT fk_system_saldo_depsec_1 FOREIGN KEY (system_departamento_id) references system_departamento(id); 
ALTER TABLE system_saldo_empenho_departamento ADD CONSTRAINT fk_system_saldo_empenho_depsec_2 FOREIGN KEY (system_users_id) references system_users(id); 
ALTER TABLE system_unit ADD CONSTRAINT fk_system_unit_1 FOREIGN KEY (system_entidade_id) references system_entidade(id); 
ALTER TABLE system_unit ADD CONSTRAINT fk_system_unit_2 FOREIGN KEY (cidade_id) references cidade(id); 
ALTER TABLE system_user_group ADD CONSTRAINT fk_system_user_group_1 FOREIGN KEY (system_group_id) references system_group(id); 
ALTER TABLE system_user_group ADD CONSTRAINT fk_system_user_group_2 FOREIGN KEY (system_user_id) references system_users(id); 
ALTER TABLE system_user_program ADD CONSTRAINT fk_system_user_program_1 FOREIGN KEY (system_program_id) references system_program(id); 
ALTER TABLE system_user_program ADD CONSTRAINT fk_system_user_program_2 FOREIGN KEY (system_user_id) references system_users(id); 
ALTER TABLE system_users ADD CONSTRAINT fk_system_user_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE system_users ADD CONSTRAINT fk_system_user_2 FOREIGN KEY (frontpage_id) references system_program(id); 
ALTER TABLE system_user_unit ADD CONSTRAINT fk_system_user_unit_1 FOREIGN KEY (system_user_id) references system_users(id); 
ALTER TABLE system_user_unit ADD CONSTRAINT fk_system_user_unit_2 FOREIGN KEY (system_unit_id) references system_unit(id); 
