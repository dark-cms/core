-- Create the default accounts
REPLACE INTO xlite_profiles (profile_id, login, password, access_level, order_id, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status, cms_profile_id, cms_name) VALUES (1,'rnd_tester@cdev.ru','eb0a191797624dd3a48fa681d3061212',100,0,'Mr.','Guest','Guest','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Guest','Guest','9876543210','Shipping street, 1','New York',34,'US','10001',1053689339,1058449247,'E',1,'____DRUPAL____');


