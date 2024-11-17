DROP DATABASE IF EXISTS brecho;
CREATE DATABASE brecho;
USE brecho;

DROP TABLE IF EXISTS Cliente;
DROP TABLE IF EXISTS Doador;
DROP TABLE IF EXISTS Cadastro;
DROP TABLE IF EXISTS Produto;
DROP TABLE IF EXISTS Enetrega;
DROP TABLE IF EXISTS Roupa;	
DROP TABLE IF EXISTS Calcado;
DROP TABLE IF EXISTS Imagem;
DROP TABLE IF EXISTS Donatario;
DROP TABLE IF EXISTS Administrador_site;
DROP TABLE IF EXISTS Usuario_cadastrado;

CREATE TABLE Usuario_geral(
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	CPF  CHAR(11) NOT NULL UNIQUE,
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
    Logradouro VARCHAR(50),
    Bairro VARCHAR(19),
    Numero INT,
    Tipo_cliente VARCHAR(9),
    ID_usuario_geral INT,
    FOREIGN KEY (ID_usuario_geral) REFERENCES Usuario_geral(ID)
);

CREATE TABLE Produto (
    ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Nome VARCHAR(50) NOT NULL,
    Categoria VARCHAR(7) NOT NULL,
    Publico_alvo VARCHAR(9) NOT NULL,
    Data_postagem DATETIME DEFAULT CURRENT_TIMESTAMP,
    Condicao VARCHAR(9) NOT NULL,
    Descricao VARCHAR(300),
    Usuario_admin VARCHAR(20),
    FOREIGN KEY (Usuario_admin) REFERENCES Administrador_site(Usuario_admin)
);

CREATE TABLE Cadastro_produto (
	ID_cliente INT,
    ID_produto INT,
    FOREIGN KEY(ID_cliente) REFERENCES Cliente(ID),
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Entrega(
	ID_cliente INT NOT NULL,
    ID_produto INT NOT NULL,
    Status_pedido VARCHAR(12) NOT NULL,
    Data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, 
    FOREIGN KEY(ID_cliente) REFERENCES Cliente(ID),
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Roupa(
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Tamanho_roupa VARCHAR(5) NOT NULL,
    Cor_roupa VARCHAR(19) NOT NULL,
    ID_produto INT NOT NULL,
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Calcado (
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Tamanho_calcado VARCHAR(5) NOT NULL,
    Cor_calcado VARCHAR(19) NOT NULL,
    ID_produto INT NOT NULL,
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Imagem (
	ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Caminho_imagem VARCHAR(255) NOT NULL,
    ID_produto INT,
    FOREIGN KEY(ID_produto) REFERENCES Produto(ID)
);

CREATE TABLE Feedback (
    ID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Nota INT NOT NULL,
    Comentario VARCHAR(500) NOT NULL,
    ID_cliente INT,
    FOREIGN KEY (ID_cliente) REFERENCES Cliente(ID)
)
