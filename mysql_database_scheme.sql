/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "article" (
  "id" int(11) NOT NULL AUTO_INCREMENT,
  "uid" int(11) NOT NULL DEFAULT '1',
  "a_sort" int(11) DEFAULT '0',
  "title" varchar(255) NOT NULL,
  "a_date" datetime NOT NULL,
  "content" text NOT NULL,
  "published" int(1) NOT NULL DEFAULT '1',
  "comments" longtext NOT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "categories" (
  "id" int(11) NOT NULL AUTO_INCREMENT,
  "uid" int(11) NOT NULL DEFAULT '1',
  "name" varchar(255) NOT NULL,
  "hits" int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "comment" (
  "id" int(11) NOT NULL AUTO_INCREMENT,
  "a_id" int(11) NOT NULL,
  "author" varchar(255) NOT NULL,
  "mail" varchar(255) NOT NULL,
  "www" varchar(255) NOT NULL,
  "comment" text NOT NULL,
  "c_date" datetime NOT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "rel_articles_categories" (
  "id" int(11) NOT NULL AUTO_INCREMENT,
  "id_a" int(11) NOT NULL,
  "id_b" int(11) NOT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "users" (
  "id" int(11) NOT NULL AUTO_INCREMENT,
  "username" varchar(255) NOT NULL,
  "password" varchar(255) NOT NULL,
  "hash" varchar(255) NOT NULL,
  "last_login_attempt" datetime NOT NULL,
  PRIMARY KEY ("id")
);
/*!40101 SET character_set_client = @saved_cs_client */;
