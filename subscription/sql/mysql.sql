
CREATE TABLE subscription_type (
	subtypeid int(11) not null auto_increment ,
	primary key(subtypeid),
	psid int(11) default '0',
	type varchar(50) not null,
	groupid int(11) default '0'
) ENGINE=MyISAM;

insert into subscription_type values(1, 0, 'Basic',4);
insert into subscription_type values(2, 0, 'Premium',5);

CREATE TABLE subscription_interval (
  subintervalid int(11) NOT NULL auto_increment,
  name varchar(25) NOT NULL,
  intervalamount int(11) default 0,
  intervaltype char(1) NOT NULL,
  orderbit int(2) NOT NULL default 0,
  PRIMARY KEY  (subintervalid)
) ENGINE=MyISAM;

INSERT INTO subscription_interval VALUES (1,'Monthly',1,'m',0);
INSERT INTO subscription_interval VALUES (2,'Yearly',12,'m',2);

CREATE TABLE subscription (
  subid int(11) NOT NULL auto_increment,
	alternatesubid varchar(50) null,
  name varchar(100) NOT NULL default '',
  subtypeid int(11) not null,
  subintervalid int(11) NOT NULL default '0',
  price decimal(7,2) default '0.00',
  orderbit int(2) default '0',
  PRIMARY KEY  (subid)
) ENGINE=MyISAM;

INSERT INTO subscription VALUES (2, null, 'Monthly Premium',2,1, 14.95,2);
INSERT INTO subscription VALUES (1, null, 'Yearly Basic',1,2,75.95,1);
INSERT INTO subscription VALUES (3, null, 'Yearly Premium',2,2, 129.95,3);

CREATE TABLE subscription_transaction (
  id int(11) NOT NULL auto_increment,
  uid int(11) NOT NULL default '0',
  subid int(11) NOT NULL default '0',
  cardnumber varchar(20) NOT NULL default '',
  cvv varchar(4) not null,
  issuerphone varchar(50),
  expirationmonth char(2) NOT NULL default '',
  expirationyear varchar(4) NOT NULL default '',
  nameoncard varchar(50) NOT NULL default '',
  address varchar(50) NOT NULL default '',
  city varchar(50) NOT NULL default '',
  state char(2) default NULL,
  country char(2) default NULL,
  zipcode varchar(15) default NULL,
  referencenumber varchar(50) NOT NULL default '',
  responsecode smallint,
	response varchar(255),
  authcode varchar(50) NOT NULL default '',
	amount decimal(7,2) NOT NULL,
  transactiondate datetime NOT NULL default '0000-00-00 00:00:00',
	transactiontype char(1) not null default 'S',
  PRIMARY KEY  (id)
) ENGINE=MyISAM;

CREATE TABLE subscription_user (
	id int not null primary key auto_increment,
	subid int(11) not null,
	uid int(11) not null,
	expiration_date datetime NOT NULL default '0000-00-00 00:00:00',
	intervaltype char(1),
	intervalamount smallint,
	amount decimal(7,2),
	cancel char(1) not null default 'N'
) ENGINE=MyISAM;

create table subscription_gateway_config (
	gateway varchar(25) not null,
	name varchar(50) not null,
	value varchar(150),
	title varchar(150),
	orderbit smallint,
	primary key (gateway, name)
) ENGINE=MyISAM;

CREATE TABLE sequences (
	sequencename varchar(50) not null primary key,
	nextval int not null);

insert into sequences values('subscription_transaction_seq', 1);


