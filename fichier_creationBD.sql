create schema hockey_player;

use hockey_player;

create table stats_joueur_actif(
	NOM_JOUEUR varchar(80),
	AGE_JOUEUR int,
    POSITION_JOUEUR varchar(30),
    SAISON varchar(20),
    EQUIPE varchar(20),
    PARTIE_JOUER int,
    BUT int,
    ASSIST int,
    POINTS int,
    PLUSMINUS int,
    MINPENALITE int);
    
SET SQL_SAFE_UPDATES = 0;
    
insert into stats_joueur_actif select Joueur, Age,  Pos, Season, Team,  GP, G, A, PTS, Plusminus, PIM 
from stats_1967_2018 d where exists (select * from stats_2018_19 j where j.Joueur = d.Joueur);

delete from hockey_player.stats_joueur_actif where SAISON = '2017-18';

insert into stats_joueur_actif select Joueur, Age, Pos, '2017-18', Team, GP, G, A, PTS, Plusminus, PIM
from stats_2017_18 d where exists (select * from stats_2018_19 j where j.Joueur = d.Joueur);

insert into stats_joueur_actif select Joueur, Age, Pos, '2018-19',  Team, GP, G, A, PTS, Plusminus, PIM
from stats_2018_19;

create schema user_pool;

use user_pool;


create table user(
	USER_NAME varchar(200),
	USER_PASSWORD varchar(200),
    USER_EMAIL varchar(320));
    
    
    
    
    
    