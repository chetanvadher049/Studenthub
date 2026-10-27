-- Back up studenthub_db before applying this migration.
-- Run once for databases created from a schema that does not yet have events.organizer.
ALTER TABLE events
    ADD COLUMN organizer VARCHAR(150) NULL AFTER category;
