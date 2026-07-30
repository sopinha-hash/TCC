CREATE DATABASE alerta_faltas; 
USE alerta_faltas; 

CREATE TABLE relatorio_temp(
 num_linha int auto_increment primary key, 
 id_aluno int,
 aluno varchar(200),
 turma varchar(20),
 total_faltas int,
 status varchar(50)
);

CREATE TABLE tab_config(
id_configuracao int auto_increment primary key, 
limite int not null
);

INSERT INTO tab_config(limite) values(100);
