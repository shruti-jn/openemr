# OpenEMR MariaDB Access – development-easy Stack

## Phase 1 – Discovery Summary

### From `docker-compose.yml`

| Item | Value |
|------|--------|
| **MySQL service name** | `mysql` |
| **Image** | `mariadb:11.8` |
| **Port mapping** | `8320:3306` (host 8320 → container 3306) |

**Relevant environment variables:**

- **mysql service:** `MYSQL_ROOT_PASSWORD: root`
- **openemr service:** `MYSQL_HOST: mysql`, `MYSQL_ROOT_PASS: root`, `MYSQL_USER: openemr`, `MYSQL_PASS: openemr`

### Client inside the mysql container

- The container does **not** provide a `mysql` binary in `PATH`.
- It provides the **MariaDB** client:
  - `/usr/bin/mariadb` – interactive/client
  - `/usr/bin/mariadb-admin` – admin (e.g. for user management)
- So `docker compose exec mysql mysql ...` fails with “executable file not found” because the client is named **mariadb**, not **mysql**.

---

## Phase 2 – Database Access Strategy

- Use the **mariadb** client inside the **mysql** container (no need for phpMyAdmin or host client for these commands).
- Database name and users were read from the running server; credentials were taken from `docker-compose.yml` and confirmed by connecting.

---

## Phase 3 – Output

### 1. Exact working command to list databases

**From the host (in the directory that contains `docker-compose.yml`):**

```bash
docker compose exec mysql mariadb -u root -proot -e "SHOW DATABASES;"
```

Or with the application user:

```bash
docker compose exec mysql mariadb -u openemr -popenemr -e "SHOW DATABASES;"
```

Example output:

```
Database
information_schema
mysql
openemr
performance_schema
sys
```

The OpenEMR application database is **openemr**.

---

### 2. Exact working command to list users

```bash
docker compose exec mysql mariadb -u root -proot -e "SELECT user, host FROM mysql.user ORDER BY user, host;"
```

Example output:

```
User         Host
healthcheck  127.0.0.1
healthcheck  ::1
healthcheck  localhost
mariadb.sys  localhost
openemr      %
root         %
root         localhost
```

---

### 3. Values for agent `.env`

Use these for the agent when it runs **on the host** and connects to the database via the exposed port:

```env
OPENEMR_DB_HOST=127.0.0.1
OPENEMR_DB_PORT=8320
OPENEMR_DB_USER=openemr
OPENEMR_DB_PASSWORD=openemr
OPENEMR_DB_NAME=openemr
```

If the agent runs **inside the same Docker Compose network** (e.g. as another service), use:

```env
OPENEMR_DB_HOST=mysql
OPENEMR_DB_PORT=3306
OPENEMR_DB_USER=openemr
OPENEMR_DB_PASSWORD=openemr
OPENEMR_DB_NAME=openemr
```

---

### 4. Why this works for this stack

- **`mariadb` not `mysql`:** The image is **MariaDB 11.8**, which ships the **mariadb** client binary. There is no `mysql` symlink or binary in the container, so any `exec` must call `mariadb`.
- **Credentials:** The openemr container is given `MYSQL_USER: openemr` and `MYSQL_PASS: openemr`; the OpenEMR image creates that user and the **openemr** database. We confirmed login with `mariadb -u openemr -popenemr`.
- **Database name:** The application database created by the OpenEMR image is **openemr**, as shown by `SHOW DATABASES` and the openemr user’s access.
- **Port:** From the host you use **8320** (mapped in compose); from another container on the same network you use service name **mysql** and port **3306**.

No application or compose files were changed; only existing configuration was read and verified with read-only metadata queries.
