CREATE
OR REPLACE VIEW view_competition_accomodation AS
SELECT
  CA.competition_id,
  CA.accomodation_id,
  A.accomodation_name_fin,
  A.accomodation_name_eng
FROM
  accomodations AS A
  INNER JOIN competition_accomodations AS CA ON CA.accomodation_id = A.accomodation_id