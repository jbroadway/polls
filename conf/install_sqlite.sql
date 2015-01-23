create table #prefix#polls (
	id integer primary key,
	title varchar(48) not null,
	question varchar(140) not null,
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
create index #prefix#polls_fallback on #prefix#polls (fallback);
create index #prefix#polls_visible on #prefix#polls (visible);

create table #prefix#polls_votes (
	id integer primary key,
	poll_id integer not null,
	ts datetime not null,
	ip varchar(15) not null,
	user_id integer,
	votes text not null
);
create index #prefix#polls_id on #prefix#polls_votes (poll_id, ts);
