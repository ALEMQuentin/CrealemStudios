-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Mar 04 Août 2015 à 11:40
-- Version du serveur :  5.5.38
-- Version de PHP :  5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `db_crealemstudios`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_articles`
--

CREATE TABLE `t_articles` (
`id` int(11) NOT NULL,
  `title` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `t_users_id` int(11) NOT NULL,
  `t_media_id` int(11) NOT NULL,
  `t_meta_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_comment`
--

CREATE TABLE `t_comment` (
`id` int(11) NOT NULL,
  `t_users_id` int(11) NOT NULL,
  `t_articles_id` int(11) NOT NULL,
  `t_product_id` int(11) NOT NULL,
  `titre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_forum_reponse`
--

CREATE TABLE `t_forum_reponse` (
`id` int(11) NOT NULL,
  `auteur` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `date_reponse` datetime NOT NULL,
  `correspondance_sujet` int(11) NOT NULL,
  `t_users_id` int(11) NOT NULL,
  `t_forum_sujet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_forum_sujet`
--

CREATE TABLE `t_forum_sujet` (
`id` int(11) NOT NULL,
  `auteur` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `date_derniere_reponse` datetime NOT NULL,
  `t_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_media`
--

CREATE TABLE `t_media` (
`id` int(11) NOT NULL,
  `title` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `alt` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_membre`
--

