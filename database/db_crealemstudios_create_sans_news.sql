-- Created by Vertabelo (http://vertabelo.com)
-- Last modification date: 2015-04-25 14:14:34.792




-- tables
-- Table t_articles
CREATE TABLE t_articles (
    id int    NOT NULL  AUTO_INCREMENT,
    title varchar(80)    NOT NULL ,
    content text    NOT NULL ,
    category varchar(20)    NOT NULL ,
    date datetime    NOT NULL DEFAULT '0000-00-00 00:00:00' ,
    t_users_id int    NOT NULL ,
    t_media_id int    NOT NULL ,
    CONSTRAINT t_articles_pk PRIMARY KEY (id)
);

-- Table t_comment
CREATE TABLE t_comment (
    id int    NOT NULL  AUTO_INCREMENT,
    t_users_id int    NOT NULL ,
    t_articles_id int    NOT NULL ,
    t_product_id int    NOT NULL ,
    titre varchar(50)    NOT NULL ,
    comment text    NOT NULL ,
    CONSTRAINT t_comment_pk PRIMARY KEY (id)
);

-- Table t_forum_reponse
CREATE TABLE t_forum_reponse (
    id int    NOT NULL  AUTO_INCREMENT,
    auteur varchar(10)    NOT NULL ,
    message text    NOT NULL ,
    date_reponse datetime    NOT NULL DEFAULT '0000-00-00 00:00:00' ,
    correspondance_sujet int    NOT NULL ,
    t_users_id int    NOT NULL ,
    t_forum_sujet_id int    NOT NULL ,
    CONSTRAINT t_forum_reponse_pk PRIMARY KEY (id)
);

-- Table t_forum_sujet
CREATE TABLE t_forum_sujet (
    id int    NOT NULL  AUTO_INCREMENT,
    auteur varchar(10)    NOT NULL ,
    title text    NOT NULL ,
    date_derniere_reponse datetime    NOT NULL DEFAULT '0000-00-00 00:00:00' ,
    t_users_id int    NOT NULL ,
    CONSTRAINT t_forum_sujet_pk PRIMARY KEY (id)
);

-- Table t_media
CREATE TABLE t_media (
    id int    NOT NULL  AUTO_INCREMENT,
    title varchar(80)    NOT NULL ,
    link varchar(100)    NOT NULL ,
    size int    NOT NULL ,
    alt text    NOT NULL ,
    CONSTRAINT t_media_pk PRIMARY KEY (id)
);

-- Table t_message
CREATE TABLE t_message (
    id int    NOT NULL  AUTO_INCREMENT,
    id_expediteur int    NOT NULL ,
    id_destinataire int    NOT NULL ,
    date datetime    NOT NULL ,
    titre varchar(70)    NOT NULL ,
    message text    NOT NULL ,
    t_media_id int    NOT NULL ,
    t_users_id int    NOT NULL ,
    CONSTRAINT t_message_pk PRIMARY KEY (id)
);

-- Table t_pages
CREATE TABLE t_pages (
    id int    NOT NULL  AUTO_INCREMENT,
    title varchar(80)    NOT NULL ,
    content text    NOT NULL ,
    date datetime    NOT NULL DEFAULT '0000-00-00 00:00:00' ,
    t_media_id int    NOT NULL ,
    CONSTRAINT t_pages_pk PRIMARY KEY (id)
);

-- Table t_product
CREATE TABLE t_product (
    id int    NOT NULL  AUTO_INCREMENT,
    title varchar(80)    NOT NULL ,
    content text    NOT NULL ,
    tag varchar(40)    NOT NULL ,
    category varchar(20)    NOT NULL ,
    price decimal(0,00)    NOT NULL ,
    stock int    NOT NULL ,
    delivery date    NOT NULL ,
    t_media_id int    NOT NULL ,
    CONSTRAINT t_product_pk PRIMARY KEY (id)
);

-- Table t_statistique
CREATE TABLE t_statistique (
    id int    NOT NULL  AUTO_INCREMENT,
    date datetime    NOT NULL ,
    page varchar(250)    NOT NULL ,
    ip varchar(15)    NOT NULL ,
    host varchar(60)    NOT NULL ,
    navigateur varchar(100)    NOT NULL ,
    referer varchar(250)    NOT NULL ,
    t_users_id int    NOT NULL ,
    CONSTRAINT t_statistique_pk PRIMARY KEY (id)
);

-- Table t_users
CREATE TABLE t_users (
    id int    NOT NULL  AUTO_INCREMENT,
    firstname varchar(60)    NOT NULL ,
    surname varchar(60)    NOT NULL ,
    mail varchar(100)    NOT NULL ,
    birthday date    NOT NULL ,
    phone int    NOT NULL ,
    address varchar(200)    NOT NULL ,
    zipcode int    NOT NULL ,
    city varchar(100)    NOT NULL ,
    status varchar(100)    NOT NULL ,
    login char(50)    NOT NULL ,
    pass_md5 text    NOT NULL ,
    registred datetime    NOT NULL DEFAULT '0000-00-00 00:00:00' ,
    display_name varchar(100)    NOT NULL ,
    company_name varchar(200)    NOT NULL ,
    t_media_id int    NOT NULL ,
    CONSTRAINT t_users_pk PRIMARY KEY (id)
);





-- foreign keys
-- Reference:  t_articles_t_media (table: t_articles)


ALTER TABLE t_articles ADD CONSTRAINT t_articles_t_media FOREIGN KEY t_articles_t_media (t_media_id)
    REFERENCES t_media (id);
-- Reference:  t_articles_t_users (table: t_articles)


ALTER TABLE t_articles ADD CONSTRAINT t_articles_t_users FOREIGN KEY t_articles_t_users (t_users_id)
    REFERENCES t_users (id);
-- Reference:  t_comment_t_articles (table: t_comment)


ALTER TABLE t_comment ADD CONSTRAINT t_comment_t_articles FOREIGN KEY t_comment_t_articles (t_articles_id)
    REFERENCES t_articles (id);
-- Reference:  t_comment_t_product (table: t_comment)


ALTER TABLE t_comment ADD CONSTRAINT t_comment_t_product FOREIGN KEY t_comment_t_product (t_product_id)
    REFERENCES t_product (id);
-- Reference:  t_comment_t_users (table: t_comment)


ALTER TABLE t_comment ADD CONSTRAINT t_comment_t_users FOREIGN KEY t_comment_t_users (t_users_id)
    REFERENCES t_users (id);
-- Reference:  t_forum_reponse_t_forum_sujet (table: t_forum_reponse)


ALTER TABLE t_forum_reponse ADD CONSTRAINT t_forum_reponse_t_forum_sujet FOREIGN KEY t_forum_reponse_t_forum_sujet (t_forum_sujet_id)
    REFERENCES t_forum_sujet (id);
-- Reference:  t_forum_reponse_t_users (table: t_forum_reponse)


ALTER TABLE t_forum_reponse ADD CONSTRAINT t_forum_reponse_t_users FOREIGN KEY t_forum_reponse_t_users (t_users_id)
    REFERENCES t_users (id);
-- Reference:  t_forum_sujet_t_users (table: t_forum_sujet)


ALTER TABLE t_forum_sujet ADD CONSTRAINT t_forum_sujet_t_users FOREIGN KEY t_forum_sujet_t_users (t_users_id)
    REFERENCES t_users (id);
-- Reference:  t_message_t_media (table: t_message)


ALTER TABLE t_message ADD CONSTRAINT t_message_t_media FOREIGN KEY t_message_t_media (t_media_id)
    REFERENCES t_media (id);
-- Reference:  t_message_t_users (table: t_message)


ALTER TABLE t_message ADD CONSTRAINT t_message_t_users FOREIGN KEY t_message_t_users (t_users_id)
    REFERENCES t_users (id);
-- Reference:  t_pages_t_media (table: t_pages)


ALTER TABLE t_pages ADD CONSTRAINT t_pages_t_media FOREIGN KEY t_pages_t_media (t_media_id)
    REFERENCES t_media (id);
-- Reference:  t_product_t_media (table: t_product)


ALTER TABLE t_product ADD CONSTRAINT t_product_t_media FOREIGN KEY t_product_t_media (t_media_id)
    REFERENCES t_media (id);
-- Reference:  t_statistique_t_users (table: t_statistique)


ALTER TABLE t_statistique ADD CONSTRAINT t_statistique_t_users FOREIGN KEY t_statistique_t_users (t_users_id)
    REFERENCES t_users (id);
-- Reference:  t_users_t_media (table: t_users)


ALTER TABLE t_users ADD CONSTRAINT t_users_t_media FOREIGN KEY t_users_t_media (t_media_id)
    REFERENCES t_media (id);



-- End of file.

