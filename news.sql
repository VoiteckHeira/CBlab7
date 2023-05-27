-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Maj 2023, 11:41
-- Wersja serwera: 10.4.21-MariaDB
-- Wersja PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `news`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL COMMENT 'name of the message',
  `type` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'type of the message\r\n(private/public)',
  `message` varchar(2000) COLLATE utf8_polish_ci NOT NULL COMMENT 'message text',
  `deleted` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'existing message - 0, deleted - 1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `message`
--

INSERT INTO `message` (`id`, `name`, `type`, `message`, `deleted`) VALUES
(1, 'New Intel technology', 'public', 'Intel has announced a new processor for desktops', 0),
(2, 'Intel shares raising', 'private', 'brokers announce: Intel shares will go up!', 0),
(3, 'New graphic card from NVidia', 'public', 'NVidia has announced a new graphic card for desktops', 0),
(4, 'Airplane crash', 'public', 'A passenger plane has crashed in Europe', 0),
(5, 'Coronavirus', 'public', 'A new version of virus was not and found!', 0),
(6, 'Bitcoin price raises', 'public', 'Price of bitcoin reaches new record.', 0),
(9, 'New Windows announced', 'public', 'A new version of windows was announced. Present buyers of Widows\r\n10 can update the system to the newest version for free.', 0),
(10, 'edek', 'public', '      Hello my World          ', 0),
(26, 'test', 'public', 'john 552d29f9290b9521e6016c2296fa4511 sF5%gR', 0),
(27, 'test', 'public', 'anie dcb710a566c2a24c8bfaf83618e728f7 sdfgh54', 0),
(28, 'test', 'public', 'susie 8c90f286786c7f3b96564e1e88e0ddab j67R', 0),
(29, 'test', 'public', 'susie 8c90f286786c7f3b96564e1e88e0ddab j67R', 0),
(36, 'test&#39;,&#39;public&#39;,(SELECT CONCAT(login,&#39; &#39;, hash,&#39; &#39;, salt) FROM user WHERE 1  LIMIT 1 OFFSET 1),0);#', 'public', 'asa', 0),
(39, 'test', NULL, 'test', 0),
(40, 'hello', 'public', '           hello     ', 0),
(42, 'hello2', 'public', '      hello2          ', 0),
(51, 'test', 'public', 'susie 8c90f286786c7f3b96564e1e88e0ddab j67R', 0),
(52, 'test\',\'public\',(SELECT CONCAT(email,\' \', hash,\' \', salt) FROM user WHERE 1 LIMIT 1),0);# ', 'public', 'hello', 0),
(53, 'test\',\'public\',(SELECT CONCAT(login,\' \', hash,\' \', salt) FROM user WHERE 1  LIMIT 1 OFFSET 1),0);# ', 'public', 'test', 0),
(54, 'test\',\'public\',(SELECT CONCAT(login,\' \', hash,\' \', salt) FROM user WHERE 1  LIMIT 1 OFFSET 1),0);# ', 'public', 'test', 0),
(82, 'hello', 'public', 'hello', 0),
(83, 'test1', 'public', 'test1', 0),
(84, 'test1', 'public', 'test1', 0),
(85, 'test2', 'public', 'test2', 0),
(86, 'test2', 'public', 'test2', 0),
(87, 'test3', 'public', 'test3', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `privilege`
--

CREATE TABLE `privilege` (
  `id` int(11) NOT NULL,
  `id_parent_privilege` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  `asset_url` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `privilege`
--

INSERT INTO `privilege` (`id`, `id_parent_privilege`, `name`, `active`, `asset_url`) VALUES
(1, NULL, 'add message', 1, '/privileges/add_message'),
(2, NULL, 'delete message', 1, '/privileges/delete_message'),
(3, NULL, 'edit message', 1, '/privileges/edit_message'),
(4, NULL, 'dispay message', 1, '/privileges/display_message'),
(5, NULL, 'create role', 1, '/privileges/create_role'),
(6, NULL, 'delete role', 1, '/privileges/delete_role'),
(7, NULL, 'edit role', 1, '/privileges/edit_role'),
(8, NULL, 'display role', 1, '/privileges/display_role'),
(9, NULL, 'create user', 1, '/privileges/create_user'),
(10, NULL, 'delete user', 1, '/privileges/delete_user'),
(11, NULL, 'edit user', 1, '/privileges/edit_user'),
(12, NULL, 'display user', 1, '/privileges/display_user'),
(13, NULL, 'create privilege', 1, '/privileges/create_privilege'),
(14, NULL, 'delete privilege', 1, '/privileges/delete_privilege'),
(15, NULL, 'edit privilege', 1, '/privileges/edit_privilege'),
(16, NULL, 'display privilege', 1, '/privileges/display_privilege'),
(17, NULL, 'nowe', NULL, NULL),
(18, NULL, 'nowe', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role`
--

CREATE TABLE `role` (
  `id` smallint(6) NOT NULL,
  `role_name` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `role`
--

INSERT INTO `role` (`id`, `role_name`, `description`) VALUES
(1, 'Administrator', 'Manages system settings and user accounts'),
(2, 'Moderator', 'Moderates discussions and manages users'),
(3, 'User', 'Standard user role with limited privileges'),
(4, 'New', 'New user role with limited privileges'),
(13, 'nowa2', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `role_privilege`
--

CREATE TABLE `role_privilege` (
  `id` int(11) NOT NULL,
  `id_role` smallint(6) DEFAULT NULL,
  `privilege_id` int(11) DEFAULT NULL,
  `issue_time` date DEFAULT NULL,
  `expire_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `role_privilege`
--

INSERT INTO `role_privilege` (`id`, `id_role`, `privilege_id`, `issue_time`, `expire_time`) VALUES
(1, 1, 1, '2023-05-16', NULL),
(2, 1, 2, '2023-05-16', NULL),
(3, 1, 3, '2023-05-16', NULL),
(4, 1, 4, '2023-05-16', NULL),
(5, 1, 5, '2023-05-16', NULL),
(6, 1, 6, '2023-05-16', NULL),
(7, 1, 7, '2023-05-16', NULL),
(8, 1, 8, '2023-05-16', NULL),
(9, 1, 9, '2023-05-16', NULL),
(10, 1, 10, '2023-05-16', NULL),
(11, 1, 11, '2023-05-16', NULL),
(12, 1, 12, '2023-05-16', NULL),
(13, 1, 13, '2023-05-16', NULL),
(14, 1, 14, '2023-05-16', NULL),
(15, 1, 15, '2023-05-16', NULL),
(16, 1, 16, '2023-05-16', NULL),
(17, 2, 1, '2023-05-16', NULL),
(18, 2, 2, '2023-05-16', NULL),
(19, 2, 3, '2023-05-16', NULL),
(20, 2, 4, '2023-05-16', NULL),
(21, 3, 1, '2023-05-16', NULL),
(22, 3, 3, '2023-05-16', NULL),
(23, 3, 4, '2023-05-16', NULL),
(26, 4, 4, '2023-05-16', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `hash` blob DEFAULT NULL,
  `salt` blob DEFAULT NULL COMMENT 'salt to use in password hashing',
  `sms_code` varchar(6) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'security code sent via sms or e-mail',
  `code_timelife` timestamp NULL DEFAULT NULL COMMENT 'timelife of security code',
  `security_question` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'additional security question used while password recovering',
  `answer` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL COMMENT 'security question answer',
  `lockout_time` timestamp NULL DEFAULT NULL COMMENT 'time to which user account is blocked',
  `session_id` blob DEFAULT NULL COMMENT 'user session identifier',
  `id_status` int(11) NOT NULL COMMENT 'account status',
  `password_form` int(11) NOT NULL DEFAULT 1 COMMENT '1- SHA512, 2-SHA512+salt,3- HMAC',
  `2fa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `login`, `email`, `hash`, `salt`, `sms_code`, `code_timelife`, `security_question`, `answer`, `lockout_time`, `session_id`, `id_status`, `password_form`, `2fa`) VALUES
(1, 'john', 'johny@gmail.com', 0x3535326432396639323930623935323165363031366332323936666134353131, 0x734635256752, '345543', '2022-01-05 13:25:36', 'Your friend\'s name?', 'Peter', NULL, NULL, 2, 1, NULL),
(2, 'susie', 'susie@gmail.com', 0x3863393066323836373836633766336239363536346531653838653064646162, 0x6a363752, '674545', '2022-01-12 13:25:36', 'Where were you on your 2015\'s holiday?', 'Turkey', NULL, NULL, 5, 1, NULL),
(3, 'anie', 'anie@gmail.com', 0x6463623731306135363663326132346338626661663833363138653732386637, 0x73646667683534, NULL, NULL, 'Your favorite color?', 'Navy blue', NULL, NULL, 1, 1, NULL),
(42, 'ada', 'ada@ada.pl', 0x8cf1ffb451ede34b55487775ee8d98778c5fa946c1878e15987e38bbec0647617cf04afe9d46b5e48520cd5b9d2f425e68f93ef106491c37413c3e04a607f2d1d143cc65544eb690750fdf91de642ffc692e56c9fca5dc9a64c57be22fc6fdbabfb979e1d69b111173ae9e7ab582eae5fb0eb11c04d1a445610f4c47625aa33f373f3cbd4eed1f5815919185236ea59b, 0x1d8172a86d1927f284c20409faeab1d6, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(43, 'aga', 'aga@aga.pl', 0x40b84a642ce9a330cc41c931d9dcd080976b75c5c849786b08cb6752ecef10334e8adb1004d06e2c377864eb92e759c400d82599b258ad99a9d5af5522eec7340a9babd9fb5528046d1065f090f1ac2d9c3279ac063db86a85ae9de0014c9c21a0ad69887e9b625123666038ad425474349b1608e78527a40f1bf53d876a6597f97854611e60ccbfd8a5b5c6348fe71f, 0xdd8e3ae93fb6dbaeee9ef79fbde5f27f, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(45, 'qwer', 'qwer@qwer.pl', 0x2b762d4fffd6d61ce90ccee37169ac411fb91c08904b96935de99ca2fe92ab45f3e2d904a3f525953e04d268b1ea51765a51ad40b0774d202e2f304ab19479ec3b3b63af50186834babe2e186320dd2f2d555d2bc81248c5e0d1522281295d58765bc1aa8bc065a59bf998f7ed348316e9c80589065194a5f7f2d7a428acbc9e2c885327fe839350fb34307b7afdd71c, 0x3a47102a0d701f5582ee402e6d5dbc41, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(46, 'ewa', 'ewa@ewa.pl', 0x51319ee53940241b1f5141993fed68456ed0eb9e735221c210f7ca099f2fe00bceecbb5a70cce6674655e714f52f3adbc982430fc392c1db28b2d919269f8ef04b37a5790a4d31639dba626c97bc244f4ed883399c2a306e75ef48b55ed8d4397e1ef12cc3cab6e75c0dbadfbbf49fcd078c631c74dfc7b7ec6fea37a171c869703bf00ba6b5e46d9bd95ef7b62f33ae, 0xe478b6852a727a5734c01072076d2fcd, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(47, 'ela', 'ela@ela.pl', 0xa84de20b172a58b6f1b046220b0635644200a7b82d2fba3838d56ff37185622561903c6597773be42119e90e3a20947b914484ddeaedf55a8ede3d311f925022b4b1b2e6642ac75c1fd1747726ed914503bfbd8713709eae0825bfa73881732c04ff13f6cc139198ec85594647eab22860e8c4deb2ee367a342271eb2876441c43b48cd9b66648bc54a96a0dd7b58a1b, 0xd6da7fe46ec8deac24f2703a242dd28e, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(60, 'aaaa', 'aaaa@aaaa.pl', 0xfbd3ce13325a44fc4784b6e47bb51c537775caaef270ccd230d933341010413d397a456207d29e1a50ce6795467335da186cf7f3b81450210dda4f1305ef5de2b67d83554b7ebe43df475fc16aabbc673284c62feb6d4338c6ea9004cc6f56578bfa25690a82c8b421b35d48d50a429a20c9223564aaf60d0753fe414cd7115304ee21bc49e8d272dff06b12f05fd3b5, 0x8caeaaf773e455950c0d3329215aea44, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(61, '111', '111@111.pl', 0xd8b56666a816b5fc709e33f2bc965f33cec2da256af9943f16f4ede5809d63c2138e6a6862ae6f69c8681298868bd80abf77af152b5db3f865c544aebed4dbb40b662d5b6d4688cdb3bb2c9480ff99e32df87e5840e89605c6692acdb233a76ea9af6d1b3e3d41a695a01baabb05fb7718bc876893ddbbaa04e42dfece4de6f352591e23d1866407d3bbbc8a7b5c9e69, 0x9b01b86e70f8640a3299f6c36a89fad4, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(62, 'ddd', 'ddd@ddd.pl', 0x4d206ff70d9c06fb20fca4ba5628e68a0a68b1e2d516c1f7451e2067a8de7f9b43bbda1b4581505283956b732cb5b504a81a9307ca2da72ead67d225a4ad7e10c1760a86c360eae862e0e2f0fda90a9c398d93d72889fbe6119f4281097ddd0a9b6c6844e5d03b48da53621609b1acb20951b342fe66bb745feb50d95f19786c1545059aad5ec89c1a04cf6139f68c6f, 0x81cac05e2b690732d242b503009a57af, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(63, 'sss', 'sss@sss.pl', 0x9f1504780bcef1b593458923b20118e001d579717bbc92a89f41d6a11635d5123987fb77251eb612f29d9e216b90003898513248564430618b2c7a50f53d9f81a2959c24a783904f7a9612dbeef8f62ab76afb68dd3ae4ba59d4c4aa72d58e0dc2fb0d494c637721192d5c4c3588247e4e94773db955511775dc186cb0ed5299c679b7608f94ab72d8a8381993d45b3b, 0x25472f59b02bd0480cea5644e7412199, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(67, 'www', 'www@www.pl', 0x4dfbbf1947fd1875dc434447406c6283b40c5fbe061fad3379884a038fafafe6b5a07347f2e7d11f18fb209dc97109cae1934fb05ea5f1ce4248594738aedf94f680c63788bf4335cc3dfe19ec18d5b8c25d0d4974869c990cb884138c46a20f06d917c2ac910b51124d08aa664aaa6beeba0ca7cc8e1c6800336948638a3d1ffedcf19836348285577a27cae7b2305e, 0x9dd39b9a992389bb6070f503a288d39a, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(68, 'fff', 'fff@fff.pl', 0x626aa31497287d9b005eab76f439d8a68a668b5976b13f7199df2bc2155c52b2495bb5272e0bfa82808652dd31585c5e4eac687f464e6ccab79722df190ba6b59586cb0a9173f0f34a8557515c6540c8c7e02b7fc3e648349969b92c606069387f21ec84cce1bad7c4a0fadad7047680d67d0145b4f6dcc627d0b3a70adc2f5224c342cb628d036f6f867a547e193d1f, 0x2430995b55fcaaf6bd4bf311d1e86be2, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(69, 'essa', 'essa@essa.pl', 0xf7ffe936b918b2b2b472dc820114cbbbcab06b54b75bab2e57c24207d76bad99f409fc9cc74e1d25ad2cb74d9db0c4a3f87dda8adf3cdeb0f9973b1ab640ba6099c4fa5d74a0a65219e7813e82e75813d55f40b69074c3319ba3ea99f1f5be2a5209442abf83f76c380f367b5d3eb823c92e36caabbbc5b1497ab649e0445a59340a9b905927199b1f47c5904150bbc0, 0x5fb63d1644f46fa7adb5f0cbcc797213, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0),
(70, 'hhh', 'hhh@hhh.pl', 0x010f71aa693703a2aabe8a43c110450761acfe5f0b3cd6e336f1d39c59304c31019665c9b6855b23d84bb522d62329157b1c28823a33675e579c83555ad64b9e4ca09ac15ff8b69e3d1d584ce5a14c73f827f5863cc19291458b91a17591796885037bbcbcf725f58970c6cbf0eb2b7e86f131e874b2111f62bdd3f825af365ec2b2ef2a410d271cfe405006662bec02, 0x45baf20e01c5580c09989bac0c4ea9e9, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_privilege`
--

CREATE TABLE `user_privilege` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_privilege` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user_privilege`
--

INSERT INTO `user_privilege` (`id`, `id_user`, `id_privilege`) VALUES
(1, 42, 1),
(2, 42, 2),
(3, 42, 3),
(4, 42, 4),
(5, 42, 5),
(6, 42, 6),
(8, 42, 8),
(9, 42, 9),
(10, 42, 10),
(11, 42, 11),
(12, 42, 12),
(13, 42, 13),
(14, 42, 14),
(15, 42, 15),
(16, 42, 16),
(17, 69, 1),
(18, 69, 3),
(19, 69, 4),
(20, 70, 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_role` smallint(6) DEFAULT NULL,
  `issue_time` date DEFAULT NULL,
  `expire_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user_role`
--

INSERT INTO `user_role` (`id`, `id_user`, `id_role`, `issue_time`, `expire_time`) VALUES
(1, 42, 1, '2023-05-16', NULL),
(2, 45, NULL, '2023-05-16', NULL),
(3, 46, NULL, '2023-05-16', NULL),
(4, 47, NULL, '2023-05-16', NULL),
(14, 68, 4, '2023-05-16', NULL),
(15, 69, 3, '2023-05-16', NULL),
(16, 70, 4, '2023-05-17', NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `privilege`
--
ALTER TABLE `privilege`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_privilege_privilege_id` (`id_parent_privilege`);

--
-- Indeksy dla tabeli `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `role_privilege`
--
ALTER TABLE `role_privilege`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_role_privilege_privilege_id` (`privilege_id`),
  ADD KEY `fk_role_privilege_role_id` (`id_role`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FKuser674283` (`id_status`);

--
-- Indeksy dla tabeli `user_privilege`
--
ALTER TABLE `user_privilege`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_privilege_user_id` (`id_user`),
  ADD KEY `fk_user_privilege_privilege_id` (`id_privilege`);

--
-- Indeksy dla tabeli `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_role_user_id` (`id_user`),
  ADD KEY `fk_user_role_role_id` (`id_role`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT dla tabeli `privilege`
--
ALTER TABLE `privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT dla tabeli `role`
--
ALTER TABLE `role`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `role_privilege`
--
ALTER TABLE `role_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT dla tabeli `user_privilege`
--
ALTER TABLE `user_privilege`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT dla tabeli `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `privilege`
--
ALTER TABLE `privilege`
  ADD CONSTRAINT `fk_privilege_privilege_id` FOREIGN KEY (`id_parent_privilege`) REFERENCES `privilege` (`id`);

--
-- Ograniczenia dla tabeli `role_privilege`
--
ALTER TABLE `role_privilege`
  ADD CONSTRAINT `fk_role_privilege_privilege_id` FOREIGN KEY (`privilege_id`) REFERENCES `privilege` (`id`),
  ADD CONSTRAINT `fk_role_privilege_role_id` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);

--
-- Ograniczenia dla tabeli `user_privilege`
--
ALTER TABLE `user_privilege`
  ADD CONSTRAINT `fk_user_privilege_privilege_id` FOREIGN KEY (`id_privilege`) REFERENCES `privilege` (`id`),
  ADD CONSTRAINT `fk_user_privilege_user_id` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);

--
-- Ograniczenia dla tabeli `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `fk_user_role_role_id` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `fk_user_role_user_id` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
