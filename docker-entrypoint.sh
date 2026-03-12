#!/bin/bash
set -e

# Deshabilitar MPMs conflictivos en runtime antes de arrancar Apache
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

exec apache2-foreground
