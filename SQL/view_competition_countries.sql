CREATE
OR REPLACE VIEW view_competition_countries AS
SELECT
  CC.competition_id,
  C.country_id,
  C.country_name_fin,
  C.country_name_eng
FROM
  competition_countries AS CC
  INNER JOIN countries AS C ON C.country_id = CC.country_id