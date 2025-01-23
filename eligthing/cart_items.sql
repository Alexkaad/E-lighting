create table eligthing.cart_items
(
    id              int auto_increment
        primary key,
    cart_id         int                                   not null,
    product_id      int                                   not null,
    quantity        int       default 1                   not null,
    price           decimal(10, 2)                        not null,
    total_price     decimal(10, 2) as (`price` * `quantity`) stored,
    attributes      longtext collate utf8mb4_bin          null
        check (json_valid(`attributes`)),
    discount_amount decimal(10, 2)                        null,
    added_at        timestamp default current_timestamp() not null,
    updated_at      timestamp default current_timestamp() not null on update current_timestamp(),
    constraint cart_items_ibfk_1
        foreign key (cart_id) references eligthing.carts (id)
            on delete cascade,
    constraint cart_items_ibfk_2
        foreign key (product_id) references eligthing.products (id)
            on delete cascade
);

create index cart_id
    on eligthing.cart_items (cart_id);

create index product_id
    on eligthing.cart_items (product_id);

