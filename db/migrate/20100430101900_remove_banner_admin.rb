class RemoveBannerAdmin < ActiveRecord::Migration  
  def self.up
    drop_table 'dev_banner_advertiser'
    drop_table 'dev_banner_management'
    drop_table 'dev_banner_salesman'
  end
  
  def self.down
    execute <<-SQL
      --
      -- Table structure for table `dev_banner_advertiser`
      --

      CREATE TABLE dev_banner_advertiser (
        id int(10) unsigned NOT NULL auto_increment,
        name text,
        PRIMARY KEY  (id)
      ) TYPE=MyISAM;
    SQL

    execute <<-SQL
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
    SQL

    execute <<-SQL
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
    SQL
  end
end