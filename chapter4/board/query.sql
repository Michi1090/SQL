create table users (
  id int not null auto_increment,
  username varchar(20) not null,
  password varchar(20) not null,
  mail varchar(50) null,
  primary key (id)
) charset = utf8 collate utf8_unicode_ci;

create table messages (
  id int not null auto_increment,
  user_id int not null,
  message varchar(255) not null,
  posted timestamp not null default current_timestamp,
  primary key (id)
) charset = utf8 collate utf8_unicode_ci;
