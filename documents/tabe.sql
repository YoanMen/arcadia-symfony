


-- Création des options pour le UICN.
INSERT INTO uicn (uicn) VALUES ('non évaluée'),
                      ('Non applicable'), 
                      ('Données insuffisantes'), 
                      ('Préoccupation mineure'),
                      ('Quasi menacée'),
                      ('Vulnérable'), 
                      ('En danger'),
                      ('En danger critique'),
                      ('Disparue au niveau régional'),
                      ('Eteinte à l\'état sauvage'),
                      ('Eteinte au niveau mondiale');

INSERT INTO region (region) VALUES ('Asie'),
                          ('Afrique'),
                          ('Europe'),
                          ('Océanie'),
                          ('Amérique du Nord'),
                          ('Amérique du Sud');


INSERT INTO schedules (schedules) VALUES ('');