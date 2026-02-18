CREATE
OR REPLACE VIEW view_competition_classes AS
SELECT
  CC.competition_id,
  C.class_id,
  C.class_name_eng,
  C.class_name_fin
FROM
  classes AS C
  INNER JOIN competition_classes AS CC ON C.class_id = CC.class_id