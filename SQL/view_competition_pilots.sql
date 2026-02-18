CREATE
OR REPLACE VIEW view_competition_pilots AS
SELECT
  P.pilot_id,
  P.pilot_first_name,
  P.pilot_last_name,
  P.pilot_phone,
  P.pilot_email,
  P.pilot_club,
  P.plane_class,
  P.pilot_accomodation,
  P.pilot_other_info,
  P.pilot_entry_fee,
  P.plane_type,
  P.plane_register,
  P.plane_competition_sign,
  P.plane_wingspan,
  P.plane_winglets,
  P.plane_engine,
  P.plane_flarm_id,
  P.plane_logger_one,
  P.plane_logger_two,
  P.pilot_link_id,
  P.entry_time,
  VCC.class_id,
  VCC.competition_id,
  VCC.class_name_fin,
  VCC.class_name_eng,
  A.accomodation_name_fin,
  A.accomodation_name_eng
FROM
  pilots AS P
  INNER JOIN view_competition_classes AS VCC ON P.plane_class = VCC.class_id
  INNER JOIN accomodations AS A ON P.pilot_accomodation = A.accomodation_id