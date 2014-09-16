create table polls (
	id integer primary key,
	title varchar(48) not null,
	desc varchar(140) not null,
	created datetime not null,
	creator integer not null,
	edited datetime not null,
	editor integer not null,
	allowed integer not null default 1,
	required integer not null default 1,
	votable boolean not null default 0,
	visible boolean not null default 0,
	fallback boolean default 0,
	options text not null
);
create index poll_title on polls (title);
create index poll_ts on polls (ts);

create table poll_votes (
	id integer primary key,
	poll_id integer not null,
	ts datetime not null,
	ip varchar(15) not null,
	user_id integer,
	results text not null
);
create index poll_id on poll_votes (poll_id, ts);
