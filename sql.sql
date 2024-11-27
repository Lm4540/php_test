create table catalog(
    id int unsigned primary key,
    local_image varchar(255),
    last_update varchar(50),
    active tinyint(1) default 1
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

create table catalog_detail(
    catalog int unsigned not null,
    product int unsigned not null,
    CONSTRAINT pk_catalog_product Primary key (catalog, product)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

create table product(
    product int unsigned primary key,
    local_image varchar(255),
    price decimal(12,2),
    discount_price decimal(12,2),
    description varchar(500),
    sku varchar(50),
    _code varchar(50)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

create table product_relation(
    product int unsigned,
    product2 int unsigned,
    CONSTRAINT pk_product_product Primary key (product, product2)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