CREATE TABLE `t_membre` (
`id` int(11) NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `pass_md5` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `t_membre`
--

INSERT INTO `t_membre` (`id`, `login`, `pass_md5`) VALUES
(1, 'ALEMQuentin', '7cde3965f74061402c35c2117b57bfae');

-- --------------------------------------------------------

--
-- Structure de la table `t_message`
--

CREATE TABLE `t_message` (
`id` int(11) NOT NULL,
  `id_expediteur` int(11) NOT NULL,
  `id_destinataire` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `titre` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `t_media_id` int(11) NOT NULL,
  `t_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_meta`
--

CREATE TABLE `t_meta` (
`id` int(11) NOT NULL,
  `description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `keyword` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_news`
--

CREATE TABLE `t_news` (
`id` int(11) NOT NULL,
  `auteur` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `date_publication` datetime NOT NULL,
  `t_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_pages`
--

CREATE TABLE `t_pages` (
`id` int(11) NOT NULL,
  `title` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `t_media_id` int(11) NOT NULL,
  `t_meta_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_product`
--

CREATE TABLE `t_product` (
`id` int(11) NOT NULL,
  `title` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `stock` int(11) NOT NULL,
  `delivery` date NOT NULL,
  `t_media_id` int(11) NOT NULL,
  `t_meta_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_statistique`
--

CREATE TABLE `t_statistique` (
`id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `page` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `navigateur` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `referer` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `t_users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `t_users`
--

CREATE TABLE `t_users` (
`id` int(11) NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `pass_md5` text COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `phone` int(11) NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `zipcode` int(11) NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `registred` datetime NOT NULL,
  `display_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `company_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `t_media_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `t_articles`
--
ALTER TABLE `t_articles`
 ADD PRIMARY KEY (`id`), ADD KEY `t_articles_t_media` (`t_media_id`), ADD KEY `t_articles_t_meta` (`t_meta_id`), ADD KEY `t_articles_t_users` (`t_users_id`);

--
-- Index pour la table `t_comment`
--
ALTER TABLE `t_comment`
 ADD PRIMARY KEY (`id`), ADD KEY `t_comment_t_articles` (`t_articles_id`), ADD KEY `t_comment_t_product` (`t_product_id`), ADD KEY `t_comment_t_users` (`t_users_id`);

--
-- Index pour la table `t_forum_reponse`
--
ALTER TABLE `t_forum_reponse`
 ADD PRIMARY KEY (`id`), ADD KEY `t_forum_reponse_t_forum_sujet` (`t_forum_sujet_id`), ADD KEY `t_forum_reponse_t_users` (`t_users_id`);

--
-- Index pour la table `t_forum_sujet`
--
ALTER TABLE `t_forum_sujet`
 ADD PRIMARY KEY (`id`), ADD KEY `t_forum_sujet_t_users` (`t_users_id`);

--
-- Index pour la table `t_media`
--
ALTER TABLE `t_media`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_membre`
--
ALTER TABLE `t_membre`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_message`
--
ALTER TABLE `t_message`
 ADD PRIMARY KEY (`id`), ADD KEY `t_message_t_media` (`t_media_id`), ADD KEY `t_message_t_users` (`t_users_id`);

--
-- Index pour la table `t_meta`
--
ALTER TABLE `t_meta`
 ADD PRIMARY KEY (`id`);

--
-- Index pour la table `t_news`
--
ALTER TABLE `t_news`
 ADD PRIMARY KEY (`id`), ADD KEY `t_news_t_users` (`t_users_id`);

--
-- Index pour la table `t_pages`
--
ALTER TABLE `t_pages`
 ADD PRIMARY KEY (`id`), ADD KEY `t_pages_t_media` (`t_media_id`), ADD KEY `t_pages_t_meta` (`t_meta_id`);

--
-- Index pour la table `t_product`
--
ALTER TABLE `t_product`
 ADD PRIMARY KEY (`id`), ADD KEY `t_product_t_media` (`t_media_id`), ADD KEY `t_product_t_meta` (`t_meta_id`);

--
-- Index pour la table `t_statistique`
--
ALTER TABLE `t_statistique`
 ADD PRIMARY KEY (`id`), ADD KEY `t_statistique_t_users` (`t_users_id`);

--
-- Index pour la table `t_users`
--
ALTER TABLE `t_users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`), ADD KEY `t_users_t_media` (`t_media_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `t_articles`
--
ALTER TABLE `t_articles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_comment`
--
ALTER TABLE `t_comment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_forum_reponse`
--
ALTER TABLE `t_forum_reponse`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_forum_sujet`
--
ALTER TABLE `t_forum_sujet`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_media`
--
ALTER TABLE `t_media`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_membre`
--
ALTER TABLE `t_membre`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `t_message`
--
ALTER TABLE `t_message`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_meta`
--
ALTER TABLE `t_meta`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_news`
--
ALTER TABLE `t_news`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_pages`
--
ALTER TABLE `t_pages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_product`
--
ALTER TABLE `t_product`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_statistique`
--
ALTER TABLE `t_statistique`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `t_users`
--
ALTER TABLE `t_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `t_articles`
--
ALTER TABLE `t_articles`
ADD CONSTRAINT `t_articles_t_media` FOREIGN KEY (`t_media_id`) REFERENCES `t_media` (`id`),
ADD CONSTRAINT `t_articles_t_meta` FOREIGN KEY (`t_meta_id`) REFERENCES `t_meta` (`id`),
ADD CONSTRAINT `t_articles_t_users` FOREIGN KEY (`t_users_id`) REFERENCES `t_users` (`id`);

--
-- Contraintes pour la table `t_comment`
--
ALTER TABLE `t_comment`
ADD CONSTRAINT `t_comment_t_articles` FOREIGN KEY (`t_articles_id`) REFERENCES `t_articles` (`id`),
ADD CONSTRAINT `t_comment_t_product` FOREIGN KEY (`t_product_id`) REFERENCES `t_product` (`id`),
ADD CONSTRAINT `t_comment_t_users` FOREIGN KEY (`t_users_id`) REFERENCES `t_users` (`id`);

--
-- Contraintes pour la table `t_forum_reponse`
--
ALTER TABLE `t_forum_reponse`
ADD CONSTRAINT `t_forum_reponse_t_forum_sujet` FOREIGN KEY (`t_forum_sujet_id`) REFERENCES `t_forum_sujet` (`id`),
ADD CONSTRAINT `t_forum_reponse_t_users` FOREIGN KEY (`t_users_id`) REFERENCES `t_users` (`id`);

--
-- Contraintes pour la table `t_forum_sujet`
--
ALTER TABLE `t_forum_sujet`
ADD CONSTRAINT `t_forum_sujet_t_users` FOREIGN KEY (`t_users_id`) REFERENCES `t_users` (`id`);

--
-- Contraintes pour la table `t_message`
--
ALTER TABLE `t_message`
ADD CONSTRAINT `t_message_t_media` FOREIGN KEY (`t_media_id`) REFERENCES `t_media` (`id`),
ADD CONSTRAINT `t_message_t_users` FOREIGN KEY (`t_users_id`) REFERENCES `t_users` (`id`);

--
-- Contraintes pour la table `t_news`
--
ALTER TABLE `t_news`
ADD CONSTRAINT `t_news_t_users` FOREIGN KEY (`t_users_id`) REFERENCES `t_users` (`id`);

--
-- Contraintes pour la table `t_pages`
--
ALTER TABLE `t_pages`
ADD CONSTRAINT `t_pages_t_media` FOREIGN KEY (`t_media_id`) REFERENCES `t_media` (`id`),
ADD CONSTRAINT `t_pages_t_meta` FOREIGN KEY (`t_meta_id`) REFERENCES `t_meta` (`id`);

--
-- Contraintes pour la table `t_product`
--
ALTER TABLE `t_product`
ADD CONSTRAINT `t_product_t_media` FOREIGN KEY (`t_media_id`) REFERENCES `t_media` (`id`),
ADD CONSTRAINT `t_product_t_meta` FOREIGN KEY (`t_meta_id`) REFERENCES `t_meta` (`id`);

--
-- Contraintes pour la table `t_statistique`
--
ALTER TABLE `t_statistique`
ADD CONSTRAINT `t_statistique_t_users` FOREIGN KEY (`t_users_id`) REFERENCES `t_users` (`id`);

--
-- Contraintes pour la table `t_users`
--
ALTER TABLE `t_users`
ADD CONSTRAINT `t_users_t_media` FOREIGN KEY (`t_media_id`) REFERENCES `t_media` (`id`),
ADD CONSTRAINT `t_users_t_membre` FOREIGN KEY (`id`) REFERENCES `t_membre` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
