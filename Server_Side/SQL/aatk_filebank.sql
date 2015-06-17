-- phpMyAdmin SQL Dump
-- version 4.4.6.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 17 2015 г., 12:38
-- Версия сервера: 5.6.24-72.2-beget-log
-- Версия PHP: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `aatk_filebank`
--

-- --------------------------------------------------------

--
-- Структура таблицы `filebank`
--
-- Создание: Июн 16 2015 г., 13:08
-- Последнее обновление: Июн 17 2015 г., 07:44
-- Последняя проверка: Июн 16 2015 г., 13:08
--

DROP TABLE IF EXISTS `filebank`;
CREATE TABLE IF NOT EXISTS `filebank` (
  `id` int(11) NOT NULL,
  `md5` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `size` int(11) NOT NULL,
  `parent` varchar(50) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Структура таблицы `queue`
--
-- Создание: Июн 16 2015 г., 16:53
-- Последнее обновление: Июн 16 2015 г., 16:53
--

DROP TABLE IF EXISTS `queue`;
CREATE TABLE IF NOT EXISTS `queue` (
  `id` int(11) NOT NULL,
  `queuename` varchar(50) NOT NULL,
  `job` varchar(1500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `filebank`
--
ALTER TABLE `filebank`
  ADD PRIMARY KEY (`id`),
  ADD FULLTEXT KEY `md5` (`md5`);
ALTER TABLE `filebank`
  ADD FULLTEXT KEY `Parent` (`parent`);

--
-- Индексы таблицы `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`id`),
  ADD FULLTEXT KEY `queuename` (`queuename`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `filebank`
--
ALTER TABLE `filebank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT для таблицы `queue`
--
ALTER TABLE `queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
