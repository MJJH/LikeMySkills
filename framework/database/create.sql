/*
	DATABASE CREATE - likeMySkills FRAMEWORK
*/

DROP TABLE IF EXISTS sr_content;
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
