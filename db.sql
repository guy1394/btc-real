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
"snap" timestamp DEFAULT now(),
PRIMARY KEY ("id"),
UNIQUE ("rur"),
CONSTRAINT "rur_pos" CHECK (rur > 0),
CONSTRAINT "cource_pos" CHECK (cource > 0),
CONSTRAINT "btc_pos" CHECK (btc > 0)
)
WITH (OIDS=FALSE)
;

ALTER TABLE "public"."qiwi2btc"
ALTER COLUMN "status" SET DEFAULT 'request',
ADD COLUMN "phone" varchar,
ADD COLUMN "wallet" varchar;

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

CREATE OR REPLACE FUNCTION "public"."AddQiwi"( _rur currency, _cource currency, _phone varchar, _wallet varchar, _ip inet )
  RETURNS int8 AS $BODY$
DECLARE
  _t int8;
BEGIN
  LOCK TABLE qiwi2btc IN ACCESS EXCLUSIVE MODE;
  LOOP
    SELECT INTO _t id FROM qiwi2btc WHERE rur=_rur LIMIT 1;
    IF _t IS NULL
    THEN
      INSERT INTO qiwi2btc(ip, rur, cource, btc, phone, wallet)
       VALUES (_ip, _rur, _cource, _rur / _cource, _phone, _wallet)
       RETURNING INTO _t id;
      RETURN _t;
    END IF;
    _rur = _rur + 1;
  END LOOP;  
END;
$BODY$
  LANGUAGE 'plpgsql' VOLATILE COST 400;
  
