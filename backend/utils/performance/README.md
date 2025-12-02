# 🎯 Sistema de Pruebas de Rendimiento - SENAParking

## ✅ Implementación Completada

Se ha implementado un sistema completo de pruebas de carga usando **Apache Bench** para medir el rendimiento de los endpoints críticos de SENAParking.

## 📁 Estructura de Archivos

```
backend/utils/performance/
├── README.md                    # Documentación general
├── USAGE.md                     # Guía de uso detallada
├── setup-test-data.php          # Preparación de datos de prueba
├── generate-summary.php         # Generador de reportes HTML
├── quick-test.bat               # Prueba rápida de verificación
├── run-all-tests.bat            # Ejecutar todas las pruebas
├── test-login-load.bat          # Pruebas de Login
├── test-vehicle-load.bat        # Pruebas de Vehículos
├── payloads/                    # Datos POST para cada endpoint
│   ├── login.txt
│   ├── register-user.txt
│   ├── register-vehicle.txt
│   └── register-access.txt
└── reports/                     # Reportes generados
    ├── login_low.txt
    ├── login_medium.txt
    ├── login_high.txt
    ├── login_stress.txt
    ├── vehicle_low.txt
    ├── vehicle_medium.txt
    ├── vehicle_high.txt
    ├── vehicle_stress.txt
    └── summary.html
```

## 🚀 Cómo Usar

### 1. Preparar Datos (Solo la primera vez)

```bash
cd c:\xampp\htdocs\SENAParking\backend\utils\performance
C:\xampp\php\php.exe setup-test-data.php
```

### 2. Prueba Rápida (Verificación)

```bash
quick-test.bat
```

### 3. Pruebas Completas de Login

```bash
test-login-load.bat
```

### 4. Pruebas Completas de Vehículos

```bash
test-vehicle-load.bat
```

### 5. Suite Completa + Reporte HTML

```bash
run-all-tests.bat
```

## 📊 Niveles de Carga

| Nivel | Requests | Concurrencia | Uso |
|-------|----------|--------------|-----|
| **Baja** | 100 | 10 | Verificación básica |
| **Media** | 500 | 50 | Carga normal esperada |
| **Alta** | 1000 | 100 | Picos de tráfico |
| **Estrés** | 2000 | 200 | Límites del sistema |

## 📈 Resultados de Prueba Inicial

### Login Endpoint (Quick Test)
- ✅ **279 requests/segundo**
- ✅ **17.9ms tiempo promedio**
- ✅ **0% requests fallidos**
- ✅ **50% de requests < 3ms**

**Evaluación**: Rendimiento excelente para el endpoint de Login.

## 🎯 Métricas Objetivo

### Requests per Second (RPS)
- 🟢 Excelente: > 100 RPS
- 🟡 Bueno: 50-100 RPS
- 🔴 Necesita optimización: < 50 RPS

### Tiempo de Respuesta
- 🟢 Excelente: < 100ms
- 🟡 Aceptable: 100-500ms
- 🔴 Lento: > 500ms

### Tasa de Fallos
- 🟢 Perfecto: 0%
- 🟡 Aceptable: < 1%
- 🔴 Crítico: > 5%

## 🔍 Análisis de Reportes

Los reportes de Apache Bench incluyen:

1. **Server Information**: Servidor, puerto, path
2. **Request Statistics**: Total, fallidos, transferidos
3. **Performance Metrics**: RPS, tiempo por request
4. **Connection Times**: Min, mean, median, max
5. **Percentiles**: Distribución de tiempos de respuesta

### Ejemplo de Interpretación

```
Requests per second:    279.18 [#/sec]
Time per request:       17.909 [ms] (mean)
Failed requests:        0
```

**Interpretación**: El sistema puede manejar ~280 logins por segundo con un tiempo de respuesta promedio de 18ms y sin fallos.

## 🛠️ Optimizaciones Sugeridas

Basado en los resultados, considera:

1. **Si RPS < 50**:
   - Optimizar queries SQL
   - Implementar índices en tablas
   - Usar prepared statements

2. **Si Tiempo > 500ms**:
   - Implementar caché (Redis/Memcached)
   - Optimizar lógica de negocio
   - Revisar N+1 queries

3. **Si Fallos > 1%**:
   - Revisar logs de errores
   - Aumentar límites de conexión MySQL
   - Verificar timeouts

## 📝 Próximos Pasos

1. ✅ Ejecutar pruebas en todos los endpoints
2. ✅ Generar reporte HTML consolidado
3. ⬜ Establecer baselines (líneas base)
4. ⬜ Implementar monitoreo continuo
5. ⬜ Optimizar endpoints lentos
6. ⬜ Documentar mejoras de rendimiento

## 🎓 Recursos Adicionales

- [Apache Bench Documentation](https://httpd.apache.org/docs/2.4/programs/ab.html)
- [PHP Performance Tips](https://www.php.net/manual/en/features.performance.php)
- [MySQL Query Optimization](https://dev.mysql.com/doc/refman/8.0/en/optimization.html)

---

**Nota**: Estas pruebas deben ejecutarse en la base de datos de prueba (`senaparking_test`) y nunca en producción.
