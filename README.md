# Sistema de Control Electoral (Arquitectura MVC - CodeIgniter 4)

Este proyecto implementa el sistema de **Control Electoral** bajo el patrón arquitectónico **MVC (Modelo - Vista - Controlador)** utilizando el framework PHP **CodeIgniter 4** y base de datos relacional local **MySQL / MariaDB** (`dbcontrolelectoral`).

---

## 🗄️ Base de Datos: `dbcontrolelectoral`

### 1. Tabla `sexo`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idsexo` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único del sexo |
| `nombre` | `VARCHAR(20)` | | Descripción o nombre del sexo |

### 2. Tabla `provincia`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idprovincia` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único de la provincia |
| `nombre` | `VARCHAR(50)` | | Nombre de la provincia |

### 3. Tabla `canton`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idcanton` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único del cantón |
| `nombre` | `VARCHAR(50)` | | Nombre del cantón |
| `idprovincia` | `INT` | **FOREIGN KEY** | Clave foránea hacia `provincia(idprovincia)` |

### 4. Tabla `persona`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idpersona` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único de la persona |
| `cedula` | `VARCHAR(15)` | **UNIQUE** | Cédula de identidad de la persona |
| `nombre` | `VARCHAR(50)` | | Nombres de la persona |
| `apellidos` | `VARCHAR(50)` | | Apellidos de la persona |
| `fechanacimiento` | `DATE` | | Fecha de nacimiento (AAAA-MM-DD) |
| `idsexo` | `INT` | **FOREIGN KEY** | Clave foránea hacia `sexo(idsexo)` |

### 5. Tabla `rolusuario`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idrolusuario` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único del rol de usuario |
| `nombre` | `VARCHAR(50)` | | Nombre o nivel del rol (Admin, Operador, etc.) |

### 6. Tabla `usuario`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idusuario` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único del usuario |
| `idpersona` | `INT` | **FOREIGN KEY** | Clave foránea hacia `persona(idpersona)` |
| `usuario` | `VARCHAR(20)` | **UNIQUE** | Nombre de usuario / cuenta de acceso |
| `password` | `VARCHAR(50)` | | Contraseña de acceso al sistema |
| `idrolusuario` | `INT` | **FOREIGN KEY** | Clave foránea hacia `rolusuario(idrolusuario)` |

### 7. Tabla `parroquia`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idparroquia` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único de la parroquia |
| `nombre` | `VARCHAR(50)` | | Nombre de la parroquia |
| `idcanton` | `INT` | **FOREIGN KEY** | Clave foránea hacia `canton(idcanton)` |

### 8. Tabla `zona`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idzona` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único de la zona electoral |
| `nombre` | `VARCHAR(50)` | | Nombre de la zona electoral / sector |
| `idparroquia` | `INT` | **FOREIGN KEY** | Clave foránea hacia `parroquia(idparroquia)` |

### 9. Tabla `recintoelectoral`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idrecintoelectoral` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único del recinto electoral |
| `nombre` | `VARCHAR(150)` | | Nombre del recinto electoral (escuela, colegio, etc.) |
| `idzona` | `INT` | **FOREIGN KEY** | Clave foránea hacia `zona(idzona)` |
| `numeroelectores` | `INT` | | Cantidad de electores asignados al recinto |

### 10. Tabla `meza`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idmeza` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único de la mesa electoral |
| `numero` | `INT` | | Número de mesa o junta receptora |
| `idsexo` | `INT` | **FOREIGN KEY** | Clave foránea hacia `sexo(idsexo)` |
| `idrecintoelectoral` | `INT` | **FOREIGN KEY** | Clave foránea hacia `recintoelectoral(idrecintoelectoral)` |

### 11. Tabla `tipodignidad`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idtipodignidad` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único del tipo de dignidad |
| `nombre` | `VARCHAR(100)` | | Nombre del cargo (Presidente, Alcalde, etc.) |

### 12. Tabla `dignidad`
| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `iddignidad` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único de la candidatura/dignidad |
| `idpersona` | `INT` | **FOREIGN KEY** | Clave foránea hacia `persona(idpersona)` |
| `idtipodignidad` | `INT` | **FOREIGN KEY** | Clave foránea hacia `tipodignidad(idtipodignidad)` |

