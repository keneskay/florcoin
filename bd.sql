-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 30 2019 г., 14:08
-- Версия сервера: 5.7.26-0ubuntu0.16.04.1
-- Версия PHP: 7.1.30-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bot`
--

-- --------------------------------------------------------

--
-- Структура таблицы `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `upgrade_id` int(11) NOT NULL,
  `price` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `upgrades`
--

CREATE TABLE `upgrades` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(111) NOT NULL,
  `speed` varchar(333) NOT NULL DEFAULT '0.001',
  `type` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `upgrades`
--

INSERT INTO `upgrades` (`id`, `name`, `price`, `speed`, `type`) VALUES
(1, 'Курсор', '0.055', '0.001', 1),
(2, 'Обычная мышка', '0.050', '0.001', 0),
(3, 'Видеокарта', '0.350', '0.003', 1),
(4, 'Стойка видеокарт', '0.512', '0.008', 1),
(5, 'Супер компьютер', '0.752', '0.010', 1),
(6, 'Сервер ВКонтакте', '0.851', '0.030', 1),
(7, 'Квантовый компьютер', '1.005', '0.080', 1),
(8, 'Видеокарта Big Bang', '15.250', '0.100', 1),
(9, 'Мышка с автокликером', '0.155', '0.003', 0),
(10, 'Мышка с макросами', '0.315', '0.008', 0),
(11, 'Мышка программиста', '0.512', '0.010', 0),
(12, 'Мышка Сатоси Накамото', '0.860', '0.030', 0),
(13, 'Мышка хакера', '1.512', '0.080', 0),
(14, 'Мышка Сноудена', '10.512', '0.100', 0),
(15, 'Мышка Админа', '100.512', '0.300', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_vk_id` int(11) UNSIGNED DEFAULT NULL,
  `nickname` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `photo` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `clicks` double NOT NULL DEFAULT '0',
  `reputation` double DEFAULT NULL,
  `priv` tinyint(1) UNSIGNED DEFAULT NULL,
  `pet` int(4) NOT NULL,
  `last_pet_pohod` datetime DEFAULT NULL,
  `house` int(11) UNSIGNED DEFAULT NULL,
  `last_house_pohod` datetime DEFAULT NULL,
  `speed` varchar(333) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0.001',
  `aspeed` varchar(333) COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '0.001',
  `speed_price` varchar(111) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `aspeed_price` varchar(111) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `history1` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `history` int(11) UNSIGNED DEFAULT NULL,
  `h_id1` int(11) UNSIGNED DEFAULT NULL,
  `h_s1` double DEFAULT NULL,
  `history2` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id2` int(11) UNSIGNED DEFAULT NULL,
  `h_s2` double DEFAULT NULL,
  `history3` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id3` int(11) UNSIGNED DEFAULT NULL,
  `h_s3` double DEFAULT NULL,
  `history4` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id4` int(11) UNSIGNED DEFAULT NULL,
  `h_s4` double DEFAULT NULL,
  `history5` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id5` int(11) UNSIGNED DEFAULT NULL,
  `h_s5` double DEFAULT NULL,
  `history6` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id6` int(11) UNSIGNED DEFAULT NULL,
  `h_s6` double DEFAULT NULL,
  `history7` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id7` int(11) UNSIGNED DEFAULT NULL,
  `h_s7` double DEFAULT NULL,
  `history8` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id8` int(11) UNSIGNED DEFAULT NULL,
  `h_s8` double DEFAULT NULL,
  `history9` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id9` int(11) UNSIGNED DEFAULT NULL,
  `h_s9` double DEFAULT NULL,
  `history10` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `h_id10` int(11) UNSIGNED DEFAULT NULL,
  `h_s10` double DEFAULT NULL,
  `v1` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `viv` int(11) UNSIGNED DEFAULT NULL,
  `v_id1` int(11) UNSIGNED DEFAULT NULL,
  `v_s1` double DEFAULT NULL,
  `v2` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id2` int(11) UNSIGNED DEFAULT NULL,
  `v_s2` double DEFAULT NULL,
  `v3` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id3` int(11) UNSIGNED DEFAULT NULL,
  `v_s3` double DEFAULT NULL,
  `v4` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id4` int(11) UNSIGNED DEFAULT NULL,
  `v_s4` double DEFAULT NULL,
  `v5` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id5` int(11) UNSIGNED DEFAULT NULL,
  `v_s5` double DEFAULT NULL,
  `v6` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id6` int(11) UNSIGNED DEFAULT NULL,
  `v_s6` double DEFAULT NULL,
  `v7` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id7` int(11) UNSIGNED DEFAULT NULL,
  `v_s7` double DEFAULT NULL,
  `v8` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id8` int(11) UNSIGNED DEFAULT NULL,
  `v_s8` double DEFAULT NULL,
  `v9` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id9` int(11) UNSIGNED DEFAULT NULL,
  `v_s9` double DEFAULT NULL,
  `v10` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `v_id10` int(11) UNSIGNED DEFAULT NULL,
  `v_s10` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `upgrades`
--
ALTER TABLE `upgrades`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_foreignkey_users_user_vk` (`user_vk_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `upgrades`
--
ALTER TABLE `upgrades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
