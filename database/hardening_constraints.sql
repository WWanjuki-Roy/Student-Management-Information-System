-- Duplicate-prevention constraints for SIMS
-- Run this after confirming there are no duplicate rows in existing data.

ALTER TABLE unit_registrations
ADD CONSTRAINT uniq_unit_registration UNIQUE (student_id, unit_id);

ALTER TABLE attendance
ADD CONSTRAINT uniq_attendance_per_day UNIQUE (student_id, unit_id, date);

ALTER TABLE results
ADD CONSTRAINT uniq_result_per_unit UNIQUE (student_id, unit_id);
