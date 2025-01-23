create table eligthing.categorys
(
    id       int auto_increment
        primary key,
    name     varchar(255)                         null,
    createAt datetime default current_timestamp() null,
    updateAt datetime default current_timestamp() null on update current_timestamp()
);

