-- tables
CREATE SCHEMA log;

CREATE TYPE job_statuses AS ENUM (
	'pending',
	'processing',
	'succeed',
	'failed'
);

CREATE TABLE log.cron_jobs (
	id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
	marked_at timestamptz NOT NULL DEFAULT now(),
	name text NOT NULL,
	self_id integer,
	status job_statuses NOT NULL,
	CONSTRAINT cron_jobs_id_fk FOREIGN KEY (self_id) REFERENCES log.cron_jobs(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE FUNCTION cron_jobs_trigger_row_bi() RETURNS trigger AS $BODY$
BEGIN
	IF (new.status = 'processing' AND (
		SELECT status NOT IN ('succeed', 'failed')
		FROM log.cron_jobs
		WHERE name = new.name
		ORDER BY id DESC
		LIMIT 1
		)
	) THEN
		RAISE EXCEPTION USING MESSAGE = format('Job "%s" can not be run, because previous is not fulfilled.', new.name);
	END IF;

	RETURN new;
END;
$BODY$ LANGUAGE plpgsql VOLATILE;

CREATE TRIGGER cron_jobs_row_bi_trigger
	BEFORE INSERT
	ON log.cron_jobs
	FOR EACH ROW EXECUTE PROCEDURE cron_jobs_trigger_row_bi();
