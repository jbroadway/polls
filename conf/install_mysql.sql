create table #prefix#polls (
	id int not null auto_increment primary key,
	title varchar(48) not null,
	question varchar(140) not null,
	created datetime not null,
	creator int not null,
	edited datetime not null,
	editor int not null,
	allowed int not null default 1,
	required int not null default 1,
	votable boolean not null default FALSE,
	visible boolean not null default FALSE,
	fallback boolean default FALSE,
	options text not null,
	index (visible),
	index (fallback)
);

create table #prefix#polls_votes (
	id int not null auto_increment primary key,
	poll_id int not null,
	ts datetime not null,
	ip varchar(15) not null,
	user_id int,
	votes text not null,
	index (poll_id, ts)
);
