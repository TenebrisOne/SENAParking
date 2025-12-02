# Guía de Uso - Pruebas de Rendimiento

## 📋 Requisitos Previos

1. **XAMPP corriendo**: Apache y MySQL deben estar activos
2. **Base de datos de prueba**: `senaparking_test` debe existir
3. **Apache Bench**: Incluido con XAMPP

## 🚀 Inicio Rápido

### Paso 1: Preparar Datos de Prueba

```bash
cd backend\utils\performance
C:\xampp\php\php.exe setup-test-data.php
```

Este script creará:
- Usuario de prueba para login
- Usuario de parqueadero para vehículos
- Limpiará datos antiguos

### Paso 2: Ejecutar Prueba Rápida

```bash
quick-test.bat
```

Esto ejecutará una prueba rápida (50 requests) para verificar que todo funciona.

### Paso 3: Ejecutar Suite Completa

```bash
run-all-tests.bat
```

Esto ejecutará todas las pruebas de carga en todos los endpoints.

## 📊 Scripts Disponibles

### Pruebas Individuales

- `test-login-load.bat` - Pruebas de carga para Login
- `test-vehicle-load.bat` - Pruebas de carga para Registro de Vehículos

### Utilidades

- `setup-test-data.php` - Preparar datos de prueba
- `generate-summary.php` - Generar resumen HTML de resultados
- `quick-test.bat` - Prueba rápida de verificación

## 📈 Interpretación de Resultados

### Métricas Clave

**Requests per second (RPS)**
- > 100 RPS: Excelente
- 50-100 RPS: Bueno
- < 50 RPS: Necesita optimización

**Time per request (ms)**
- < 100ms: Excelente
- 100-500ms: Aceptable
- > 500ms: Lento, requiere optimización

**Failed requests**
- 0: Perfecto
- < 1%: Aceptable
- > 5%: Crítico, revisar logs

### Archivos de Reporte

Los reportes se guardan en `backend/utils/performance/reports/`:

- `*_low.txt` - Carga baja (100 req, 10 concurrentes)
- `*_medium.txt` - Carga media (500 req, 50 concurrentes)
- `*_high.txt` - Carga alta (1000 req, 100 concurrentes)
- `*_stress.txt` - Prueba de estrés (2000 req, 200 concurrentes)
- `summary.html` - Resumen visual consolidado

## 🔧 Solución de Problemas

### Error: "Connection refused"

- Verifica que Apache esté corriendo
- Confirma que la URL es correcta
- Revisa que el puerto 80 esté disponible

### Error: "Failed requests" alto

- Revisa logs de PHP: `C:\xampp\php\logs\php_error_log`
- Revisa logs de Apache: `C:\xampp\apache\logs\error.log`
- Verifica que la base de datos esté disponible

### Rendimiento bajo

- Optimiza queries SQL (usa EXPLAIN)
- Implementa caché
- Revisa índices en tablas
- Considera aumentar recursos de MySQL

## 📝 Notas Importantes

1. **No ejecutar en producción**: Estas pruebas generan mucha carga
2. **Base de datos de prueba**: Siempre usa `senaparking_test`
3. **Limpieza**: Los datos de prueba se acumulan, limpia periódicamente
4. **Concurrencia**: Ajusta según tu hardware (más concurrencia = más RAM)

## 🎯 Mejores Prácticas

1. Ejecuta las pruebas en horarios de baja actividad
2. Monitorea el uso de CPU y memoria durante las pruebas
3. Compara resultados antes y después de optimizaciones
4. Documenta los cambios que mejoran el rendimiento
5. Establece baselines (líneas base) para comparaciones futuras
