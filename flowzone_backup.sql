--
-- PostgreSQL database dump
--

\restrict vkvKk3yAYJ6dcmvv5YD3r8ib0eSCW0BDst5BkmQgPImdw4jN8IM3d2RNr80gfUo

-- Dumped from database version 15.18
-- Dumped by pg_dump version 18.3

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
-- Name: blog_posts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.blog_posts_id_seq OWNED BY public.blog_posts.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO postgres;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO postgres;

--
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
    respuesta_empresa text,
    CONSTRAINT calificaciones_tipo_check CHECK (((tipo)::text = ANY (ARRAY[('lugar'::character varying)::text, ('hotel'::character varying)::text])))
);


ALTER TABLE public.calificaciones OWNER TO postgres;

--
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
-- Name: calificaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.calificaciones_id_seq OWNED BY public.calificaciones.id;


--
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
-- Name: comentarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comentarios_id_seq OWNED BY public.comentarios.id;


--
-- Name: empresa_imagenes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.empresa_imagenes (
    id bigint NOT NULL,
    empresa_id bigint NOT NULL,
    ruta character varying(500) NOT NULL,
    titulo character varying(200),
    categoria character varying(100),
    orden smallint DEFAULT '0'::smallint NOT NULL,
    activa boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.empresa_imagenes OWNER TO postgres;

--
-- Name: empresa_imagenes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.empresa_imagenes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.empresa_imagenes_id_seq OWNER TO postgres;

--
-- Name: empresa_imagenes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.empresa_imagenes_id_seq OWNED BY public.empresa_imagenes.id;


--
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
    tipo_empresa character varying(100),
    servicios json,
    descripcion text,
    logo character varying(500),
    nit character varying(20),
    sitio_web character varying(300),
    instagram character varying(200),
    facebook character varying(200)
);


ALTER TABLE public.empresas OWNER TO postgres;

--
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
-- Name: empresas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.empresas_id_seq OWNED BY public.empresas.id;


--
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
-- Name: eventos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.eventos_id_seq OWNED BY public.eventos.id;


--
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
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
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
-- Name: favoritos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.favoritos_id_seq OWNED BY public.favoritos.id;


--
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
    longitud numeric(9,6),
    disponible_hoy boolean DEFAULT true NOT NULL,
    hora_inicio time(0) without time zone,
    hora_fin time(0) without time zone,
    stock_diario integer,
    stock_actual integer,
    dias_semana json
);


ALTER TABLE public.gastronomia OWNER TO postgres;

--
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
-- Name: gastronomia_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.gastronomia_id_seq OWNED BY public.gastronomia.id;


--
-- Name: habitaciones; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.habitaciones (
    id bigint NOT NULL,
    hotel_id bigint NOT NULL,
    nombre character varying(100) NOT NULL,
    tipo character varying(255) NOT NULL,
    num_camas integer DEFAULT 1 NOT NULL,
    tipo_cama character varying(255) DEFAULT 'doble'::character varying NOT NULL,
    capacidad_personas integer DEFAULT 2 NOT NULL,
    precio_noche numeric(10,2) NOT NULL,
    disponible boolean DEFAULT true NOT NULL,
    descripcion text,
    amenidades json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT habitaciones_tipo_cama_check CHECK (((tipo_cama)::text = ANY ((ARRAY['individual'::character varying, 'doble'::character varying, 'queen'::character varying, 'king'::character varying, 'mixta'::character varying])::text[]))),
    CONSTRAINT habitaciones_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['sencilla'::character varying, 'doble'::character varying, 'triple'::character varying, 'suite'::character varying, 'familiar'::character varying])::text[])))
);


ALTER TABLE public.habitaciones OWNER TO postgres;

--
-- Name: habitaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.habitaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.habitaciones_id_seq OWNER TO postgres;

--
-- Name: habitaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.habitaciones_id_seq OWNED BY public.habitaciones.id;


--
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
    updated_at timestamp(0) without time zone,
    empresa_id bigint
);


