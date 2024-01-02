drop database telegramcovid;
create database telegramcovid;
use telegramcovid;
create table nazionale(
    id int primary key AUTO_INCREMENT,
data date,

ricoverati int,
terapia int,
totale_osp int,
isolamento_dom int,
totale_pos int,
dimessi int,
    deceduti int,
    sospetti int,
    screening int,
    casi_testati int
);
create table regione(
    id int primary key AUTO_INCREMENT,
data date,
    regione varchar(30),

    dimessi int,
    deceduti int,
    casi_testati int

    
);
create table provincia(
    id int primary key AUTO_INCREMENT,
data date,
 regione varchar(40),
    provincia varchar(40),
    nuovi_casi int
);