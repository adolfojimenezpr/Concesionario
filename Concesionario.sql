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

CREATE TABLE `pedido` (
  `Id` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Confirmado` tinyint(1) NOT NULL DEFAULT '0',
  `Fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `producto` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(200) COLLATE utf8_spanish_ci NOT NULL,
  `Precio` float NOT NULL,
  `Id_Proveedor` int(11) NOT NULL,
  `DadoDeBaja` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

CREATE TABLE `producto_pedido` (
  `Id_Producto` int(11) NOT NULL,
  `Id_Pedido` int(11) NOT NULL,
  `Cantidad` int(11) NOT NULL DEFAULT '1',
  `Confirmado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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
