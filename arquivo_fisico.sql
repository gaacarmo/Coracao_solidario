DROP DATABASE IF EXISTS brecho;
CREATE DATABASE brecho;
USE brecho;

DROP TABLE IF EXISTS Cliente;
DROP TABLE IF EXISTS Cadastro_produto;
DROP TABLE IF EXISTS Produto;
DROP TABLE IF EXISTS Entrega_produto;
DROP TABLE IF EXISTS Roupa;	
DROP TABLE IF EXISTS Calcado;
DROP TABLE IF EXISTS Imagem;
DROP TABLE IF EXISTS Administrador_site;
DROP TABLE IF EXISTS Usuario_cadastrado;

CREATE TABLE Usuario_geral(
	CPF  CHAR(11) PRIMARY KEY NOT NULL UNIQUE,
    Nome_completo VARCHAR(100) NOT NULL,
    Data_nascimento DATE NOT NULL,
    Telefone VARCHAR(11) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE Administrador_site(
	Usuario_admin VARCHAR(20) PRIMARY KEY NOT NULL UNIQUE,
    Senha_admin VARCHAR(15) NOT NULL UNIQUE
);

CREATE TABLE Cliente (
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	Usuario_cliente VARCHAR(20) NOT NULL UNIQUE,
    Senha_cliente VARCHAR(15) NOT NULL UNIQUE,
    Rua VARCHAR(50),
    Bairro VARCHAR(19),
    Numero INT,
    Tipo_cliente VARCHAR(9)
);

CREATE TABLE Produto (
    ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(50) NOT NULL,
    Categoria VARCHAR(7) NOT NULL,
    Publico_alvo VARCHAR(9) NOT NULL,
    Data_postagem DATETIME DEFAULT CURRENT_TIMESTAMP,
    Condicao VARCHAR(9) NOT NULL,
    Descricao VARCHAR(200),
    Usuario_admin VARCHAR(20),
    FOREIGN KEY (Usuario_admin) REFERENCES Administrador_site(Usuario_admin)
);

CREATE TABLE Cadastro_produto (
	ID_doador INT NOT NULL,
    ID_produto INT NOT NULL,
    FOREIGN KEY(ID_doador) REFERENCES Doador(ID),
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Entrega_produto(
	ID_donatario INT NOT NULL,
    ID_produto INT NOT NULL,
    Status VARCHAR(12) NOT NULL,
    Data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, 
    FOREIGN KEY(ID_donatario) REFERENCES Donatario(ID),
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Roupa(
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Tamanho_roupa VARCHAR(3) NOT NULL,
    Cor_roupa VARCHAR(19) NOT NULL,
    ID_produto INT NOT NULL,
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Calcado (
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Tamanho_calcado VARCHAR(3) NOT NULL,
    Cor_calcado VARCHAR(19) NOT NULL,
    ID_produto INT NOT NULL,
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Imagem (
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Caminho_imagem VARCHAR(100) NOT NULL,
    ID_produto INT,
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);
