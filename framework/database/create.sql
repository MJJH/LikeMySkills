/*
	DATABASE CREATE - likeMySkills FRAMEWORK
*/
drop table if exists sr_media
drop table if exists sr_mediatype
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

CREATE TABLE sr_mediatype(
    typeid integer AUTO_INCREMENT,
    name varchar(255) not null,
    
    constraint pk_sr_mediatype
    PRIMARY KEY (typeid)
);

CREATE TABLE sr_media(
    mediaid integer AUTO_INCREMENT,
    uploader integer not null,
    content integer not null,
    type integer not null,
    path text,
	size bigint,
	extension varchar(255),
    
    constraint pk_sr_media
    PRIMARY KEY (mediaid),
    
    constraint fk_sr_media_user
    FOREIGN KEY (uploader)
    REFERENCES sr_user(userid),
    
    CONSTRAINT fk_sr_media_mediatype
    FOREIGN KEY (type)
    REFERENCES sr_mediatype(typeid),
    
    CONSTRAINT fk_sr_media_content
    foreign key (content)
    REFERENCES sr_content(contentid)
);