
CREATE TABLE XOOPS_subscription2_type (
	subtypeid int(11) not null auto_increment ,
	primary key(subtypeid),
	psid int(11) default '0',
	type varchar(50) not null,
	groupid int(11) default '0'
) TYPE=MyISAM;

CREATE TABLE XOOPS_subscription2_interval (
  subintervalid int(11) NOT NULL auto_increment,
  name varchar(25) NOT NULL,
  intervalamount int(11) default 0,
  intervaltype char(1) NOT NULL,
  orderbit int(2) NOT NULL default 0,
  PRIMARY KEY  (subintervalid)
) TYPE=MyISAM;

CREATE TABLE XOOPS_subscription2 (
  subid int(11) NOT NULL auto_increment,
  name varchar(100) NOT NULL default '',
  subtypeid int(11) not null,
  subintervalid int(11) NOT NULL default '0',
  price decimal(7,2) default '0.00',
  orderbit int(2) default '0',
  PRIMARY KEY  (subid)
) TYPE=MyISAM;

CREATE TABLE XOOPS_subscription2_transaction (
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
) TYPE=MyISAM;

CREATE TABLE XOOPS_subscription2_user (
	id int not null primary key auto_increment,
	subid int(11) not null,
	uid int(11) not null,
	expiration_date datetime NOT NULL default '0000-00-00 00:00:00',
	intervaltype char(1),
	intervalamount smallint,
	amount decimal(7,2),
	cancel char(1) not null default 'N'
) TYPE=MyISAM;

CREATE TABLE XOOPS_sequences2 (
	sequencename varchar(50) not null primary key,
	nextval int not null);


DELETE FROM XOOPS_SUBSCRIPTION;

INSERT INTO XOOPS_SUBSCRIPTION SELECT * FROM XOOPS_SUBSCRIPTION2;

DELETE FROM XOOPS_SUBSCRIPTION_TYPE;

INSERT INTO XOOPS_SUBSCRIPTION_TYPE SELECT * FROM XOOPS_SUBSCRIPTION2_TYPE;

DELETE FROM XOOPS_SUBSCRIPTION_INTERVAL;

INSERT INTO XOOPS_SUBSCRIPTION_INTERVAL SELECT * FROM
XOOPS_SUBSCRIPTION2_INTERVAL;

DELETE FROM XOOPS_SUBSCRIPTION_USER;

INSERT INTO XOOPS_SUBSCRIPTION_USER SELECT * FROM XOOPS_SUBSCRIPTION2_USER;

DELETE FROM XOOPS_SUBSCRIPTION_TRANSACTION;

INSERT INTO XOOPS_SUBSCRIPTION_TRANSACTION 
  SELECT id, uid, subid, cardnumber, cvv, issuerphone,
	    expirationmonth, expirationyear, nameoncard, address,
			    city, state, country, zipcode, referencenumber, responsecode,
					    response, authcode, amount, transactiondate, 'S' FROM
							XOOPS_SUBSCRIPTION2_TRANSACTION;

INSERT INTO XOOPS_SEQUENCES SELECT * FROM XOOPS_SEQUENCES2;

DROP TABLE XOOPS_SEQUENCES2;
DROP TABLE XOOPS_SUBSCRIPTION2_TYPE;
DROP TABLE XOOPS_SUBSCRIPTION2_INTERVAL;
DROP TABLE XOOPS_SUBSCRIPTION2_USER;
DROP TABLE XOOPS_SUBSCRIPTION2_TRANSACTION;
DROP TABLE XOOPS_SUBSCRIPTION2;

