

-- création du compte administrateur, email = jose-zoo@arcadia.com mdp 'LeZooArcadia@01'

INSERT INTO user (username, email, password, roles) VALUE 
      ('José', 'jose-zoo@arcadia.com', '$2y$13$b6YUW.z4qRSUGexB7qzUHOGTdrWbXvZowx.DHTZNdNvoF8MT53dNq', '["ROLE_ADMIN"]');

INSERT INTO user (username, email, password, roles) VALUE 
      ('Employee', 'employee@arcadia.com', '$2y$13$b6YUW.z4qRSUGexB7qzUHOGTdrWbXvZowx.DHTZNdNvoF8MT53dNq', '["ROLE_EMPLOYEE"]');

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
                      ('Éteinte à l\'état sauvage'),
                      ('Éteinte au niveau mondiale');

-- création des régions
INSERT INTO region (region) VALUES ('Asie'),
                          ('Afrique'),
                          ('Europe'),
                          ('Océanie'),
                          ('Amérique du Nord'),
                          ('Amérique du Sud');

-- création de la lignes pour les horaires
INSERT INTO schedules (schedules) VALUES ('');