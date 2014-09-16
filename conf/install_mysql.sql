create table polls (
	id int not null auto_increment primary key,
	title varchar(48) not null,
	desc varchar(140) not null,
	created datetime not null,
	creator int not null,
	edited datetime not null,
	editor int not null,
	allowed int not null default 1,
	required int not null default 1,
	votable boolean not null default FALSE,
	visible boolean not null default FALSE,
	fallback boolean default FALSE,
	options text not null
	index (title),
	index (ts)
);

create table poll_votes (
	id int not null auto_increment primary key,
	poll_id int not null,
	ts datetime not null,
	ip varchar(15) not null,
	user_id int,
	results text not null,
	index (poll_id, ts)
);
