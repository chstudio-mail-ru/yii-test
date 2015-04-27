-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 27 2015 г., 10:11
-- Версия сервера: 5.5.25
-- Версия PHP: 5.4.40

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `canvas`
--

-- --------------------------------------------------------

--
-- Структура таблицы `image_list`
--

CREATE TABLE IF NOT EXISTS `image_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `imageName` varchar(255) NOT NULL,
  `createTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

--
-- Дамп данных таблицы `image_list`
--

INSERT INTO `image_list` (`id`, `userId`, `imageName`, `createTime`) VALUES
(84, 45, '45--tpnBifWtkKEFpzPriDhfEPm_iWs9lqu.png', '2015-04-25 07:36:34'),
(85, 45, '45-Zn_oRV14622x4NBesFDLwLMkPxD4-uXv.png', '2015-04-25 07:36:59'),
(86, 45, '45-4FEhJC1-Jzu6p3A0HXHb5-RFITVwdvqn.png', '2015-04-25 07:37:19'),
(87, 45, '45-v2lay_hi1V9E9kfVAlQNdubqznH8lW26.png', '2015-04-25 07:38:25'),
(88, 44, '44-RPnjQpTqvaLKgvf8-U9PJznV9X76S9xx.png', '2015-04-25 07:39:49'),
(89, 44, '44-tFGvyv2g5-OI2Ze0YpU1z-r0CdpDJxec.png', '2015-04-25 07:42:05'),
(90, 44, '44-I-MOycZwJnKmFp49hfSns4yksgFS-KFC.png', '2015-04-25 07:42:40'),
(92, 46, '46--1E4L2dNmIqk7PBVnrnQtRC639Ft3dAb.png', '2015-04-25 07:54:04'),
(93, 44, '44-FoLoFv2ZSYCQCHrPApNa5223S3fXxADC.png', '2015-04-26 21:44:29');

-- --------------------------------------------------------

--
-- Структура таблицы `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` char(40) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `session`
-- removed

-- --------------------------------------------------------

--
-- Структура таблицы `user_list`
--

CREATE TABLE IF NOT EXISTS `user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `useremail` varchar(75) NOT NULL,
  `username` varchar(75) NOT NULL,
  `password` varchar(32) NOT NULL,
  `accessToken` varchar(32) NOT NULL,
  `authKey` varchar(32) NOT NULL,
  `registerTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `useremail` (`useremail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

--
-- Дамп данных таблицы `user_list`
--

INSERT INTO `user_list` (`id`, `useremail`, `username`, `password`, `accessToken`, `authKey`, `registerTime`) VALUES
(44, 'dmaverkov@gmail.com', 'Denis Averkov', 'c4ca4238a0b923820dcc509a6f75849b', 'UDTmnq7l64yjnGeSkzU5BH7Gr69qgrQ3', 'okrQbgLrR38BCB1-7KzxmAhvzGSD8mdU', '2015-04-24 13:50:52'),
(45, 'a@a.a', 'тест a@a.a', 'c4ca4238a0b923820dcc509a6f75849b', '7Vbcrm4veeAk3f1RRwPWyyWvJ7I0HFX-', 'igAICt-fu2dldARd40XFMTgnv-HR1cu5', '2015-04-24 22:16:35'),
(46, 'a1@a.a', 'тест a1@a.a', 'c4ca4238a0b923820dcc509a6f75849b', 'yQjRpB5OAv5nyw9t-HVN_AHLxkpoRXHJ', 'H9pr2PwVDjsgwx4szJywDjIXrjgbG3uK', '2015-04-25 07:54:04');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
