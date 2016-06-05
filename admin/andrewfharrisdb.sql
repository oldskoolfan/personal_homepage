drop database if exists andrewfharrisdb;
create database andrewfharrisdb;
use andrewfharrisdb;

create table albums(
	album_id int unsigned auto_increment primary key,
    album_title nvarchar(255),
    album_desc text,
    credits text,
    release_year year,
    label nvarchar(50),
    date_created datetime default current_timestamp,
    date_modified timestamp
);

create table songs(
	song_id int unsigned auto_increment primary key,
    album_id int unsigned,
    song_title nvarchar(255),
    recorded_date date,
    audio longblob,
    file_type nvarchar(20),
    credits text,
    date_created datetime default current_timestamp,
    date_modified timestamp,
    foreign key albums_songs_fk(album_id) references albums(album_id)
		on delete cascade
);

create table thoughts(
	thought_id int unsigned auto_increment primary key,
    thought_title nvarchar(255),
    thought_text longtext,
    date_created datetime default current_timestamp,
    date_modified timestamp
);

create table images(
	image_id int unsigned auto_increment primary key,
    album_id int unsigned, # could be an album title
    thought_id int unsigned, # or a blog image
	image_title nvarchar(255),
    image_type nvarchar(20),
    image_data longblob,
    date_created datetime default current_timestamp,
    date_modified timestamp,
    foreign key albums_image_fk(album_id) references albums(album_id)
		on delete cascade,
	foreign key thoughts_images_fk(thought_id) references thoughts(thought_id)
		on delete cascade
);

