Te lo explico como **un solo diagrama**, pero marcando qué parte aparece en qué página del PDF y cómo se conecta todo. 

---

## 1. Contexto general

* Hay **dos swimlanes**:

  * `Administrador` (quien hace clic en botones).
  * `sistema` (validaciones, consultas a BD, envío de correos).
* El proceso completo se llama **“Gestionar reportes de E/S / Gestionar reporte”**, donde **E/S = Entradas / Salidas**.

---

## 2. Inicio y validación de sesión (pág. 1)

1. El **Administrador** inicia el flujo con:
   **“Click en btn(Generar reportes)”**. 
2. En el swimlane de **sistema** se ejecuta:

   * **“comprobar si hay sesión activa”**.
3. Decisión:

   * **Si NO hay sesión activa** → el sistema muestra:

     > *“mensaje del sistema – Error: ‘Surgió un error. Vuelva a iniciar sesión’”*
     > y el flujo **termina** (punto final negro). 
   * **Si SÍ hay sesión activa** → pasa a mostrar la interfaz de reportes (pág. 2).

---

## 3. Menú principal de reportes (pág. 2 y 7)

En el swimlane **sistema**:

4. Se muestra: **“mostrar interfaz <generar reportes>”**.

5. Nota asociada (pág. 2 y también aislada en pág. 7):
   **<Generar reporte> – ¿Qué desea realizar?**

   * `btn(Generar reporte de entrada)`
   * `btn(Generar reporte de salida)`
   * `btn(Regresar)` 

6. Desde aquí hay tres posibles caminos:

   * **Generar reporte de entrada**
   * **Generar reporte de salida**
   * **Regresar** (volver a interfaz de menú y terminar el flujo).

---

## 4. Flujo: “Generar reporte de entrada” (pág. 1 y 2)

### 4.1. Mostrar tabla inicial de entradas

7. El Administrador hace **“click in btn(Generar reporte de entrada)”**. 

8. En **sistema**:

   * Se llama a **“inventario_entradas en la BD”**.
   * Se muestra **“interfaz generar reportes de entrada”**.

9. Aparece la nota: **<Generar reportes de entrada> – Mostrar tabla entradas:**

   * `txt(Colocar nombre)`
   * `btn(Buscar)`
   * `btn(Generar reporte)`
   * `btn(Filtrar por)`
   * `btn(Regresar)` (pág. 1). 

10. Hay una decisión llamada **“¿Qué acción realizar?”** que abre tres grandes subflujos:

    * Regresar
    * Filtrar
    * Buscar / Generar reporte (repartidos entre pág. 1 y 2)

### 4.2. Opción: Regresar (pág. 2)

11. **click in btn(Regresar)** → acción **“regresar a interfaz de menú”** y punto final. 

### 4.3. Opción: Filtrar (pág. 1)

12. El usuario hace **“click in btn(Filtrar)”**.
13. El sistema: **“verificar que este usando el filtro seleccionado de menor a mayor”**.
14. Decisión **“¿Está usando?”**: 

    * **Sí** → mostrar lista *“según lo seleccionado de mayor a menor”*.
    * **No** → mostrar lista *“según lo seleccionado de menor a mayor”*.
15. Luego en ambos casos:

    * **“hacer un select en la BD”**.
    * **“actualizar la tabla”**.
16. Se muestra de nuevo la nota:
    **<Generar reportes de entrada> – Mostrar tabla entradas (actualizada)** con los mismos controles que antes (pág. 1). 

### 4.4. Opción: Buscar y generar reporte (pág. 2)

17. **Buscar**:

* El Administrador hace **“click in btn(Buscar)”**.
* El sistema ejecuta **“validar campo txt(Colocar nombre)”**.
* Decisión **“validación = true?”**:

  * **No** → se muestra mensaje de error 02:
    *“Campo vacío”* y termina ese camino.
  * **Sí** → *“buscar coincidencias en la BD”* y luego *“actualizar la tabla”* (se conecta con la misma tabla actualizada de entradas). 

18. **Generar reporte (entrada)**:

