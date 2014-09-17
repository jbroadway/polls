create table #prefix#polls (
	id serial not null primary key,
	title character varying(48) not null,
	question character varying(140) not null,
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
create index #prefix#polls_fallback on #prefix#polls (fallback);
create index #prefix#polls_visible on #prefix#polls (visible);

create table #prefix#polls_votes (
	id serial not null primary key,
	poll_id integer not null,
	ts timestamp not null,
	ip character varying(15) not null,
	user_id integer,
	votes text not null
);
create index #prefix#polls_id on #prefix#polls_votes (poll_id, ts);