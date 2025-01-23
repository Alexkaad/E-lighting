create table eligthing.users
(
    id        int auto_increment
        primary key,
    firstname varchar(255)                         not null,
    name      varchar(255)                         not null,
    email     varchar(255)                         not null,
    password  varchar(255)                         not null,
    telephone varchar(255)                         null,
    role      varchar(255)                         null,
    createAt  datetime default current_timestamp() null,
    genre     varchar(255)                         null,
    updateAt  datetime default current_timestamp() null on update current_timestamp(),
    constraint email
        unique (email)
);

