create table eligthing.carts
(
    id              int auto_increment
        primary key,
    user_id         int                                                                   null,
    session_id      varchar(255)                                                          null,
    status          enum ('active', 'completed', 'abandoned') default 'active'            not null,
    total_price     decimal(10, 2)                                                        null,
    currency        varchar(3)                                default 'USD'               null,
    discount_code   varchar(50)                                                           null,
    discount_amount decimal(10, 2)                                                        null,
    tax_amount      decimal(10, 2)                                                        null,
    shipping_method varchar(255)                                                          null,
    shipping_cost   decimal(10, 2)                                                        null,
    notes           text                                                                  null,
    is_guest        tinyint(1)                                default 0                   null,
    abandoned_at    timestamp                                 default current_timestamp() not null on update current_timestamp(),
    created_at      timestamp                                 default current_timestamp() not null,
    updated_at      timestamp                                 default current_timestamp() not null on update current_timestamp(),
    constraint carts_ibfk_1
        foreign key (user_id) references eligthing.users (id)
            on delete cascade
);

create index user_id
    on eligthing.carts (user_id);

