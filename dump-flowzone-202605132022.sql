--
-- PostgreSQL database dump
--

\restrict WZgZxCvGM2DGYn4GP2gFLkHhSNmeZOdeLKgc8TfMNMgg0lrtuh6C7VfsEDzb26K

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

-- Started on 2026-05-13 20:22:45

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 253 (class 1259 OID 24620)
-- Name: blog_posts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.blog_posts (
    id bigint NOT NULL,
    titulo character varying(200) NOT NULL,
    contenido text NOT NULL,
    imagen character varying(255),
    tipo character varying(255) DEFAULT 'noticia'::character varying NOT NULL,
    autor character varying(150),
    empresa_id bigint,
    usuario_id bigint,
    publicado boolean DEFAULT true NOT NULL,
    fecha_publicacion timestamp(0) without time zone,
    slug character varying(220),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT blog_posts_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['evento'::character varying, 'noticia'::character varying])::text[])))
);


ALTER TABLE public.blog_posts OWNER TO postgres;

--
-- TOC entry 252 (class 1259 OID 24619)
-- Name: blog_posts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.blog_posts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.blog_posts_id_seq OWNER TO postgres;

--
-- TOC entry 5308 (class 0 OID 0)
-- Dependencies: 252
-- Name: blog_posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.blog_posts_id_seq OWNED BY public.blog_posts.id;


--
-- TOC entry 225 (class 1259 OID 16440)
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 16451)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 17235)
-- Name: calificaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.calificaciones (
    id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    tipo character varying(30) NOT NULL,
    item_id bigint NOT NULL,
    calificacion integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    comentario text,
    CONSTRAINT calificaciones_tipo_check CHECK (((tipo)::text = ANY (ARRAY[('lugar'::character varying)::text, ('hotel'::character varying)::text])))
);


ALTER TABLE public.calificaciones OWNER TO postgres;

--
-- TOC entry 246 (class 1259 OID 17234)
-- Name: calificaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.calificaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.calificaciones_id_seq OWNER TO postgres;

--
-- TOC entry 5309 (class 0 OID 0)
-- Dependencies: 246
-- Name: calificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.calificaciones_id_seq OWNED BY public.calificaciones.id;


--
-- TOC entry 245 (class 1259 OID 17222)
-- Name: comentarios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.comentarios (
    id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    lugar_id bigint NOT NULL,
    comentario text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.comentarios OWNER TO postgres;

--
-- TOC entry 244 (class 1259 OID 17221)
-- Name: comentarios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.comentarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.comentarios_id_seq OWNER TO postgres;

--
-- TOC entry 5310 (class 0 OID 0)
-- Dependencies: 244
-- Name: comentarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comentarios_id_seq OWNED BY public.comentarios.id;


--
-- TOC entry 261 (class 1259 OID 24742)
-- Name: detalle_pedidos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.detalle_pedidos (
    id bigint NOT NULL,
    pedido_id bigint NOT NULL,
    item_type character varying(255) NOT NULL,
    item_id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    precio_unitario numeric(10,2) NOT NULL,
    cantidad integer NOT NULL,
    subtotal numeric(10,2) NOT NULL,
    opciones json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.detalle_pedidos OWNER TO postgres;

--
-- TOC entry 260 (class 1259 OID 24741)
-- Name: detalle_pedidos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.detalle_pedidos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.detalle_pedidos_id_seq OWNER TO postgres;

--
-- TOC entry 5311 (class 0 OID 0)
-- Dependencies: 260
-- Name: detalle_pedidos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.detalle_pedidos_id_seq OWNED BY public.detalle_pedidos.id;


--
-- TOC entry 233 (class 1259 OID 17133)
-- Name: empresas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.empresas (
    id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    nombre character varying(200) NOT NULL,
    telefono character varying(30),
    direccion character varying(400),
    aprobado boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    tipo_servicio character varying(255),
    descripcion text,
    logo character varying(255),
    correo character varying(255),
    horario character varying(255)
);


ALTER TABLE public.empresas OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 17132)
-- Name: empresas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.empresas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.empresas_id_seq OWNER TO postgres;

--
-- TOC entry 5312 (class 0 OID 0)
-- Dependencies: 232
-- Name: empresas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.empresas_id_seq OWNED BY public.empresas.id;


--
-- TOC entry 239 (class 1259 OID 17178)
-- Name: eventos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eventos (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    fecha date NOT NULL,
    hora time(0) without time zone,
    ubicacion character varying(200),
    categoria character varying(100),
    imagen character varying(255),
    precio numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    organizador character varying(150),
    contacto character varying(150),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.eventos OWNER TO postgres;

--
-- TOC entry 238 (class 1259 OID 17177)
-- Name: eventos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.eventos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.eventos_id_seq OWNER TO postgres;

--
-- TOC entry 5313 (class 0 OID 0)
-- Dependencies: 238
-- Name: eventos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.eventos_id_seq OWNED BY public.eventos.id;


--
-- TOC entry 231 (class 1259 OID 16493)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 16492)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5314 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 249 (class 1259 OID 17251)
-- Name: favoritos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.favoritos (
    id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    tipo character varying(30) NOT NULL,
    item_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT favoritos_tipo_check CHECK (((tipo)::text = ANY (ARRAY[('lugar'::character varying)::text, ('hotel'::character varying)::text])))
);


ALTER TABLE public.favoritos OWNER TO postgres;

--
-- TOC entry 248 (class 1259 OID 17250)
-- Name: favoritos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.favoritos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.favoritos_id_seq OWNER TO postgres;

--
-- TOC entry 5315 (class 0 OID 0)
-- Dependencies: 248
-- Name: favoritos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.favoritos_id_seq OWNED BY public.favoritos.id;


--
-- TOC entry 241 (class 1259 OID 17193)
-- Name: gastronomia; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.gastronomia (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    tipo character varying(100),
    precio_promedio numeric(10,2),
    restaurante character varying(150),
    direccion character varying(200),
    telefono character varying(20),
    imagen character varying(255),
    ingredientes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    empresa_id bigint,
    ubicacion character varying(200),
    latitud numeric(9,6),
    longitud numeric(9,6)
);


ALTER TABLE public.gastronomia OWNER TO postgres;

--
-- TOC entry 240 (class 1259 OID 17192)
-- Name: gastronomia_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.gastronomia_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.gastronomia_id_seq OWNER TO postgres;

--
-- TOC entry 5316 (class 0 OID 0)
-- Dependencies: 240
-- Name: gastronomia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gastronomia_id_seq OWNED BY public.gastronomia.id;


--
-- TOC entry 255 (class 1259 OID 24668)
-- Name: hero_images; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hero_images (
    id bigint NOT NULL,
    titulo character varying(255),
    url character varying(255) NOT NULL,
    seccion character varying(255) DEFAULT 'hero'::character varying NOT NULL,
    activa boolean DEFAULT true NOT NULL,
    orden integer DEFAULT 0 NOT NULL,
    tipo character varying(255) DEFAULT 'url'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.hero_images OWNER TO postgres;

--
-- TOC entry 254 (class 1259 OID 24667)
-- Name: hero_images_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hero_images_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hero_images_id_seq OWNER TO postgres;

--
-- TOC entry 5317 (class 0 OID 0)
-- Dependencies: 254
-- Name: hero_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hero_images_id_seq OWNED BY public.hero_images.id;


--
-- TOC entry 237 (class 1259 OID 17163)
-- Name: hoteles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hoteles (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    precio numeric(10,2) NOT NULL,
    ubicacion character varying(200),
    latitud numeric(10,8),
    longitud numeric(11,8),
    imagen character varying(255),
    servicios text,
    capacidad integer,
    disponibilidad boolean DEFAULT true NOT NULL,
    telefono character varying(20),
    email character varying(150),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    empresa_id bigint
);


ALTER TABLE public.hoteles OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 17162)
-- Name: hoteles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hoteles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.hoteles_id_seq OWNER TO postgres;

--
-- TOC entry 5318 (class 0 OID 0)
-- Dependencies: 236
-- Name: hoteles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hoteles_id_seq OWNED BY public.hoteles.id;


--
-- TOC entry 229 (class 1259 OID 16478)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 16463)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 227 (class 1259 OID 16462)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5319 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 235 (class 1259 OID 17149)
-- Name: lugares; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lugares (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    ubicacion character varying(200),
    latitud numeric(10,8),
    longitud numeric(11,8),
    categoria character varying(100),
    imagen character varying(255),
    precio_entrada numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    horario character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.lugares OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 17148)
-- Name: lugares_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.lugares_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.lugares_id_seq OWNER TO postgres;

--
-- TOC entry 5320 (class 0 OID 0)
-- Dependencies: 234
-- Name: lugares_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lugares_id_seq OWNED BY public.lugares.id;


--
-- TOC entry 220 (class 1259 OID 16389)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 16388)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5321 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 251 (class 1259 OID 17265)
-- Name: notificaciones_admin; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notificaciones_admin (
    id bigint NOT NULL,
    empresa_id bigint NOT NULL,
    mensaje text NOT NULL,
    leido boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.notificaciones_admin OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 17264)
-- Name: notificaciones_admin_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.notificaciones_admin_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.notificaciones_admin_id_seq OWNER TO postgres;

--
-- TOC entry 5322 (class 0 OID 0)
-- Dependencies: 250
-- Name: notificaciones_admin_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notificaciones_admin_id_seq OWNED BY public.notificaciones_admin.id;


--
-- TOC entry 223 (class 1259 OID 16419)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- TOC entry 259 (class 1259 OID 24722)
-- Name: pedidos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.pedidos (
    id bigint NOT NULL,
    usuario_id bigint,
    total numeric(10,2) NOT NULL,
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    metodo_pago character varying(255),
    referencia character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    guest_nombre character varying(255),
    guest_email character varying(255),
    guest_telefono character varying(255),
    CONSTRAINT pedidos_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'pagado'::character varying, 'cancelado'::character varying])::text[])))
);


ALTER TABLE public.pedidos OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 24721)
-- Name: pedidos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.pedidos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pedidos_id_seq OWNER TO postgres;

--
-- TOC entry 5323 (class 0 OID 0)
-- Dependencies: 258
-- Name: pedidos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.pedidos_id_seq OWNED BY public.pedidos.id;


--
-- TOC entry 263 (class 1259 OID 24775)
-- Name: planes_turisticos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.planes_turisticos (
    id bigint NOT NULL,
    empresa_id bigint NOT NULL,
    titulo character varying(255),
    evento_id bigint,
    gastronomia_id bigint,
    hotel_id bigint,
    lugar_id bigint,
    subtotal numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    descuento numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    precio_final numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.planes_turisticos OWNER TO postgres;

--
-- TOC entry 262 (class 1259 OID 24774)
-- Name: planes_turisticos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.planes_turisticos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.planes_turisticos_id_seq OWNER TO postgres;

--
-- TOC entry 5324 (class 0 OID 0)
-- Dependencies: 262
-- Name: planes_turisticos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.planes_turisticos_id_seq OWNED BY public.planes_turisticos.id;


--
-- TOC entry 243 (class 1259 OID 17205)
-- Name: reservas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reservas (
    id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    hotel_id bigint NOT NULL,
    fecha_entrada date NOT NULL,
    fecha_salida date NOT NULL,
    num_personas integer NOT NULL,
    precio_total numeric(10,2) NOT NULL,
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    metodo_pago character varying(255),
    referencia_pago character varying(20),
    estado_pago character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    CONSTRAINT reservas_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'confirmada'::character varying, 'cancelada'::character varying])::text[]))),
    CONSTRAINT reservas_estado_pago_check CHECK (((estado_pago)::text = ANY ((ARRAY['pendiente'::character varying, 'pagado'::character varying, 'fallido'::character varying])::text[]))),
    CONSTRAINT reservas_metodo_pago_check CHECK (((metodo_pago)::text = ANY ((ARRAY['nequi'::character varying, 'bancolombia_pse'::character varying, 'tarjeta'::character varying])::text[])))
);


