create table sample_table (
  id int not null auto_increment,
  name varchar(20) not null,
  message varchar(255) not null,
  primary key(id)
  )
engine = innodb
default charset = utf8;

insert into sample_table (name, message) values('taro', 'Hello');
insert into sample_table (name, message) values('hanako', 'こんにちは');
insert into sample_table (name, message) values('sahiko', 'Welcome to MySQL.');

select * from sample_table;
select id, name from sample_table;

drop table sample_table;

select * from customers where id = 1;

update customers set corp = "Shuwa System Inc." where id = 1;
update customers set corp = "トヨタ自動車" where id = 2;

select * from customers where address like "東京都%";

select * from customers where id > 1 and id < 4;
select * from customers where address like "東京%" or address like "愛知%"  or address like "京都%";
select * from customers where id > 1 xor id < 4;

alter table customers add created datetime;
alter table customers change created modified timestamp;
alter table customers drop modified;

start transaction;
insert into customers (corp, staff) value ("中村製作所", "中村主水");
update customers set mail = "mondo@nakamura" where staff = "中村主水";
update customers set tel = "000-0000-0000" where staff = "中村主水";
update customers set address = "江戸北町奉行所内" where staff = "中村主水";
commit;

create table products (
  id int not null auto_increment,
  name varchar(50) not null,
  price int,
  primary key (id)
) engine = innodb
default charset = utf8;

create table orders (
  id int not null auto_increment,
  products_id int not null,
  customer_id int not null,
  quantity int not null,
  primary key (id)
) engine = innodb
default charset = utf8;

insert into products (name, price) values ("自立式NC工作機", 2500);
insert into products (name, price) values ("A.I.学習型ロボット", 700);
insert into products (name, price) values ("全自動溶接マシン", 1280);

insert into orders (products_id, customer_id, quantity) value(1, 1, 10);
insert into orders (products_id, customer_id, quantity) value(1, 2, 10);
insert into orders (products_id, customer_id, quantity) value(1, 3, 3);
insert into orders (products_id, customer_id, quantity) value(2, 1, 20);
insert into orders (products_id, customer_id, quantity) value(1, 2, 1);
insert into orders (products_id, customer_id, quantity) value(3, 3, 3);

select * from orders join products on orders.product_id = products.id;
alter table orders change products_id product_id int;

select orders.id, customer_id, name, price, quantity
from orders
join products on orders.product_id = products.id;

select orders.id, product_id, corp, staff, quantity
from orders
join customers on orders.customer_id = customers.id;

select orders.id, name, price, corp, staff, quantity
from orders
join customers
join products
on orders.product_id = products.id
and orders.customer_id = customers.id;

select customers.id, corp, staff, quantity
from customers
left join orders
on orders.customer_id = customers.id;

select customers.id, corp, staff, quantity
from orders
right join customers
on orders.customer_id = customers.id;

select price * quantity
from orders
join products
on orders.product_id = products.id;

select floor(price * 1.08), floor(price * 1.08) * quantity
from orders
join products
on orders.product_id = products.id;

select @p := floor(price * 1.08), @p * quantity
from orders
join products
on orders.product_id = products.id;

select @n := ceiling(rand() * 5);
select id, corp, staff from customers where id = @n;

select @n := ceiling(rand() * 10);
select if (@n >0 and @n < 5, @n, @n := 1);
select id, corp, staff from customers where id = @n;

select @n := ceiling(rand() * 3);
select @genre := case @n
when 1 then "orders"
when 2 then "customers"
when 3 then "products"
else "NOT SELECTED."
end;
select @genre;

delimiter //
create procedure randomdata()
begin
set @n := ceiling(rand() * 3);
set @genre := case @n
when 1 then "orders"
when 2 then "customers"
when 3 then "products"
else "NOT SELECTED."
end;
select @genre;
end//
delimiter ;

delimiter //
create procedure randomrecord(genre varchar(10))
begin
  case genre
    when "orders" then
    begin
      select @n := count(*) from orders;
      set @r := ceiling(rand() * @n);
      select * from orders where id = @r;
    end;
    when "customers" then
    begin
      select @n := count(*) from customers;
      set @r := ceiling(rand() * @n);
      select * from customers where id = @r;
    end;
    when "products" then
    begin
      select @n := count(*) from products;
      set @r := ceiling(rand() * @n);
      select * from products where id = @r;
    end;
    else select "NOT SELECTED." ;
  end case;
end //
delimiter ;

call randomrecord("orders");
call randomrecord("products");
call randomrecord("customers");

delimiter //
create function taxprice(num int) returns int
begin
declare sel_p int;
  set sel_p = (select price from products where id = num);
  return floor(sel_p * 1.08);
end//
delimiter ;

select name, price, taxprice(1) from products where id=1;
