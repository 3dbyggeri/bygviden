-- MySQL dump 9.11
--
-- Host: localhost    Database: bygviden
-- ------------------------------------------------------
-- Server version	4.0.24

--
-- Table structure for table `dev_a_user`
--

CREATE TABLE dev_a_user (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(50) default NULL,
  full_name varchar(50) default NULL,
  password varchar(100) default NULL,
  mail varchar(100) default NULL,
  language varchar(20) default NULL,
  warning int(11) default NULL,
  sessionTime int(11) default NULL,
  sessid varchar(200) default NULL,
  sessionStart timestamp(14) NOT NULL,
  pane varchar(50) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_adnews`
--

CREATE TABLE dev_adnews (
  id int(10) unsigned NOT NULL auto_increment,
  title varchar(200) default NULL,
  producent int(11) default NULL,
  body text,
  published char(1) default 'y',
  website varchar(200) default NULL,
  image varchar(200) default NULL,
  created datetime default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_agent2element`
--

CREATE TABLE dev_agent2element (
  agent int(11) default NULL,
  element int(11) default NULL,
  category int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_agentstyring`
--

CREATE TABLE dev_agentstyring (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'new element',
  parent int(11) default NULL,
  position int(11) default NULL,
  description text,
  start_text text,
  created datetime default NULL,
  creator int(11) default NULL,
  edited datetime default NULL,
  threshold int(11) default '70',
  results int(11) default '5',
  autonomy char(1) default 'n',
  cache text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_agentstyring_state`
--

CREATE TABLE dev_agentstyring_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_banner_advertiser`
--

CREATE TABLE dev_banner_advertiser (
  id int(10) unsigned NOT NULL auto_increment,
  name text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_banner_management`
--

CREATE TABLE dev_banner_management (
  id int(10) unsigned NOT NULL auto_increment,
  construction_id varchar(200) default NULL,
  construction_name text,
  sold_to int(11) default NULL,
  sold_by int(11) default NULL,
  sold_period_start date default NULL,
  sold_period_end date default NULL,
  sold_comment text,
  reserved_to int(11) default NULL,
  reserved_by int(11) default NULL,
  reserved_period_start date default NULL,
  reserved_period_end date default NULL,
  reserved_comment text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_banner_salesman`
--

CREATE TABLE dev_banner_salesman (
  id int(10) unsigned NOT NULL auto_increment,
  name text,
  email varchar(200) default NULL,
  password varchar(200) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_beton`
--

CREATE TABLE dev_beton (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'Ingen referance',
  parent int(11) default NULL,
  element_id int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_beton_state`
--

CREATE TABLE dev_beton_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_blog`
--

CREATE TABLE dev_blog (
  id int(10) unsigned NOT NULL auto_increment,
  title varchar(255) default NULL,
  post text,
  created datetime default NULL,
  edited datetime default NULL,
  publish char(1) default 'n',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_branche2category`
--

CREATE TABLE dev_branche2category (
  branche_name varchar(200) default NULL,
  category_id int(11) default NULL,
  branche_element_id int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_branche_relevans`
--

CREATE TABLE dev_branche_relevans (
  publikations_id int(11) default NULL,
  branche_id int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_branche_tree`
--

CREATE TABLE dev_branche_tree (
  id int(10) unsigned NOT NULL auto_increment,
  parent int(11) default NULL,
  element_id int(11) default NULL,
  branche_id int(11) default NULL,
  position int(11) default NULL,
  level int(11) default NULL,
  path varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_brancher`
--

CREATE TABLE dev_brancher (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'Ny branche',
  label varchar(200) default 'Ny branche',
  timepublish datetime default NULL,
  timeunpublish datetime default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_bruger`
--

CREATE TABLE dev_bruger (
  id int(10) unsigned NOT NULL auto_increment,
  bruger_navn varchar(200) default NULL,
  medlemsnr int(11) default NULL,
  parent int(11) default '0',
  active char(1) default 'y',
  sessid varchar(200) default NULL,
  sesstart timestamp(14) NOT NULL,
  password varchar(50) default NULL,
  firmanavn1 varchar(50) default NULL,
  firmanavn2 varchar(50) default NULL,
  firmanavn3 varchar(50) default NULL,
  gade varchar(50) default NULL,
  sted varchar(50) default NULL,
  postnr varchar(10) default NULL,
  city varchar(50) default NULL,
  land varchar(50) default NULL,
  medlem char(1) default 'y',
  email varchar(50) default NULL,
  restricted_shop char(1) default 'y',
  clipkort_amount decimal(10,0) default NULL,
  gratist char(1) default 'n',
  organization varchar(200) NOT NULL default 'BYG',
  temaeditor char(1) default 'n',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_bruger_rabat`
--

CREATE TABLE dev_bruger_rabat (
  id int(10) unsigned NOT NULL auto_increment,
  kilde_id int(11) default NULL,
  antal_bruger varchar(200) default NULL,
  pris decimal(11,2) default '0.00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_buildingelements`
--

CREATE TABLE dev_buildingelements (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'new element',
  parent int(11) default NULL,
  position int(11) default NULL,
  goderaad text,
  created datetime default NULL,
  creator int(11) default NULL,
  edited datetime default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_buildingelements_state`
--

CREATE TABLE dev_buildingelements_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_bygcatpubbranch`
--

CREATE TABLE dev_bygcatpubbranch (
  element_id int(11) NOT NULL default '0',
  category_id int(11) NOT NULL default '0',
  publication_id int(11) NOT NULL default '0',
  branche varchar(50) NOT NULL default '',
  KEY element_id (element_id,category_id,publication_id,branche)
) TYPE=MyISAM;

--
-- Table structure for table `dev_categori`
--

CREATE TABLE dev_categori (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'new element',
  position int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_categori2element`
--

CREATE TABLE dev_categori2element (
  categori int(11) default NULL,
  element int(11) default NULL,
  open char(1) default 'y'
) TYPE=MyISAM;

--
-- Table structure for table `dev_general`
--

CREATE TABLE dev_general (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'Ingen referance',
  parent int(11) default NULL,
  element_id int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_general_state`
--

CREATE TABLE dev_general_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_houses`
--

CREATE TABLE dev_houses (
  id int(10) unsigned NOT NULL auto_increment,
  x int(11) default NULL,
  y int(11) default NULL,
  branche_id varchar(255) NOT NULL default '',
  label varchar(255) NOT NULL default '',
  link varchar(255) NOT NULL default '',
  pointer varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_kilde2element`
--

CREATE TABLE dev_kilde2element (
  kilde int(11) default NULL,
  element int(11) default NULL,
  category int(11) default NULL,
  KEY kilde (kilde),
  KEY element (element),
  KEY category (category)
) TYPE=MyISAM;

--
-- Table structure for table `dev_kildestyring`
--

CREATE TABLE dev_kildestyring (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'new element',
  parent int(11) default NULL,
  position int(11) default NULL,
  description text,
  created datetime default NULL,
  creator int(11) default NULL,
  edited datetime default NULL,
  element_type enum('root','leverandor','kategori','publikation','folder') default NULL,
  kilde_url text,
  forlag_url text,
  logo_url text,
  publish char(1) default 'y',
  timepublish datetime default NULL,
  timeunpublish datetime default NULL,
  crawling char(1) default 'y',
  brugsbetingelser enum('fuldtekst_alle','fuldtekst_medlemmer','resume_alle','resume_medlemmer') default 'fuldtekst_alle',
  betaling char(1) default 'n',
  enkelt_betaling char(1) default 'y',
  abonament_betaling char(1) default 'n',
  enkelt_pris decimal(11,0) default '0',
  abonament_pris decimal(11,0) default '0',
  abonament_periode int(11) default '12',
  urls_open char(1) default 'y',
  publish_open char(1) default 'y',
  brugs_open char(1) default 'y',
  adresse text,
  telefon varchar(20) default NULL,
  fax varchar(20) default NULL,
  mail varchar(200) default NULL,
  udgivelses_dato date default NULL,
  revisions_dato date default NULL,
  digital_udgave char(1) default NULL,
  crawling_depth int(11) default NULL,
  crawling_cuantitie int(11) default NULL,
  log_in char(1) default 'n',
  log_name varchar(200) default 'autonomy',
  log_password varchar(200) default '32ReCvQa',
  log_domain varchar(200) default NULL,
  db varchar(200) default NULL,
  custom_summary char(1) default 'n',
  forbidden_words varchar(200) default NULL,
  required_words varchar(200) default NULL,
  indholdsfortegnelse char(1) default 'n',
  observation text,
  betegnelse varchar(200) default '',
  overrule_betaling varchar(100) default 'n',
  bruger_rabat char(1) default 'n',
  PRIMARY KEY  (id),
  KEY id (id),
  KEY parent (parent)
) TYPE=MyISAM;

--
-- Table structure for table `dev_kildestyring_state`
--

CREATE TABLE dev_kildestyring_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL,
  KEY id (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_kloak`
--

CREATE TABLE dev_kloak (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'Ingen referance',
  parent int(11) default NULL,
  element_id int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id),
  KEY id (id),
  KEY parent (parent),
  KEY element_id (element_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_kloak_state`
--

CREATE TABLE dev_kloak_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL,
  KEY id (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_maling`
--

CREATE TABLE dev_maling (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'Ingen referance',
  parent int(11) default NULL,
  element_id int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id),
  KEY id (id),
  KEY parent (parent),
  KEY element_id (element_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_maling_state`
--

CREATE TABLE dev_maling_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL,
  KEY id (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_mur`
--

CREATE TABLE dev_mur (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'Ingen referance',
  parent int(11) default NULL,
  element_id int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id),
  KEY id (id),
  KEY parent (parent),
  KEY element_id (element_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_mur_state`
--

CREATE TABLE dev_mur_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL,
  KEY id (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_newsletter`
--

CREATE TABLE dev_newsletter (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(255) default NULL,
  created datetime default NULL,
  mailed datetime default NULL,
  numberofrecipients int(11) default NULL,
  chimp_id varchar(250) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY chimp_id (chimp_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_newsletter2paragraph`
--

CREATE TABLE dev_newsletter2paragraph (
  newsletter int(11) default NULL,
  paragraph int(11) default NULL,
  position int(11) default NULL,
  col int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_newsletterparagraph`
--

CREATE TABLE dev_newsletterparagraph (
  id int(10) unsigned NOT NULL auto_increment,
  created datetime default NULL,
  body text,
  reference int(11) default NULL,
  referencetype char(1) default 'T',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_paragraph`
--

CREATE TABLE dev_paragraph (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(255) default NULL,
  body text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_producent`
--

CREATE TABLE dev_producent (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'ny producent',
  description text,
  timepublish datetime default NULL,
  timeunpublish datetime default NULL,
  kilde_url varchar(200) default NULL,
  logo_url varchar(200) default NULL,
  adresse text,
  CVR varchar(20) default NULL,
  telefon varchar(20) default NULL,
  fax varchar(20) default NULL,
  mail varchar(200) default NULL,
  data_open char(1) default 'y',
  publish_open char(1) default 'y',
  product_open char(1) default 'y',
  observation text,
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_producer`
--

CREATE TABLE dev_producer (
  id int(10) unsigned NOT NULL auto_increment,
  name text,
  user_name varchar(200) default NULL,
  user_password varchar(200) default NULL,
  description text,
  observation text,
  publish char(1) default 'n',
  publish_request char(1) default 'n',
  home_page varchar(200) default NULL,
  logo_url varchar(200) default NULL,
  adresse text,
  CVR varchar(20) default NULL,
  telefon varchar(20) default NULL,
  fax varchar(20) default NULL,
  mail varchar(200) default NULL,
  admin_mail varchar(200) default NULL,
  kilde_id int(11) default NULL,
  admin_name varchar(200) default NULL,
  admin_telefon varchar(200) default NULL,
  advertise_signup datetime default NULL,
  advertise_deal varchar(200) default 'none',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_product`
--

CREATE TABLE dev_product (
  id int(10) unsigned NOT NULL auto_increment,
  producent int(11) default NULL,
  name varchar(200) default 'Nyt produkt',
  description text,
  kilde_url varchar(200) default NULL,
  logo_url varchar(200) default NULL,
  timepublish datetime default NULL,
  timeunpublish datetime default NULL,
  data_open char(1) default 'y',
  publish_open char(1) default 'y',
  observation text,
  PRIMARY KEY  (id),
  KEY id (id),
  KEY producent (producent)
) TYPE=MyISAM;

--
-- Table structure for table `dev_product2element`
--

CREATE TABLE dev_product2element (
  product_id int(11) default NULL,
  element_id int(11) default NULL,
  branche_name varchar(200) default NULL,
  publish char(1) default 'n',
  publish_request datetime default NULL,
  node_id int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_product2varegruppe`
--

CREATE TABLE dev_product2varegruppe (
  varegruppe_id int(11) NOT NULL default '0',
  product_id int(11) NOT NULL default '0',
  KEY varegruppe_id (varegruppe_id,product_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_product_category`
--

CREATE TABLE dev_product_category (
  id int(10) unsigned NOT NULL auto_increment,
  name text,
  description text,
  home_page varchar(200) default NULL,
  producer_id int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_product_links`
--

CREATE TABLE dev_product_links (
  id int(10) unsigned NOT NULL auto_increment,
  product_id int(11) default NULL,
  label text,
  url text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_products`
--

CREATE TABLE dev_products (
  id int(10) unsigned NOT NULL auto_increment,
  name text,
  description text,
  observation text,
  producer_id int(11) default NULL,
  category_id int(11) default NULL,
  varegruppe_id int(11) default NULL,
  home_page varchar(200) default NULL,
  logo_url varchar(200) default NULL,
  publish char(1) default 'n',
  publish_request datetime default NULL,
  usage_description varchar(200) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_produkt2element`
--

CREATE TABLE dev_produkt2element (
  produkt int(11) default NULL,
  element int(11) default NULL,
  category int(11) default NULL,
  KEY produkt (produkt),
  KEY element (element),
  KEY category (category)
) TYPE=MyISAM;

--
-- Table structure for table `dev_realms`
--

CREATE TABLE dev_realms (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(50) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_role`
--

CREATE TABLE dev_role (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(50) default NULL,
  description text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_roles_constrains`
--

CREATE TABLE dev_roles_constrains (
  doc int(11) default NULL,
  role int(11) default NULL,
  realm int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_search_branche_stats`
--

CREATE TABLE dev_search_branche_stats (
  search_id int(11) default NULL,
  branche varchar(250) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_search_category_stats`
--

CREATE TABLE dev_search_category_stats (
  search_id int(11) default NULL,
  category varchar(250) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_search_stats`
--

CREATE TABLE dev_search_stats (
  id int(10) unsigned NOT NULL auto_increment,
  requested datetime default NULL,
  results_count int(11) default NULL,
  query text,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_stats`
--

CREATE TABLE dev_stats (
  id int(10) unsigned NOT NULL auto_increment,
  timestamp timestamp(14) NOT NULL,
  sessid varchar(200) NOT NULL default '',
  machine_id varchar(200) NOT NULL default '',
  user_id int(11) unsigned default '0',
  element_id int(11) unsigned default '0',
  publication_id int(11) default '0',
  branche_id varchar(50) default '0',
  referer_id int(11) unsigned default '0',
  page_type varchar(100) default '',
  query_terms text,
  results int(11) default '0',
  browser_ip varchar(250) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY id (id),
  KEY user_id (user_id),
  KEY element_id (element_id),
  KEY publication_id (publication_id),
  KEY branche_id (branche_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_subscriber`
--

CREATE TABLE dev_subscriber (
  id int(10) unsigned NOT NULL auto_increment,
  email varchar(255) default NULL,
  active char(1) default 'y',
  subscribed datetime default NULL,
  icontact_id varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_tema`
--

CREATE TABLE dev_tema (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(255) default NULL,
  resume text,
  forside_slot int(11) default NULL,
  forside_name varchar(255) default NULL,
  forside_category varchar(255) default NULL,
  forside_resume text,
  icon varchar(255) default NULL,
  created datetime default NULL,
  edited datetime default NULL,
  private char(1) default 'n',
  publish char(1) default 'y',
  editor_id int(11) default NULL,
  position int(11) NOT NULL default '0',
  creator int(11) NOT NULL default '0',
  creator_name varchar(250) NOT NULL default '',
  creator_email varchar(250) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_tema_bygning`
--

CREATE TABLE dev_tema_bygning (
  id int(10) unsigned NOT NULL auto_increment,
  tema_id int(11) default NULL,
  node_id int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_tema_editor`
--

CREATE TABLE dev_tema_editor (
  id int(10) unsigned NOT NULL auto_increment,
  title varchar(255) default NULL,
  name varchar(255) default NULL,
  email varchar(255) default NULL,
  resume text,
  photo varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_tema_kilde`
--

CREATE TABLE dev_tema_kilde (
  id int(10) unsigned NOT NULL auto_increment,
  tema_id int(11) default NULL,
  name varchar(255) default NULL,
  kilde_id int(11) default NULL,
  url varchar(255) default NULL,
  comment text,
  is_bibliotek char(1) default 'n',
  position int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_tema_page`
--

CREATE TABLE dev_tema_page (
  id int(10) unsigned NOT NULL auto_increment,
  tema_id int(11) default NULL,
  name varchar(255) default NULL,
  body text,
  created datetime default NULL,
  edited datetime default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_trae`
--

CREATE TABLE dev_trae (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(200) default 'Ingen referance',
  parent int(11) default NULL,
  element_id int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id),
  KEY id (id),
  KEY parent (parent),
  KEY element_id (element_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_trae_state`
--

CREATE TABLE dev_trae_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL,
  KEY id (id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_usage`
--

CREATE TABLE dev_usage (
  bruger_id int(11) default NULL,
  publication_id int(11) default NULL,
  url varchar(200) default NULL,
  title varchar(200) default NULL,
  pris decimal(11,2) default '0.00',
  abonament_periode int(11) default NULL,
  readed datetime default NULL,
  archived datetime default NULL,
  KEY bruger_id (bruger_id),
  KEY publication_id (publication_id)
) TYPE=MyISAM;

--
-- Table structure for table `dev_user2role`
--

CREATE TABLE dev_user2role (
  role int(11) default NULL,
  user int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_varegruppe2element`
--

CREATE TABLE dev_varegruppe2element (
  varegruppe int(11) default NULL,
  element int(11) default NULL,
  category int(11) default NULL
) TYPE=MyISAM;

--
-- Table structure for table `dev_varegrupper`
--

CREATE TABLE dev_varegrupper (
  id int(10) unsigned NOT NULL auto_increment,
  name varchar(250) default NULL,
  parent int(11) default NULL,
  position int(11) default NULL,
  PRIMARY KEY  (id),
  KEY parent (parent)
) TYPE=MyISAM;

--
-- Table structure for table `dev_varegrupper_state`
--

CREATE TABLE dev_varegrupper_state (
  id int(11) default NULL,
  uid varchar(200) default NULL,
  time datetime default NULL,
  KEY id (id)
) TYPE=MyISAM;

