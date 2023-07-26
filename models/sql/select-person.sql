SELECT
  p.id,
  firstName,
  lastName,
  address,
  c.name city_name,
  c.zipCode city_zipCode,
  c.country city_country,
  isMale
FROM
  person p
  JOIN city c ON c.id = p.cityId