* El usuario hace **“click in btn(Generar reporte)”**.
* El sistema **“obtener datos de entrada de la BD”**.
* Luego **“imprimir datos en un Excel”** (esto conecta después con el envío por correo, pág. 2 y 6). 

---

## 5. Flujo: “Generar reporte de salida” (pág. 3 y 4)

Es casi simétrico al de entradas, pero con datos de salida:

### 5.1. Mostrar tabla inicial de salidas (pág. 3)

19. Desde el menú principal el Administrador hace
    **“click in btn(Generar reporte de salida)”**.
20. En **sistema**:

* **“llamar inventario_salida en la BD”**.
* **“mostrar interfaz generar reportes de salida”**.

21. Nota: **<Generar reportes de salida> – Mostrar tabla salida:**

* `txt(Colocar nombre)`
* `btn(Buscar)`
* `btn(Generar reporte)`
* `btn(Filtrar por)`
* `btn(Regresar)` 

22. De nuevo la decisión **“¿Qué acción realizar?”** abre:

* Regresar
* Buscar
* Filtrar
* Generar reporte

### 5.2. Regresar (pág. 3)

23. **click in btn(Regresar)** → flujo vuelve a menú y termina (similar a entradas).

### 5.3. Buscar en salidas (pág. 3)

24. **click in btn(Buscar)**
25. Sistema: **“validar campo txt(Colocar nombre)”**.
26. Decisión **“validación = true?”**:

* **No** → muestra mensaje de error 02: *“Campo vacío”* y termina ese camino.
* **Sí** → *“buscar coincidencias en la BD”* y luego *“actualizar la tabla”*.

27. Se muestra nota **“Mostrar tabla salida (actualizada)”** igual que en entradas (pág. 3–4). 

### 5.4. Filtrar salidas (pág. 3–4)

28. **click in btn(Filtrar)**.
29. Sistema: **“verificar que este usando el filtro seleccionado de menor a mayor”**.
30. Decisión **“¿Está usando?”**:

* **Sí** → mostrar lista según lo seleccionado de mayor a menor.
* **No** → mostrar lista según lo seleccionado de menor a mayor.

31. Luego: **“hacer un select en la BD”** y **“actualizar la tabla”**.
32. Se muestra nota **<Generar reportes de salida> – Mostrar tabla salida (actualizada)** (pág. 4). 

### 5.5. Generar reporte (salidas) (pág. 3)

33. **click in btn(Generar reporte)**.
34. Sistema: **“obtener datos de salida de la BD”**.
35. Después, igual que en entradas, imprime en Excel (se unifica con el flujo de envío de Excel). 

---

## 6. Envío del Excel al correo (pág. 2 y 6)

Una vez que el sistema ya imprimió los datos en un Excel (sea de entradas o salidas):

36. El sistema ejecuta: **“enviar el excel al correo del usuario”**.
37. Decisión **“¿se envió?”**: 

* **No** →

  * Muestra mensaje de error 03:
    *“Error. No se ha logrado enviar el correo”*.
  * Luego **“regresar a la interfaz <Generar reporte>”** (vuelve al menú).
* **Sí** →

  * Muestra mensaje:
    *“Se ha enviado el reporte a su correo”*.
  * Luego también **regresa a la interfaz <Generar reporte>**.

Con eso, el proceso queda **cíclico**: el usuario puede volver a generar otro reporte o salir.

---

## 7. Resumen del flujo “normal” (sin errores)

Para que lo veas más simple, la **ruta típica ideal** sería:

1. Click en **Generar reportes**.
2. Sesión válida.
3. Se muestra **<Generar reporte>** (menú).
4. Elige **Generar reporte de entrada** o **salida**.
5. El sistema muestra la tabla (entradas/salidas).
6. El usuario puede **buscar** y/o **filtrar** para ajustar la tabla.
7. Hace **click en Generar reporte**.
8. El sistema toma los datos, los imprime en **Excel**.
9. El sistema **envía el Excel al correo** del usuario.
10. Muestra mensaje de éxito y vuelve al menú de **<Generar reporte>**.

---

Si quieres, en el siguiente mensaje puedo convertir **todo este flujo completo** a un solo código PlantUML, ya bien ordenado, usando decisiones `if/else` y notas igualito al diagrama.
