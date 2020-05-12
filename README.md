## SQL à lancer sur la base de données
create table messages (
id int auto_increment primary key,
pseudo varchar(100) not null,
message text not null,
creationDate datetime null,
status varchar(32) null,
replyTo int null,
class varchar(32) null,
constraint messages_messages_id_fk
foreign key (replyTo) references messages (id)
);

## données à modifier
dans data.php, remplacer :
- 'NAME_ADMIN' par ton pseudo admin
- 'PASSWORD_ADMIN' par ton mot de passe admin
- 'CLASS' par le nom de classe de ton instance in(< 32 char)