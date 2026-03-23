INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (1,1,'Vendas de mercadorias'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (2,1,'Vendas de produtos'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (3,1,'Venda de insumos'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (4,1,'Serviços de manutenção'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (5,1,'Receitas financeiras'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (6,2,'Compras de matérias primas'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (7,2,'Compras de insumos'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (8,2,'Pagamento de salários'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (9,2,'Investimentos em imobilizado'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (10,2,'Despesas administrativas'); 

INSERT INTO categoria (id,tipo_conta_id,nome) VALUES (11,2,'Despesas comerciais'); 

INSERT INTO categoria_cliente (id,nome) VALUES (1,'Supermercado'); 

INSERT INTO categoria_cliente (id,nome) VALUES (2,'Posto de gasolina'); 

INSERT INTO categoria_cliente (id,nome) VALUES (3,'Igreja'); 

INSERT INTO categoria_cliente (id,nome) VALUES (4,'Escola'); 

INSERT INTO categoria_cliente (id,nome) VALUES (5,'Consumidor final'); 

INSERT INTO categoria_cliente (id,nome) VALUES (6,'Fornecedor'); 

INSERT INTO categoria_cliente (id,nome) VALUES (7,'Vendedor'); 

INSERT INTO cidade (id,estado_id,nome,codigo_ibge) VALUES (1,1,'Lajeado','123123'); 

INSERT INTO conta (id,pessoa_id,tipo_conta_id,categoria_id,forma_pagamento_id,pedido_venda_id,dt_vencimento,dt_emissao,dt_pagamento,valor,parcela,obs,mes_vencimento,ano_vencimento,ano_mes_vencimento,mes_emissao,ano_emissao,ano_mes_emissao,mes_pagamento,ano_pagamento,ano_mes_pagamento,created_at,updated_at,deleted_at,system_unit_id,system_departamento_id,system_entidade_id,natureza_conta_id) VALUES (1,1,2,8,1,null,'2022-07-20','2022-07-20',null,150,1,'',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null); 

INSERT INTO conta (id,pessoa_id,tipo_conta_id,categoria_id,forma_pagamento_id,pedido_venda_id,dt_vencimento,dt_emissao,dt_pagamento,valor,parcela,obs,mes_vencimento,ano_vencimento,ano_mes_vencimento,mes_emissao,ano_emissao,ano_mes_emissao,mes_pagamento,ano_pagamento,ano_mes_pagamento,created_at,updated_at,deleted_at,system_unit_id,system_departamento_id,system_entidade_id,natureza_conta_id) VALUES (2,1,2,6,2,null,'2022-07-21','2022-07-20',null,250,1,'',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null); 

INSERT INTO conta (id,pessoa_id,tipo_conta_id,categoria_id,forma_pagamento_id,pedido_venda_id,dt_vencimento,dt_emissao,dt_pagamento,valor,parcela,obs,mes_vencimento,ano_vencimento,ano_mes_vencimento,mes_emissao,ano_emissao,ano_mes_emissao,mes_pagamento,ano_pagamento,ano_mes_pagamento,created_at,updated_at,deleted_at,system_unit_id,system_departamento_id,system_entidade_id,natureza_conta_id) VALUES (3,1,2,11,3,null,'2022-07-30','2022-07-01',null,300,1,'',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null); 

INSERT INTO conta (id,pessoa_id,tipo_conta_id,categoria_id,forma_pagamento_id,pedido_venda_id,dt_vencimento,dt_emissao,dt_pagamento,valor,parcela,obs,mes_vencimento,ano_vencimento,ano_mes_vencimento,mes_emissao,ano_emissao,ano_mes_emissao,mes_pagamento,ano_pagamento,ano_mes_pagamento,created_at,updated_at,deleted_at,system_unit_id,system_departamento_id,system_entidade_id,natureza_conta_id) VALUES (4,1,2,10,1,null,'2022-07-30','2022-07-01',null,400,1,'',null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null); 

INSERT INTO estado (id,nome,sigla,codigo_ibge) VALUES (1,'Rio Grande do Sul','RS',''); 

INSERT INTO forma_pagamento (id,nome) VALUES (1,'Dinheiro'); 

INSERT INTO forma_pagamento (id,nome) VALUES (2,'Boleto'); 

INSERT INTO forma_pagamento (id,nome) VALUES (3,'Cartão'); 

INSERT INTO grupo_pessoa (id,nome) VALUES (1,'Funcionário'); 

INSERT INTO grupo_pessoa (id,nome) VALUES (2,'Vendedor'); 

INSERT INTO grupo_pessoa (id,nome) VALUES (3,'Cliente'); 

INSERT INTO grupo_pessoa (id,nome) VALUES (4,'Fornecedor'); 

INSERT INTO grupo_pessoa (id,nome) VALUES (5,'Distribuidor'); 

INSERT INTO grupo_pessoa (id,nome) VALUES (6,'Revendedor'); 

INSERT INTO grupo_pessoa (id,nome) VALUES (7,'Transportadora'); 

INSERT INTO natureza_conta (id,created_at,updated_at,deleted_at,descricao) VALUES (1,null,null,null,'Orçamentária'); 

INSERT INTO natureza_conta (id,created_at,updated_at,deleted_at,descricao) VALUES (2,null,null,null,'Extra-Orçamentária'); 

INSERT INTO pessoa (id,tipo_cliente_id,categoria_cliente_id,system_user_id,nome,documento,obs,fone,email,created_at,updated_at,deleted_at,login,senha) VALUES (1,1,5,null,'Cliente 01','111.111.111-11','','(51) 9 9813-1234','cliente@cliente.com.br',null,null,null,null,null); 

INSERT INTO pessoa (id,tipo_cliente_id,categoria_cliente_id,system_user_id,nome,documento,obs,fone,email,created_at,updated_at,deleted_at,login,senha) VALUES (2,1,7,1,'Vendedor 01','1111111','','','',null,null,null,null,null); 

INSERT INTO pessoa (id,tipo_cliente_id,categoria_cliente_id,system_user_id,nome,documento,obs,fone,email,created_at,updated_at,deleted_at,login,senha) VALUES (3,2,6,null,'Fornecedor 01','1111111','','','',null,null,null,null,null); 

INSERT INTO pessoa (id,tipo_cliente_id,categoria_cliente_id,system_user_id,nome,documento,obs,fone,email,created_at,updated_at,deleted_at,login,senha) VALUES (4,2,null,null,'Transportadora','111111111','','','',null,null,null,null,null); 

INSERT INTO pessoa_grupo (id,pessoa_id,grupo_pessoa_id) VALUES (1,1,3); 

INSERT INTO pessoa_grupo (id,pessoa_id,grupo_pessoa_id) VALUES (2,2,2); 

INSERT INTO pessoa_grupo (id,pessoa_id,grupo_pessoa_id) VALUES (3,3,4); 

INSERT INTO pessoa_grupo (id,pessoa_id,grupo_pessoa_id) VALUES (4,4,7); 

INSERT INTO system_group (id,name,uuid) VALUES (1,'Admin',null); 

INSERT INTO system_group (id,name,uuid) VALUES (2,'Standard',null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (1,1,1,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (2,1,2,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (3,1,3,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (4,1,4,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (5,1,5,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (6,1,6,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (7,1,8,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (8,1,9,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (9,1,11,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (10,1,14,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (11,1,15,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (12,2,10,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (13,2,12,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (14,2,13,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (15,2,16,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (16,2,17,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (17,2,18,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (18,2,19,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (19,2,20,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (20,1,21,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (21,2,22,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (22,2,23,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (23,2,24,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (24,2,25,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (25,1,26,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (26,1,27,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (27,1,28,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (28,1,29,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (29,2,30,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (30,1,31,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (31,1,32,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (32,1,33,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (33,1,34,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (34,1,35,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (35,1,36,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (36,1,37,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (37,1,38,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (38,1,39,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (39,1,40,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (40,1,41,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (41,1,42,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (42,1,43,null); 

INSERT INTO system_group_program (id,system_group_id,system_program_id,actions) VALUES (43,1,44,null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (1,'System Group Form','SystemGroupForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (2,'System Group List','SystemGroupList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (3,'System Program Form','SystemProgramForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (4,'System Program List','SystemProgramList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (5,'System User Form','SystemUserForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (6,'System User List','SystemUserList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (7,'Common Page','CommonPage',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (8,'System PHP Info','SystemPHPInfoView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (9,'System ChangeLog View','SystemChangeLogView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (10,'Welcome View','WelcomeView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (11,'System Sql Log','SystemSqlLogList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (12,'System Profile View','SystemProfileView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (13,'System Profile Form','SystemProfileForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (14,'System SQL Panel','SystemSQLPanel',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (15,'System Access Log','SystemAccessLogList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (16,'System Message Form','SystemMessageForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (17,'System Message List','SystemMessageList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (18,'System Message Form View','SystemMessageFormView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (19,'System Notification List','SystemNotificationList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (20,'System Notification Form View','SystemNotificationFormView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (21,'System Document Category List','SystemDocumentCategoryFormList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (22,'System Document Form','SystemDocumentForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (23,'System Document Upload Form','SystemDocumentUploadForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (24,'System Document List','SystemDocumentList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (25,'System Shared Document List','SystemSharedDocumentList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (26,'System Unit Form','SystemUnitForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (27,'System Unit List','SystemUnitList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (28,'System Access stats','SystemAccessLogStats',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (29,'System Preference form','SystemPreferenceForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (30,'System Support form','SystemSupportForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (31,'System PHP Error','SystemPHPErrorLogView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (32,'System Database Browser','SystemDatabaseExplorer',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (33,'System Table List','SystemTableList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (34,'System Data Browser','SystemDataBrowser',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (35,'System Menu Editor','SystemMenuEditor',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (36,'System Request Log','SystemRequestLogList',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (37,'System Request Log View','SystemRequestLogView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (38,'System Administration Dashboard','SystemAdministrationDashboard',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (39,'System Log Dashboard','SystemLogDashboard',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (40,'System Session dump','SystemSessionDumpView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (41,'Files diff','SystemFilesDiff',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (42,'System Information','SystemInformationView',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (43,'System Entidade Form','SystemEntidadeForm',null); 

INSERT INTO system_program (id,name,controller,actions) VALUES (44,'System Entidade List','SystemEntidadeList',null); 

INSERT INTO system_unit (id,name,connection_name,email,cep,rua,numero,bairro,complemento,cnpj,telefone01,telefone02,logo,longitude,latitude,system_entidade_id,cidade_id) VALUES (1,'Matriz','matriz',null,null,null,null,null,null,null,null,null,null,null,null,null,null); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (1,1,1); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (2,2,2); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (3,1,2); 

INSERT INTO system_user_program (id,system_user_id,system_program_id) VALUES (1,2,7); 

INSERT INTO system_users (id,name,login,password,email,frontpage_id,system_unit_id,active,accepted_term_policy_at,accepted_term_policy,two_factor_enabled,two_factor_type,two_factor_secret) VALUES (1,'Administrator','admin','21232f297a57a5a743894a0e4a801fc3','admin@admin.net',10,null,'Y','','',null,null,null); 

INSERT INTO system_users (id,name,login,password,email,frontpage_id,system_unit_id,active,accepted_term_policy_at,accepted_term_policy,two_factor_enabled,two_factor_type,two_factor_secret) VALUES (2,'User','user','ee11cbb19052e40b07aac0ca060c23ee','user@user.net',7,null,'Y','','',null,null,null); 

INSERT INTO system_user_unit (id,system_user_id,system_unit_id) VALUES (1,1,1); 

INSERT INTO tipo_anexo (id,nome) VALUES (1,'Recibo'); 

INSERT INTO tipo_anexo (id,nome) VALUES (2,'Boleto'); 

INSERT INTO tipo_cliente (id,nome,sigla) VALUES (1,'Física','PF'); 

INSERT INTO tipo_cliente (id,nome,sigla) VALUES (2,'Jurídica','PJ'); 

INSERT INTO tipo_conta (id,nome) VALUES (1,'Receber'); 

INSERT INTO tipo_conta (id,nome) VALUES (2,'Pagar'); 

INSERT INTO tipo_system_documentos (id,created_at,updated_at,deleted_at,descricao) VALUES (1,null,null,null,'Balanços Anuais'); 

INSERT INTO tipo_system_documentos (id,created_at,updated_at,deleted_at,descricao) VALUES (2,null,null,null,'Efetuados Judicialmente'); 

INSERT INTO tipo_system_documentos (id,created_at,updated_at,deleted_at,descricao) VALUES (3,null,null,null,'Tabelas de Remuneração'); 

INSERT INTO tipo_system_documentos (id,created_at,updated_at,deleted_at,descricao) VALUES (4,null,null,null,'Contratos de Gestão'); 