ALTER TABLE public.hero_images OWNER TO postgres;

--
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
-- Name: hero_images_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hero_images_id_seq OWNED BY public.hero_images.id;


--
-- Name: hoteles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hoteles (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    precio numeric(10,2) NOT NULL,
    ubicacion character varying(200),
    latitud numeric(9,6),
    longitud numeric(9,6),
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
-- Name: hoteles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hoteles_id_seq OWNED BY public.hoteles.id;


--
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
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: lugares; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.lugares (
    id bigint NOT NULL,
    nombre character varying(150) NOT NULL,
    descripcion text,
    ubicacion character varying(200),
    latitud numeric(9,6),
    longitud numeric(9,6),
    categoria character varying(100),
    imagen character varying(255),
    precio_entrada numeric(10,2) DEFAULT '0'::numeric NOT NULL,
    horario character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.lugares OWNER TO postgres;

--
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
-- Name: lugares_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.lugares_id_seq OWNED BY public.lugares.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
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
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
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
-- Name: notificaciones_admin_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.notificaciones_admin_id_seq OWNED BY public.notificaciones_admin.id;


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data text NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.notifications OWNER TO postgres;

--
-- Name: paquetes_turisticos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.paquetes_turisticos (
    id bigint NOT NULL,
    empresa_id bigint NOT NULL,
    nombre character varying(200) NOT NULL,
    descripcion text NOT NULL,
    itinerario text,
    ruta json,
    incluye json,
    no_incluye json,
    duracion_dias integer DEFAULT 1 NOT NULL,
    duracion_horas integer,
    cupo_maximo integer DEFAULT 10 NOT NULL,
    cupo_minimo integer DEFAULT 1 NOT NULL,
    cupo_disponible integer DEFAULT 10 NOT NULL,
    precio_adulto numeric(10,2) NOT NULL,
    precio_nino numeric(10,2),
    punto_salida character varying(300),
    hora_salida time(0) without time zone,
    fechas_disponibles json,
    activo boolean DEFAULT true NOT NULL,
    imagen character varying(500),
    dificultad character varying(50),
    que_llevar json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.paquetes_turisticos OWNER TO postgres;

--
-- Name: paquetes_turisticos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.paquetes_turisticos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.paquetes_turisticos_id_seq OWNER TO postgres;

--
-- Name: paquetes_turisticos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.paquetes_turisticos_id_seq OWNED BY public.paquetes_turisticos.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
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
    updated_at timestamp(0) without time zone,
    habitacion_id bigint,
    tipo_plan character varying(50),
    descripcion text,
    publicado boolean DEFAULT false NOT NULL,
    imagen character varying(500),
    fecha_inicio date,
    fecha_fin date
);


ALTER TABLE public.planes_turisticos OWNER TO postgres;

--
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
-- Name: planes_turisticos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.planes_turisticos_id_seq OWNED BY public.planes_turisticos.id;


--
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
    wompi_transaction_id character varying(255),
    habitacion_id bigint,
    CONSTRAINT reservas_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'confirmada'::character varying, 'cancelada'::character varying])::text[]))),
    CONSTRAINT reservas_estado_pago_check CHECK (((estado_pago)::text = ANY ((ARRAY['pendiente'::character varying, 'pagado'::character varying, 'fallido'::character varying])::text[]))),
    CONSTRAINT reservas_metodo_pago_check CHECK (((metodo_pago)::text = ANY ((ARRAY['efectivo'::character varying, 'tarjeta'::character varying, 'transferencia'::character varying, 'wompi'::character varying])::text[])))
);


ALTER TABLE public.reservas OWNER TO postgres;

--
-- Name: reservas_habitacion; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reservas_habitacion (
    id bigint NOT NULL,
    habitacion_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    fecha_entrada date NOT NULL,
    fecha_salida date NOT NULL,
    num_huespedes integer DEFAULT 1 NOT NULL,
    precio_total numeric(10,2) NOT NULL,
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    notas text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT reservas_habitacion_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'confirmada'::character varying, 'cancelada'::character varying, 'completada'::character varying])::text[])))
);


