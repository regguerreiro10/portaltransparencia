CREATE TABLE api_error( 
      id number(10)    NOT NULL , 
      classe varchar  (255)   , 
      metodo varchar  (255)   , 
      url varchar  (500)   , 
      dados varchar  (3000)   , 
      error_message varchar  (3000)   , 
      created_at timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria( 
      id number(10)    NOT NULL , 
      tipo_conta_id number(10)    NOT NULL , 
      nome varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE categoria_cliente( 
      id number(10)    NOT NULL , 
      nome varchar  (255)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_cache( 
      id number(10)    NOT NULL , 
      cep varchar  (10)   , 
      rua varchar  (10)   , 
      cidade varchar  (500)   , 
      bairro varchar  (500)   , 
      codigo_ibge varchar  (20)   , 
      uf varchar  (2)   , 
      cidade_id number(10)   , 
      estado_id number(10)   , 
      created_at timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cidade( 
      id number(10)    NOT NULL , 
      estado_id number(10)    NOT NULL , 
      nome varchar  (255)    NOT NULL , 
      codigo_ibge varchar  (10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
      tipo_conta_id number(10)    NOT NULL , 
      categoria_id number(10)    NOT NULL , 
      forma_pagamento_id number(10)    NOT NULL , 
      pedido_venda_id number(10)   , 
      dt_vencimento date   , 
      dt_emissao date   , 
      dt_pagamento date   , 
      valor binary_double   , 
      parcela number(10)   , 
      obs varchar(3000)   , 
      mes_vencimento number(10)   , 
      ano_vencimento number(10)   , 
      ano_mes_vencimento number(10)   , 
      mes_emissao number(10)   , 
      ano_emissao number(10)   , 
      ano_mes_emissao number(10)   , 
      mes_pagamento number(10)   , 
      ano_pagamento number(10)   , 
      ano_mes_pagamento number(10)   , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      system_unit_id number(10)   , 
      system_departamento_id number(10)   , 
      system_entidade_id number(10)   , 
      natureza_conta_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE conta_anexo( 
      id number(10)    NOT NULL , 
      conta_id number(10)    NOT NULL , 
      tipo_anexo_id number(10)    NOT NULL , 
      descricao varchar(3000)   , 
      arquivo varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE dotacao_orcamentaria_conta( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      conta_id number(10)   , 
      system_saldo_empenho_departamento_id number(10)   , 
      valor binary_double   , 
      saldo_atual binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE error_log_crontab( 
      id number(10)    NOT NULL , 
      classe varchar(3000)   , 
      metodo varchar(3000)   , 
      mensagem varchar(3000)   , 
      created_at timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE estado( 
      id number(10)    NOT NULL , 
      nome varchar  (255)    NOT NULL , 
      sigla char  (2)    NOT NULL , 
      codigo_ibge varchar  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE forma_pagamento( 
      id number(10)    NOT NULL , 
      nome varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo_pessoa( 
      id number(10)    NOT NULL , 
      nome varchar  (255)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE natureza_conta( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      descricao varchar  (40)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa( 
      id number(10)    NOT NULL , 
      tipo_cliente_id number(10)    NOT NULL , 
      categoria_cliente_id number(10)   , 
      system_user_id number(10)   , 
      nome varchar  (500)    NOT NULL , 
      documento varchar  (20)    NOT NULL , 
      obs varchar  (1000)   , 
      fone varchar  (255)   , 
      email varchar  (255)   , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      login varchar  (255)   , 
      senha varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_contato( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
      email varchar  (255)   , 
      nome varchar  (255)   , 
      telefone varchar  (255)   , 
      obs varchar  (500)   , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_departamento( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      pessoa_id number(10)   , 
      system_departamento_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_endereco( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
      cidade_id number(10)    NOT NULL , 
      nome varchar  (255)   , 
      principal char  (1)   , 
      cep varchar  (10)   , 
      rua varchar  (500)   , 
      numero varchar  (20)   , 
      bairro varchar  (500)   , 
      complemento varchar  (500)   , 
      data_desativacao date   , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE pessoa_grupo( 
      id number(10)    NOT NULL , 
      pessoa_id number(10)    NOT NULL , 
      grupo_pessoa_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_departamento( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      system_unit_id number(10)   , 
      cidade_id number(10)   , 
      nome varchar(3000)   , 
      rua varchar  (500)   , 
      cep varchar  (10)   , 
      bairro varchar  (500)   , 
      numero varchar  (20)   , 
      longitude varchar  (20)   , 
      latitude varchar  (20)   , 
      email varchar  (500)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_documentos( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      system_unit_id number(10)   , 
      data date   , 
      mes number(10)   , 
      ano number(10)   , 
      arquivo varchar  (500)   , 
      tipo_system_documentos_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_entidade( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      cnpj varchar  (20)   , 
      nome varchar(3000)   , 
      email varchar(3000)   , 
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
      cidade_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      uuid varchar  (36)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group_program( 
      id number(10)    NOT NULL , 
      system_group_id number(10)    NOT NULL , 
      system_program_id number(10)    NOT NULL , 
      actions varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_preference( 
      id varchar  (255)    NOT NULL , 
      preference varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_program( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      controller varchar(3000)    NOT NULL , 
      actions varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_saldo_empenho_departamento( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
      system_departamento_id number(10)   , 
      saldo binary_double   , 
      historico varchar  (500)   , 
      numero_documento_empenho varchar  (500)   , 
      data_documento_empenho date   , 
      numero_processo varchar  (500)   , 
      arquivo_empenho varchar  (500)   , 
      system_users_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_unit( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      connection_name varchar(3000)   , 
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
      system_entidade_id number(10)   , 
      cidade_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_group( 
      id number(10)    NOT NULL , 
      system_user_id number(10)    NOT NULL , 
      system_group_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_program( 
      id number(10)    NOT NULL , 
      system_user_id number(10)    NOT NULL , 
      system_program_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_users( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      login varchar(3000)    NOT NULL , 
      password varchar(3000)    NOT NULL , 
      email varchar(3000)   , 
      frontpage_id number(10)   , 
      system_unit_id number(10)   , 
      active char  (1)   , 
      accepted_term_policy_at varchar(3000)   , 
      accepted_term_policy char  (1)   , 
      two_factor_enabled char  (1)    DEFAULT 'N' , 
      two_factor_type varchar  (100)   , 
      two_factor_secret varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_unit( 
      id number(10)    NOT NULL , 
      system_user_id number(10)    NOT NULL , 
      system_unit_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_anexo( 
      id number(10)    NOT NULL , 
      nome varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_cliente( 
      id number(10)    NOT NULL , 
      nome varchar  (255)    NOT NULL , 
      sigla char  (2)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_conta( 
      id number(10)    NOT NULL , 
      nome varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_system_documentos( 
      id number(10)    NOT NULL , 
      created_at timestamp(0)   , 
      updated_at timestamp(0)   , 
      deleted_at timestamp(0)   , 
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
 CREATE SEQUENCE api_error_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER api_error_id_seq_tr 

BEFORE INSERT ON api_error FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT api_error_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE categoria_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER categoria_id_seq_tr 

BEFORE INSERT ON categoria FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT categoria_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE categoria_cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER categoria_cliente_id_seq_tr 

BEFORE INSERT ON categoria_cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT categoria_cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cep_cache_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cep_cache_id_seq_tr 

BEFORE INSERT ON cep_cache FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cep_cache_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cidade_id_seq_tr 

BEFORE INSERT ON cidade FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE conta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER conta_id_seq_tr 

BEFORE INSERT ON conta FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT conta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE conta_anexo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER conta_anexo_id_seq_tr 

BEFORE INSERT ON conta_anexo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT conta_anexo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE dotacao_orcamentaria_conta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER dotacao_orcamentaria_conta_id_seq_tr 

BEFORE INSERT ON dotacao_orcamentaria_conta FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT dotacao_orcamentaria_conta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE error_log_crontab_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER error_log_crontab_id_seq_tr 

BEFORE INSERT ON error_log_crontab FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT error_log_crontab_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER estado_id_seq_tr 

BEFORE INSERT ON estado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE forma_pagamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER forma_pagamento_id_seq_tr 

BEFORE INSERT ON forma_pagamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT forma_pagamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE grupo_pessoa_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER grupo_pessoa_id_seq_tr 

BEFORE INSERT ON grupo_pessoa FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT grupo_pessoa_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE natureza_conta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER natureza_conta_id_seq_tr 

BEFORE INSERT ON natureza_conta FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT natureza_conta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_id_seq_tr 

BEFORE INSERT ON pessoa FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pessoa_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_contato_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_contato_id_seq_tr 

BEFORE INSERT ON pessoa_contato FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pessoa_contato_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_departamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_departamento_id_seq_tr 

BEFORE INSERT ON pessoa_departamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pessoa_departamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_endereco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_endereco_id_seq_tr 

BEFORE INSERT ON pessoa_endereco FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pessoa_endereco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE pessoa_grupo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER pessoa_grupo_id_seq_tr 

BEFORE INSERT ON pessoa_grupo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT pessoa_grupo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE system_departamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER system_departamento_id_seq_tr 

BEFORE INSERT ON system_departamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT system_departamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE system_documentos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER system_documentos_id_seq_tr 

BEFORE INSERT ON system_documentos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT system_documentos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE system_entidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER system_entidade_id_seq_tr 

BEFORE INSERT ON system_entidade FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT system_entidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE system_saldo_empenho_departamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER system_saldo_empenho_departamento_id_seq_tr 

BEFORE INSERT ON system_saldo_empenho_departamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT system_saldo_empenho_departamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_anexo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_anexo_id_seq_tr 

BEFORE INSERT ON tipo_anexo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_anexo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_cliente_id_seq_tr 

BEFORE INSERT ON tipo_cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_conta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_conta_id_seq_tr 

BEFORE INSERT ON tipo_conta FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_conta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_system_documentos_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_system_documentos_id_seq_tr 

BEFORE INSERT ON tipo_system_documentos FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_system_documentos_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 