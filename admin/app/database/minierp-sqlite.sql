PRAGMA foreign_keys=OFF; 

CREATE TABLE api_error( 
      id  INTEGER    NOT NULL  , 
      classe varchar  (255)   , 
      metodo varchar  (255)   , 
      url varchar  (500)   , 
      dados varchar  (3000)   , 
      error_message varchar  (3000)   , 
      created_at datetime   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id  INTEGER    NOT NULL  , 
      tipo_conta_id int   NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(tipo_conta_id) REFERENCES tipo_conta(id)) ; 

CREATE TABLE categoria_cliente( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_cache( 
      id  INTEGER    NOT NULL  , 
      cep varchar  (10)   , 
      rua varchar  (10)   , 
      cidade varchar  (500)   , 
      bairro varchar  (500)   , 
      codigo_ibge varchar  (20)   , 
      uf varchar  (2)   , 
      cidade_id int   , 
      estado_id int   , 
      created_at datetime   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id  INTEGER    NOT NULL  , 
      estado_id int   NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
      codigo_ibge varchar  (10)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(estado_id) REFERENCES estado(id)) ; 

CREATE TABLE conta( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      tipo_conta_id int   NOT NULL  , 
      categoria_id int   NOT NULL  , 
      forma_pagamento_id int   NOT NULL  , 
      pedido_venda_id int   , 
      dt_vencimento date   , 
      dt_emissao date   , 
      dt_pagamento date   , 
      valor double   , 
      parcela int   , 
      obs text   , 
      mes_vencimento int   , 
      ano_vencimento int   , 
      ano_mes_vencimento int   , 
      mes_emissao int   , 
      ano_emissao int   , 
      ano_mes_emissao int   , 
      mes_pagamento int   , 
      ano_pagamento int   , 
      ano_mes_pagamento int   , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      system_unit_id int   , 
      system_departamento_id int   , 
      system_entidade_id int   , 
      natureza_conta_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(tipo_conta_id) REFERENCES tipo_conta(id),
FOREIGN KEY(categoria_id) REFERENCES categoria(id),
FOREIGN KEY(forma_pagamento_id) REFERENCES forma_pagamento(id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(system_departamento_id) REFERENCES system_departamento(id),
FOREIGN KEY(system_entidade_id) REFERENCES system_entidade(id),
FOREIGN KEY(natureza_conta_id) REFERENCES natureza_conta(id)) ; 

CREATE TABLE conta_anexo( 
      id  INTEGER    NOT NULL  , 
      conta_id int   NOT NULL  , 
      tipo_anexo_id int   NOT NULL  , 
      descricao text   , 
      arquivo text   , 
 PRIMARY KEY (id),
FOREIGN KEY(conta_id) REFERENCES conta(id),
FOREIGN KEY(tipo_anexo_id) REFERENCES tipo_anexo(id)) ; 

CREATE TABLE dotacao_orcamentaria_conta( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      conta_id int   , 
      system_saldo_empenho_departamento_id int   , 
      valor double   , 
      saldo_atual double   , 
 PRIMARY KEY (id),
FOREIGN KEY(conta_id) REFERENCES conta(id),
FOREIGN KEY(system_saldo_empenho_departamento_id) REFERENCES system_saldo_empenho_departamento(id)) ; 

CREATE TABLE error_log_crontab( 
      id  INTEGER    NOT NULL  , 
      classe text   , 
      metodo text   , 
      mensagem text   , 
      created_at datetime   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
      sigla char  (2)   NOT NULL  , 
      codigo_ibge varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE forma_pagamento( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo_pessoa( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza_conta( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      descricao varchar  (40)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa( 
      id  INTEGER    NOT NULL  , 
      tipo_cliente_id int   NOT NULL  , 
      categoria_cliente_id int   , 
      system_user_id int   , 
      nome varchar  (500)   NOT NULL  , 
      documento varchar  (20)   NOT NULL  , 
      obs varchar  (1000)   , 
      fone varchar  (255)   , 
      email varchar  (255)   , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      login varchar  (255)   , 
      senha varchar  (255)   , 
 PRIMARY KEY (id),
FOREIGN KEY(tipo_cliente_id) REFERENCES tipo_cliente(id),
FOREIGN KEY(categoria_cliente_id) REFERENCES categoria_cliente(id),
FOREIGN KEY(system_user_id) REFERENCES system_users(id)) ; 

CREATE TABLE pessoa_contato( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      email varchar  (255)   , 
      nome varchar  (255)   , 
      telefone varchar  (255)   , 
      obs varchar  (500)   , 
      created_at datetime   , 
      updated_at datetime   , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id)) ; 

CREATE TABLE pessoa_departamento( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      pessoa_id int   , 
      system_departamento_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(system_departamento_id) REFERENCES system_departamento(id)) ; 

CREATE TABLE pessoa_endereco( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      cidade_id int   NOT NULL  , 
      nome varchar  (255)   , 
      principal char  (1)   , 
      cep varchar  (10)   , 
      rua varchar  (500)   , 
      numero varchar  (20)   , 
      bairro varchar  (500)   , 
      complemento varchar  (500)   , 
      data_desativacao date   , 
      created_at datetime   , 
      updated_at datetime   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id)) ; 

CREATE TABLE pessoa_grupo( 
      id  INTEGER    NOT NULL  , 
      pessoa_id int   NOT NULL  , 
      grupo_pessoa_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(pessoa_id) REFERENCES pessoa(id),
FOREIGN KEY(grupo_pessoa_id) REFERENCES grupo_pessoa(id)) ; 

CREATE TABLE system_departamento( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      system_unit_id int   , 
      cidade_id int   , 
      nome text   , 
      rua varchar  (500)   , 
      cep varchar  (10)   , 
      bairro varchar  (500)   , 
      numero varchar  (20)   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
      email varchar  (500)   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id)) ; 

CREATE TABLE system_documentos( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      system_unit_id int   , 
      data date   , 
      mes int   , 
      ano int   , 
      arquivo varchar  (500)   , 
      tipo_system_documentos_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(tipo_system_documentos_id) REFERENCES tipo_system_documentos(id)) ; 

CREATE TABLE system_entidade( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
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
      cidade_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id)) ; 

CREATE TABLE system_group( 
      id int   NOT NULL  , 
      name text   NOT NULL  , 
      uuid varchar  (36)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group_program( 
      id int   NOT NULL  , 
      system_group_id int   NOT NULL  , 
      system_program_id int   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_program_id) REFERENCES system_program(id),
FOREIGN KEY(system_group_id) REFERENCES system_group(id)) ; 

CREATE TABLE system_preference( 
      id varchar  (255)   NOT NULL  , 
      preference text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_program( 
      id int   NOT NULL  , 
      name text   NOT NULL  , 
      controller text   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_saldo_empenho_departamento( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      system_departamento_id int   , 
      saldo double   , 
      historico varchar  (500)   , 
      numero_documento_empenho varchar  (500)   , 
      data_documento_empenho date   , 
      numero_processo varchar  (500)   , 
      arquivo_empenho varchar  (500)   , 
      system_users_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_departamento_id) REFERENCES system_departamento(id),
FOREIGN KEY(system_users_id) REFERENCES system_users(id)) ; 

CREATE TABLE system_unit( 
      id int   NOT NULL  , 
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
      system_entidade_id int   , 
      cidade_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_entidade_id) REFERENCES system_entidade(id),
FOREIGN KEY(cidade_id) REFERENCES cidade(id)) ; 

CREATE TABLE system_user_group( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_group_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_group_id) REFERENCES system_group(id),
FOREIGN KEY(system_user_id) REFERENCES system_users(id)) ; 

CREATE TABLE system_user_program( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_program_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_program_id) REFERENCES system_program(id),
FOREIGN KEY(system_user_id) REFERENCES system_users(id)) ; 

CREATE TABLE system_users( 
      id int   NOT NULL  , 
      name text   NOT NULL  , 
      login text   NOT NULL  , 
      password text   NOT NULL  , 
      email text   , 
      frontpage_id int   , 
      system_unit_id int   , 
      active char  (1)   , 
      accepted_term_policy_at text   , 
      accepted_term_policy char  (1)   , 
      two_factor_enabled char  (1)     DEFAULT 'N', 
      two_factor_type varchar  (100)   , 
      two_factor_secret varchar  (255)   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(frontpage_id) REFERENCES system_program(id)) ; 

CREATE TABLE system_user_unit( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_user_id) REFERENCES system_users(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE tipo_anexo( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cliente( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
      sigla char  (2)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta( 
      id  INTEGER    NOT NULL  , 
      nome text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_system_documentos( 
      id  INTEGER    NOT NULL  , 
      created_at datetime   , 
      updated_at datetime   , 
      deleted_at datetime   , 
      descricao varchar  (100)   , 
 PRIMARY KEY (id)) ; 

 
 