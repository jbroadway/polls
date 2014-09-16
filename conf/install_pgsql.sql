create table polls (
	id serial not null primary key,
	title character varying(48) not null,
	desc character varying(140) not null,
	created timestamp not null,
	creator integer not null,
	edited timestamp not null,
	editor integer not null,
	allowed integer not null default 1,
	required integer not null default 1,
	votable boolean not null default FALSE,
	visible boolean not null default FALSE,
	fallback boolean default FALSE,
	options text not null
);
create index polls_fallback on polls (fallback);
create index polls_visible on polls (visible);

create table polls_votes (
	id serial not null primary key,
	poll_id integer not null,
	ts timestamp not null,
	ip character varying(15) not null,
	user_id integer,
	results text not null
);
create index polls_id on polls_votes (poll_id, ts);