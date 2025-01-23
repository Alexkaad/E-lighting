create table eligthing.products
(
    id          int auto_increment
        primary key,
    name        varchar(255)                         not null,
    price       decimal(10, 2)                       not null,
    description text                                 null,
    reference   varchar(255)                         null,
    createur_id int                                  null,
    sku         varchar(255)                         null,
    created_at  datetime default current_timestamp() null,
    updated_at  datetime                             null on update current_timestamp(),
    brand_id    int                                  null,
    category_id int                                  null,
    constraint products_ibfk_1
        foreign key (createur_id) references eligthing.createurs (id)
            on delete cascade,
    constraint products_ibfk_2
        foreign key (brand_id) references eligthing.brands (id)
            on delete cascade,
    constraint products_ibfk_3
        foreign key (category_id) references eligthing.categorys (id)
            on delete cascade
);

create index brand_id
    on eligthing.products (brand_id);

create index category_id
    on eligthing.products (category_id);

create index createur_id
    on eligthing.products (createur_id);

create index idx_product_created_at
    on eligthing.products (created_at);

create index idx_product_name
    on eligthing.products (name);

create index idx_product_price
    on eligthing.products (price);

