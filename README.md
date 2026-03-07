# Gestor de tareas PHP y persistencia con BBDD
#### Autor: Jaime Olucha Buendía
--- 
### Repositorio GitHub

**👉 https://github.com/JaimeOlucha/holu-task-manager**

--- 

### 🌐 Acceso al Proyecto
Este repositorio contiene el código fuente del proyecto. 
Para aumentar la experiencia de uso, el sistema ya se encuentra desplegado y listo para su uso.


**Puedes ver la aplicación funcionando en tiempo real en el siguiente enlace:**
**👉 https://gestortareas.holu.es/**

---

### 🗄️ Configuración de la Base de Datos (MySQL)
Si quieres ejecutar el proyecto de forma local, sigue estos pasos para importar la base de datos:

1. Localiza el archivo: En la carpeta /database, encontrarás un archivo llamado entregable_tareas_bd.sql.
2. Crea la base de datos: Desde tu gestor (phpMyAdmin, MySQL Workbench o terminal), ejecuta:

```sql
CREATE DATABASE nombre_de_tu_bd;
```
#### Importar datos:

1. Vía Terminal: mysql -u usuario -p nombre_de_tu_bd < entregable_tareas_bd.sql
2. Vía phpMyAdmin: Entra en tu base de datos, ve a la pestaña "Importar", selecciona el archivo .sql y haz clic en "Continuar".