### 13. Tabla `mezadignidad` (Dignidades a ser elegidas por mesa)
Contiene las dignidades que van a ser elegidas en cada mesa electoral, donde `numeropapeleta` representa la **cantidad de papeletas que fueron contadas** para dicha dignidad en esa junta receptora del voto.

| Campo | Tipo | Clave | Descripción |
| :--- | :--- | :--- | :--- |
| `idmezadignidad` | `INT` | **PRIMARY KEY** (Auto Increment) | Identificador único del registro mesa-dignidad |
| `idmeza` | `INT` | **FOREIGN KEY** | Clave foránea hacia `meza(idmeza)` |
| `iddignidad` | `INT` | **FOREIGN KEY** | Clave foránea hacia `dignidad(iddignidad)` |
| `numeropapeleta` | `INT` | | Cantidad de papeletas que fueron contadas para esta dignidad en la mesa |

El archivo con el script de estructura y datos iniciales se encuentra en [`dbcontrolelectoral.sql`](file:///var/www/html/controlelectoral/dbcontrolelectoral.sql).

---

## 🏛️ Arquitectura MVC del Proyecto

```
controlelectoral/
│
├── app/
│   ├── Config/
│   │   ├── Database.php              # Configuración de conexión MySQL
│   │   ├── Routes.php                # Mapeo de rutas RESTful / CRUD para todos los módulos
│   │   └── App.php                   # BaseURL y configuración general
│   │
│   ├── Controllers/
│   │   ├── PersonaController.php     # Controlador de personas (navegador individual + listar)
│   │   ├── SexoController.php        # Controlador CRUD de sexos
│   │   ├── ProvinciaController.php   # Controlador CRUD de provincias
│   │   ├── CantonController.php      # Controlador CRUD de cantones
│   │   ├── ParroquiaController.php   # Controlador CRUD de parroquias
│   │   ├── ZonaController.php        # Controlador CRUD de zonas electorales
│   │   ├── RecintoelectoralController.php # Controlador CRUD de recintos electorales
│   │   ├── MezaController.php        # Controlador de mesas electorales (navegador individual con fotos de dignidades + listar)
│   │   ├── TipodignidadController.php # Controlador CRUD de tipos de dignidad
│   │   ├── DignidadController.php    # Controlador de candidaturas/dignidades (navegador individual + listar)
│   │   ├── MezadignidadController.php # Controlador CRUD de dignidades a elegir por mesa y conteo de papeletas
│   │   ├── RolusuarioController.php  # Controlador CRUD de roles de usuario
│   │   ├── UsuarioController.php     # Controlador CRUD de usuarios
│   │   └── Home.php                  # Controlador de inicio
│   │
│   ├── Models/
│   │   ├── PersonaModel.php          # Modelo y validaciones de persona (JOIN sexo)
│   │   ├── SexoModel.php             # Modelo y validaciones de sexo
│   │   ├── ProvinciaModel.php        # Modelo y validaciones de provincia
│   │   ├── CantonModel.php           # Modelo y validaciones de cantón (JOIN provincia)
│   │   ├── ParroquiaModel.php        # Modelo y validaciones de parroquia (JOIN canton y provincia)
│   │   ├── ZonaModel.php             # Modelo y validaciones de zona (JOIN parroquia, canton y provincia)
│   │   ├── RecintoelectoralModel.php # Modelo y validaciones de recinto (JOIN zona, parroquia, canton, prov)
│   │   ├── MezaModel.php             # Modelo y validaciones de mesa (JOIN sexo, recinto, zona, parroquia)
│   │   ├── TipodignidadModel.php     # Modelo y validaciones de tipos de dignidad
│   │   ├── DignidadModel.php         # Modelo y validaciones de dignidades (JOIN persona y tipodignidad)
│   │   ├── MezadignidadModel.php     # Modelo y validaciones de dignidades por mesa (JOIN meza, dignidad, persona, tipo)
│   │   ├── RolusuarioModel.php       # Modelo y validaciones de rol de usuario
│   │   └── UsuarioModel.php          # Modelo y validaciones de usuario (JOIN persona y rol)
│   │
│   └── Views/
│       ├── layout/
│       │   └── main.php              # Plantilla con Menú Vertical Colapsable y Bootstrap 5
│       ├── persona/ (index - navegador individual, listar, create, edit, show)
│       ├── sexo/ (index, create, edit)
│       ├── provincia/ (index, create, edit)
│       ├── canton/ (index, create, edit)
│       ├── parroquia/ (index, create, edit)
│       ├── zona/ (index, create, edit)
│       ├── recintoelectoral/ (index, create, edit)
│       ├── meza/ (index - navegador individual, listar, create, edit)
│       ├── tipodignidad/ (index, create, edit)
│       ├── dignidad/ (index - navegador individual, listar, create, edit)
│       ├── mezadignidad/ (index, create, edit)
│       ├── rolusuario/ (index, create, edit)
│       └── usuario/ (index, create, edit, show)
│
├── repositorio/
│   ├── fotos/                        # Fotos de personas almacenadas como {cedula}.jpg
│   └── actaescrutinio/               # Actas de escrutinio como evidencia de votos almacenadas como {idmeza}.jpg
├── public/
│   └── index.php                     # Front controller principal de CodeIgniter 4
├── index.php                         # Redirección automática de la raíz a /persona
├── .env                              # Variables de entorno y credenciales de BD
└── dbcontrolelectoral.sql            # Script SQL completo
```

---

## 🚀 Acceso en el Navegador

Una vez encendido Apache, puede acceder mediante:

- **Acceso Directo (Raíz):**
  [http://localhost/controlelectoral/](http://localhost/controlelectoral/)
- **Módulo de Personas (Navegador Individual Registro por Registro):**
  [http://localhost/controlelectoral/public/index.php/persona](http://localhost/controlelectoral/public/index.php/persona)
- **Módulo de Personas (Listado General en Tabla):**
  [http://localhost/controlelectoral/public/index.php/persona/listar](http://localhost/controlelectoral/public/index.php/persona/listar)
- **Módulo de Sexos:**
  [http://localhost/controlelectoral/public/index.php/sexo](http://localhost/controlelectoral/public/index.php/sexo)
- **Módulo de Provincias:**
  [http://localhost/controlelectoral/public/index.php/provincia](http://localhost/controlelectoral/public/index.php/provincia)
- **Módulo de Cantones:**
  [http://localhost/controlelectoral/public/index.php/canton](http://localhost/controlelectoral/public/index.php/canton)
- **Módulo de Parroquias:**
  [http://localhost/controlelectoral/public/index.php/parroquia](http://localhost/controlelectoral/public/index.php/parroquia)
- **Módulo de Zonas:**
  [http://localhost/controlelectoral/public/index.php/zona](http://localhost/controlelectoral/public/index.php/zona)
- **Módulo de Recintos Electorales:**
  [http://localhost/controlelectoral/public/index.php/recintoelectoral](http://localhost/controlelectoral/public/index.php/recintoelectoral)
- **Módulo de Mesas Electorales (Navegador Individual Registro por Registro):**
  [http://localhost/controlelectoral/public/index.php/meza](http://localhost/controlelectoral/public/index.php/meza)
- **Módulo de Mesas Electorales (Listado General en Tabla):**
  [http://localhost/controlelectoral/public/index.php/meza/listar](http://localhost/controlelectoral/public/index.php/meza/listar)
- **Módulo de Tipos de Dignidad:**
  [http://localhost/controlelectoral/public/index.php/tipodignidad](http://localhost/controlelectoral/public/index.php/tipodignidad)
- **Módulo de Dignidades / Candidaturas (Navegador Individual Registro por Registro):**
  [http://localhost/controlelectoral/public/index.php/dignidad](http://localhost/controlelectoral/public/index.php/dignidad)
- **Módulo de Dignidades / Candidaturas (Listado General en Tabla):**
  [http://localhost/controlelectoral/public/index.php/dignidad/listar](http://localhost/controlelectoral/public/index.php/dignidad/listar)
- **Módulo de Papeletas por Mesa (Meza-Dignidad):**
  [http://localhost/controlelectoral/public/index.php/mezadignidad](http://localhost/controlelectoral/public/index.php/mezadignidad)
- **Módulo de Roles de Usuario:**
  [http://localhost/controlelectoral/public/index.php/rolusuario](http://localhost/controlelectoral/public/index.php/rolusuario)
- **Módulo de Usuarios:**
  [http://localhost/controlelectoral/public/index.php/usuario](http://localhost/controlelectoral/public/index.php/usuario)