ALTER TABLE public.reservas_habitacion OWNER TO postgres;

--
-- Name: reservas_habitacion_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reservas_habitacion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reservas_habitacion_id_seq OWNER TO postgres;

--
-- Name: reservas_habitacion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reservas_habitacion_id_seq OWNED BY public.reservas_habitacion.id;


--
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
-- Name: reservas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reservas_id_seq OWNED BY public.reservas.id;


--
-- Name: reservas_paquete; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.reservas_paquete (
    id bigint NOT NULL,
    paquete_id bigint NOT NULL,
    usuario_id bigint NOT NULL,
    fecha_reserva date NOT NULL,
    num_adultos integer DEFAULT 1 NOT NULL,
    num_ninos integer DEFAULT 0 NOT NULL,
    precio_total numeric(10,2) NOT NULL,
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    notas text,
    telefono_contacto character varying(30),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT reservas_paquete_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'confirmada'::character varying, 'cancelada'::character varying, 'completada'::character varying])::text[])))
);


ALTER TABLE public.reservas_paquete OWNER TO postgres;

--
-- Name: reservas_paquete_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.reservas_paquete_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reservas_paquete_id_seq OWNER TO postgres;

--
-- Name: reservas_paquete_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.reservas_paquete_id_seq OWNED BY public.reservas_paquete.id;


--
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
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: blog_posts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts ALTER COLUMN id SET DEFAULT nextval('public.blog_posts_id_seq'::regclass);


--
-- Name: calificaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones ALTER COLUMN id SET DEFAULT nextval('public.calificaciones_id_seq'::regclass);


--
-- Name: comentarios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios ALTER COLUMN id SET DEFAULT nextval('public.comentarios_id_seq'::regclass);


--
-- Name: empresa_imagenes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresa_imagenes ALTER COLUMN id SET DEFAULT nextval('public.empresa_imagenes_id_seq'::regclass);


--
-- Name: empresas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas ALTER COLUMN id SET DEFAULT nextval('public.empresas_id_seq'::regclass);


