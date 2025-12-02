# Pruebas de Regresión - SENAParking

## Objetivo
Detectar automáticamente cuando cambios en el código rompen funcionalidad existente.

## ¿Qué son las Pruebas de Regresión?

Las pruebas de regresión verifican que:
- ✅ Funcionalidad existente sigue funcionando después de cambios
- ✅ Nuevas features no rompen código antiguo
- ✅ Bug fixes no introducen nuevos bugs
- ✅ Refactorizaciones mantienen el comportamiento

## Componentes del Sistema

### 1. Suite de Regresión
Ejecuta automáticamente:
- Pruebas Unitarias (34 tests)
- Pruebas de Integración (15 tests)
- Pruebas E2E (7 tests)
- **Total: 56 tests**

### 2. Baselines (Líneas Base)
Snapshots de resultados esperados que sirven como referencia.

### 3. Comparador
Detecta diferencias entre:
- Baseline (esperado)
- Resultado actual (obtenido)

### 4. Reportes
Genera reportes detallados de:
- Tests que pasaron
- Tests que fallaron (regresiones)
- Nuevos tests añadidos
- Tests removidos

## Flujo de Trabajo

```
1. Establecer Baseline
   ↓
2. Hacer cambios en código
   ↓
3. Ejecutar Suite de Regresión
   ↓
4. Comparar con Baseline
   ↓
5. Generar Reporte
   ↓
6. Aprobar/Rechazar cambios
```

## Cuándo Ejecutar

- ✅ Antes de cada commit importante
- ✅ Antes de merge a main/master
- ✅ Después de refactorizar
- ✅ Antes de cada release
- ✅ En CI/CD pipeline (automático)

## Tipos de Regresiones Detectadas

1. **Funcionales**: Feature que dejó de funcionar
2. **Rendimiento**: Código más lento que antes
3. **Seguridad**: Vulnerabilidad introducida
4. **Compatibilidad**: Rompe integración con otros módulos
