CREATE TABLE api_error( 
      id  SERIAL    NOT NULL  , 
      classe varchar  (255)   , 
      metodo varchar  (255)   , 
      url varchar  (500)   , 
      dados varchar  (3000)   , 
      error_message varchar  (3000)   , 
      created_at timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id  SERIAL    NOT NULL  , 
      tipo_conta_id integer   NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_cliente( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_cache( 
      id  SERIAL    NOT NULL  , 
      cep varchar  (10)   , 
      rua varchar  (10)   , 
      cidade varchar  (500)   , 
      bairro varchar  (500)   , 
      codigo_ibge varchar  (20)   , 
      uf varchar  (2)   , 
      cidade_id integer   , 
      estado_id integer   , 
      created_at timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  SERIAL    NOT NULL  , 
      estado_id integer   NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
      codigo_ibge varchar  (10)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
      tipo_conta_id integer   NOT NULL  , 
      categoria_id integer   NOT NULL  , 
      forma_pagamento_id integer   NOT NULL  , 
      pedido_venda_id integer   , 
      dt_vencimento date   , 
      dt_emissao date   , 
      dt_pagamento date   , 
      valor float   , 
      parcela integer   , 
      obs text   , 
      mes_vencimento integer   , 
      ano_vencimento integer   , 
      ano_mes_vencimento integer   , 
      mes_emissao integer   , 
      ano_emissao integer   , 
      ano_mes_emissao integer   , 
      mes_pagamento integer   , 
      ano_pagamento integer   , 
      ano_mes_pagamento integer   , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      system_unit_id integer   , 
      system_departamento_id integer   , 
      system_entidade_id integer   , 
      natureza_conta_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta_anexo( 
      id  SERIAL    NOT NULL  , 
      conta_id integer   NOT NULL  , 
      tipo_anexo_id integer   NOT NULL  , 
      descricao text   , 
      arquivo text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dotacao_orcamentaria_conta( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      conta_id integer   , 
      system_saldo_empenho_departamento_id integer   , 
      valor float   , 
      saldo_atual float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE error_log_crontab( 
      id  SERIAL    NOT NULL  , 
      classe text   , 
      metodo text   , 
      mensagem text   , 
      created_at timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
      sigla char  (2)   NOT NULL  , 
      codigo_ibge varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE forma_pagamento( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo_pessoa( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza_conta( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      descricao varchar  (40)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa( 
      id  SERIAL    NOT NULL  , 
      tipo_cliente_id integer   NOT NULL  , 
      categoria_cliente_id integer   , 
      system_user_id integer   , 
      nome varchar  (500)   NOT NULL  , 
      documento varchar  (20)   NOT NULL  , 
      obs varchar  (1000)   , 
      fone varchar  (255)   , 
      email varchar  (255)   , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      login varchar  (255)   , 
      senha varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_contato( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
      email varchar  (255)   , 
      nome varchar  (255)   , 
      telefone varchar  (255)   , 
      obs varchar  (500)   , 
      created_at timestamp   , 
      updated_at timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_departamento( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      pessoa_id integer   , 
      system_departamento_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_endereco( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
      cidade_id integer   NOT NULL  , 
      nome varchar  (255)   , 
      principal char  (1)   , 
      cep varchar  (10)   , 
      rua varchar  (500)   , 
      numero varchar  (20)   , 
      bairro varchar  (500)   , 
      complemento varchar  (500)   , 
      data_desativacao date   , 
      created_at timestamp   , 
      updated_at timestamp   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_grupo( 
      id  SERIAL    NOT NULL  , 
      pessoa_id integer   NOT NULL  , 
      grupo_pessoa_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_departamento( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      system_unit_id integer   , 
      cidade_id integer   , 
      nome text   , 
      rua varchar  (500)   , 
      cep varchar  (10)   , 
      bairro varchar  (500)   , 
      numero varchar  (20)   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
      email varchar  (500)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_documentos( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      system_unit_id integer   , 
      data date   , 
      mes integer   , 
      ano integer   , 
      arquivo varchar  (500)   , 
      tipo_system_documentos_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_entidade( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      cnpj varchar  (20)   , 
      nome text   , 
      email text   , 
      cep varchar  (10)   , 
      numero varchar  (10)   , 
      rua varchar  (500)   , 
      bairro varchar  (500)   , 
      telefone01 varchar  (20)   , 
      telefone02 varchar  (20)   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
      logo varchar  (500)   , 
      complemento varchar  (500)   , 
      cidade_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group( 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      uuid varchar  (36)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group_program( 
      id integer   NOT NULL  , 
      system_group_id integer   NOT NULL  , 
      system_program_id integer   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_preference( 
      id varchar  (255)   NOT NULL  , 
      preference text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_program( 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      controller text   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_saldo_empenho_departamento( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      system_departamento_id integer   , 
      saldo float   , 
      historico varchar  (500)   , 
      numero_documento_empenho varchar  (500)   , 
      data_documento_empenho date   , 
      numero_processo varchar  (500)   , 
      arquivo_empenho varchar  (500)   , 
      system_users_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_unit( 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      connection_name text   , 
      email varchar  (500)   , 
      cep varchar  (10)   , 
      rua varchar  (500)   , 
      numero varchar  (10)   , 
      bairro varchar  (500)   , 
      complemento varchar  (500)   , 
      cnpj varchar  (20)   , 
      telefone01 varchar  (50)   , 
      telefone02 varchar  (50)   , 
      logo varchar  (500)   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
      system_entidade_id integer   , 
      cidade_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_group( 
      id integer   NOT NULL  , 
      system_user_id integer   NOT NULL  , 
      system_group_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_program( 
      id integer   NOT NULL  , 
      system_user_id integer   NOT NULL  , 
      system_program_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_users( 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      login text   NOT NULL  , 
      password text   NOT NULL  , 
      email text   , 
      frontpage_id integer   , 
      system_unit_id integer   , 
      active char  (1)   , 
      accepted_term_policy_at text   , 
      accepted_term_policy char  (1)   , 
      two_factor_enabled char  (1)     DEFAULT 'N', 
      two_factor_type varchar  (100)   , 
      two_factor_secret varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_unit( 
      id integer   NOT NULL  , 
      system_user_id integer   NOT NULL  , 
      system_unit_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_anexo( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cliente( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
      sigla char  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta( 
      id  SERIAL    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_system_documentos( 
      id  SERIAL    NOT NULL  , 
      created_at timestamp   , 
      updated_at timestamp   , 
      deleted_at timestamp   , 
      descricao varchar  (100)   , 
 PRIMARY KEY (id)) ; 

 
  
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
 
 CREATE index idx_categoria_tipo_conta_id on categoria(tipo_conta_id); 
CREATE index idx_cidade_estado_id on cidade(estado_id); 
CREATE index idx_conta_tipo_conta_id on conta(tipo_conta_id); 
CREATE index idx_conta_categoria_id on conta(categoria_id); 
CREATE index idx_conta_forma_pagamento_id on conta(forma_pagamento_id); 
CREATE index idx_conta_pessoa_id on conta(pessoa_id); 
CREATE index idx_conta_system_unit_id on conta(system_unit_id); 
CREATE index idx_conta_system_departamento_id on conta(system_departamento_id); 
CREATE index idx_conta_system_entidade_id on conta(system_entidade_id); 
CREATE index idx_conta_natureza_conta_id on conta(natureza_conta_id); 
CREATE index idx_conta_anexo_conta_id on conta_anexo(conta_id); 
CREATE index idx_conta_anexo_tipo_anexo_id on conta_anexo(tipo_anexo_id); 
CREATE index idx_dotacao_orcamentaria_conta_conta_id on dotacao_orcamentaria_conta(conta_id); 
CREATE index idx_dotacao_orcamentaria_conta_system_saldo_em_69c18f3cb1884 on dotacao_orcamentaria_conta(system_saldo_empenho_departamento_id); 
CREATE index idx_pessoa_tipo_cliente_id on pessoa(tipo_cliente_id); 
CREATE index idx_pessoa_categoria_cliente_id on pessoa(categoria_cliente_id); 
CREATE index idx_pessoa_system_user_id on pessoa(system_user_id); 
CREATE index idx_pessoa_contato_pessoa_id on pessoa_contato(pessoa_id); 
CREATE index idx_pessoa_departamento_pessoa_id on pessoa_departamento(pessoa_id); 
CREATE index idx_pessoa_departamento_system_departamento_id on pessoa_departamento(system_departamento_id); 
CREATE index idx_pessoa_endereco_pessoa_id on pessoa_endereco(pessoa_id); 
CREATE index idx_pessoa_endereco_cidade_id on pessoa_endereco(cidade_id); 
CREATE index idx_pessoa_grupo_pessoa_id on pessoa_grupo(pessoa_id); 
CREATE index idx_pessoa_grupo_grupo_pessoa_id on pessoa_grupo(grupo_pessoa_id); 
CREATE index idx_system_departamento_system_unit_id on system_departamento(system_unit_id); 
CREATE index idx_system_departamento_cidade_id on system_departamento(cidade_id); 
CREATE index idx_system_documentos_system_unit_id on system_documentos(system_unit_id); 
CREATE index idx_system_documentos_tipo_system_documentos_id on system_documentos(tipo_system_documentos_id); 
CREATE index idx_system_entidade_cidade_id on system_entidade(cidade_id); 
CREATE index idx_system_group_program_system_program_id on system_group_program(system_program_id); 
CREATE index idx_system_group_program_system_group_id on system_group_program(system_group_id); 
CREATE index idx_system_saldo_empenho_departamento_system_departamento_id on system_saldo_empenho_departamento(system_departamento_id); 
CREATE index idx_system_saldo_empenho_departamento_system_users_id on system_saldo_empenho_departamento(system_users_id); 
CREATE index idx_system_unit_system_entidade_id on system_unit(system_entidade_id); 
CREATE index idx_system_unit_cidade_id on system_unit(cidade_id); 
CREATE index idx_system_user_group_system_group_id on system_user_group(system_group_id); 
CREATE index idx_system_user_group_system_user_id on system_user_group(system_user_id); 
CREATE index idx_system_user_program_system_program_id on system_user_program(system_program_id); 
CREATE index idx_system_user_program_system_user_id on system_user_program(system_user_id); 
CREATE index idx_system_users_system_unit_id on system_users(system_unit_id); 
CREATE index idx_system_users_frontpage_id on system_users(frontpage_id); 
CREATE index idx_system_user_unit_system_user_id on system_user_unit(system_user_id); 
CREATE index idx_system_user_unit_system_unit_id on system_user_unit(system_unit_id); 
