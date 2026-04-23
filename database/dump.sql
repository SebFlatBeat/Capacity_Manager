--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'SQL_ASCII';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: absences_sprints; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE absences_sprints (
    id_membre integer NOT NULL,
    pi_code character varying(20) NOT NULL,
    numero_iteration integer NOT NULL,
    jours_conges numeric(4,1) DEFAULT 0
);


ALTER TABLE public.absences_sprints OWNER TO "sebastien.darre";

--
-- Name: affectations_mco; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE affectations_mco (
    id_mco integer NOT NULL,
    id_membre integer NOT NULL,
    id_sprint integer NOT NULL,
    commentaire character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.affectations_mco OWNER TO "sebastien.darre";

--
-- Name: affectations_mco_id_mco_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE affectations_mco_id_mco_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.affectations_mco_id_mco_seq OWNER TO "sebastien.darre";

--
-- Name: affectations_mco_id_mco_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE affectations_mco_id_mco_seq OWNED BY affectations_mco.id_mco;


--
-- Name: affectations_mco_id_mco_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('affectations_mco_id_mco_seq', 22, true);


--
-- Name: affectations_tra; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE affectations_tra (
    id_tra integer NOT NULL,
    id_membre integer NOT NULL,
    id_sprint integer NOT NULL,
    nb_semaines integer NOT NULL
);


ALTER TABLE public.affectations_tra OWNER TO "sebastien.darre";

--
-- Name: affectations_tra_id_tra_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE affectations_tra_id_tra_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.affectations_tra_id_tra_seq OWNER TO "sebastien.darre";

--
-- Name: affectations_tra_id_tra_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE affectations_tra_id_tra_seq OWNED BY affectations_tra.id_tra;


--
-- Name: affectations_tra_id_tra_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('affectations_tra_id_tra_seq', 23, true);


--
-- Name: equipes; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE equipes (
    id_equipe integer NOT NULL,
    nom_equipe character varying(50) NOT NULL
);


ALTER TABLE public.equipes OWNER TO "sebastien.darre";

--
-- Name: equipes_id_equipe_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE equipes_id_equipe_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.equipes_id_equipe_seq OWNER TO "sebastien.darre";

--
-- Name: equipes_id_equipe_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE equipes_id_equipe_seq OWNED BY equipes.id_equipe;


--
-- Name: equipes_id_equipe_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('equipes_id_equipe_seq', 2, true);


--
-- Name: historique_pi; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE historique_pi (
    id integer NOT NULL,
    pi_code character varying(20) NOT NULL,
    total_pts numeric(10,2) NOT NULL,
    build_pts numeric(10,2) NOT NULL,
    apollo_pts numeric(10,2),
    disco_pts numeric(10,2),
    allstars_pts numeric(10,2),
    ordre integer NOT NULL,
    mco_pts numeric(6,2) DEFAULT 0,
    tra_pts numeric(6,2) DEFAULT 0,
    anomalies_build_pts numeric(6,2) DEFAULT 0,
    statut character varying(20) DEFAULT 'PLANNING'::character varying,
    date_debut date,
    date_fin date,
    iterations integer DEFAULT 4,
    jours_par_iteration integer DEFAULT 15
);


ALTER TABLE public.historique_pi OWNER TO "sebastien.darre";

--
-- Name: historique_pi_id_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE historique_pi_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.historique_pi_id_seq OWNER TO "sebastien.darre";

--
-- Name: historique_pi_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE historique_pi_id_seq OWNED BY historique_pi.id;


--
-- Name: historique_pi_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('historique_pi_id_seq', 9, true);


--
-- Name: jours_feries; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE jours_feries (
    id_ferie integer NOT NULL,
    id_pays integer NOT NULL,
    date_ferie date NOT NULL,
    description character varying(100) DEFAULT NULL::character varying
);


ALTER TABLE public.jours_feries OWNER TO "sebastien.darre";

--
-- Name: jours_feries_id_ferie_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE jours_feries_id_ferie_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jours_feries_id_ferie_seq OWNER TO "sebastien.darre";

--
-- Name: jours_feries_id_ferie_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE jours_feries_id_ferie_seq OWNED BY jours_feries.id_ferie;


--
-- Name: jours_feries_id_ferie_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('jours_feries_id_ferie_seq', 20, true);


--
-- Name: kpi_ratios_equipe; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE kpi_ratios_equipe (
    id_ratio integer NOT NULL,
    id_equipe integer NOT NULL,
    pi_code character varying(20) NOT NULL,
    point_dev_jour double precision,
    point_dev_sprint double precision,
    nbre_dev_moyen double precision,
    charge_engagee numeric DEFAULT 0,
    realise numeric DEFAULT 0
);


ALTER TABLE public.kpi_ratios_equipe OWNER TO "sebastien.darre";

--
-- Name: kpi_ratios_equipe_id_ratio_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE kpi_ratios_equipe_id_ratio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.kpi_ratios_equipe_id_ratio_seq OWNER TO "sebastien.darre";

--
-- Name: kpi_ratios_equipe_id_ratio_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE kpi_ratios_equipe_id_ratio_seq OWNED BY kpi_ratios_equipe.id_ratio;


--
-- Name: kpi_ratios_equipe_id_ratio_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('kpi_ratios_equipe_id_ratio_seq', 16, true);


--
-- Name: membres; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE membres (
    id_membre integer NOT NULL,
    id_equipe character varying(100) NOT NULL,
    id_pays integer NOT NULL,
    nom character varying(100) NOT NULL,
    role character varying(50) DEFAULT NULL::character varying,
    taux_plein double precision DEFAULT 15,
    velocity_base double precision
);


ALTER TABLE public.membres OWNER TO "sebastien.darre";

--
-- Name: membres_id_membre_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE membres_id_membre_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.membres_id_membre_seq OWNER TO "sebastien.darre";

--
-- Name: membres_id_membre_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE membres_id_membre_seq OWNED BY membres.id_membre;


--
-- Name: membres_id_membre_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('membres_id_membre_seq', 18, true);


--
-- Name: parametres_engagement; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE parametres_engagement (
    id_param integer NOT NULL,
    pi_code character varying(20) NOT NULL,
    pourcentage_anomalies double precision DEFAULT 0.15 NOT NULL
);


ALTER TABLE public.parametres_engagement OWNER TO "sebastien.darre";

--
-- Name: parametres_engagement_id_param_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE parametres_engagement_id_param_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.parametres_engagement_id_param_seq OWNER TO "sebastien.darre";

--
-- Name: parametres_engagement_id_param_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE parametres_engagement_id_param_seq OWNED BY parametres_engagement.id_param;


--
-- Name: parametres_engagement_id_param_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('parametres_engagement_id_param_seq', 1, false);


--
-- Name: pays; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE pays (
    id_pays integer NOT NULL,
    nom_pays character varying(50) NOT NULL
);


ALTER TABLE public.pays OWNER TO "sebastien.darre";

--
-- Name: pays_id_pays_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE pays_id_pays_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pays_id_pays_seq OWNER TO "sebastien.darre";

--
-- Name: pays_id_pays_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE pays_id_pays_seq OWNED BY pays.id_pays;


--
-- Name: pays_id_pays_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('pays_id_pays_seq', 3, true);


--
-- Name: predictions; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE predictions (
    id_prediction integer NOT NULL,
    id_equipe integer NOT NULL,
    pi_code character varying(20) NOT NULL,
    label_prediction character varying(100) DEFAULT NULL::character varying,
    velo_build_prevue double precision,
    velo_build_totale_pi double precision,
    velo_totale_equipe double precision,
    velo_totale_pi double precision,
    anomalies_prevues double precision,
    pourcentage_ano_dedie double precision
);


ALTER TABLE public.predictions OWNER TO "sebastien.darre";

--
-- Name: predictions_id_prediction_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE predictions_id_prediction_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.predictions_id_prediction_seq OWNER TO "sebastien.darre";

--
-- Name: predictions_id_prediction_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE predictions_id_prediction_seq OWNED BY predictions.id_prediction;


--
-- Name: predictions_id_prediction_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('predictions_id_prediction_seq', 1, false);


--
-- Name: presence_membre; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE presence_membre (
    id_presence integer NOT NULL,
    id_membre integer NOT NULL,
    date_jour date NOT NULL,
    presence double precision DEFAULT 1
);


ALTER TABLE public.presence_membre OWNER TO "sebastien.darre";

--
-- Name: presence_membre_id_presence_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE presence_membre_id_presence_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.presence_membre_id_presence_seq OWNER TO "sebastien.darre";

--
-- Name: presence_membre_id_presence_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE presence_membre_id_presence_seq OWNED BY presence_membre.id_presence;


--
-- Name: presence_membre_id_presence_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('presence_membre_id_presence_seq', 1, false);


--
-- Name: sprints; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE sprints (
    id_sprint integer NOT NULL,
    numero_iteration integer NOT NULL,
    date_debut date NOT NULL,
    date_fin date NOT NULL,
    semaine_numero integer,
    pi_code character varying(20) DEFAULT 'PI 2026.2'::character varying
);


ALTER TABLE public.sprints OWNER TO "sebastien.darre";

--
-- Name: sprints_id_sprint_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE sprints_id_sprint_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.sprints_id_sprint_seq OWNER TO "sebastien.darre";

--
-- Name: sprints_id_sprint_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE sprints_id_sprint_seq OWNED BY sprints.id_sprint;


--
-- Name: sprints_id_sprint_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('sprints_id_sprint_seq', 5, true);


--
-- Name: utilisateurs; Type: TABLE; Schema: public; Owner: sebastien.darre; Tablespace: 
--

CREATE TABLE utilisateurs (
    id integer NOT NULL,
    login character varying(50) NOT NULL,
    mot_de_passe character varying(255) NOT NULL,
    role character varying(20) NOT NULL,
    equipes_ids character varying(100),
    id_membre integer
);


ALTER TABLE public.utilisateurs OWNER TO "sebastien.darre";

--
-- Name: utilisateurs_id_seq; Type: SEQUENCE; Schema: public; Owner: sebastien.darre
--

CREATE SEQUENCE utilisateurs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.utilisateurs_id_seq OWNER TO "sebastien.darre";

--
-- Name: utilisateurs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: sebastien.darre
--

ALTER SEQUENCE utilisateurs_id_seq OWNED BY utilisateurs.id;


--
-- Name: utilisateurs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: sebastien.darre
--

SELECT pg_catalog.setval('utilisateurs_id_seq', 4, true);


--
-- Name: id_mco; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE affectations_mco ALTER COLUMN id_mco SET DEFAULT nextval('affectations_mco_id_mco_seq'::regclass);


--
-- Name: id_tra; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE affectations_tra ALTER COLUMN id_tra SET DEFAULT nextval('affectations_tra_id_tra_seq'::regclass);


--
-- Name: id_equipe; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE equipes ALTER COLUMN id_equipe SET DEFAULT nextval('equipes_id_equipe_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE historique_pi ALTER COLUMN id SET DEFAULT nextval('historique_pi_id_seq'::regclass);


--
-- Name: id_ferie; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE jours_feries ALTER COLUMN id_ferie SET DEFAULT nextval('jours_feries_id_ferie_seq'::regclass);


--
-- Name: id_ratio; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE kpi_ratios_equipe ALTER COLUMN id_ratio SET DEFAULT nextval('kpi_ratios_equipe_id_ratio_seq'::regclass);


--
-- Name: id_membre; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE membres ALTER COLUMN id_membre SET DEFAULT nextval('membres_id_membre_seq'::regclass);


--
-- Name: id_param; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE parametres_engagement ALTER COLUMN id_param SET DEFAULT nextval('parametres_engagement_id_param_seq'::regclass);


--
-- Name: id_pays; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE pays ALTER COLUMN id_pays SET DEFAULT nextval('pays_id_pays_seq'::regclass);


--
-- Name: id_prediction; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE predictions ALTER COLUMN id_prediction SET DEFAULT nextval('predictions_id_prediction_seq'::regclass);


--
-- Name: id_presence; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE presence_membre ALTER COLUMN id_presence SET DEFAULT nextval('presence_membre_id_presence_seq'::regclass);


--
-- Name: id_sprint; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE sprints ALTER COLUMN id_sprint SET DEFAULT nextval('sprints_id_sprint_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: sebastien.darre
--

ALTER TABLE utilisateurs ALTER COLUMN id SET DEFAULT nextval('utilisateurs_id_seq'::regclass);


--
-- Data for Name: absences_sprints; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY absences_sprints (id_membre, pi_code, numero_iteration, jours_conges) FROM stdin;
4	PI 2026.2	3	0.0
4	PI 2026.2	2	0.0
4	PI 2026.2	4	0.0
4	PI 2026.2	1	0.0
14	PI 2026.2	2	0.0
15	PI 2026.2	2	0.0
6	PI 2026.2	1	0.0
5	PI 2026.1	1	0.0
6	PI 2026.1	2	0.0
15	PI 2026.1	2	0.0
14	PI 2026.1	3	0.0
5	PI 2026.1	2	0.0
1	PI 2026.2	1	1.0
18	PI 2026.2	1	12.0
2	PI 2026.2	1	7.0
3	PI 2026.2	1	5.0
5	PI 2026.2	1	8.0
7	PI 2026.2	1	2.0
12	PI 2026.2	1	9.0
16	PI 2026.2	1	9.0
2	PI 2026.2	2	9.0
5	PI 2026.2	2	5.0
12	PI 2026.2	2	4.0
11	PI 2026.2	2	10.0
16	PI 2026.2	2	1.0
17	PI 2026.2	2	4.0
2	PI 2026.2	3	4.0
8	PI 2026.2	3	6.0
6	PI 2026.2	3	4.0
3	PI 2026.2	3	1.0
2	PI 2026.2	4	4.0
6	PI 2026.2	4	5.0
8	PI 2026.2	4	3.0
7	PI 2026.2	4	1.0
1	PI 2026.2	3	1.0
12	PI 2026.2	3	4.0
13	PI 2026.2	3	10.0
17	PI 2026.2	3	4.0
16	PI 2026.2	3	1.0
14	PI 2026.2	4	5.0
17	PI 2026.2	4	0.0
13	PI 2026.2	4	5.0
12	PI 2026.2	4	7.0
7	PI 2026.2	3	5.0
\.


--
-- Data for Name: affectations_mco; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY affectations_mco (id_mco, id_membre, id_sprint, commentaire) FROM stdin;
8	16	4	
9	15	1	
10	13	2	
11	14	3	
12	8	1	
13	6	2	
18	4	4	
21	5	3	
\.


--
-- Data for Name: affectations_tra; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY affectations_tra (id_tra, id_membre, id_sprint, nb_semaines) FROM stdin;
13	4	3	3
3	16	3	3
12	15	3	3
16	6	3	2
17	7	4	1
18	17	4	1
15	8	3	1
\.


--
-- Data for Name: equipes; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY equipes (id_equipe, nom_equipe) FROM stdin;
1	APOLLO
2	DISCOVERY
\.


--
-- Data for Name: historique_pi; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY historique_pi (id, pi_code, total_pts, build_pts, apollo_pts, disco_pts, allstars_pts, ordre, mco_pts, tra_pts, anomalies_build_pts, statut, date_debut, date_fin, iterations, jours_par_iteration) FROM stdin;
2	2024.4	197.00	187.00	62.00	64.00	61.00	1	\N	\N	10.00	ARCHIVE	\N	\N	4	15
3	2025.1	390.00	335.00	78.00	106.00	151.00	2	55.00	\N	\N	ARCHIVE	\N	\N	4	15
4	2025.2	376.00	229.00	52.50	83.00	93.50	3	120.00	27.00	\N	ARCHIVE	\N	\N	4	15
8	2025.3	388.50	211.00	50.00	67.00	94.00	4	65.00	56.00	56.50	ARCHIVE	\N	\N	4	15
6	2025.4	412.00	227.00	48.00	96.00	83.00	5	75.50	43.50	66.00	ARCHIVE	\N	\N	4	15
7	2026.1	260.50	159.50	65.00	94.50	\N	6	60.00	30.00	11.00	ARCHIVE	\N	\N	4	15
9	2026.2	0.00	0.00	0.00	0.00	0.00	7	\N	\N	\N	PLANNING	\N	\N	4	15
\.


--
-- Data for Name: jours_feries; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY jours_feries (id_ferie, id_pays, date_ferie, description) FROM stdin;
1	0	2026-05-01	Fete du travail
3	4	2026-01-06	Epifanía del Señor
4	2	2026-01-22	Fête de Saint Vincent Martyr
5	4	2026-03-19	San José
7	4	2026-04-03	Viernes Santo
8	1	2026-04-06	Lundi de Pâques
9	2	2026-04-06	Lundi de Pâques
10	3	2026-04-07	Férié
11	2	2026-04-13	Férié
12	1	2026-05-08	Victoire 1945
13	1	2026-05-14	Ascension
14	1	2026-05-25	Lundi de Pentecôte
15	3	2026-06-09	Jour de la communauté de Murcie
16	2	2026-06-24	San Juan
17	1	2026-07-14	Fête Nationale
18	0	2026-07-15	Assomption
19	3	2026-09-15	Férié
20	4	2026-10-12	Fiesta Nacional de Espana
6	3	2026-04-02	Jueves Santo
2	0	2026-01-01	Jour de l'an
\.


--
-- Data for Name: kpi_ratios_equipe; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY kpi_ratios_equipe (id_ratio, id_equipe, pi_code, point_dev_jour, point_dev_sprint, nbre_dev_moyen, charge_engagee, realise) FROM stdin;
3	1	2025.1	0.5	\N	\N	0	0
4	2	2025.1	0.5	\N	\N	0	0
5	1	2025.2	0.5	\N	\N	0	0
6	2	2025.2	0.5	\N	\N	0	0
7	1	2025.4	0.5	\N	\N	0	0
8	2	2025.4	0.5	\N	\N	0	0
9	1	2024.4	0.5	\N	\N	0	0
10	2	2024.4	0.5	\N	\N	0	0
11	1	2026.1	0.5	\N	\N	0	0
12	2	2026.1	0.5	\N	\N	0	0
13	1	2026.2	0.5	\N	\N	0	0
14	2	2026.2	0.5	\N	\N	0	0
15	1	2025.3	0.5	\N	\N	0	0
16	2	2025.3	0.5	\N	\N	0	0
1	1	PI 2026.2	0.5	\N	\N	71	20.5
2	2	PI 2026.2	0.5	\N	\N	75	11.5
\.


--
-- Data for Name: membres; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY membres (id_membre, id_equipe, id_pays, nom, role, taux_plein, velocity_base) FROM stdin;
3	1	1	Axel	RT	15	\N
4	1	2	Andr�s	Dev	15	\N
5	1	2	Mehdi	Dev	15	\N
6	1	1	Nicolas	Dev	15	\N
7	1	1	Julien	Dev	15	\N
8	1	1	Thomas	Dev	15	\N
10	2	2	Begona	PPO	15	\N
11	2	1	Sebastien	PO	15	\N
12	2	1	Maxime	RT	15	\N
13	2	2	Adrian	Dev	15	\N
14	2	3	Matias	Dev	15	\N
15	2	2	Sergio	Dev	15	\N
16	2	1	Marina	Dev	15	\N
17	2	1	Aya	Dev	15	\N
2	1	1	Val�rie	PPO	15	\N
1	1,2	1	Tim	SM	15	\N
18	1	1	Mathilde	PO	15	\N
\.


--
-- Data for Name: parametres_engagement; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY parametres_engagement (id_param, pi_code, pourcentage_anomalies) FROM stdin;
\.


--
-- Data for Name: pays; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY pays (id_pays, nom_pays) FROM stdin;
1	France
2	Espagne Valence
3	Espagne Murcie
0	Tous
4	Espagne
\.


--
-- Data for Name: predictions; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY predictions (id_prediction, id_equipe, pi_code, label_prediction, velo_build_prevue, velo_build_totale_pi, velo_totale_equipe, velo_totale_pi, anomalies_prevues, pourcentage_ano_dedie) FROM stdin;
\.


--
-- Data for Name: presence_membre; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY presence_membre (id_presence, id_membre, date_jour, presence) FROM stdin;
\.


--
-- Data for Name: sprints; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY sprints (id_sprint, numero_iteration, date_debut, date_fin, semaine_numero, pi_code) FROM stdin;
1	1	2026-03-30	2026-04-17	\N	PI 2026.2
2	2	2026-04-20	2026-05-08	\N	PI 2026.2
3	3	2026-05-11	2026-05-29	\N	PI 2026.2
4	4	2026-06-01	2026-06-26	\N	PI 2026.2
5	1	2026-06-29	2026-07-17	\N	PI 2026.3
\.


--
-- Data for Name: utilisateurs; Type: TABLE DATA; Schema: public; Owner: sebastien.darre
--

COPY utilisateurs (id, login, mot_de_passe, role, equipes_ids, id_membre) FROM stdin;
1	rte.admin	test	ADMIN	\N	\N
4	boss.externe	test	LECTEUR	\N	\N
3	valerie.po	test	CONTRIBUTEUR	1	2
2	tim.sm	test	SM	1,2	1
\.


--
-- Name: absences_sprints_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY absences_sprints
    ADD CONSTRAINT absences_sprints_pkey PRIMARY KEY (id_membre, pi_code, numero_iteration);


--
-- Name: affectations_mco_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY affectations_mco
    ADD CONSTRAINT affectations_mco_pkey PRIMARY KEY (id_mco);


--
-- Name: affectations_tra_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY affectations_tra
    ADD CONSTRAINT affectations_tra_pkey PRIMARY KEY (id_tra);


--
-- Name: equipes_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY equipes
    ADD CONSTRAINT equipes_pkey PRIMARY KEY (id_equipe);


--
-- Name: historique_pi_pi_code_key; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY historique_pi
    ADD CONSTRAINT historique_pi_pi_code_key UNIQUE (pi_code);


--
-- Name: historique_pi_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY historique_pi
    ADD CONSTRAINT historique_pi_pkey PRIMARY KEY (id);


--
-- Name: jours_feries_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY jours_feries
    ADD CONSTRAINT jours_feries_pkey PRIMARY KEY (id_ferie);


--
-- Name: kpi_ratios_equipe_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY kpi_ratios_equipe
    ADD CONSTRAINT kpi_ratios_equipe_pkey PRIMARY KEY (id_ratio);


--
-- Name: membres_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY membres
    ADD CONSTRAINT membres_pkey PRIMARY KEY (id_membre);


--
-- Name: parametres_engagement_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY parametres_engagement
    ADD CONSTRAINT parametres_engagement_pkey PRIMARY KEY (id_param);


--
-- Name: pays_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY pays
    ADD CONSTRAINT pays_pkey PRIMARY KEY (id_pays);


--
-- Name: predictions_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY predictions
    ADD CONSTRAINT predictions_pkey PRIMARY KEY (id_prediction);


--
-- Name: presence_membre_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY presence_membre
    ADD CONSTRAINT presence_membre_pkey PRIMARY KEY (id_presence);


--
-- Name: sprints_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY sprints
    ADD CONSTRAINT sprints_pkey PRIMARY KEY (id_sprint);


--
-- Name: utilisateurs_login_key; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY utilisateurs
    ADD CONSTRAINT utilisateurs_login_key UNIQUE (login);


--
-- Name: utilisateurs_pkey; Type: CONSTRAINT; Schema: public; Owner: sebastien.darre; Tablespace: 
--

ALTER TABLE ONLY utilisateurs
    ADD CONSTRAINT utilisateurs_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

