CREATE DATABASE alerta_faltas;
USE alerta_faltas; 

CREATE TABLE relatorio_temp(
 num_linha int auto_increment primary key,
 aluno varchar(200),
 id_aluno int,
 total_faltas int,
 status varchar(20)
);