--
-- Name: eventos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos ALTER COLUMN id SET DEFAULT nextval('public.eventos_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: favoritos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos ALTER COLUMN id SET DEFAULT nextval('public.favoritos_id_seq'::regclass);


--
-- Name: gastronomia id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gastronomia ALTER COLUMN id SET DEFAULT nextval('public.gastronomia_id_seq'::regclass);


--
-- Name: habitaciones id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitaciones ALTER COLUMN id SET DEFAULT nextval('public.habitaciones_id_seq'::regclass);


--
-- Name: hero_images id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hero_images ALTER COLUMN id SET DEFAULT nextval('public.hero_images_id_seq'::regclass);


--
-- Name: hoteles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hoteles ALTER COLUMN id SET DEFAULT nextval('public.hoteles_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: lugares id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lugares ALTER COLUMN id SET DEFAULT nextval('public.lugares_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: notificaciones_admin id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones_admin ALTER COLUMN id SET DEFAULT nextval('public.notificaciones_admin_id_seq'::regclass);


--
-- Name: paquetes_turisticos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paquetes_turisticos ALTER COLUMN id SET DEFAULT nextval('public.paquetes_turisticos_id_seq'::regclass);


--
-- Name: planes_turisticos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planes_turisticos ALTER COLUMN id SET DEFAULT nextval('public.planes_turisticos_id_seq'::regclass);


--
-- Name: reservas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas ALTER COLUMN id SET DEFAULT nextval('public.reservas_id_seq'::regclass);


--
-- Name: reservas_habitacion id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_habitacion ALTER COLUMN id SET DEFAULT nextval('public.reservas_habitacion_id_seq'::regclass);


--
-- Name: reservas_paquete id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_paquete ALTER COLUMN id SET DEFAULT nextval('public.reservas_paquete_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: blog_posts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.blog_posts (id, titulo, contenido, imagen, tipo, autor, empresa_id, usuario_id, publicado, fecha_publicacion, slug, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: calificaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.calificaciones (id, usuario_id, tipo, item_id, calificacion, created_at, updated_at, comentario, respuesta_empresa) FROM stdin;
\.


--
-- Data for Name: comentarios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.comentarios (id, usuario_id, lugar_id, comentario, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: empresa_imagenes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.empresa_imagenes (id, empresa_id, ruta, titulo, categoria, orden, activa, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: empresas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.empresas (id, usuario_id, nombre, telefono, direccion, aprobado, created_at, updated_at, tipo_empresa, servicios, descripcion, logo, nit, sitio_web, instagram, facebook) FROM stdin;
\.


--
-- Data for Name: eventos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eventos (id, nombre, descripcion, fecha, hora, ubicacion, categoria, imagen, precio, organizador, contacto, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: favoritos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.favoritos (id, usuario_id, tipo, item_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: gastronomia; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.gastronomia (id, nombre, descripcion, tipo, precio_promedio, restaurante, direccion, telefono, imagen, ingredientes, created_at, updated_at, empresa_id, ubicacion, latitud, longitud, disponible_hoy, hora_inicio, hora_fin, stock_diario, stock_actual, dias_semana) FROM stdin;
\.


--
-- Data for Name: habitaciones; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.habitaciones (id, hotel_id, nombre, tipo, num_camas, tipo_cama, capacidad_personas, precio_noche, disponible, descripcion, amenidades, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: hero_images; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hero_images (id, titulo, url, seccion, activa, orden, tipo, created_at, updated_at, empresa_id) FROM stdin;
\.


--
-- Data for Name: hoteles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.hoteles (id, nombre, descripcion, precio, ubicacion, latitud, longitud, imagen, servicios, capacidad, disponibilidad, telefono, email, created_at, updated_at, empresa_id) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: lugares; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.lugares (id, nombre, descripcion, ubicacion, latitud, longitud, categoria, imagen, precio_entrada, horario, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_03_19_133938_create_empresas_table	1
5	2026_03_19_133952_create_lugares_table	1
6	2026_03_19_134020_create_hoteles_table	1
7	2026_03_19_134037_create_eventos_table	1
8	2026_03_19_134049_create_gastronomia_table	1
9	2026_03_19_134111_create_reservas_table	1
10	2026_03_19_134117_create_comentarios_table	1
11	2026_03_19_134124_create_calificaciones_table	1
12	2026_03_19_134130_create_favoritos_table	1
13	2026_03_19_134141_create_notificaciones_admin_table	1
14	2026_03_24_222812_add_foreign_keys	1
15	2026_03_25_000001_create_blog_posts_table	1
16	2026_03_25_000002_update_calificaciones_add_types	1
17	2026_03_25_000003_update_gastronomia_add_empresa	1
18	2026_03_25_100000_create_hero_images_table	1
19	2026_03_25_200000_update_favoritos_add_types	1
20	2026_03_25_200001_add_empresa_id_to_hoteles	1
21	2026_04_14_014527_add_latitud_longitud_to_gastronomia_table	1
22	2026_04_27_220442_add_pago_to_reservas_table	1
23	2026_04_28_000001_create_planes_turisticos_table	1
24	2026_05_18_211807_add_campos_empresa_to_empresas_table	1
25	2026_05_18_221016_create_habitaciones_table	1
26	2026_05_18_221016_create_reservas_habitacion_table	1
27	2026_05_18_221017_add_disponibilidad_to_gastronomia_table	1
28	2026_05_18_221017_create_paquetes_turisticos_table	1
29	2026_05_18_221018_create_reservas_paquete_table	1
30	2026_05_19_192634_add_campos_plan_to_planes_turisticos_table	1
31	2026_05_24_193930_add_wompi_transaction_id_to_reservas	1
32	2026_05_24_195724_update_metodo_pago_check_on_reservas_table	1
33	2026_05_30_030034_add_respuesta_empresa_to_calificaciones_table	1
34	2026_05_30_221746_create_notifications_table	1
35	2026_05_30_233835_add_empresa_id_to_hero_images_table	1
36	2026_05_30_234004_add_empresa_id_to_hero_images_table	1
37	2026_05_31_000001_create_empresa_imagenes_table	1
38	2026_05_31_142501_add_habitacion_id_to_reservas_table	1
39	2026_05_31_205925_add_fechas_to_planes_turisticos_table	1
\.


--
-- Data for Name: notificaciones_admin; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notificaciones_admin (id, empresa_id, mensaje, leido, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notifications (id, type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: paquetes_turisticos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.paquetes_turisticos (id, empresa_id, nombre, descripcion, itinerario, ruta, incluye, no_incluye, duracion_dias, duracion_horas, cupo_maximo, cupo_minimo, cupo_disponible, precio_adulto, precio_nino, punto_salida, hora_salida, fechas_disponibles, activo, imagen, dificultad, que_llevar, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: planes_turisticos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.planes_turisticos (id, empresa_id, titulo, evento_id, gastronomia_id, hotel_id, lugar_id, subtotal, descuento, precio_final, created_at, updated_at, habitacion_id, tipo_plan, descripcion, publicado, imagen, fecha_inicio, fecha_fin) FROM stdin;
\.


--
-- Data for Name: reservas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reservas (id, usuario_id, hotel_id, fecha_entrada, fecha_salida, num_personas, precio_total, estado, created_at, updated_at, metodo_pago, referencia_pago, estado_pago, wompi_transaction_id, habitacion_id) FROM stdin;
\.


--
-- Data for Name: reservas_habitacion; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reservas_habitacion (id, habitacion_id, usuario_id, fecha_entrada, fecha_salida, num_huespedes, precio_total, estado, notas, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: reservas_paquete; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.reservas_paquete (id, paquete_id, usuario_id, fecha_reserva, num_adultos, num_ninos, precio_total, estado, notas, telefono_contacto, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
I4vgn4j7wSDfJyK62zzB40ZsLqIq4NNMcB1Dpnpn	\N	172.18.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36	YTozOntzOjY6Il90b2tlbiI7czo0MDoiWG5sb2xLeVBxQjVGaDZpWlE0bmNBbkF6M1dUQllLN212MWpaUGZ3UCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1781049122
VWEAc2iB5EjVjZrUmArUyXHJeQ3XmGuWeqYZ8Z1P	\N	172.18.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0	YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlNBN0xFbENWczM2eXowQTA3d3k0amk0Q0xURFc3blhub05BNDFWYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==	1781049163
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, rol, estado, avatar, telefono, remember_token, created_at, updated_at) FROM stdin;
\.


--
-- Name: blog_posts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.blog_posts_id_seq', 1, false);


--
-- Name: calificaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.calificaciones_id_seq', 1, false);


--
-- Name: comentarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.comentarios_id_seq', 1, false);


--
-- Name: empresa_imagenes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.empresa_imagenes_id_seq', 1, false);


--
-- Name: empresas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.empresas_id_seq', 1, false);


--
-- Name: eventos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.eventos_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: favoritos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.favoritos_id_seq', 1, false);


--
-- Name: gastronomia_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.gastronomia_id_seq', 1, false);


--
-- Name: habitaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.habitaciones_id_seq', 1, false);


--
-- Name: hero_images_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hero_images_id_seq', 1, false);


--
-- Name: hoteles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hoteles_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: lugares_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.lugares_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 39, true);


--
-- Name: notificaciones_admin_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.notificaciones_admin_id_seq', 1, false);


--
-- Name: paquetes_turisticos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.paquetes_turisticos_id_seq', 1, false);


--
-- Name: planes_turisticos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.planes_turisticos_id_seq', 1, false);


--
-- Name: reservas_habitacion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reservas_habitacion_id_seq', 1, false);


--
-- Name: reservas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reservas_id_seq', 1, false);


--
-- Name: reservas_paquete_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.reservas_paquete_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- Name: blog_posts blog_posts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_pkey PRIMARY KEY (id);


--
-- Name: blog_posts blog_posts_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_slug_unique UNIQUE (slug);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: calificaciones calificaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones
    ADD CONSTRAINT calificaciones_pkey PRIMARY KEY (id);


--
-- Name: calificaciones calificaciones_usuario_id_tipo_item_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones
    ADD CONSTRAINT calificaciones_usuario_id_tipo_item_id_unique UNIQUE (usuario_id, tipo, item_id);


--
-- Name: comentarios comentarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_pkey PRIMARY KEY (id);


--
-- Name: empresa_imagenes empresa_imagenes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresa_imagenes
    ADD CONSTRAINT empresa_imagenes_pkey PRIMARY KEY (id);


--
-- Name: empresas empresas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas
    ADD CONSTRAINT empresas_pkey PRIMARY KEY (id);


--
-- Name: empresas empresas_usuario_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas
    ADD CONSTRAINT empresas_usuario_id_unique UNIQUE (usuario_id);


--
-- Name: eventos eventos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eventos
    ADD CONSTRAINT eventos_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: favoritos favoritos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_pkey PRIMARY KEY (id);


--
-- Name: favoritos favoritos_usuario_id_tipo_item_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_usuario_id_tipo_item_id_unique UNIQUE (usuario_id, tipo, item_id);


--
-- Name: gastronomia gastronomia_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gastronomia
    ADD CONSTRAINT gastronomia_pkey PRIMARY KEY (id);


--
-- Name: habitaciones habitaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitaciones
    ADD CONSTRAINT habitaciones_pkey PRIMARY KEY (id);


--
-- Name: hero_images hero_images_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hero_images
    ADD CONSTRAINT hero_images_pkey PRIMARY KEY (id);


--
-- Name: hoteles hoteles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hoteles
    ADD CONSTRAINT hoteles_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: lugares lugares_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.lugares
    ADD CONSTRAINT lugares_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: notificaciones_admin notificaciones_admin_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones_admin
    ADD CONSTRAINT notificaciones_admin_pkey PRIMARY KEY (id);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: paquetes_turisticos paquetes_turisticos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paquetes_turisticos
    ADD CONSTRAINT paquetes_turisticos_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: planes_turisticos planes_turisticos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planes_turisticos
    ADD CONSTRAINT planes_turisticos_pkey PRIMARY KEY (id);


--
-- Name: reservas_habitacion reservas_habitacion_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_habitacion
    ADD CONSTRAINT reservas_habitacion_pkey PRIMARY KEY (id);


--
-- Name: reservas_paquete reservas_paquete_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_paquete
    ADD CONSTRAINT reservas_paquete_pkey PRIMARY KEY (id);


--
-- Name: reservas reservas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas
    ADD CONSTRAINT reservas_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: blog_posts_tipo_publicado_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX blog_posts_tipo_publicado_index ON public.blog_posts USING btree (tipo, publicado);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: calificaciones_tipo_item_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX calificaciones_tipo_item_id_index ON public.calificaciones USING btree (tipo, item_id);


--
-- Name: empresa_imagenes_empresa_id_activa_orden_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX empresa_imagenes_empresa_id_activa_orden_index ON public.empresa_imagenes USING btree (empresa_id, activa, orden);


--
-- Name: eventos_fecha_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eventos_fecha_index ON public.eventos USING btree (fecha);


--
-- Name: gastronomia_tipo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX gastronomia_tipo_index ON public.gastronomia USING btree (tipo);


--
-- Name: hoteles_disponibilidad_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX hoteles_disponibilidad_index ON public.hoteles USING btree (disponibilidad);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: lugares_categoria_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX lugares_categoria_index ON public.lugares USING btree (categoria);


--
-- Name: notificaciones_admin_leido_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notificaciones_admin_leido_index ON public.notificaciones_admin USING btree (leido);


--
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: blog_posts blog_posts_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE SET NULL;


--
-- Name: blog_posts blog_posts_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.blog_posts
    ADD CONSTRAINT blog_posts_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: calificaciones calificaciones_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.calificaciones
    ADD CONSTRAINT calificaciones_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: comentarios comentarios_lugar_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_lugar_id_foreign FOREIGN KEY (lugar_id) REFERENCES public.lugares(id) ON DELETE CASCADE;


--
-- Name: comentarios comentarios_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comentarios
    ADD CONSTRAINT comentarios_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: empresa_imagenes empresa_imagenes_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresa_imagenes
    ADD CONSTRAINT empresa_imagenes_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE CASCADE;


--
-- Name: empresas empresas_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.empresas
    ADD CONSTRAINT empresas_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: favoritos favoritos_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.favoritos
    ADD CONSTRAINT favoritos_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: gastronomia gastronomia_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.gastronomia
    ADD CONSTRAINT gastronomia_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE SET NULL;


--
-- Name: habitaciones habitaciones_hotel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.habitaciones
    ADD CONSTRAINT habitaciones_hotel_id_foreign FOREIGN KEY (hotel_id) REFERENCES public.hoteles(id) ON DELETE CASCADE;


--
-- Name: hero_images hero_images_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hero_images
    ADD CONSTRAINT hero_images_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE SET NULL;


--
-- Name: hoteles hoteles_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hoteles
    ADD CONSTRAINT hoteles_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE SET NULL;


--
-- Name: notificaciones_admin notificaciones_admin_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notificaciones_admin
    ADD CONSTRAINT notificaciones_admin_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE CASCADE;


--
-- Name: paquetes_turisticos paquetes_turisticos_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.paquetes_turisticos
    ADD CONSTRAINT paquetes_turisticos_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE CASCADE;


--
-- Name: planes_turisticos planes_turisticos_empresa_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.planes_turisticos
    ADD CONSTRAINT planes_turisticos_empresa_id_foreign FOREIGN KEY (empresa_id) REFERENCES public.empresas(id) ON DELETE CASCADE;


--
-- Name: reservas_habitacion reservas_habitacion_habitacion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_habitacion
    ADD CONSTRAINT reservas_habitacion_habitacion_id_foreign FOREIGN KEY (habitacion_id) REFERENCES public.habitaciones(id) ON DELETE CASCADE;


--
-- Name: reservas reservas_habitacion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas
    ADD CONSTRAINT reservas_habitacion_id_foreign FOREIGN KEY (habitacion_id) REFERENCES public.habitaciones(id) ON DELETE SET NULL;


--
-- Name: reservas_habitacion reservas_habitacion_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_habitacion
    ADD CONSTRAINT reservas_habitacion_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: reservas reservas_hotel_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas
    ADD CONSTRAINT reservas_hotel_id_foreign FOREIGN KEY (hotel_id) REFERENCES public.hoteles(id) ON DELETE CASCADE;


--
-- Name: reservas_paquete reservas_paquete_paquete_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_paquete
    ADD CONSTRAINT reservas_paquete_paquete_id_foreign FOREIGN KEY (paquete_id) REFERENCES public.paquetes_turisticos(id) ON DELETE CASCADE;


--
-- Name: reservas_paquete reservas_paquete_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas_paquete
    ADD CONSTRAINT reservas_paquete_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: reservas reservas_usuario_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.reservas
    ADD CONSTRAINT reservas_usuario_id_foreign FOREIGN KEY (usuario_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict vkvKk3yAYJ6dcmvv5YD3r8ib0eSCW0BDst5BkmQgPImdw4jN8IM3d2RNr80gfUo

