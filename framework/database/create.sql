/*
	DATABASE CREATE - likeMySkills FRAMEWORK
*/
drop table if exists sr_follow;
drop table if exists sr_content;
drop table if exists sr_user;
drop table if exists sr_permission;

create table sr_permission(
    role varchar(255),
    `write` boolean DEFAULT '0',
    `read`boolean DEFAULT '0',
    
    constraint pk_sr_permission
    primary key (role)
);



create table sr_user(
    userid integer AUTO_INCREMENT,
    username varchar(255),
    password varchar(255),
    email varchar(255),
    role varchar(255) default 'user',
	registered timestamp,
	`status` varchar(30) default 'Inactive',
    
    constraint pk_sr_user
    primary key (userid),
    
    CONSTRAINT fk_sr_user_sr_permission
    FOREIGN KEY (role)
    REFERENCES sr_permission(role)
);



CREATE TABLE sr_content(
    contentid integer AUTO_INCREMENT,
    type varchar(30),
    title varchar(120) not null,
    date timestamp,
    content text,
    author integer not null,
    
    constraint pk_sr_content
    PRIMARY KEY (contentid),
    
    CONSTRAINT fk_sr_content_sr_user
    FOREIGN KEY (author)
    REFERENCES sr_user(userid)
);

create table sr_follow(
	followid integer auto_increment,
	userid integer not null,
	authorid integer not null,
	date timestamp,
	following boolean,
	
	constraint pk_sr_follow
	primary key (followid),
	
	constraint fk_sr_follow_userid
	foreign key (userid)
	references sr_user(userid),
	
	constraint fk_sr_follow_authorid
	foreign key (authorid)
	references sr_user(userid)
);
