create table eligthing.product_images
(
    id         int auto_increment
        primary key,
    product_id int                                    not null,
    image_path varchar(255)                           not null,
    is_primary tinyint(1) default 0                   null,
    created_at timestamp  default current_timestamp() not null,
    updated_at timestamp  default current_timestamp() not null on update current_timestamp(),
    constraint product_images_ibfk_1
        foreign key (product_id) references eligthing.products (id)
            on delete cascade
);

create index product_id
    on eligthing.product_images (product_id);

