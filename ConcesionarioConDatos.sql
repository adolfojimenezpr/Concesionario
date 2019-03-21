SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `concesionario` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `concesionario`;

CREATE TABLE `mensaje` (
  `Id` int(11) NOT NULL,
  `Asunto` varchar(260) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `Leido` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `mensaje` (`Id`, `Asunto`, `Descripcion`, `Leido`) VALUES
(1, 'MsjA', 'Mensaje A', 0),
(2, 'MsjB', 'Mensaje B', 0),
(3, 'MsjC', 'Mensaje C', 0);

CREATE TABLE `pedido` (
  `Id` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Confirmado` tinyint(1) NOT NULL DEFAULT '0',
  `Fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `pedido` (`Id`, `Id_Usuario`, `Confirmado`, `Fecha`) VALUES
(1, 3, 0, '2018-04-10'),
(2, 3, 0, '2018-04-16'),
(3, 5, 0, '2018-04-21'),
(4, 5, 0, '2017-05-04');

CREATE TABLE `producto` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Precio` float NOT NULL,
  `Id_Proveedor` int(11) NOT NULL,
  `DadoDeBaja` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `producto` (`Id`, `Nombre`, `Precio`, `Id_Proveedor`, `DadoDeBaja`) VALUES
(1, 'ProdA1', 15, 4, 0),
(2, 'ProdA2', 30, 4, 0),
(3, 'ProdB1', 25, 2, 0),
(4, 'ProdB2', 20, 2, 0);

CREATE TABLE `producto_pedido` (
  `Id_Producto` int(11) NOT NULL,
  `Id_Pedido` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL DEFAULT '1',
  `Confirmado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `producto_pedido` (`Id_Producto`, `Id_Pedido`, `Cantidad`, `Confirmado`) VALUES
(1, 1, 1, 0),
(1, 3, 1, 0),
(2, 1, 2, 0),
(2, 4, 2, 0),
(3, 2, 3, 0),
(3, 3, 2, 0),
(4, 2, 2, 0),
(4, 4, 3, 0);

CREATE TABLE `usuario` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(200) COLLATE utf8_spanish_ci DEFAULT NULL,
  `Login` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `Contrasena` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `Rol` enum('Proveedor','Concesionario','Administrador') COLLATE utf8_spanish_ci NOT NULL,
  `Logueado` tinyint(1) NOT NULL DEFAULT '0',
  `Bloqueado` tinyint(1) NOT NULL DEFAULT '0',
  `DadoDeBaja` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuario` (`Id`, `Nombre`, `Login`, `Contrasena`, `Rol`, `Logueado`, `Bloqueado`, `DadoDeBaja`) VALUES
(1, NULL, 'admin', 'admin', 'Administrador', 0, 0, 0),
(2, 'ProveB', 'proveb', 'proveb', 'Proveedor', 0, 0, 0),
(3, 'ConceA', 'concea', 'concea', 'Concesionario', 0, 0, 0),
(4, 'ProveA', 'provea', 'provea', 'Proveedor', 0, 0, 0),
(5, 'ConceB', 'conceb', 'conceb', 'Concesionario', 0, 0, 0);


ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `pedido`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_Usuario` (`Id_Usuario`);

ALTER TABLE `producto`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Id_Proveedor` (`Id_Proveedor`);

ALTER TABLE `producto_pedido`
  ADD PRIMARY KEY (`Id_Producto`,`Id_Pedido`) USING BTREE,
  ADD KEY `Id_Producto` (`Id_Producto`),
  ADD KEY `Id_Pedido` (`Id_Pedido`);

ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id`);


ALTER TABLE `mensaje`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pedido`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `producto`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuario`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `pedido`
  ADD CONSTRAINT `Pedido_ibfk_1` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuario` (`Id`);

ALTER TABLE `producto`
  ADD CONSTRAINT `Producto_ibfk_1` FOREIGN KEY (`Id_Proveedor`) REFERENCES `usuario` (`Id`);

ALTER TABLE `producto_pedido`
  ADD CONSTRAINT `Producto_Pedido_ibfk_1` FOREIGN KEY (`Id_Pedido`) REFERENCES `pedido` (`Id`),
  ADD CONSTRAINT `Producto_Pedido_ibfk_2` FOREIGN KEY (`Id_Producto`) REFERENCES `producto` (`Id`);
COMMIT;
