-- Таблица пользователей --
create table if not exists `users` (
    `id` int(10) unsigned not null auto_increment,
    `name` varchar(255) not null,
    `email` varchar(255) not null,
    `password` varchar(255) not null,
    `sessid` varchar(255) not null,
    `create_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    primary key (id)
)
engine = innodb
auto_increment = 1
character set utf8
collate utf8_general_ci;