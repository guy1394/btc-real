CREATE TYPE transaction_status AS ENUM (
'request',
'wait_qiwi',
'order',
'send_btc',
'close'
);

CREATE DOMAIN currency AS NUMERIC(20, 10);

CREATE TABLE "public"."qiwi2btc" (
"id" bigserial,
"rur" currency,
"cource" currency,
"btc" currency,
"status" transaction_status,
"ip" inet,
"snap" timestamp,
PRIMARY KEY ("id"),
UNIQUE ("rur"),
CONSTRAINT "rur_pos" CHECK (rur > 0),
CONSTRAINT "cource_pos" CHECK (cource > 0),
CONSTRAINT "btc_pos" CHECK (btc > 0)
)
WITH (OIDS=FALSE)
;

CREATE TABLE "public"."log_qiwi2btc" (
"id" bigserial NOT NULL,
"tr_id" int8,
"status" transaction_status,
"snap" timestamp,
"ip" inet,
PRIMARY KEY ("id"),
UNIQUE ("tr_id", "status")
)
WITH (OIDS=FALSE)
;

CREATE OR REPLACE RULE "forbid_delete" AS ON DELETE TO "public"."log_qiwi2btc" DO INSTEAD NOTHING;

CREATE OR REPLACE FUNCTION "public"."Qiwi2BTCLog"()
  RETURNS "pg_catalog"."trigger" AS $BODY$
BEGIN
NEW.snap = now();
INSERT INTO log_qiwi2btc(tr_id, status, ip, snap) VALUES (NEW.id, NEW.status, NEW.ip, NEW.snap);
RETURN NEW;
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE COST 100;
  
CREATE TRIGGER "Qiwi2BTCLog_trig" BEFORE INSERT OR UPDATE OF "status", "ip", "snap" ON "public"."qiwi2btc"
FOR EACH ROW
EXECUTE PROCEDURE "public"."Qiwi2BTCLog"();
  
