-- Portable script for creating the pizza database
-- on your dev system:
-- mysql -u root -p < dev_setup.sql    
-- mysql -D pizzadb -u root -p < createdb.sql 
--  or, on topcat:
-- mysql -D <user>db -u <user> -p < createdb.sql 
create table shop_users (
id integer auto_increment,
username varchar(20),
room integer not null,
primary key (id),
unique (username)
);

create table menu_sizes(
id integer,
size varchar(30) not null,
diameter int not null,
unique (size),
primary key(id)
);

-- is meat: 1 for meat toppings, 0 if not
create table menu_toppings(
id integer auto_increment,
topping varchar(30) not null,
is_meat int not null,
unique (topping),
primary key(id)
);

create table status_values (
status_value varchar(10) primary key
);

create table pizza_orders(
id integer auto_increment,
user_id integer,
size varchar(30) not null,
day integer not null,
status varchar(10),
foreign key (user_id) references shop_users(id),
foreign key (status) references status_values(status_value),
primary key(id)
);

-- toppings for a pizza order
-- Note: we can't use a foreign key to menu_toppings here because the topping
-- might be deleted while the order is still in the system
-- We don't need to know if the topping is meat or not once ordered
create table order_topping (
order_id integer not null,
topping varchar(30) not null,
primary key (order_id, topping),
foreign key (order_id) references pizza_orders(id));

-- one-row table doesn't need a primary key
create table pizza_sys_tab (
current_day integer not null
);

insert into pizza_sys_tab values (1);
-- minimal toppings and sizes on the menu: two each
insert into menu_toppings values (1,'Pepperoni', 1);
insert into menu_toppings values (2,'Onions', 0);
insert into menu_sizes values (1,'Small', 12);
insert into menu_sizes values (2,'Large', 16);
insert into status_values values ('Preparing');
insert into status_values values ('Baked');
insert into status_values values ('Finished');
insert into shop_users values (1, 'joe', 6);
insert into shop_users values (2, 'sue', 3);

