# Guía de Uso - Pruebas de Regresión

## 🚀 Inicio Rápido

### Paso 1: Establecer Baseline (Primera vez)

```bash
cd c:\xampp\htdocs\SENAParking\backend\utils\regression
establish-baseline.bat
```

Esto ejecutará todos los tests y guardará los resultados como referencia.

**IMPORTANTE**: Solo ejecuta esto cuando:
- ✅ Todos los tests pasen
- ✅ El código esté en estado estable
- ✅ Antes de empezar a hacer cambios

### Paso 2: Hacer Cambios en el Código

Desarrolla normalmente:
- Añade features
- Refactoriza código
- Corrige bugs

### Paso 3: Ejecutar Pruebas de Regresión

```bash
run-regression-suite.bat
```

Esto:
1. Ejecuta todos los tests (Unit, Integration, E2E)
2. Compara resultados con la baseline
3. Detecta regresiones automáticamente
4. Genera reporte HTML

### Paso 4: Revisar Resultados

El reporte se guarda en:
```
backend/utils/regression/reports/[timestamp]/regression-report.html
```

Abre el HTML en tu navegador para ver:
- ❌ Regresiones detectadas
- ✅ Mejoras
- 🆕 Nuevos tests
- 📊 Comparación detallada

## 📋 Comandos Disponibles

### `establish-baseline.bat`
Establece una nueva baseline.

**Cuándo usar**:
- Primera vez que usas el sistema
- Después de un release exitoso
- Cuando quieras actualizar la referencia

### `run-regression-suite.bat`
Ejecuta suite completa y compara con baseline.

**Cuándo usar**:
- Antes de cada commit importante
- Después de refactorizar
- Antes de merge a main
- En CI/CD pipeline

## 🎯 Interpretación de Resultados

### ✅ Sin Regresiones
```
✅ TODAS LAS PRUEBAS PASARON
No se detectaron regresiones.
```
**Acción**: Puedes hacer commit/merge con confianza.

### ❌ Con Regresiones
```
❌ SE DETECTARON REGRESIONES
Revisa los reportes para más detalles.
```

**Acciones**:
1. Abre el reporte HTML
2. Identifica qué tests fallaron
3. Revisa tus cambios recientes
4. Corrige el código
5. Vuelve a ejecutar

### 🆕 Nuevos Tests
Si añadiste tests nuevos, aparecerán como "Nuevos Tests".
Esto es normal y positivo.

### ✅ Mejoras
Si arreglaste tests que antes fallaban, aparecerán como "Mejoras".
¡Excelente trabajo!

## 🔄 Flujo de Trabajo Recomendado

### Para Desarrollo Diario

```bash
# 1. Antes de empezar a trabajar
git pull origin main

# 2. Crear rama para tu feature
git checkout -b feature/mi-feature

# 3. Desarrollar...
# (hacer cambios en código)

# 4. Ejecutar regresión
cd backend\utils\regression
run-regression-suite.bat

# 5. Si todo pasa
git add .
git commit -m "feat: mi feature"
git push

# 6. Si hay regresiones
# Corregir y volver al paso 4
```

### Para Releases

```bash
# 1. Ejecutar regresión completa
run-regression-suite.bat

# 2. Verificar que todo pase
# (revisar reporte HTML)

# 3. Si todo OK, establecer nueva baseline
establish-baseline.bat

# 4. Hacer release
git tag v1.0.0
git push --tags
```

## 📊 Estructura de Reportes

Cada ejecución crea un directorio con timestamp:
```
reports/
└── 20251202_084530/
    ├── unit-results.xml
    ├── unit-output.txt
    ├── integration-results.xml
    ├── integration-output.txt
    ├── e2e-results.xml
    ├── e2e-output.txt
    └── regression-report.html
```

## 🔧 Solución de Problemas

### "No se encontró baseline"
**Solución**: Ejecuta `establish-baseline.bat` primero.

### "Muchas regresiones detectadas"
**Posibles causas**:
1. Cambios grandes en el código
2. Baseline desactualizada
3. Problemas con base de datos de prueba

**Solución**:
1. Revisa el reporte HTML para detalles
2. Verifica que la BD de prueba esté limpia
3. Si es intencional, actualiza baseline

### Tests intermitentes
**Solución**:
1. Identifica tests que fallan aleatoriamente
2. Revisa dependencias de tiempo/orden
3. Añade `@depends` o `setUp`/`tearDown` adecuados

## 💡 Mejores Prácticas

1. **Baseline Frecuente**: Actualiza después de cada release
2. **Commits Pequeños**: Más fácil identificar qué causó regresión
3. **Revisar Reportes**: No ignores las regresiones
4. **CI/CD**: Integra en tu pipeline
5. **Documentar**: Si actualizas baseline, documenta por qué

## 🎓 Recursos

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Regression Testing Best Practices](https://martinfowler.com/bliki/RegressionTesting.html)
- [Continuous Integration](https://www.atlassian.com/continuous-delivery/continuous-integration)