ALTER TABLE public.reservas OWNER TO postgres;

--
-- TOC entry 242 (class 1259 OID 17204)
-- Name: reservas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reservas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reservas_id_seq OWNER TO postgres;

--
-- TOC entry 5325 (class 0 OID 0)
-- Dependencies: 242
-- Name: reservas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reservas_id_seq OWNED BY public.reservas.id;


--
-- TOC entry 257 (class 1259 OID 24702)
-- Name: servicios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.servicios (
    id bigint NOT NULL,
    empresa_id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text NOT NULL,
    precio numeric(10,2),
    imagen character varying(255),
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.servicios OWNER TO postgres;

--
-- TOC entry 256 (class 1259 OID 24701)
-- Name: servicios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.servicios_id_seq OWNER TO postgres;

--
-- TOC entry 5326 (class 0 OID 0)
-- Dependencies: 256
-- Name: servicios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.servicios_id_seq OWNED BY public.servicios.id;


--
-- TOC entry 224 (class 1259 OID 16428)
-- Name: sessions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 16399)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    rol character varying(255) DEFAULT 'usuario'::character varying NOT NULL,
    estado character varying(255) DEFAULT 'activo'::character varying NOT NULL,
    avatar character varying(255),
    telefono character varying(20),
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT users_estado_check CHECK (((estado)::text = ANY ((ARRAY['activo'::character varying, 'pendiente'::character varying, 'bloqueado'::character varying])::text[]))),
    CONSTRAINT users_rol_check CHECK (((rol)::text = ANY ((ARRAY['admin'::character varying, 'usuario'::character varying, 'empresa'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 221 (class 1259 OID 16398)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5327 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4995 (class 2604 OID 24623)
-- Name: blog_posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts ALTER COLUMN id SET DEFAULT nextval('public.blog_posts_id_seq'::regclass);


--
-- TOC entry 4991 (class 2604 OID 17238)
-- Name: calificaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones ALTER COLUMN id SET DEFAULT nextval('public.calificaciones_id_seq'::regclass);


--
-- TOC entry 4990 (class 2604 OID 17225)
-- Name: comentarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios ALTER COLUMN id SET DEFAULT nextval('public.comentarios_id_seq'::regclass);


--
-- TOC entry 5007 (class 2604 OID 24745)
-- Name: detalle_pedidos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_pedidos ALTER COLUMN id SET DEFAULT nextval('public.detalle_pedidos_id_seq'::regclass);


--
-- TOC entry 4978 (class 2604 OID 17136)
-- Name: empresas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas ALTER COLUMN id SET DEFAULT nextval('public.empresas_id_seq'::regclass);


--
-- TOC entry 4984 (class 2604 OID 17181)
-- Name: eventos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos ALTER COLUMN id SET DEFAULT nextval('public.eventos_id_seq'::regclass);


--
-- TOC entry 4976 (class 2604 OID 16496)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4992 (class 2604 OID 17254)
-- Name: favoritos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos ALTER COLUMN id SET DEFAULT nextval('public.favoritos_id_seq'::regclass);


--
-- TOC entry 4986 (class 2604 OID 17196)
-- Name: gastronomia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gastronomia ALTER COLUMN id SET DEFAULT nextval('public.gastronomia_id_seq'::regclass);


--
-- TOC entry 4998 (class 2604 OID 24671)
-- Name: hero_images id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hero_images ALTER COLUMN id SET DEFAULT nextval('public.hero_images_id_seq'::regclass);


--
-- TOC entry 4982 (class 2604 OID 17166)
-- Name: hoteles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hoteles ALTER COLUMN id SET DEFAULT nextval('public.hoteles_id_seq'::regclass);


--
-- TOC entry 4975 (class 2604 OID 16466)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4980 (class 2604 OID 17152)
-- Name: lugares id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lugares ALTER COLUMN id SET DEFAULT nextval('public.lugares_id_seq'::regclass);


--
-- TOC entry 4971 (class 2604 OID 16392)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4993 (class 2604 OID 17268)
-- Name: notificaciones_admin id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones_admin ALTER COLUMN id SET DEFAULT nextval('public.notificaciones_admin_id_seq'::regclass);


--
-- TOC entry 5005 (class 2604 OID 24725)
-- Name: pedidos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos ALTER COLUMN id SET DEFAULT nextval('public.pedidos_id_seq'::regclass);


--
-- TOC entry 5008 (class 2604 OID 24778)
-- Name: planes_turisticos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planes_turisticos ALTER COLUMN id SET DEFAULT nextval('public.planes_turisticos_id_seq'::regclass);


--
-- TOC entry 4987 (class 2604 OID 17208)
-- Name: reservas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas ALTER COLUMN id SET DEFAULT nextval('public.reservas_id_seq'::regclass);


--
-- TOC entry 5003 (class 2604 OID 24705)
-- Name: servicios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios ALTER COLUMN id SET DEFAULT nextval('public.servicios_id_seq'::regclass);


--
-- TOC entry 4972 (class 2604 OID 16402)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5292 (class 0 OID 24620)
-- Dependencies: 253
-- Data for Name: blog_posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.blog_posts (id, titulo, contenido, imagen, tipo, autor, empresa_id, usuario_id, publicado, fecha_publicacion, slug, created_at, updated_at) FROM stdin;
1	Festival del Folclor 2026	El festival anual de música y danza tradicional tolimense se celebrará en la plaza principal.	\N	noticia	\N	\N	\N	t	2026-04-22 12:23:50	festival-del-folclor-2026-69e8bdd6a0365	2026-04-22 12:23:50	2026-04-23 13:21:14
2	Nueva ruta ecoturística	Se habilitó una nueva ruta de senderismo que conecta tres cascadas del municipio de Ortega.	\N	noticia	\N	\N	\N	t	2026-04-22 12:23:00	nueva-ruta-ecoturistica-69e8bdd6a6d28	2026-04-22 12:23:50	2026-04-23 14:29:29
3	Descubre Ortega: El Tesoro Escondido del Tolima	Ortega es un municipio del Tolima que guarda paisajes naturales únicos, cultura indígena Pijao y una gastronomía auténtica que enamora a todo viajero. En este artículo te contamos por qué debe ser tu próximo destino.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	descubre-ortega-el-tesoro-escondido-del-tolima-69f0065e0aacc	2026-04-28 00:59:10	2026-04-28 00:59:10
4	Guía Completa para Visitar la Cascada La Palmita	La Cascada La Palmita es una de las joyas naturales de Ortega. Con una caída de 30 metros rodeada de selva, es perfecta para senderismo y fotografía. Te explicamos cómo llegar, qué llevar y los mejores horarios para visitarla.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	guia-completa-para-visitar-la-cascada-la-palmita-69f0065e10acc	2026-04-28 00:59:10	2026-04-28 00:59:10
5	5 Razones para Hacer Ecoturismo en el Tolima	El Tolima es uno de los destinos ecoturísticos más ricos de Colombia. Desde reservas naturales hasta petroglifos indígenas, la biodiversidad y el patrimonio cultural hacen de este departamento un paraíso para el viajero consciente.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	5-razones-para-hacer-ecoturismo-en-el-tolima-69f0065e1226b	2026-04-28 00:59:10	2026-04-28 00:59:10
6	Gastronomía Tolimense: Platos que Debes Probar	El viudo de capaz, el tamal tolimense y la lechona son solo el comienzo. La cocina del Tolima es un viaje por sabores ancestrales que combinan maíz, plátano, yuca y carnes criollas en preparaciones únicas en Colombia.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	gastronomia-tolimense-platos-que-debes-probar-69f0065e13457	2026-04-28 00:59:10	2026-04-28 00:59:10
7	Cómo Llegar a Ortega desde Bogotá en Bus	Viajar de Bogotá a Ortega es más fácil de lo que crees. El trayecto tarda aproximadamente 4 horas en bus desde el Terminal de Transporte del Sur. En esta guía te explicamos rutas, empresas de transporte y consejos para el viaje.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	como-llegar-a-ortega-desde-bogota-en-bus-69f0065e141d1	2026-04-28 00:59:10	2026-04-28 00:59:10
8	Los Mejores Hoteles Campestres de Ortega	Desde eco lodges hasta glamping bajo las estrellas, Ortega tiene opciones de alojamiento para todos los presupuestos. Conoce los mejores lugares para hospedarte y disfrutar de la naturaleza tolimense.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	los-mejores-hoteles-campestres-de-ortega-69f0065e154bb	2026-04-28 00:59:10	2026-04-28 00:59:10
9	Petroglifos Pijao: Historia Milenaria en Piedra	La Piedra Pintada de Ortega es uno de los sitios arqueológicos más importantes del Tolima. Sus petroglifos, tallados por la cultura indígena Pijao hace más de 1000 años, son un testimonio fascinante de la historia precolombina.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	petroglifos-pijao-historia-milenaria-en-piedra-69f0065e1647a	2026-04-28 00:59:10	2026-04-28 00:59:10
10	Turismo de Aventura en el Río Saldaña	El río Saldaña ofrece experiencias únicas para los amantes de la aventura: rafting, kayak, pesca deportiva y baños en sus aguas cristalinas. Descubre las mejores épocas del año y los operadores locales recomendados.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	turismo-de-aventura-en-el-rio-saldana-69f0065e1746d	2026-04-28 00:59:10	2026-04-28 00:59:10
11	Festival del Río: La Gran Fiesta de Ortega	Cada año, Ortega celebra el Festival del Río Saldaña con música folclórica, danzas tradicionales y gastronomía típica. Te contamos todo lo que necesitas saber para vivir esta experiencia cultural única.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	festival-del-rio-la-gran-fiesta-de-ortega-69f0065e186f0	2026-04-28 00:59:10	2026-04-28 00:59:10
12	Avistamiento de Aves en la Reserva Natural Pijao	La Reserva Natural Pijao alberga más de 180 especies de aves en su bosque seco tropical. Con binoculares y paciencia, podrás observar desde el loro real hasta el tucán esmeralda en un entorno natural espectacular.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	avistamiento-de-aves-en-la-reserva-natural-pijao-69f0065e1978f	2026-04-28 00:59:10	2026-04-28 00:59:10
13	Turismo Rural: Aprende a Cultivar en Ortega	Las fincas agroturísticas de Ortega te invitan a vivir la experiencia de cosechar mango, cacao y plátano junto a familias campesinas. Una experiencia educativa y reconfortante lejos del ruido de la ciudad.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	turismo-rural-aprende-a-cultivar-en-ortega-69f0065e1bdbb	2026-04-28 00:59:10	2026-04-28 00:59:10
14	Qué Hacer en Ortega con Niños	Ortega es un destino ideal para familias con niños. Actividades como recorridos en finca, baños en río, observación de animales y talleres de artesanía hacen de cada visita una aventura educativa para los más pequeños.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	que-hacer-en-ortega-con-ninos-69f0065e1d80b	2026-04-28 00:59:10	2026-04-28 00:59:10
15	Mirador El Cielo: 360° de Pura Naturaleza	Desde el Alto de La Cruz, el Mirador El Cielo ofrece una vista panorámica de 360° sobre el valle del río Saldaña y las montañas del Tolima. El mejor lugar para ver el amanecer y el atardecer del municipio.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	mirador-el-cielo-360-de-pura-naturaleza-69f0065e1ef9d	2026-04-28 00:59:10	2026-04-28 00:59:10
16	Viaje Sostenible: Cómo Ser un Turista Responsable	El turismo responsable es clave para preservar los destinos naturales del Tolima. Te damos consejos prácticos para reducir tu huella ambiental, apoyar a las comunidades locales y disfrutar de forma sostenible.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	viaje-sostenible-como-ser-un-turista-responsable-69f0065e20547	2026-04-28 00:59:10	2026-04-28 00:59:10
17	Los Termales de Ortega: Relajación Natural	Las aguas termales de La Sulfurosa en Ortega tienen propiedades medicinales reconocidas por la comunidad local. Una tarde en estas piscinas naturales es la mejor manera de cerrar un día de aventura en la naturaleza.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	los-termales-de-ortega-relajacion-natural-69f0065e22b68	2026-04-28 00:59:10	2026-04-28 00:59:10
18	Artesanías Pijao: Arte que Cuenta Historias	Los artesanos de Ortega preservan las tradiciones de la cultura Pijao a través de tejidos, cerámicas y tallas en madera. Conoce los talleres locales y lleva a casa una pieza única con historia y alma tolimense.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:10	artesanias-pijao-arte-que-cuenta-historias-69f0065e2396b	2026-04-28 00:59:10	2026-04-28 00:59:10
19	Descubre Ortega: El Tesoro Escondido del Tolima	Ortega es un municipio del Tolima que guarda paisajes naturales únicos, cultura indígena Pijao y una gastronomía auténtica que enamora a todo viajero. En este artículo te contamos por qué debe ser tu próximo destino.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	descubre-ortega-el-tesoro-escondido-del-tolima-69f0065fa6c47	2026-04-28 00:59:11	2026-04-28 00:59:11
20	Guía Completa para Visitar la Cascada La Palmita	La Cascada La Palmita es una de las joyas naturales de Ortega. Con una caída de 30 metros rodeada de selva, es perfecta para senderismo y fotografía. Te explicamos cómo llegar, qué llevar y los mejores horarios para visitarla.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	guia-completa-para-visitar-la-cascada-la-palmita-69f0065fa9290	2026-04-28 00:59:11	2026-04-28 00:59:11
21	5 Razones para Hacer Ecoturismo en el Tolima	El Tolima es uno de los destinos ecoturísticos más ricos de Colombia. Desde reservas naturales hasta petroglifos indígenas, la biodiversidad y el patrimonio cultural hacen de este departamento un paraíso para el viajero consciente.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	5-razones-para-hacer-ecoturismo-en-el-tolima-69f0065fa9a7a	2026-04-28 00:59:11	2026-04-28 00:59:11
22	Gastronomía Tolimense: Platos que Debes Probar	El viudo de capaz, el tamal tolimense y la lechona son solo el comienzo. La cocina del Tolima es un viaje por sabores ancestrales que combinan maíz, plátano, yuca y carnes criollas en preparaciones únicas en Colombia.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	gastronomia-tolimense-platos-que-debes-probar-69f0065faa20d	2026-04-28 00:59:11	2026-04-28 00:59:11
23	Cómo Llegar a Ortega desde Bogotá en Bus	Viajar de Bogotá a Ortega es más fácil de lo que crees. El trayecto tarda aproximadamente 4 horas en bus desde el Terminal de Transporte del Sur. En esta guía te explicamos rutas, empresas de transporte y consejos para el viaje.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	como-llegar-a-ortega-desde-bogota-en-bus-69f0065faaea3	2026-04-28 00:59:11	2026-04-28 00:59:11
24	Los Mejores Hoteles Campestres de Ortega	Desde eco lodges hasta glamping bajo las estrellas, Ortega tiene opciones de alojamiento para todos los presupuestos. Conoce los mejores lugares para hospedarte y disfrutar de la naturaleza tolimense.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	los-mejores-hoteles-campestres-de-ortega-69f0065fab93a	2026-04-28 00:59:11	2026-04-28 00:59:11
25	Petroglifos Pijao: Historia Milenaria en Piedra	La Piedra Pintada de Ortega es uno de los sitios arqueológicos más importantes del Tolima. Sus petroglifos, tallados por la cultura indígena Pijao hace más de 1000 años, son un testimonio fascinante de la historia precolombina.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	petroglifos-pijao-historia-milenaria-en-piedra-69f0065fac1bd	2026-04-28 00:59:11	2026-04-28 00:59:11
26	Turismo de Aventura en el Río Saldaña	El río Saldaña ofrece experiencias únicas para los amantes de la aventura: rafting, kayak, pesca deportiva y baños en sus aguas cristalinas. Descubre las mejores épocas del año y los operadores locales recomendados.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	turismo-de-aventura-en-el-rio-saldana-69f0065faca4b	2026-04-28 00:59:11	2026-04-28 00:59:11
27	Festival del Río: La Gran Fiesta de Ortega	Cada año, Ortega celebra el Festival del Río Saldaña con música folclórica, danzas tradicionales y gastronomía típica. Te contamos todo lo que necesitas saber para vivir esta experiencia cultural única.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	festival-del-rio-la-gran-fiesta-de-ortega-69f0065fad18f	2026-04-28 00:59:11	2026-04-28 00:59:11
28	Avistamiento de Aves en la Reserva Natural Pijao	La Reserva Natural Pijao alberga más de 180 especies de aves en su bosque seco tropical. Con binoculares y paciencia, podrás observar desde el loro real hasta el tucán esmeralda en un entorno natural espectacular.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	avistamiento-de-aves-en-la-reserva-natural-pijao-69f0065fada68	2026-04-28 00:59:11	2026-04-28 00:59:11
29	Turismo Rural: Aprende a Cultivar en Ortega	Las fincas agroturísticas de Ortega te invitan a vivir la experiencia de cosechar mango, cacao y plátano junto a familias campesinas. Una experiencia educativa y reconfortante lejos del ruido de la ciudad.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	turismo-rural-aprende-a-cultivar-en-ortega-69f0065fae516	2026-04-28 00:59:11	2026-04-28 00:59:11
30	Qué Hacer en Ortega con Niños	Ortega es un destino ideal para familias con niños. Actividades como recorridos en finca, baños en río, observación de animales y talleres de artesanía hacen de cada visita una aventura educativa para los más pequeños.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	que-hacer-en-ortega-con-ninos-69f0065faf1ba	2026-04-28 00:59:11	2026-04-28 00:59:11
31	Mirador El Cielo: 360° de Pura Naturaleza	Desde el Alto de La Cruz, el Mirador El Cielo ofrece una vista panorámica de 360° sobre el valle del río Saldaña y las montañas del Tolima. El mejor lugar para ver el amanecer y el atardecer del municipio.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	mirador-el-cielo-360-de-pura-naturaleza-69f0065fafd5f	2026-04-28 00:59:11	2026-04-28 00:59:11
32	Viaje Sostenible: Cómo Ser un Turista Responsable	El turismo responsable es clave para preservar los destinos naturales del Tolima. Te damos consejos prácticos para reducir tu huella ambiental, apoyar a las comunidades locales y disfrutar de forma sostenible.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	viaje-sostenible-como-ser-un-turista-responsable-69f0065fb081b	2026-04-28 00:59:11	2026-04-28 00:59:11
33	Los Termales de Ortega: Relajación Natural	Las aguas termales de La Sulfurosa en Ortega tienen propiedades medicinales reconocidas por la comunidad local. Una tarde en estas piscinas naturales es la mejor manera de cerrar un día de aventura en la naturaleza.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	los-termales-de-ortega-relajacion-natural-69f0065fb6071	2026-04-28 00:59:11	2026-04-28 00:59:11
34	Artesanías Pijao: Arte que Cuenta Historias	Los artesanos de Ortega preservan las tradiciones de la cultura Pijao a través de tejidos, cerámicas y tallas en madera. Conoce los talleres locales y lleva a casa una pieza única con historia y alma tolimense.	\N	noticia	\N	\N	\N	f	2026-04-28 00:59:11	artesanias-pijao-arte-que-cuenta-historias-69f0065fb6f77	2026-04-28 00:59:11	2026-04-28 00:59:11
35	el cerro de los Abechucos	ES MUY BONITO	https://th.bing.com/th/id/OIP.AaGzAlj7P6XDEL6WmWIKaAHaFj?w=287&h=215&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3	evento	Hotel El Paraíso S.A.S	1	12	f	2026-04-28 11:44:22	el-cerro-de-los-abechucos-69f09d964a795	2026-04-28 11:44:22	2026-04-28 11:44:22
\.


--
-- TOC entry 5264 (class 0 OID 16440)
-- Dependencies: 225
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- TOC entry 5265 (class 0 OID 16451)
-- Dependencies: 226
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 5286 (class 0 OID 17235)
-- Dependencies: 247
-- Data for Name: calificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calificaciones (id, usuario_id, tipo, item_id, calificacion, created_at, updated_at, comentario) FROM stdin;
\.


--
-- TOC entry 5284 (class 0 OID 17222)
-- Dependencies: 245
-- Data for Name: comentarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comentarios (id, usuario_id, lugar_id, comentario, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5300 (class 0 OID 24742)
-- Dependencies: 261
-- Data for Name: detalle_pedidos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.detalle_pedidos (id, pedido_id, item_type, item_id, nombre, precio_unitario, cantidad, subtotal, opciones, created_at, updated_at) FROM stdin;
1	1	hotel	1	Hotel Campestre El Paraísos	120000.00	1	120000.00	{"fecha_entrada":"2026-04-29","fecha_salida":"2026-05-22"}	2026-04-27 03:02:21	2026-04-27 03:02:21
\.


--
-- TOC entry 5272 (class 0 OID 17133)
-- Dependencies: 233
-- Data for Name: empresas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.empresas (id, usuario_id, nombre, telefono, direccion, aprobado, created_at, updated_at, tipo_servicio, descripcion, logo, correo, horario) FROM stdin;
1	12	Hotel El Paraíso S.A.S	320123376	Km 2 Vía Ortega-Chaparral	t	2026-03-19 15:35:18	2026-03-24 11:45:23	\N	\N	\N	\N	\N
2	15	Lorens	3173899366	Ortega-Tolima	t	2026-03-25 06:06:47	2026-03-25 06:07:15	\N	\N	\N	\N	\N
3	24	Turismo Tolima SAS	3201112233	Calle 5 #4-23, Ortega	f	2026-04-28 00:28:37	2026-04-28 00:28:37	\N	\N	\N	\N	\N
4	25	Aventura Saldaña	3112223344	Carrera 6 #3-45, Ortega	f	2026-04-28 00:28:37	2026-04-28 00:28:37	\N	\N	\N	\N	\N
5	26	Sabor Tolimense SAS	3023334455	Calle 7 #5-67, Ortega	f	2026-04-28 00:28:38	2026-04-28 00:28:38	\N	\N	\N	\N	\N
6	27	Finca Ecoturística Las Palmas	3134445566	Km 3 Vía Chaparral	f	2026-04-28 00:28:38	2026-04-28 00:28:38	\N	\N	\N	\N	\N
7	28	Eventos y Logística Ortega	3045556677	Carrera 3 #6-89, Ortega	f	2026-04-28 00:28:39	2026-04-28 00:28:39	\N	\N	\N	\N	\N
8	29	Transporte Turístico El Valle	3156667788	Calle 4 #7-12, Ortega	f	2026-04-28 00:28:39	2026-04-28 00:28:39	\N	\N	\N	\N	\N
9	30	Arte y Artesanías Pijao	3067778899	Plaza de Mercado, Ortega	f	2026-04-28 00:28:40	2026-04-28 00:28:40	\N	\N	\N	\N	\N
10	31	Guías Nativos del Saldaña	3178889900	Vereda La Palmita, Ortega	f	2026-04-28 00:28:40	2026-04-28 00:28:40	\N	\N	\N	\N	\N
11	32	Hotel Campestre SAS	3089990011	Km 2 Vía Ortega-Chaparral	f	2026-04-28 00:28:41	2026-04-28 00:28:41	\N	\N	\N	\N	\N
12	33	Wellness Termal Ortega	3190001122	Vereda La Sulfurosa	f	2026-04-28 00:28:41	2026-04-28 00:28:41	\N	\N	\N	\N	\N
13	34	Fotografía y Turismo Visual	3201112234	Calle 6 #5-34, Ortega	f	2026-04-28 00:28:42	2026-04-28 00:28:42	\N	\N	\N	\N	\N
14	35	Agroturismo Familiar Los Mangos	3112223345	Km 5 Vía El Guamo	f	2026-04-28 00:28:42	2026-04-28 00:28:42	\N	\N	\N	\N	\N
15	36	Camping Salvaje Ortega	3023334456	Vereda El Triunfo	f	2026-04-28 00:28:43	2026-04-28 00:28:43	\N	\N	\N	\N	\N
16	37	Cocina de la Abuela SAS	3134445567	Carrera 5 #4-56, Ortega	f	2026-04-28 00:28:44	2026-04-28 00:28:44	\N	\N	\N	\N	\N
17	38	Deportes en Río SAS	3045556678	Orillas Río Saldaña	f	2026-04-28 00:28:44	2026-04-28 00:28:44	\N	\N	\N	\N	\N
18	39	Centro Cultural Pijao	3156667789	Calle 3 #8-23, Ortega	f	2026-04-28 00:28:44	2026-04-28 00:28:44	\N	\N	\N	\N	\N
\.


--
-- TOC entry 5278 (class 0 OID 17178)
-- Dependencies: 239
-- Data for Name: eventos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos (id, nombre, descripcion, fecha, hora, ubicacion, categoria, imagen, precio, organizador, contacto, created_at, updated_at) FROM stdin;
2	Festival del Folclor	Festival anual de música y danza tradicional tolimense	2026-07-15	\N	Plaza Principal, Ortega	\N	\N	0.00	\N	\N	2026-04-22 12:19:04	2026-04-22 12:19:04
3	Feria Agropecuaria	Exposición de productos agrícolas y ganaderos	2026-08-20	\N	Parque Ferial, Ortega	\N	\N	0.00	\N	\N	2026-04-22 12:19:04	2026-04-22 12:19:04
1	bagres a la venta	el dia del bagres	2026-03-13	01:27:00	ortega-tolima	la mas bagruda	https://th.bing.com/th/id/OIP.AaGzAlj7P6XDEL6WmWIKaAHaFj?w=287&h=215&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3	100.00	andrea	12345678	2026-03-24 01:29:27	2026-04-23 05:00:59
4	Festival del Río Saldaña	Festival folclórico anual con música, danzas y gastronomía típica a orillas del río	2026-06-15	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
5	Feria Agropecuaria Ortega	Exposición de productos agrícolas, ganadería y artesanías del municipio	2026-07-20	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
6	Noche de Velas Ortega	Desfile de velas y faroles por las calles del centro histórico	2026-12-07	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
7	Festival de la Arepa Tolimense	Competencia y muestra gastronómica de la arepa de choclo y maíz	2026-08-10	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
8	Concierto Vallenato en el Parque	Noche de vallenato con artistas regionales y nacionales	2026-09-05	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
9	Torneo de Tejo Intermunicipal	Torneo oficial de tejo entre municipios del Tolima	2026-10-12	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
10	Semana del Ecoturismo	Actividades de senderismo, avistamiento de aves y talleres ambientales	2026-04-22	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
11	Exposición de Arte Pijao	Muestra de arte indígena con pinturas, tejidos y esculturas	2026-05-18	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
12	Tarde de Cine al Aire Libre	Proyección de películas colombianas bajo las estrellas	2026-08-28	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
13	Carrera de Montaña Pacandé	Competencia de trail running por los senderos del cerro Pacandé	2026-11-08	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
14	Festival de la Chicha y el Masato	Celebración de bebidas tradicionales con música y juegos típicos	2026-06-29	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
15	Encuentro de Cultivadores	Foro y muestra de caficultores, cacaoteros y paneleros del Tolima	2026-09-15	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
16	Noche de Leyendas Tolimenses	Narración de leyendas y mitos indígenas alrededor de la fogata	2026-10-31	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
17	Ciclovía Rural Ortega	Recorrido en bicicleta por veredas y paisajes del municipio	2026-05-03	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
18	Foro de Turismo Sostenible	Conferencias y paneles sobre turismo responsable en el Tolima	2026-07-04	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
19	Maratón Cultural Pijao	Visita guiada a petroglifos, iglesia colonial y museo local	2026-11-21	\N	\N	\N	\N	0.00	\N	\N	2026-04-28 00:53:56	2026-04-28 00:53:56
\.


--
-- TOC entry 5270 (class 0 OID 16493)
-- Dependencies: 231
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5288 (class 0 OID 17251)
-- Dependencies: 249
-- Data for Name: favoritos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.favoritos (id, usuario_id, tipo, item_id, created_at, updated_at) FROM stdin;
1	11	hotel	1	2026-03-25 05:32:45	2026-03-25 05:32:45
2	10	lugar	2	2026-04-28 12:31:18	2026-04-28 12:31:18
3	10	hotel	12	2026-04-28 12:34:13	2026-04-28 12:34:13
4	11	hotel	23	2026-04-28 12:45:02	2026-04-28 12:45:02
\.


--
-- TOC entry 5280 (class 0 OID 17193)
-- Dependencies: 241
-- Data for Name: gastronomia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gastronomia (id, nombre, descripcion, tipo, precio_promedio, restaurante, direccion, telefono, imagen, ingredientes, created_at, updated_at, empresa_id, ubicacion, latitud, longitud) FROM stdin;
1	bagres a la venta	delisioso	Plato típico	23445.00	ño se	Km 2 Vía Ortega-Chaparral	3173899366	https://th.bing.com/th/id/OIP.jxYj034O61ATyYNndTuuvwHaEw?w=275&h=180&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3	el vergel	2026-03-25 01:57:58	2026-03-25 01:57:58	1	ortega-tolima	\N	\N
23	fresa	\N	Postre	100.00	delicias	\N	\N	\N	\N	2026-04-23 06:09:18	2026-04-23 06:10:23	\N	\N	\N	\N
25	Viudo de Capaz	Sopa tradicional tolimense con pescado capaz, plátano y yuca	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
26	Tamal Tolimense	Tamal de maíz relleno de cerdo, arveja y zanahoria envuelto en hoja de plátano	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
27	Lechona Tolimense	Cerdo relleno de arroz, arveja y especias asado por 12 horas	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
28	Insulso de Maíz	Postre tradicional de maíz tierno con panela y canela	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
29	Masato de Arroz	Bebida fermentada de arroz con canela y clavo, fría y refrescante	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
30	Mojarra Frita del Saldaña	Mojarra entera frita del río Saldaña con patacones y ensalada	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
31	Arepa de Maíz Pelado	Arepa gruesa de maíz pelado con hogao y queso campesino	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
32	Sancocho de Gallina Criolla	Sancocho tradicional de gallina criolla con papa, yuca y mazorca	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
33	Chicha de Maíz Artesanal	Chicha fermentada de maíz amarillo preparada de forma ancestral	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
34	Envuelto de Choclo	Envuelto dulce de maíz tierno con queso y panela	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
35	Caldo de Costilla Campesino	Caldo espeso de costilla de res con papa criolla y cebolla larga	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
36	Bizcocho de Achira	Galleta artesanal de harina de achira con queso, crujiente y esponjosa	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
37	Guarrús Tolimense	Postre de maíz molido con panela, mantequilla y anís	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
38	Peto de Maíz	Bebida caliente de maíz con leche y panela, ideal para el frío	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
39	Arroz con Leche de Hacienda	Arroz cremoso con leche fresca de vaca, canela y arequipe	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
40	Bandeja Tolimense	Plato completo con tamal, lechona, arepa, chicharrón y mazorca	\N	\N	\N	\N	\N	\N	\N	2026-04-28 00:57:57	2026-04-28 00:57:57	\N	\N	\N	\N
\.


--
-- TOC entry 5294 (class 0 OID 24668)
-- Dependencies: 255
-- Data for Name: hero_images; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hero_images (id, titulo, url, seccion, activa, orden, tipo, created_at, updated_at) FROM stdin;
6	\N	hero/vCv8c3tb3bcPL8y1lPQHdhvGR4NML7ls0T90AVwe.jpg	destacadas	t	2	upload	2026-04-02 20:48:45	2026-04-02 20:48:45
7	\N	hero/hTpm6uDhd6JckpfWLeIe2fcd1Lx0NE8krwMD0ArL.jpg	cards	t	1	upload	2026-04-02 20:49:12	2026-04-02 20:49:12
3	el cerro de los Abechucos	hero/ARaBxursNDNpUkqfchI3CoLHJ2t9fMuD0JxfpahE.jpg	destacadas	f	1	upload	2026-03-25 03:12:33	2026-04-02 21:27:38
4	el cerro de los Abechucos	hero/1ZBF9xWJEWblkQhHmnKBrkXSH4Ft9HWnjZ4Cw3cM.jpg	hero	f	0	upload	2026-03-25 05:06:17	2026-04-07 12:43:45
5	\N	hero/X1DprJKgcq2Uo0RHAUWlFYuVPQ6cuB5Z7Og8Od15.jpg	hero	t	1	upload	2026-04-02 20:48:10	2026-04-07 12:43:45
8	\N	hero/cB0owXDpuSvl1jNMOeblfQU35x6Qp5z8xbYTlKIq.jpg	hero	f	2	upload	2026-04-07 12:43:40	2026-04-07 12:43:51
\.


--
-- TOC entry 5276 (class 0 OID 17163)
-- Dependencies: 237
-- Data for Name: hoteles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hoteles (id, nombre, descripcion, precio, ubicacion, latitud, longitud, imagen, servicios, capacidad, disponibilidad, telefono, email, created_at, updated_at, empresa_id) FROM stdin;
3	COYOTE	hermoso divino precioso	120000.00	ortega-tolima	-0.00000300	-0.00000500	uploads/hoteles/nPO0RSZyrpPUyIwdFjnxcSYx5dWsHLpVIHcC0fKG.jpg	PARQUEADERO	70	t	3174673829	COYOTE@gamil.com	2026-03-25 05:04:52	2026-03-25 05:04:52	\N
12	Hostal El Viajero	Hostal céntrico ideal para mochileros y turistas	45000.00	Carrera 6 #5-10, Ortega	\N	\N	https://th.bing.com/th/id/OIP.lpFeBvweMNkFGpiEbU-dmAHaHM?w=155&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi, Cocina Compartida, Lockers	25	t	3045678901	\N	2026-04-28 00:27:13	2026-04-28 01:16:55	\N
13	Hotel Boutique La Hacienda	Hotel boutique en casona colonial restaurada	200000.00	Calle 7 #4-56, Ortega	\N	\N	https://th.bing.com/th/id/OIP.tiOXaFU-vjy7oHxm4LLWsAHaE3?w=234&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi, Spa, Restaurante Gourmet, Jacuzzi	16	t	3156789012	\N	2026-04-28 00:27:13	2026-04-28 01:17:24	\N
24	Casa Viejas	natural	30000.00	ortega-tolima	3.93402100	-75.22126700	https://th.bing.com/th/id/OIP.AaGzAlj7P6XDEL6WmWIKaAHaFj?w=287&h=215&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3	piscina, restaurante, cancha de futbol, tenis, miradores	50	t	3174673829	casavieja@gmail.com	2026-04-28 13:51:16	2026-04-28 13:51:16	\N
10	Finca Hotel El Mango	Hotel finca con recorridos agroturísticos y piscina	150000.00	Km 4 Vía El Guamo	\N	\N	https://www.hotelescolombia.co/wp-content/uploads/2021/06/HOTEL-CASA-DE-LAS-PALMAS-GETSEMANI-CARTAGENA-HOTELES-COLOMBIA-0001.jpg	WiFi, Piscina, Caballos, Tours	60	t	3023456789	\N	2026-04-28 00:27:13	2026-04-28 01:13:24	\N
5	Hostal Central	Hostal económico en el centro del municipio	60000.00	Centro, Ortega	\N	\N	https://th.bing.com/th/id/OIP.ywzsPJYT0LQXvYhvS8aaYAHaDE?w=350&h=145&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi,Desayuno	20	t	3109876543	\N	2026-04-27 22:09:09	2026-04-28 01:14:33	\N
8	Hotel Campestre El Paraíso	Hotel campestre con piscina natural y zonas verdes	120000.00	Km 2 Vía Ortega-Chaparral	\N	\N	https://th.bing.com/th/id/OIP.xI9Fxhevbntshd4eeEIhXAHaEK?w=333&h=187&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi, Piscina, Restaurante, Parqueadero	40	t	3201234567	\N	2026-04-28 00:27:13	2026-04-28 01:15:33	\N
9	Posada La Ceiba	Posada familiar con habitaciones cómodas y desayuno incluido	80000.00	Calle 5 #8-23, Ortega	\N	\N	https://th.bing.com/th/id/OIP.RDSd0sBVRArf1XGTVz427AHaJ4?w=169&h=220&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi, Desayuno, TV Cable	20	t	3112345678	\N	2026-04-28 00:27:13	2026-04-28 01:16:03	\N
11	Cabañas Río Saldaña	Cabañas privadas a orillas del río Saldaña	95000.00	Orillas Río Saldaña	\N	\N	https://th.bing.com/th/id/OIP.66ddRsozF9v68Ij9AF3AdQHaFj?w=233&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	Río, BBQ, Hamacas, Parqueadero	30	t	3134567890	\N	2026-04-28 00:27:13	2026-04-28 01:16:25	\N
14	Eco Lodge Pacandé	Ecolodge en el cerro con vista al valle y senderos	180000.00	Cerro Pacandé, Ortega	\N	\N	https://th.bing.com/th/id/OIP.V6MlR_3x8Dtn4X80NxlaIgHaFN?w=282&h=198&c=7&r=0&o=5&dpr=1.3&pid=1.7	Tours, Senderismo, Comida Orgánica	20	t	3067890123	\N	2026-04-28 00:27:13	2026-04-28 01:18:28	\N
15	Glamping Las Estrellas	Carpas de lujo bajo las estrellas del Tolima	130000.00	Vereda El Triunfo, Ortega	\N	\N	https://th.bing.com/th/id/OIP.IqwMOPvJdIfUcVzZXRLA6gHaE8?w=274&h=182&c=7&r=0&o=5&dpr=1.3&pid=1.7	Glamping, Fogata, Telescopio, Desayuno	12	t	3178901234	\N	2026-04-28 00:27:13	2026-04-28 01:18:56	\N
16	Hotel Los Llanos	Hotel de negocios con sala de conferencias	110000.00	Av Principal #12-34, Ortega	\N	\N	https://th.bing.com/th/id/OIP.D-Km242_diuiPshnAb5zwwHaE8?w=274&h=182&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi, Sala Conferencias, Restaurante	50	t	3089012345	\N	2026-04-28 00:27:13	2026-04-28 01:19:18	\N
17	Posada Don Jesús	Posada tradicional con gastronomía típica tolimense	60000.00	Calle 3 #6-78, Ortega	\N	\N	https://th.bing.com/th/id/OIP.pbvAWBum77LcO6oJ2-tXsQHaFj?w=205&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi, Comida Típica, Parqueadero	15	t	3190123456	\N	2026-04-28 00:27:13	2026-04-28 01:19:47	\N
18	Resort Termal La Sulfurosa	Resort con acceso a piscinas termales naturales	250000.00	Vereda La Sulfurosa	\N	\N	https://th.bing.com/th/id/OIP.e4AMJe3eao68k0ekplZk_QHaFx?w=215&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	Termales, Spa, Restaurante, Yoga	35	t	3201234568	\N	2026-04-28 00:27:13	2026-04-28 01:20:13	\N
19	Cabaña El Cedro	Cabaña privada en bosque con chimenea	140000.00	Vereda Los Cedros, Ortega	\N	\N	https://th.bing.com/th/id/OIP.Tfb4Ezyid743LcZXwzzMvAHaFj?w=237&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	Chimenea, BBQ, Jardín Privado	8	t	3112345679	\N	2026-04-28 00:27:13	2026-04-28 01:20:37	\N
20	Hotel Plaza Central	Hotel moderno frente al parque principal	100000.00	Calle 6 #7-89, Ortega	\N	\N	https://th.bing.com/th/id/OIP.O-f4mzdsyhJnQjL9de3nogHaE7?w=267&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	WiFi, Restaurante, TV Smart	45	t	3023456790	\N	2026-04-28 00:27:13	2026-04-28 01:20:57	\N
21	Villa El Retiro	Villa privada con cancha de tejo y zona BBQ	170000.00	Km 1 Vía Guamo	\N	\N	https://th.bing.com/th/id/OIP.tAZgTLHb4BZ0THcrk5tTfAAAAA?w=212&h=140&c=7&r=0&o=5&dpr=1.3&pid=1.7	Piscina, Tejo, BBQ, Parqueadero	24	t	3134567891	\N	2026-04-28 00:27:13	2026-04-28 01:21:14	\N
22	Hostal Tierra Viva	Hostal ecológico con huerta orgánica y clases de cocina	55000.00	Carrera 4 #8-12, Ortega	\N	\N	https://th.bing.com/th/id/OIP.Z8YBQXtoPUscEOENh9XniQHaEK?w=283&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	Huerta, Cocina Compartida, WiFi	18	t	3045678902	\N	2026-04-28 00:27:13	2026-04-28 01:21:36	\N
23	Hotel Río Verde	Hotel con vista al río y kayak incluido	125000.00	Orillas Río Verde, Ortega	\N	\N	https://th.bing.com/th/id/OIP.3_jBBm6sP1d62VMUhqXvcQHaHX?w=181&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	Kayak, Piscina, Restaurante, WiFi	32	t	3156789013	\N	2026-04-28 00:27:13	2026-04-28 01:22:03	\N
25	Salón Principal	Amplio salón con iluminación natural y aire acondicionado	500000.00	Bogotá, Centro	\N	\N	\N	WiFi, Proyector, Audio	100	t	3001234567	\N	2026-04-28 13:56:19	2026-04-28 13:56:19	\N
2	Casa Vieja	hola beby	30000.00	ortega-tolima	3.93379200	-75.22142800	https://th.bing.com/th/id/OIP.AaGzAlj7P6XDEL6WmWIKaAHaFj?w=287&h=215&c=7&r=0&o=7&dpr=1.5&pid=1.7&rm=3	piscina, restaurante, cancha de futbol, tenis, miradores	50	t	3174673829	casavieja@gmail.com	2026-03-24 01:02:49	2026-04-28 13:16:39	\N
26	Sala de Reuniones A	Sala equipada para reuniones ejecutivas	150000.00	Bogotá, Chapinero	\N	\N	\N	WiFi, TV, Café	20	t	3007654321	\N	2026-04-28 13:56:19	2026-04-28 13:56:19	\N
\.


--
-- TOC entry 5268 (class 0 OID 16478)
-- Dependencies: 229
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 5267 (class 0 OID 16463)
-- Dependencies: 228
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 5274 (class 0 OID 17149)
-- Dependencies: 235
-- Data for Name: lugares; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lugares (id, nombre, descripcion, ubicacion, latitud, longitud, categoria, imagen, precio_entrada, horario, created_at, updated_at) FROM stdin;
11	Mirador El Cielo	Vista panorámica de 360° sobre el valle del río Saldaña	Alto de La Cruz, Ortega	\N	\N	Mirador	https://th.bing.com/th/id/OIP.0HY4NExWaL3g9fD2df5GbwHaHa?w=200&h=200&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	24 horas	2026-04-28 00:25:26	2026-04-28 01:40:03
2	Mirador El Cielos	Vista espectacular...	Alto de La Cruz, Ortega	3.83456000	-75.24567900	Mirador	https://th.bing.com/th/id/OIP.vnso2x_vfPjNGC2BC7aiNAHaE8?w=311&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	24 horas	\N	2026-04-28 01:23:40
26	Iglesia San Roque	Iglesia colonial de 1890 con arquitectura republicana	Centro, Ortega	\N	\N	Patrimonio	https://th.bing.com/th/id/OIP.FWXUn0C49LAT9hrY-TCBOgHaE8?w=235&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	7:00am - 12pm, 3pm-7pm	2026-04-28 00:25:26	2026-04-28 01:28:06
5	Cascada El Salto	Hermosa cascada natural rodeada de vegetación	Vereda El Limón, Ortega	\N	\N	naturaleza	https://th.bing.com/th/id/OIP.yHd59eB0-_DpF43gXt4z9gAAAA?w=236&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	\N	2026-04-22 12:12:50	2026-04-28 01:28:30
6	Parque Central	Plaza principal del municipio con kiosco y jardines	Centro, Ortega	\N	\N	turistico	https://th.bing.com/th/id/OIP.l0p07t7zWPfSGq_GD76tXQHaEK?w=295&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	\N	2026-04-22 12:12:50	2026-04-28 01:29:45
25	Finca Agro El Mango	Finca agroturística con mangos, plátanos y cultivos	Km 3 Vía Guamo	\N	\N	Agroturismo	https://th.bing.com/th/id/OIP.HX-BAFcc5wI7OdeKgF-STgHaEK?w=300&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	18000.00	8:00am - 5:00pm	2026-04-28 00:25:26	2026-04-28 01:36:14
7	Hotel El Paraíso	Hotel campestre con piscina y zonas verdes	Km 2 Vía Ortega-Chaparral	\N	\N	naturaleza	https://th.bing.com/th/id/OIP.vzTEqK3n0BskQ-nbdICMtwHaE8?w=238&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	\N	2026-04-27 22:13:52	2026-04-28 01:37:02
8	Hostal Central	Hostal económico en el centro del municipio	Centro, Ortega	\N	\N	naturaleza	https://th.bing.com/th/id/OIP.BnGRsMv1RUWKHSjJ6m-x9gHaHZ?w=178&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	\N	2026-04-27 22:13:52	2026-04-28 01:37:43
9	Hotel El Paraíso	Hotel campestre con piscina y zonas verdes	Km 2 Vía Ortega-Chaparral	\N	\N	naturaleza	https://th.bing.com/th/id/OIP.Mp6QSFJxaOAghETO9JBrfwHaFj?w=245&h=184&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	\N	2026-04-27 22:14:06	2026-04-28 01:38:48
10	Hostal Central	Hostal económico en el centro del municipio	Centro, Ortega	\N	\N	turistico	https://th.bing.com/th/id/OIP.AAY6wXsNFUvRaE5o4ZG4VwHaE5?w=249&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	\N	2026-04-27 22:14:06	2026-04-28 01:39:32
12	Cascada La Palmita	Caída de agua de 30m rodeada de selva tropical	Vereda La Palmita, Ortega	\N	\N	Cascada	https://th.bing.com/th/id/OIP.WsBHJV8LC8-O26LrLoEsHgHaE7?w=197&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	5000.00	7:00am - 5:00pm	2026-04-28 00:25:26	2026-04-28 01:41:48
13	Parque Principal Ortega	Centro del municipio con kioscos y zona de descanso	Centro, Ortega	\N	\N	Parque	https://th.bing.com/th/id/OIP.z_K-G_aQFUaLAUtop-rdBQHaE8?w=251&h=180&c=7&r=0&o=5&dpr=1.3&pid=1.7	0.00	Todo el día	2026-04-28 00:25:26	2026-04-28 01:42:58
24	Mirador Tres Cruces	Tres cruces históricas con vista al valle	Vereda San Isidro	\N	\N	Mirador	https://www.fotopaises.com/Fotos-Paises/t1024/2017/1/30/2472_1485627306.jpg	0.00	Todo el día	2026-04-28 00:25:26	2026-04-28 01:47:13
14	Laguna El Guamo	Laguna natural ideal para pesca deportiva y picnic	Vereda El Guamo, Ortega	\N	\N	Laguna	https://mexicorutamagica.mx/wp-content/uploads/2021/04/lagunas-zempoala-morelos-esta-abierto-2021.jpg	8000.00	6:00am - 6:00pm	2026-04-28 00:25:26	2026-04-28 01:48:59
15	Cerro Pacandé	Cerro emblemático con senderos ecológicos	Límites Ortega-Natagaima	\N	\N	Senderismo	https://definicion.de/wp-content/uploads/2009/07/cerro-1.jpg	0.00	6:00am - 4:00pm	2026-04-28 00:25:26	2026-04-28 01:50:46
16	Balneario Río Saldaña	Playa de río con aguas cristalinas	Orillas Río Saldaña, Ortega	\N	\N	Balneario	https://igui-ecologia.s3.amazonaws.com/wp-content/uploads/2017/08/ImagemRio.jpg	10000.00	8:00am - 6:00pm	2026-04-28 00:25:26	2026-04-28 01:52:02
17	Hacienda El Otoño	Finca colonial con recorridos de café y cacao	Km 5 Vía Chaparral, Ortega	\N	\N	Hacienda	https://haciendatepich.com.mx/wp-content/uploads/2021/07/fachada1-1536x1024.jpg	15000.00	8:00am - 5:00pm	2026-04-28 00:25:26	2026-04-28 01:53:46
18	Piedra Pintada	Petroglifos indígenas Pijao de más de 1000 años	Vereda Llano Grande, Ortega	\N	\N	Sitio Histórico	https://aamtuc.org/wp-content/uploads/2019/01/Piedra-Pintada.jpg	3000.00	8:00am - 4:00pm	2026-04-28 00:25:26	2026-04-28 01:55:42
19	Cañón del Cobre	Formación geológica de roca roja con sendero	Vereda El Cobre, Ortega	\N	\N	Cañón	https://th.bing.com/th/id/R.7bdf884cd59b13834b299521802e69b4?rik=lorKYxZfzu69Rg&riu=http%3a%2f%2fblog.redbus.co%2fwp-content%2fuploads%2f2019%2f04%2f100_8759.jpg&ehk=vqEuFTxpWdmKqL8YcEjgLRUH7j5Fv%2b4EVYDj2w%2bQpK0%3d&risl=&pid=ImgRaw&r=0	7000.00	7:00am - 5:00pm	2026-04-28 00:25:26	2026-04-28 01:56:40
20	Reserva Natural Pijao	Bosque seco tropical con avistamiento de aves	Km 8 Vía Natagaima	\N	\N	Reserva Natural	https://cdn2.hubspot.net/hubfs/1794060/Fotos%20Art%C3%ADculos%20-%20Redes/01_FEBRERO_RESERVAS_NATURALES.jpg	12000.00	6:00am - 5:00pm	2026-04-28 00:25:26	2026-04-28 01:58:06
21	Plaza de Mercado	Mercado campesino tradicional con productos locales	Centro, Ortega	\N	\N	Mercado	https://viajandox.com.co/uploads/attractive_9.jpg	0.00	Sábados 6am-2pm	2026-04-28 00:25:26	2026-04-28 01:59:34
22	Sendero de los Ceibos	Camino entre ceibos centenarios con observación de fauna	Vereda Potrerillo	\N	\N	Senderismo	https://st4.depositphotos.com/9999814/24062/i/950/depositphotos_240621264-stock-photo-beautiful-wooden-path-trail-nature.jpg	0.00	6:00am - 5:00pm	2026-04-28 00:25:26	2026-04-28 02:00:25
23	Termales La Sulfurosa	Aguas termales sulfurosas con propiedades medicinales	Vereda La Sulfurosa, Ortega	\N	\N	Termal	https://th.bing.com/th/id/R.4b6753c70ebb83bb94b6354dada8099b?rik=RXHeKaExNB1G%2bQ&riu=http%3a%2f%2fblog.redbus.co%2fwp-content%2fuploads%2f2019%2f10%2fTermales-Santa-Rosa-de-Cabal.jpg&ehk=TFaTP9d0yrKrOfpJXtPRyc8YLCNIj9Hm80UhJtYpQAg%3d&risl=&pid=ImgRaw&r=0	20000.00	7:00am - 6:00pm	2026-04-28 00:25:26	2026-04-28 02:01:18
\.


--
-- TOC entry 5259 (class 0 OID 16389)
-- Dependencies: 220
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
44	2026_03_19_133938_create_empresas_table	2
45	2026_03_19_133952_create_lugares_table	2
46	2026_03_19_134020_create_hoteles_table	2
47	2026_03_19_134037_create_eventos_table	2
48	2026_03_19_134049_create_gastronomia_table	2
49	2026_03_19_134111_create_reservas_table	2
50	2026_03_19_134117_create_comentarios_table	2
51	2026_03_19_134124_create_calificaciones_table	2
52	2026_03_19_134130_create_favoritos_table	2
53	2026_03_19_134141_create_notificaciones_admin_table	2
54	2026_03_24_222812_add_foreign_keys	3
55	2026_03_25_000001_create_blog_posts_table	4
56	2026_03_25_000002_update_calificaciones_add_types	4
57	2026_03_25_000003_update_gastronomia_add_empresa	4
58	2026_03_25_100000_create_hero_images_table	5
59	2026_03_25_200000_update_favoritos_add_types	6
60	2026_03_25_200001_add_empresa_id_to_hoteles	6
61	2026_04_07_154057_add_servicio_fields_to_empresas_table	7
62	2026_04_07_154736_create_servicios_table	8
63	2026_04_14_014527_add_latitud_longitud_to_gastronomia_table	9
64	2026_04_27_011046_create_pedidos_table	10
65	2026_04_27_011050_create_detalle_pedidos_table	10
66	2026_04_27_023120_add_guest_fields_to_pedidos_table	11
67	2026_04_27_220442_add_pago_to_reservas_table	12
68	2026_04_28_000001_create_planes_turisticos_table	13
\.


--
-- TOC entry 5290 (class 0 OID 17265)
-- Dependencies: 251
-- Data for Name: notificaciones_admin; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notificaciones_admin (id, empresa_id, mensaje, leido, created_at, updated_at) FROM stdin;
1	1	SOLICITUD NUEVO HOTEL\nsapa gordille godille gordille	t	2026-03-25 00:28:28	2026-03-25 00:29:59
\.


--
-- TOC entry 5262 (class 0 OID 16419)
-- Dependencies: 223
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5298 (class 0 OID 24722)
-- Dependencies: 259
-- Data for Name: pedidos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.pedidos (id, usuario_id, total, estado, metodo_pago, referencia, created_at, updated_at, guest_nombre, guest_email, guest_telefono) FROM stdin;
1	11	120000.00	pendiente	nequi	FZ-69EED1BDDD958	2026-04-27 03:02:21	2026-04-27 03:02:21	\N	\N	\N
\.


--
-- TOC entry 5302 (class 0 OID 24775)
-- Dependencies: 263
-- Data for Name: planes_turisticos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.planes_turisticos (id, empresa_id, titulo, evento_id, gastronomia_id, hotel_id, lugar_id, subtotal, descuento, precio_final, created_at, updated_at) FROM stdin;
1	1	Plan 28/04/2026 13:04	15	1	8	8	143445.00	28689.00	114756.00	2026-04-28 13:04:23	2026-04-28 13:04:23
2	1	Plan 28/04/2026 13:53	15	1	2	14	61445.00	12289.00	49156.00	2026-04-28 13:53:28	2026-04-28 13:53:28
\.


--
-- TOC entry 5282 (class 0 OID 17205)
-- Dependencies: 243
-- Data for Name: reservas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reservas (id, usuario_id, hotel_id, fecha_entrada, fecha_salida, num_personas, precio_total, estado, created_at, updated_at, metodo_pago, referencia_pago, estado_pago) FROM stdin;
20	11	2	2026-04-28	2026-04-30	1	60000.00	cancelada	2026-04-27 03:06:56	2026-04-27 04:10:39	\N	\N	pendiente
25	15	2	2026-05-01	2026-05-03	1	2.00	pendiente	2026-04-27 18:16:23	2026-04-27 18:16:23	\N	\N	pendiente
18	10	2	2026-04-30	2026-05-04	1	0.00	pendiente	2026-04-27 02:43:07	2026-04-27 18:23:30	\N	\N	pendiente
27	13	3	2026-05-02	2026-05-03	1	0.00	pendiente	2026-04-27 18:24:43	2026-04-27 18:24:43	\N	\N	pendiente
28	11	2	2026-04-29	2026-05-07	1	240000.00	confirmada	2026-04-28 00:03:28	2026-04-28 00:03:28	nequi	FZ-260428-74270	pagado
29	10	23	2026-04-30	2026-05-01	1	125000.00	confirmada	2026-04-28 12:32:18	2026-04-28 12:32:18	nequi	FZ-260428-65099	pagado
30	10	12	2026-04-29	2026-04-30	1	45000.00	confirmada	2026-04-28 12:34:29	2026-04-28 12:34:29	nequi	FZ-260428-40310	pagado
31	11	8	2026-04-29	2026-04-30	1	120000.00	pendiente	2026-04-28 13:00:16	2026-04-28 13:00:16	\N	\N	pendiente
32	11	8	2026-05-06	2026-05-08	1	240000.00	confirmada	2026-04-28 13:01:40	2026-04-28 13:01:40	nequi	FZ-260428-69077	pagado
33	11	23	2026-04-30	2026-05-02	1	250000.00	confirmada	2026-04-28 13:08:38	2026-04-28 13:08:38	nequi	FZ-260428-39087	pagado
34	11	22	2026-04-30	2026-05-07	3	385000.00	confirmada	2026-04-28 13:48:57	2026-04-28 13:48:57	nequi	FZ-260428-42067	pagado
\.


--
-- TOC entry 5296 (class 0 OID 24702)
-- Dependencies: 257
-- Data for Name: servicios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.servicios (id, empresa_id, nombre, descripcion, precio, imagen, activo, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5263 (class 0 OID 16428)
-- Dependencies: 224
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
CxBd0vQoyLRgigmATUHlLEtJq4lGuw9pgXwKVoT0	15	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0	YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQXozc2VoNldyWWFKZ2pRUkplTXlsNUJ0cFNrN2tMdEJibFozR0ZleCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9lbXByZXNhL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNzoiZW1wcmVzYS5kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxNTt9	1774419061
\.


--
-- TOC entry 5261 (class 0 OID 16399)
-- Dependencies: 222
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, rol, estado, avatar, telefono, remember_token, created_at, updated_at) FROM stdin;
10	Administrador	admin@flowzone.com	\N	$2y$12$eq/y7hytYJx20DoUgjQHYOA4fVKpZOX3yFq4XBUXVHSYPe3h8mfbe	admin	activo	\N	\N	\N	2026-03-19 15:35:18	2026-03-19 15:35:18
11	Juan Pérez	juan@example.com	\N	$2y$12$hsyiEMo.yFcrdtRvD02/R.Wj.DLNWZvuFulH5MfW6OTI8UVGUt4R.	usuario	activo	\N	\N	\N	2026-03-19 15:35:18	2026-03-19 15:35:18
12	Hotel El Paraíso S.A.S	empresa@example.com	\N	$2y$12$LirjvceRcHjSaiQbJmaQ2ehQH3Yx6SNaPl91qV7XTr3o063Av5Y0K	empresa	activo	\N	\N	\N	2026-03-19 15:35:18	2026-03-19 15:35:18
13	stephanie	stephaniesancheztapiero@gmail.com	\N	$2y$12$qNcKsvZ8oK1jTkU6sw5SS.VL0oP0LbrXY4a4.n4gWBM0f3XY7xDO6	usuario	activo	\N	3173899366	\N	2026-03-25 00:14:51	2026-03-25 00:14:51
14	el bagre	222stephasantapiez@gmail.com	\N	$2y$12$Tkl2RMcKhxYnrMVUZ2u2XOePATbYrr4my1Oodo7sSs/xpVyrpBFJO	empresa	pendiente	\N	312679876	\N	2026-03-25 00:19:57	2026-03-25 00:19:57
15	lorens	admin@empresa.com	\N	$2y$12$8HAdrz0sDhxTGA7xiOgx5eDtTrQtOQ1MuxkUZ0rGBHmNOybO0x356	empresa	activo	\N	3173899366	\N	2026-03-25 06:06:47	2026-03-25 06:07:15
17	Adri	adrillegatarde@dormir.com	\N	$2y$12$vZckwjNqc3AH8NNG7pe20uj.j8jjoXMbs/Jc6AE4WJ6z/1E/OoJ42	usuario	activo	\N	\N	\N	2026-04-09 12:44:22	2026-04-09 12:44:22
21	Juan Pérez	juan.perez@ejemplo.com	\N	$2y$12$Vyf0fGb2jPQxBNuBaiIKduDfctFSIdC7FI0wH4ofU6ch0HOmvy2bW	usuario	activo	\N	\N	\N	2026-04-23 11:55:34	2026-04-23 11:55:34
22	María García	maria.garcia@ejemplo.com	\N	$2y$12$DszTknXycknQrSkEFhgDIuFluqsnyi8bp1tVJYjPgTGKm8VBpsHcq	usuario	activo	\N	\N	\N	2026-04-23 11:55:35	2026-04-23 11:55:35
23	samir montiel	samirmontiel@gmail.com	\N	$2y$12$FJDZ14snWQHiBs.lPmg7Keor..mBgTge9xDCNj4XXdyt6slBtSAxS	usuario	activo	\N	354678976	\N	2026-04-28 00:23:36	2026-04-28 00:23:36
24	Turismo Tolima SAS	info@turismotolima.com	\N	$2y$12$Q2vC1HsLV57dfEYs4c.3Be4yTCQtPix/XtXmb9ZxGUtDI4A3JiEnS	empresa	pendiente	\N	3201112233	\N	2026-04-28 00:28:37	2026-04-28 00:28:37
25	Aventura Saldaña	aventura@saldana.com	\N	$2y$12$AVwEH4nxds/7kXss0x0dJeVfxgPfj9xvLiJHMvSXBeZq4qq8kMK5e	empresa	pendiente	\N	3112223344	\N	2026-04-28 00:28:37	2026-04-28 00:28:37
26	Sabor Tolimense SAS	sabor@tolimense.com	\N	$2y$12$xKcWTS1gg20CUtvEFcKEDuSXz0wxAbJJB2lYSFFGh/Vv7iWEZrWV.	empresa	pendiente	\N	3023334455	\N	2026-04-28 00:28:38	2026-04-28 00:28:38
27	Finca Ecoturística Las Palmas	laspalmas@finca.com	\N	$2y$12$yBcFziZ/4qQEKDWfbWgZl.f8Qp7qT/XdU4wSGcHbCyfwu2YO1Nuq.	empresa	pendiente	\N	3134445566	\N	2026-04-28 00:28:38	2026-04-28 00:28:38
28	Eventos y Logística Ortega	eventos@ortega.com	\N	$2y$12$2jAwAkJBOW5fwyYNrcXpo.eO2gNm0BvhaLz4EOlT5mzuZZ1zgfAZG	empresa	pendiente	\N	3045556677	\N	2026-04-28 00:28:39	2026-04-28 00:28:39
29	Transporte Turístico El Valle	elvalle@transporte.com	\N	$2y$12$1jfEGKZw8t/nuUHSOU92x.Te7K/G80IdUk6oiYnYLVutFZHSIomKu	empresa	pendiente	\N	3156667788	\N	2026-04-28 00:28:39	2026-04-28 00:28:39
30	Arte y Artesanías Pijao	artesanias@pijao.com	\N	$2y$12$BAeMYaHa8/Njd2J4vLzGkuZqqeHsDS1T4cm8BcNNSnoa5Uc.QVS3m	empresa	pendiente	\N	3067778899	\N	2026-04-28 00:28:40	2026-04-28 00:28:40
31	Guías Nativos del Saldaña	guias@saldana.com	\N	$2y$12$B9yf4eb5qkI5U0ir1AR8g.qYKLybZFs96SBA70LjLqbTbBpbAvsPW	empresa	pendiente	\N	3178889900	\N	2026-04-28 00:28:40	2026-04-28 00:28:40
32	Hotel Campestre SAS	info@hotelcampestre.com	\N	$2y$12$o3JSDXovWUL8/zSQWrTVFuNBclLCVbAopA8riANUy/huAyB8JOptq	empresa	pendiente	\N	3089990011	\N	2026-04-28 00:28:41	2026-04-28 00:28:41
33	Wellness Termal Ortega	wellness@termal.com	\N	$2y$12$Puwip9V5.9d5EQ/atlrL/OipX4RFAxJwoSr1U2nCGrJ5lgpJhBwQ.	empresa	pendiente	\N	3190001122	\N	2026-04-28 00:28:41	2026-04-28 00:28:41
34	Fotografía y Turismo Visual	foto@turismovisual.com	\N	$2y$12$PtZRvtGu1XA/dKRWy6F9vu.2QZWLyj1kQRIlmZ2YZiKfi5DobEwba	empresa	pendiente	\N	3201112234	\N	2026-04-28 00:28:42	2026-04-28 00:28:42
35	Agroturismo Familiar Los Mangos	losmangos@agro.com	\N	$2y$12$B4CeARBd3anqN7Nz7fiI4ODW9rkyPRbdIqXLtE7it3kXZZJIUNvw2	empresa	pendiente	\N	3112223345	\N	2026-04-28 00:28:42	2026-04-28 00:28:42
36	Camping Salvaje Ortega	camping@salvaje.com	\N	$2y$12$k9ZVXgoDd53CSQfyvr9WHeMS8lSnamOxroHGpsO.icmf0p5JFVqyW	empresa	pendiente	\N	3023334456	\N	2026-04-28 00:28:43	2026-04-28 00:28:43
37	Cocina de la Abuela SAS	abuela@cocina.com	\N	$2y$12$/kTUJoqaoDhwNu9dnexZPO7ATJ.5.MfBbAHDPGjbqgVqqgCzVhJYa	empresa	pendiente	\N	3134445567	\N	2026-04-28 00:28:44	2026-04-28 00:28:44
38	Deportes en Río SAS	rios@deportes.com	\N	$2y$12$nJsMFu.sDKNaqgRq9bsd0.sFCau81.ecafREZy49t3HQr9wAa08pq	empresa	pendiente	\N	3045556678	\N	2026-04-28 00:28:44	2026-04-28 00:28:44
39	Centro Cultural Pijao	cultura@pijao.com	\N	$2y$12$J2rGU28zX1iWUphBMBdTX.cWrgQolJZKYm4YONXupXzOMMGAY3VX6	empresa	pendiente	\N	3156667789	\N	2026-04-28 00:28:44	2026-04-28 00:28:44
\.


--
-- TOC entry 5328 (class 0 OID 0)
-- Dependencies: 252
-- Name: blog_posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.blog_posts_id_seq', 35, true);


--
-- TOC entry 5329 (class 0 OID 0)
-- Dependencies: 246
-- Name: calificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.calificaciones_id_seq', 1, false);


--
-- TOC entry 5330 (class 0 OID 0)
-- Dependencies: 244
-- Name: comentarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.comentarios_id_seq', 1, false);


--
-- TOC entry 5331 (class 0 OID 0)
-- Dependencies: 260
-- Name: detalle_pedidos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.detalle_pedidos_id_seq', 1, true);


--
-- TOC entry 5332 (class 0 OID 0)
-- Dependencies: 232
-- Name: empresas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.empresas_id_seq', 18, true);


--
-- TOC entry 5333 (class 0 OID 0)
-- Dependencies: 238
-- Name: eventos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.eventos_id_seq', 19, true);


--
-- TOC entry 5334 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5335 (class 0 OID 0)
-- Dependencies: 248
-- Name: favoritos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.favoritos_id_seq', 5, true);


--
-- TOC entry 5336 (class 0 OID 0)
-- Dependencies: 240
-- Name: gastronomia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gastronomia_id_seq', 40, true);


--
-- TOC entry 5337 (class 0 OID 0)
-- Dependencies: 254
-- Name: hero_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hero_images_id_seq', 8, true);


--
-- TOC entry 5338 (class 0 OID 0)
-- Dependencies: 236
-- Name: hoteles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hoteles_id_seq', 26, true);


--
-- TOC entry 5339 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5340 (class 0 OID 0)
-- Dependencies: 234
-- Name: lugares_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lugares_id_seq', 26, true);


--
-- TOC entry 5341 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 68, true);


--
-- TOC entry 5342 (class 0 OID 0)
-- Dependencies: 250
-- Name: notificaciones_admin_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificaciones_admin_id_seq', 1, true);


--
-- TOC entry 5343 (class 0 OID 0)
-- Dependencies: 258
-- Name: pedidos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.pedidos_id_seq', 1, true);


--
-- TOC entry 5344 (class 0 OID 0)
-- Dependencies: 262
-- Name: planes_turisticos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.planes_turisticos_id_seq', 2, true);


--
-- TOC entry 5345 (class 0 OID 0)
-- Dependencies: 242
-- Name: reservas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reservas_id_seq', 34, true);


--
-- TOC entry 5346 (class 0 OID 0)
-- Dependencies: 256
-- Name: servicios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.servicios_id_seq', 1, false);


--
-- TOC entry 5347 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 39, true);


--
-- TOC entry 5081 (class 2606 OID 24635)
-- Name: blog_posts blog_posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_pkey PRIMARY KEY (id);


--
-- TOC entry 5083 (class 2606 OID 24648)
-- Name: blog_posts blog_posts_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_slug_unique UNIQUE (slug);


--
-- TOC entry 5038 (class 2606 OID 16460)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 5035 (class 2606 OID 16449)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 5069 (class 2606 OID 17246)
-- Name: calificaciones calificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones
    ADD CONSTRAINT calificaciones_pkey PRIMARY KEY (id);


--
-- TOC entry 5072 (class 2606 OID 24650)
-- Name: calificaciones calificaciones_usuario_id_tipo_item_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones
    ADD CONSTRAINT calificaciones_usuario_id_tipo_item_id_unique UNIQUE (usuario_id, tipo, item_id);


--
-- TOC entry 5067 (class 2606 OID 17233)
-- Name: comentarios comentarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_pkey PRIMARY KEY (id);


--
-- TOC entry 5092 (class 2606 OID 24757)
-- Name: detalle_pedidos detalle_pedidos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_pedidos
    ADD CONSTRAINT detalle_pedidos_pkey PRIMARY KEY (id);


--
-- TOC entry 5049 (class 2606 OID 17145)
-- Name: empresas empresas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas
    ADD CONSTRAINT empresas_pkey PRIMARY KEY (id);


--
-- TOC entry 5051 (class 2606 OID 17147)
-- Name: empresas empresas_usuario_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas
    ADD CONSTRAINT empresas_usuario_id_unique UNIQUE (usuario_id);


--
-- TOC entry 5060 (class 2606 OID 17190)
-- Name: eventos eventos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos
    ADD CONSTRAINT eventos_pkey PRIMARY KEY (id);


--
-- TOC entry 5045 (class 2606 OID 16508)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 5047 (class 2606 OID 16510)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 5074 (class 2606 OID 17261)
-- Name: favoritos favoritos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_pkey PRIMARY KEY (id);


--
-- TOC entry 5076 (class 2606 OID 24687)
-- Name: favoritos favoritos_usuario_id_tipo_item_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_usuario_id_tipo_item_id_unique UNIQUE (usuario_id, tipo, item_id);


--
-- TOC entry 5062 (class 2606 OID 17202)
-- Name: gastronomia gastronomia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gastronomia
    ADD CONSTRAINT gastronomia_pkey PRIMARY KEY (id);


--
-- TOC entry 5086 (class 2606 OID 24685)
-- Name: hero_images hero_images_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hero_images
    ADD CONSTRAINT hero_images_pkey PRIMARY KEY (id);


--
-- TOC entry 5057 (class 2606 OID 17175)
-- Name: hoteles hoteles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hoteles
    ADD CONSTRAINT hoteles_pkey PRIMARY KEY (id);


--
-- TOC entry 5043 (class 2606 OID 16491)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 5040 (class 2606 OID 16476)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 5054 (class 2606 OID 17160)
-- Name: lugares lugares_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lugares
    ADD CONSTRAINT lugares_pkey PRIMARY KEY (id);


--
-- TOC entry 5022 (class 2606 OID 16397)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 5079 (class 2606 OID 17277)
-- Name: notificaciones_admin notificaciones_admin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones_admin
    ADD CONSTRAINT notificaciones_admin_pkey PRIMARY KEY (id);


--
-- TOC entry 5028 (class 2606 OID 16427)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 5090 (class 2606 OID 24735)
-- Name: pedidos pedidos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos
    ADD CONSTRAINT pedidos_pkey PRIMARY KEY (id);


--
-- TOC entry 5094 (class 2606 OID 24788)
-- Name: planes_turisticos planes_turisticos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planes_turisticos
    ADD CONSTRAINT planes_turisticos_pkey PRIMARY KEY (id);


--
-- TOC entry 5065 (class 2606 OID 17220)
-- Name: reservas reservas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas
    ADD CONSTRAINT reservas_pkey PRIMARY KEY (id);


--
-- TOC entry 5088 (class 2606 OID 24715)
-- Name: servicios servicios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_pkey PRIMARY KEY (id);


--
-- TOC entry 5031 (class 2606 OID 16437)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 5024 (class 2606 OID 16418)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 5026 (class 2606 OID 16416)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 5084 (class 1259 OID 24646)
-- Name: blog_posts_tipo_publicado_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX blog_posts_tipo_publicado_index ON public.blog_posts USING btree (tipo, publicado);


--
-- TOC entry 5033 (class 1259 OID 16450)
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- TOC entry 5036 (class 1259 OID 16461)
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- TOC entry 5070 (class 1259 OID 24651)
-- Name: calificaciones_tipo_item_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX calificaciones_tipo_item_id_index ON public.calificaciones USING btree (tipo, item_id);


--
-- TOC entry 5058 (class 1259 OID 17191)
-- Name: eventos_fecha_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eventos_fecha_index ON public.eventos USING btree (fecha);


--
-- TOC entry 5063 (class 1259 OID 17203)
-- Name: gastronomia_tipo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX gastronomia_tipo_index ON public.gastronomia USING btree (tipo);


--
-- TOC entry 5055 (class 1259 OID 17176)
-- Name: hoteles_disponibilidad_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX hoteles_disponibilidad_index ON public.hoteles USING btree (disponibilidad);


--
-- TOC entry 5041 (class 1259 OID 16477)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 5052 (class 1259 OID 17161)
-- Name: lugares_categoria_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX lugares_categoria_index ON public.lugares USING btree (categoria);


--
-- TOC entry 5077 (class 1259 OID 17278)
-- Name: notificaciones_admin_leido_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notificaciones_admin_leido_index ON public.notificaciones_admin USING btree (leido);


--
-- TOC entry 5029 (class 1259 OID 16439)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 5032 (class 1259 OID 16438)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 5105 (class 2606 OID 24636)
-- Name: blog_posts blog_posts_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE SET NULL;


--
-- TOC entry 5106 (class 2606 OID 24641)
-- Name: blog_posts blog_posts_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5102 (class 2606 OID 24604)
-- Name: calificaciones calificaciones_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones
    ADD CONSTRAINT calificaciones_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5100 (class 2606 OID 24599)
-- Name: comentarios comentarios_lugar_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_lugar_id_foreign FOREIGN KEY (lugar_id) REFERENCES public.lugares(id) ON DELETE CASCADE;


--
-- TOC entry 5101 (class 2606 OID 24594)
-- Name: comentarios comentarios_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5109 (class 2606 OID 24758)
-- Name: detalle_pedidos detalle_pedidos_pedido_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.detalle_pedidos
    ADD CONSTRAINT detalle_pedidos_pedido_id_foreign FOREIGN KEY (pedido_id) REFERENCES public.pedidos(id) ON DELETE CASCADE;


--
-- TOC entry 5095 (class 2606 OID 24579)
-- Name: empresas empresas_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas
    ADD CONSTRAINT empresas_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5103 (class 2606 OID 24609)
-- Name: favoritos favoritos_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5097 (class 2606 OID 24662)
-- Name: gastronomia gastronomia_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gastronomia
    ADD CONSTRAINT gastronomia_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE SET NULL;


--
-- TOC entry 5096 (class 2606 OID 24695)
-- Name: hoteles hoteles_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hoteles
    ADD CONSTRAINT hoteles_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE SET NULL;


--
-- TOC entry 5104 (class 2606 OID 24614)
-- Name: notificaciones_admin notificaciones_admin_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones_admin
    ADD CONSTRAINT notificaciones_admin_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE CASCADE;


--
-- TOC entry 5108 (class 2606 OID 24763)
-- Name: pedidos pedidos_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.pedidos
    ADD CONSTRAINT pedidos_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5110 (class 2606 OID 24789)
-- Name: planes_turisticos planes_turisticos_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planes_turisticos
    ADD CONSTRAINT planes_turisticos_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE CASCADE;


--
-- TOC entry 5098 (class 2606 OID 24589)
-- Name: reservas reservas_hotel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas
    ADD CONSTRAINT reservas_hotel_id_foreign FOREIGN KEY (hotel_id) REFERENCES public.hoteles(id) ON DELETE CASCADE;


--
-- TOC entry 5099 (class 2606 OID 24584)
-- Name: reservas reservas_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas
    ADD CONSTRAINT reservas_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5107 (class 2606 OID 24716)
-- Name: servicios servicios_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE CASCADE;


-- Completed on 2026-05-13 20:22:45

--
-- PostgreSQL database dump complete
--

\unrestrict WZgZxCvGM2DGYn4GP2gFLkHhSNmeZOdeLKgc8TfMNMgg0lrtuh6C7VfsEDzb26K

