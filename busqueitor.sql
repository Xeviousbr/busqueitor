CREATE TABLE `busqcomunicadores` (
  `idComunicador` int(11) NOT NULL,
  `Nome` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `busqcomunicadores` (`idComunicador`, `Nome`, `created_at`, `updated_at`) VALUES
(1, 'Whasapp', '2020-03-23 03:22:20', NULL),
(2, 'Telegram', '2020-03-23 03:22:32', NULL);

ALTER TABLE `busqcomunicadores`
  ADD PRIMARY KEY (`idComunicador`);

ALTER TABLE `busqcomunicadores`
  MODIFY `idComunicador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

CREATE TABLE `busqcategorias` (
  `idcategoria` int(11) NOT NULL,
  `idpai` int(11) NOT NULL,
  `nome` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `busqcategorias`
  ADD PRIMARY KEY (`idcategoria`);

ALTER TABLE `busqcategorias`
  MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `busqgrupos` (
  `idgrupo` int(11) NOT NULL,
  `idComunicador` int(11) NOT NULL,
  `idcategoria` int(11) NOT NULL,
  `nome` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `endereco` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `qtdusers` int(11) NOT NULL,
  `atualizacao` date NOT NULL,
  `foneadm` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `busqgrupos`
  ADD PRIMARY KEY (`idgrupo`),
  ADD KEY `FK_grupos` (`idComunicador`);

ALTER TABLE `busqgrupos`
  MODIFY `idgrupo` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `busqgrupos`
  ADD CONSTRAINT `FK_grupos` FOREIGN KEY (`idComunicador`) REFERENCES `busqcomunicadores` (`idComunicador`);