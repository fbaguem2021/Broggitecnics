 # Broggitecnics 112

Emergency call management simulator

## Carta de Llamada

<hr>

### Datos basicos a introducir

- Datos administrativos
- Identificador de llamada
- Apuntes sobre la llamada
- Localización
- Tipo de emergencia
- Agencia a enviar 
    - Bomberos, Policia, Ambulancia, *etc*
- Finalización de llamada

### Datos a obtener en la llamada:

#### **Datos Administrativos**
- Duracion de llamada
- Código de expediente
- Código de llamada

#### **Identificación de llamada/interlocutor**
- Numero de telefono de la llamada
- Procedencia de la llamada
    - Municipio, Direccion, etc
- Antecedentes del telefono
- Guardar fecha y hora de la llamada

#### **Localización de la llamada**
- Si es de Cataluña, identificar la comarca, provincia y municipo. Si no lo es indicar solo la provincia y el municipio es opcional
- Seleccionar el tipo de localización
    - Calle, Punto singular, Carretera, Población, Provincia, etcç
    - Si es ***Cataluña***, la localización sera de tipo __Provincia__
    - Si es una ***Entidad Población***, no se pediran mas datos
    - Si es una ***Calle***, Se debera indicar: __nombre__, __punto kilometrico__ y __sentido__
    - Si es un ***Punto Singular***, Se deverà indicar: __nombre__
    - En **cualquier** tipo de localización, se tendra un campo para otros puntos de referencia

### **Tipificación de la emergencia**
- Seleccionar el tipo de incidente segun los datos aportados. (tipos de incidentes y relacion con estos)
    - Dentro los tipos de incidentes, seleccionar el tipo concreto
    - Mostrar datos sobre el incidente

### **Envio de agencias**
- Mostrar las agencias y seleccionar la adequada

### **Finalización de la llamada**
- Associar la llamada a un expediente
    - Mientras se introducen los datos, ir mostrando possibles expedientes relacionados
        - Llamadas con el mismo numero, mismo lugar, mismo tipo de incidente, etc
- Si al final no es necessario el envio de los servicios de emergencias, al acavar la llamada, crear un nuevo expediente

<hr>

## Miembros del equipo:
- [Francesc Bagué Martí](https://github.com/fbaguem2021)
- [Guillem Turró Gil](https://github.com/gturro)
- [José Nirella](https://github.com/jnirella)
