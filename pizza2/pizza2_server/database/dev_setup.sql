-- Setup for development machine
-- Create pizzadb, or recreate it
-- Create a user for it
drop database if exists pizzadb; -- only for your server
create database pizzadb; -- only for your own server

--User now needs alter privs
GRANT SELECT, INSERT, DELETE, UPDATE, ALTER
ON pizzadb.*
TO pizza_user@localhost
IDENTIFIED BY 'pa55word';

